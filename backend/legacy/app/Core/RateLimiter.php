<?php
declare(strict_types=1);

namespace Phelon\Core;

use PDO;

final class RateLimiter
{
    public function __construct(private PDO $pdo) {}

    public function hit(string $scope, int $limit): void
    {
        $bucket = hash('sha256', $scope);
        $window = intdiv(time(), 900);
        $this->pdo->beginTransaction();
        try {
            $statement = $this->pdo->prepare('INSERT INTO phelon_rate_limits (bucket, window_id, attempts)
                VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE
                attempts = IF(window_id = VALUES(window_id), LEAST(attempts + 1, 65535), 1),
                window_id = VALUES(window_id)');
            $statement->execute([$bucket, $window]);
            $statement = $this->pdo->prepare('SELECT attempts FROM phelon_rate_limits WHERE bucket = ?');
            $statement->execute([$bucket]);
            $attempts = (int) $statement->fetchColumn();
            $this->pdo->commit();
        } catch (\Throwable $error) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $error;
        }
        if ($attempts > $limit) {
            header('Retry-After: ' . (900 - (time() % 900)));
            throw new HttpError(429, 'Too many requests. Please wait and try again.');
        }
    }
}
