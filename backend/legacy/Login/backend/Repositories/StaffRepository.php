<?php
declare(strict_types=1);

namespace Phelon\Login\Repositories;

use PDO;

final class StaffRepository
{
    public function __construct(private PDO $pdo) {}

    public function byEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare('SELECT id, display_name, password_hash, active
            FROM phelon_staff_users WHERE email = ?');
        $statement->execute([$email]);
        return $statement->fetch() ?: null;
    }

    public function activeById(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT id, display_name FROM phelon_staff_users WHERE id = ? AND active = 1');
        $statement->execute([$id]);
        return $statement->fetch() ?: null;
    }

    public function create(string $email, string $name, string $hash): void
    {
        $this->pdo->prepare('INSERT INTO phelon_staff_users (email, display_name, password_hash) VALUES (?, ?, ?)')
            ->execute([$email, $name, $hash]);
    }
}
