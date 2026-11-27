<?php

namespace App\Services;

use App\Repositories\ResourcePurchaseRepository;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ResourceLibraryService
{
    private const DOCUMENTS = ['47' => '47_starter_kit.pdf', '497' => '497_workbook.pdf'];

    public function __construct(private ResourcePurchaseRepository $purchases) {}

    public function verify(array $input, Session $session): JsonResponse
    {
        $row = $this->record($input['transaction']);
        abort_unless($this->fileFor($row) && $row->expiry_code && Carbon::parse($row->expiry_code)->isFuture()
            && hash_equals((string) $row->access_code, $input['code']), 403, 'That purchase reference or access code is not valid.');
        $session->regenerate();
        $session->put('resource_purchase', $input['transaction']);
        $session->put('resource_code', hash('sha256', (string) $row->access_code));
        return response()->json(['document' => '/api/library/document', 'csrfToken' => $session->token()])->header('Cache-Control', 'no-store');
    }

    public function resend(string $transaction): array
    {
        $row = $this->record($transaction);
        if ($this->fileFor($row) && filter_var($row->email, FILTER_VALIDATE_EMAIL)) {
            abort_unless(config('mail.default') === 'smtp', 503, 'Email delivery is not configured. Contact the team for a new code.');
            $code = (string) random_int(100000, 999999);
            DB::transaction(function () use ($row, $code) {
                $this->purchases->replaceCode($row->transactionId, $code, now()->addMinutes(15));
                Mail::raw("Your PHELONPRENEUR resource access code is {$code}. It expires in 15 minutes.",
                    static fn ($message) => $message->to($row->email)->subject('PHELONPRENEUR resource access code'));
            });
        }
        return ['message' => 'If the purchase is eligible, an access code will be sent to its email address.'];
    }

    public function document(Session $session): BinaryFileResponse
    {
        $transaction = $session->get('resource_purchase');
        abort_unless(is_string($transaction), 403);
        $row = $this->record($transaction);
        $file = $this->fileFor($row);
        abort_unless($file && $row->expiry_code && Carbon::parse($row->expiry_code)->isFuture()
            && hash_equals((string) $session->get('resource_code'), hash('sha256', (string) $row->access_code)), 403);
        $path = resource_path('private/'.$file);
        abort_unless(is_file($path), 404);
        return response()->download($path, $file, ['Cache-Control' => 'no-store', 'X-Content-Type-Options' => 'nosniff']);
    }

    private function record(string $transaction): ?object
    {
        abort_unless(config('phelon.library_enabled'), 503, 'Online library access is not enabled. Contact the team for help.');
        try {
            return $this->purchases->find($transaction);
        } catch (QueryException) {
            abort(503, 'Online library access is temporarily unavailable.');
        }
    }

    private function fileFor(?object $row): ?string
    {
        return self::DOCUMENTS[(string) (float) preg_replace('/[^0-9.]/', '', (string) ($row->total ?? ''))] ?? null;
    }
}
