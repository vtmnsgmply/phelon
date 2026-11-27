<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\ReadinessIntakeRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class ReadinessIntake
{
    private ?array $schema = null;

    public function __construct(
        private BookingRepository $bookings,
        private ReadinessIntakeRepository $intakes,
    ) {}

    private function schema(): array
    {
        return $this->schema ??= json_decode(
            file_get_contents(resource_path('catalog/intake.json')), true, flags: JSON_THROW_ON_ERROR
        );
    }

    public function labels(): array
    {
        $labels = [];
        foreach ($this->schema() as $step) {
            foreach ($step['fields'] as $field) {
                $labels[$field['name']] = $field['label'];
            }
        }
        return $labels;
    }

    public function reference(string $reference): array
    {
        $normalized = strtoupper($reference);
        if (preg_match('/\A[A-F0-9]{24}\z/', $normalized) && $this->bookings->referenceExists($normalized)) {
            return [$normalized, false];
        }
        if (config('phelon.legacy_intake_enabled') && preg_match('/\A[A-Za-z0-9_-]{16,110}\z/', $reference)
            && $this->intakes->legacyAppointmentExists($reference)) {
            return ['legacy:'.$reference, true];
        }
        abort(404, 'This intake link is not available. Please contact the team.');
    }

    public function answers(array $answers, bool $legacy): array
    {
        $rules = [];
        $labels = [];
        foreach ($this->schema() as $step) {
            foreach ($step['fields'] as $field) {
                $name = $field['name'];
                if (!preg_match('/\A[a-z0-9_]+\z/', $name)
                    || (!$legacy && $name === 'consent_7')
                    || (isset($field['when']) && ($answers[$field['when'][0]] ?? null) !== $field['when'][1])
                    || (isset($field['concerns']) && !array_intersect(
                        array_filter((array) ($answers['concerns'] ?? []), 'is_string'),
                        $field['concerns']
                    ))) {
                    continue;
                }
                $options = array_column($field['options'] ?? [], 0);
                $required = $field['required'] ? 'required' : 'nullable';
                if ($field['type'] === 'checkbox' && str_starts_with($name, 'consent_')) {
                    $rules[$name] = ['required', 'accepted'];
                } elseif ($field['type'] === 'checkbox') {
                    $rules[$name] = [$required, 'array', 'max:25'];
                    $rules[$name.'.*'] = ['string', 'distinct', Rule::in($options)];
                } else {
                    $rules[$name] = [$required, 'string', 'max:1500'];
                    if ($options) {
                        $rules[$name][] = Rule::in($options);
                    } elseif ($field['type'] === 'email') {
                        $rules[$name][] = 'email:rfc';
                    } elseif ($field['type'] === 'date') {
                        $rules[$name][] = 'date_format:Y-m-d';
                    }
                }
                $labels[$name] = $field['label'];
            }
        }
        return Validator::make($answers, $rules, [], $labels)->validate();
    }
}
