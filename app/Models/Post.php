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

    // New method to get posts from followed users
    public function getFeedPosts($userId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.name as user_name, u.id as user_id, u.profile_picture as user_profile_picture
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.user_id IN (
                SELECT following_id FROM followers WHERE follower_id = ?
            ) OR p.user_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // New method to get posts by specific user
    public function getPostsByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.name as user_name, u.id as user_id, u.profile_picture as user_profile_picture
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$userId]);
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

    // Like functionality
    public function likePost($userId, $postId) {
        // Check if already liked
        $checkStmt = $this->db->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $checkStmt->execute([$userId, $postId]);
        
        if ($checkStmt->fetch()) {
            // Unlike if already liked
            $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
            return $stmt->execute([$userId, $postId]);
        } else {
            // Like the post
            $stmt = $this->db->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
            return $stmt->execute([$userId, $postId]);
        }
    }

    public function getLikeCount($postId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function isLikedByUser($userId, $postId) {
        $stmt = $this->db->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        return (bool) $stmt->fetch();
    }

    // Comment functionality
    public function addComment($userId, $postId, $content) {
        $stmt = $this->db->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $postId, $content]);
    }

    public function getComments($postId) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.name as user_name, u.profile_picture as user_profile_picture
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.post_id = ? 
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentCount($postId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM comments WHERE post_id = ?");
        $stmt->execute([$postId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function deleteComment($commentId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        return $stmt->execute([$commentId, $userId]);
    }
}