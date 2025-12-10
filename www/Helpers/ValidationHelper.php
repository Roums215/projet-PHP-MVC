<?php
namespace App\Helpers;

class ValidationHelper
{
    /**
     * Valide la force du mot de passe
     * Exigences: 8+ caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial (optionnel)
     */
    public static function validatePassword(string $password, bool $requireSpecial = true): bool
    {
        $hasMinLength = strlen($password) >= 8;
        $hasUppercase = preg_match('#[A-Z]#', $password);
        $hasLowercase = preg_match('#[a-z]#', $password);
        $hasDigit = preg_match('#[0-9]#', $password);
        $hasSpecial = preg_match('#[\W]#', $password);

        if ($requireSpecial) {
            return $hasMinLength && $hasUppercase && $hasLowercase && $hasDigit && $hasSpecial;
        }
        return $hasMinLength && $hasUppercase && $hasLowercase && $hasDigit;
    }

    /**
     * Valide un email
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide la longueur minimale d'une chaîne
     */
    public static function validateMinLength(string $value, int $minLength): bool
    {
        return strlen(trim($value)) >= $minLength;
    }

    /**
     * Nettoie et formate le prénom
     */
    public static function cleanFirstname(string $firstname): string
    {
        return ucwords(strtolower(trim($firstname)));
    }

    /**
     * Nettoie et formate le nom
     */
    public static function cleanLastname(string $lastname): string
    {
        return strtoupper(trim($lastname));
    }

    /**
     * Nettoie et normalise un email
     */
    public static function cleanEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public static function isAdmin(): bool
    {
        return isset($_SESSION['user']) && (($_SESSION['user']['role'] ?? 'user') === 'admin');
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }
}

