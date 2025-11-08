<?php
namespace App\Models;

use PDO;
use PDOException;

class Post {
    private $db;
    
    public function __construct() {
        $this->db = new PDO(
            "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME'),
            getenv('DB_USER'),
            getenv('DB_PASS')
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function create($userId, $content, $image = null) {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $content, $image]);
    }

    public function getAllWithUsers() {
        $stmt = $this->db->prepare("
            SELECT p.*, u.name as user_name, u.id as user_id, u.profile_picture as user_profile_picture
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($postId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.name as user_name, u.id as user_id, u.profile_picture as user_profile_picture
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($postId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        return $stmt->execute([$postId, $userId]);
    }
}