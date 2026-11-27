<?php
declare(strict_types=1);

namespace Phelon\Login\Requests;

use Phelon\Core\HttpError;

final class LoginRequest
{
    public function __construct(public readonly string $email, public readonly string $password) {}

    public static function from(array $body): self
    {
        $email = is_string($body['email'] ?? null) ? strtolower(trim($body['email'])) : '';
        $password = $body['password'] ?? null;
        if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || !is_string($password) || $password === '' || strlen($password) > 72 || str_contains($password, "\0")) {
            throw new HttpError(401, 'Email or password was incorrect.');
        }
        return new self($email, $password);
    }
}
