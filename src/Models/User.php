<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Models;

use DateTime;
use TinyFramework\Auth\Authenticatable;
use TinyFramework\Database\BaseModel;

/**
 * @property null|string $id
 * @property null|string $email
 * @property null|string $password
 * @property string|null $verification_key
 * @property null|DateTime $verification_at
 * @property string|null $password_reset_key
 * @property null|DateTime $password_reset_at
 * @property null|DateTime $created_at
 * @property null|DateTime $updated_at
 */
class User extends BaseModel implements Authenticatable
{
    protected string $table = 'users';

    protected array $attributes = [
        'id' => null,
        'email' => null,
        'password' => null,
        'verification_key' => null,
        'verification_at' => null,
        'password_reset_key' => null,
        'password_reset_at' => null,
    ];

    protected array $casts = [
        'id' => 'string',
        'email' => 'string',
        'password' => 'string',
        'verification_at' => 'datetime',
        'verification_key' => 'string',
        'password_reset_at' => 'datetime',
        'password_reset_key' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getAuthIdentifier(): string
    {
        return $this->id;
    }

    public function getAuthName(): string
    {
        return $this->email;
    }
}
