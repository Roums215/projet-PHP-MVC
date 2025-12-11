<?php
namespace App\Helpers;

class EnvHelper {
    private static ?array $env = null;

    /**
     * Charge les variables d'environnement depuis le fichier .env
     */
    private static function loadEnv(): void {
        if (self::$env !== null) {
            return;
        }

        self::$env = [];
        $envFile = __DIR__ . '/../../.env';

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Ignorer les commentaires et les lignes vides
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    [$key, $value] = explode('=', $line, 2);
                    self::$env[trim($key)] = trim($value);
                }
            }
        }
    }

    /**
     * Récupère une variable d'environnement
     * Ordre de priorité: variable système > fichier .env > valeur par défaut
     */
    public static function get(string $key, string|int|null $default = null): string|int|null {
        self::loadEnv();

        // Essayer d'abord les variables système
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        // Ensuite, le fichier .env
        if (isset(self::$env[$key])) {
            return self::$env[$key];
        }

        // Fallback sur la valeur par défaut
        return $default;
    }

    /**
     * Récupère une variable d'environnement de base de données
     */
    public static function getDbHost(): string {
        $value = self::get('POSTGRES_HOST') ?? self::get('DB_HOST');
        if (!$value) {
            throw new \Exception('POSTGRES_HOST or DB_HOST is required. Configure it in .env file');
        }
        return $value;
    }

    public static function getDbPort(): int {
        $value = self::get('POSTGRES_PORT') ?? self::get('DB_PORT');
        return (int)($value ?? 5432);
    }

    public static function getDbName(): string {
        $value = self::get('POSTGRES_DB') ?? self::get('DB_NAME');
        if (!$value) {
            throw new \Exception('POSTGRES_DB or DB_NAME is required. Configure it in .env file');
        }
        return $value;
    }

    public static function getDbUser(): string {
        $value = self::get('POSTGRES_USER') ?? self::get('DB_USER');
        if (!$value) {
            throw new \Exception('POSTGRES_USER or DB_USER is required. Configure it in .env file');
        }
        return $value;
    }

    public static function getDbPassword(): string {
        $value = self::get('POSTGRES_PASSWORD') ?? self::get('DB_PASSWORD');
        if (!$value) {
            throw new \Exception('POSTGRES_PASSWORD or DB_PASSWORD is required. Configure it in .env file');
        }
        return $value;
    }

    /**
     * Récupère les variables SMTP
     */
    public static function getSmtpHost(): string {
        $value = self::get('SMTP_HOST');
        if (!$value) {
            throw new \Exception('SMTP_HOST est requis. Configurez-le dans le fichier .env');
        }
        return $value;
    }

    public static function getSmtpPort(): int {
        $value = self::get('SMTP_PORT');
        return (int)($value ?? 587);
    }

    public static function getSmtpUser(): string {
        $value = self::get('SMTP_USER');
        if (!$value) {
            throw new \Exception('SMTP_USER est requis. Configurez-le dans le fichier .env');
        }
        return $value;
    }

    public static function getSmtpPassword(): string {
        $value = self::get('SMTP_PASSWORD');
        if (!$value) {
            throw new \Exception('SMTP_PASSWORD est requis. Configurez-le dans le fichier .env');
        }
        return $value;
    }

    public static function getSmtpFromEmail(): string {
        $value = self::get('SMTP_FROM_EMAIL');
        if (!$value) {
            throw new \Exception('SMTP_FROM_EMAIL est requis. Configurez-le dans le fichier .env');
        }
        return $value;
    }

    public static function getSmtpFromName(): string {
        return self::get('SMTP_FROM_NAME', 'Application');
    }
}
