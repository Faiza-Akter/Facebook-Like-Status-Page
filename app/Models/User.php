<?php
namespace App\Models;

use PDO;

class User
{
    private static $db;

    private static function connect(): PDO
    {
        if (self::$db === null) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $db = getenv('DB_NAME') ?: 'authboard';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            self::$db = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$db;
    }

    // Helper method to get proper image URLs
    private static function getImageUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        // If the path already starts with /assets/, return as is
        if (strpos($path, '/assets/') === 0) {
            return $path;
        }

        // If it's just a filename or relative path, prepend /assets/uploads/profiles/
        if (strpos($path, '/') === false) {
            return '/assets/uploads/profiles/' . $path;
        }

        // Ensure it starts with a slash
        return '/' . ltrim($path, '/');
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = self::connect()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        
        if ($row && !empty($row['profile_picture'])) {
            $row['profile_picture'] = self::getImageUrl($row['profile_picture']);
        }

        return $row ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = self::connect()->prepare('SELECT id, name, email, profile_picture, created_at FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row && !empty($row['profile_picture'])) {
            $row['profile_picture'] = self::getImageUrl($row['profile_picture']);
        }

        return $row ?: null;
    }

    public static function create(string $name, string $email, string $password): int
    {
        $stmt = self::connect()->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $password]);
        return (int) self::connect()->lastInsertId();
    }

    public static function updateProfilePicture($userId, $profilePicture): bool
    {
        $stmt = self::connect()->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        return $stmt->execute([$profilePicture, $userId]);
    }

    public static function updateProfile(int $userId, string $name, string $email): bool
    {
        $stmt = self::connect()->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        return $stmt->execute([$name, $email, $userId]);
    }

    // Additional helper method to ensure database connection is available
    public static function testConnection(): bool
    {
        try {
            self::connect();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    // New methods for follow system
    public static function getPublicProfile(int $userId): ?array
    {
        $stmt = self::connect()->prepare('
            SELECT id, name, profile_picture, created_at,
            (SELECT COUNT(*) FROM followers WHERE following_id = ?) as follower_count,
            (SELECT COUNT(*) FROM followers WHERE follower_id = ?) as following_count,
            (SELECT COUNT(*) FROM posts WHERE user_id = ?) as post_count
            FROM users WHERE id = ?
        ');
        $stmt->execute([$userId, $userId, $userId, $userId]);
        $row = $stmt->fetch();

        if ($row && !empty($row['profile_picture'])) {
            $row['profile_picture'] = self::getImageUrl($row['profile_picture']);
        }

        return $row ?: null;
    }

    public static function isFollowing(int $followerId, int $followingId): bool
    {
        $stmt = self::connect()->prepare('SELECT id FROM followers WHERE follower_id = ? AND following_id = ?');
        $stmt->execute([$followerId, $followingId]);
        return (bool) $stmt->fetch();
    }

    public static function follow(int $followerId, int $followingId): bool
    {
        // Don't allow following yourself
        if ($followerId === $followingId) {
            return false;
        }

        // Check if already following
        if (self::isFollowing($followerId, $followingId)) {
            return true;
        }

        $stmt = self::connect()->prepare('INSERT INTO followers (follower_id, following_id) VALUES (?, ?)');
        return $stmt->execute([$followerId, $followingId]);
    }

    public static function unfollow(int $followerId, int $followingId): bool
    {
        $stmt = self::connect()->prepare('DELETE FROM followers WHERE follower_id = ? AND following_id = ?');
        return $stmt->execute([$followerId, $followingId]);
    }

    public static function getFollowers(int $userId): array
    {
        $stmt = self::connect()->prepare('
            SELECT u.id, u.name, u.profile_picture, u.created_at
            FROM followers f
            JOIN users u ON f.follower_id = u.id
            WHERE f.following_id = ?
            ORDER BY f.created_at DESC
        ');
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            if (!empty($row['profile_picture'])) {
                $row['profile_picture'] = self::getImageUrl($row['profile_picture']);
            }
        }

        return $rows;
    }

    public static function getFollowing(int $userId): array
    {
        $stmt = self::connect()->prepare('
            SELECT u.id, u.name, u.profile_picture, u.created_at
            FROM followers f
            JOIN users u ON f.following_id = u.id
            WHERE f.follower_id = ?
            ORDER BY f.created_at DESC
        ');
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            if (!empty($row['profile_picture'])) {
                $row['profile_picture'] = self::getImageUrl($row['profile_picture']);
            }
        }

        return $rows;
    }

    public static function searchUsers(string $query): array
    {
        $stmt = self::connect()->prepare('
            SELECT id, name, profile_picture, created_at
            FROM users 
            WHERE name LIKE ? OR email LIKE ?
            ORDER BY name
            LIMIT 20
        ');
        $searchTerm = '%' . $query . '%';
        $stmt->execute([$searchTerm, $searchTerm]);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            if (!empty($row['profile_picture'])) {
                $row['profile_picture'] = self::getImageUrl($row['profile_picture']);
            }
        }

        return $rows;
    }
}