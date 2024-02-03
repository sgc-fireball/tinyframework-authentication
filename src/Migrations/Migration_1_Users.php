<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Migrations;

use TinyFramework\Database\DatabaseInterface;
use TinyFramework\Database\MigrationInterface;

class Migration_1_Users implements MigrationInterface
{
    public function __construct(protected DatabaseInterface $database)
    {
    }

    public function up(): void
    {
        $this->database->execute(
            implode(' ', [
                'CREATE TABLE IF NOT EXISTS `users` (',
                '`id` char(36) NOT NULL,',
                '`email` varchar(255) NOT NULL,',
                '`password` varchar(255) DEFAULT NULL,',
                '`verification_key` char(36) DEFAULT NULL,',
                '`verification_at` TIMESTAMP NULL DEFAULT NULL,',
                '`password_reset_key` char(36) DEFAULT NULL,',
                '`password_reset_at` TIMESTAMP NULL DEFAULT NULL,',
                '`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,',
                '`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,',
                'PRIMARY KEY (`id`),',
                'UNIQUE KEY email (email)',
                ')',
            ])
        );
    }

    public function down(): void
    {
        $this->database->execute('DROP TABLE IF EXISTS `users`');
    }
}
