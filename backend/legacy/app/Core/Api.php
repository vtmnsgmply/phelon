<?php
declare(strict_types=1);

namespace Phelon\Core;

final class Api
{
    public static function run(callable $handler): void
    {
        ini_set('display_errors', '0');
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        header('X-Content-Type-Options: nosniff');
        try {
            $result = $handler($_SERVER['REQUEST_METHOD'] ?? '');
            http_response_code($result[0]);
            echo json_encode($result[1], JSON_THROW_ON_ERROR);
        } catch (HttpError $error) {
            http_response_code($error->status);
            echo json_encode(['error' => $error->getMessage()]);
        } catch (\Throwable $error) {
            // Avoid logging query parameters, contact details or connection credentials.
            error_log('Phelon API failed: ' . $error::class . ' (' . $error->getCode() . ')');
            http_response_code(503);
            echo '{"error":"This service is temporarily unavailable. Please try again later."}';
        }
    }

    public static function json(): array
    {
        if (strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '', 2)[0])) !== 'application/json') {
            throw new HttpError(415, 'Send the request as JSON.');
        }
        $body = file_get_contents('php://input', false, null, 0, 8193);
        if ($body === false || strlen($body) > 8192) {
            throw new HttpError(413, 'The request is too large.');
        }
        try {
            $object = json_decode($body, true, 12, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new HttpError(400, 'Invalid JSON.');
        }
        if (!is_array($object) || !str_starts_with(ltrim($body), '{')) {
            throw new HttpError(400, 'Send a JSON object.');
        }
        return $object;
    }
}
