<?php

namespace App\Services;

use App\Repositories\IntakeCriteria;
use App\Repositories\ReadinessIntakeRepository;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReadinessIntakeService
{
    public function __construct(
        private ReadinessIntake $schema,
        private ReadinessIntakeRepository $intakes,
    ) {}

    public function status(string $inputReference): JsonResponse
    {
        try {
            [$reference, $legacy] = $this->schema->reference($inputReference);
            $submitted = $this->intakes->exists($reference);
        } catch (QueryException) {
            abort(503, 'Intake is temporarily unavailable.');
        }
        return response()->json(['submitted' => $submitted, 'legacy' => $legacy])->header('Cache-Control', 'no-store');
    }

    public function submit(array $input): JsonResponse
    {
        $files = [];
        try {
            [$reference, $legacy] = $this->schema->reference($input['reference']);
            abort_if($this->intakes->exists($reference), 409, 'This intake was already submitted.');
            $answers = $this->schema->answers((array) json_decode($input['answers'], true, flags: JSON_THROW_ON_ERROR), $legacy);
            foreach ($input['files'] ?? [] as $file) {
                $path = $file->store('intakes', 'local');
                abort_unless(is_string($path), 503, 'Could not save an attachment.');
                $files[] = $path;
            }
            $this->intakes->create(
                $reference,
                Crypt::encryptString(json_encode($answers, JSON_THROW_ON_ERROR)),
                Crypt::encryptString(json_encode($files, JSON_THROW_ON_ERROR)),
            );
        } catch (\Throwable $error) {
            Storage::disk('local')->delete($files);
            if ($error instanceof UniqueConstraintViolationException) {
                abort(409, 'This intake was already submitted.');
            }
            if ($error instanceof QueryException) {
                abort(503, 'Intake is temporarily unavailable. Your submission was not saved.');
            }
            throw $error;
        }
        return response()->json(['submitted' => true], 201);
    }

    public function index(): array
    {
        return ['intakes' => $this->intakes->findIntakes()->all()];
    }

    public function show(int $id): array
    {
        $intake = $this->findIntake($id);
        $files = $this->attachments($intake);
        $answers = json_decode(Crypt::decryptString($intake->answers), true, flags: JSON_THROW_ON_ERROR);
        return [
            'answers' => $answers,
            'labels' => array_intersect_key($this->schema->labels(), $answers),
            'attachments' => count($files),
        ];
    }

    public function attachment(int $id, int $file): StreamedResponse
    {
        $intake = $this->findIntake($id);
        abort_unless($intake->files, 404);
        $files = $this->attachments($intake);
        abort_unless(isset($files[$file]) && Storage::disk('local')->exists($files[$file]), 404);
        return Storage::disk('local')->download($files[$file], 'attachment-'.($file + 1).'.'.pathinfo($files[$file], PATHINFO_EXTENSION),
            ['X-Content-Type-Options' => 'nosniff']);
    }

    private function findIntake(int $id): object
    {
        return $this->intakes->findIntakes(new IntakeCriteria(id: $id), withDetails: true)->first() ?? abort(404);
    }

    private function attachments(object $intake): array
    {
        return $intake->files ? json_decode(Crypt::decryptString($intake->files), true, flags: JSON_THROW_ON_ERROR) : [];
    }
}
