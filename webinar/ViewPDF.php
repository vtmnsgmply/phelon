<?php
session_start();

ini_set('display_errors', 0);
error_reporting(E_ALL);

$transactionId = trim((string) ($_GET['transactionId'] ?? ''));
$documentsDir = realpath(__DIR__ . '/../Documents');

function viewPdfLog(string $message, array $context = []): void
{
    file_put_contents(
        __DIR__ . '/view_pdf.log',
        date('Y-m-d H:i:s') . ' | ' . $message . ' | ' . json_encode($context) . PHP_EOL,
        FILE_APPEND
    );
}

function stopPdfAccess(int $statusCode, string $message): void
{
    http_response_code($statusCode);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $message;
    exit;
}

function pdfFileForTotal($total): ?string
{
    $cleanTotal = preg_replace('/[^0-9.]/', '', trim((string) $total));
    $normalizedTotal = (string) (float) $cleanTotal;

    $map = [
        '47' => '47_starter_kit.pdf',
        '497' => '497_workbook.pdf',
    ];

    return $map[$normalizedTotal] ?? null;
}

function availableDocumentFiles(string $documentsDir): array
{
    $files = scandir($documentsDir);

    if ($files === false) {
        return [];
    }

    return array_values(array_filter($files, function ($file) use ($documentsDir) {
        return is_file($documentsDir . DIRECTORY_SEPARATOR . $file);
    }));
}

function resolveDocumentPath(string $documentsDir, string $pdfFile): string|false
{
    $directPath = realpath($documentsDir . DIRECTORY_SEPARATOR . $pdfFile);

    if ($directPath !== false && is_file($directPath)) {
        return $directPath;
    }

    foreach (availableDocumentFiles($documentsDir) as $file) {
        if (strtolower($file) === strtolower($pdfFile)) {
            $matchedPath = realpath($documentsDir . DIRECTORY_SEPARATOR . $file);

            if ($matchedPath !== false && is_file($matchedPath)) {
                return $matchedPath;
            }
        }
    }

    return false;
}

if ($transactionId === '') {
    stopPdfAccess(400, 'Missing transaction ID.');
}

$sessionAccess = $_SESSION['pdf_access'][$transactionId] ?? null;

if (
    !is_array($sessionAccess) ||
    empty($sessionAccess['verified']) ||
    empty($sessionAccess['expires_at'])
) {
    stopPdfAccess(403, 'Please verify your access code before viewing this PDF.');
}

try {
    $sessionExpiry = new DateTime((string) $sessionAccess['expires_at']);

    if (new DateTime() > $sessionExpiry) {
        unset($_SESSION['pdf_access'][$transactionId]);
        stopPdfAccess(403, 'Your access code has expired. Please request a new code.');
    }

    require_once __DIR__ . '/../env.php';

    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new RuntimeException('Database connection is not available.');
    }

    $stmt = $pdo->prepare("
        SELECT total, expiry_code
        FROM customers
        WHERE transactionId = :transactionId
        LIMIT 1
    ");

    $stmt->execute([
        ':transactionId' => $transactionId,
    ]);

    $record = $stmt->fetch();

    if (!$record || empty($record['expiry_code'])) {
        stopPdfAccess(404, 'Access record was not found.');
    }

    if (new DateTime() > new DateTime((string) $record['expiry_code'])) {
        unset($_SESSION['pdf_access'][$transactionId]);
        stopPdfAccess(403, 'Your access code has expired. Please request a new code.');
    }

    if ($documentsDir === false) {
        throw new RuntimeException('Documents folder was not found.');
    }

    $pdfFile = pdfFileForTotal($record['total'] ?? null);

    if ($pdfFile === null) {
        viewPdfLog('No PDF mapped for transaction total', [
            'transactionId' => $transactionId,
            'total' => $record['total'] ?? '',
        ]);
        stopPdfAccess(404, 'No PDF is available for this purchase.');
    }

    $requestedPdfPath = $documentsDir . DIRECTORY_SEPARATOR . $pdfFile;
    $pdfPath = resolveDocumentPath($documentsDir, $pdfFile);
    $normalizedDocumentsDir = strtolower(str_replace('\\', '/', rtrim($documentsDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR));
    $normalizedPdfPath = $pdfPath !== false ? strtolower(str_replace('\\', '/', $pdfPath)) : '';

    viewPdfLog('PDF path resolved', [
        'transactionId' => $transactionId,
        'total' => $record['total'] ?? '',
        'pdf_file' => $pdfFile,
        'documents_dir' => $documentsDir,
        'requested_path' => $requestedPdfPath,
        'resolved_path' => $pdfPath ?: '',
        'exists' => $pdfPath !== false && is_file($pdfPath),
        'available_files' => $pdfPath === false ? availableDocumentFiles($documentsDir) : [],
    ]);

    if ($pdfPath === false || strpos($normalizedPdfPath, $normalizedDocumentsDir) !== 0 || !is_file($pdfPath)) {
        stopPdfAccess(404, 'The requested PDF was not found.');
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($pdfPath) . '"');
    header('Content-Length: ' . filesize($pdfPath));
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: private, no-store, max-age=0');

    readfile($pdfPath);
    exit;
} catch (Throwable $e) {
    error_log('ViewPDF error: ' . $e->getMessage());
    stopPdfAccess(500, 'The PDF could not be loaded right now.');
}
