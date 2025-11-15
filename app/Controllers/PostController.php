<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    // Helper method for post images
    private function getPostImageUrl($path) {
        if (empty($path)) {
            return null;
        }
        
        // If the path already starts with /assets/, return as is
        if (strpos($path, '/assets/') === 0) {
            return $path;
        }
        
        // If it's just a filename or relative path, prepend /assets/uploads/posts/
        if (strpos($path, '/') === false) {
            return '/assets/uploads/posts/' . $path;
        }
        
        // Ensure it starts with a slash
        return '/' . ltrim($path, '/');
    }

    public function index() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }
        
        $posts = $this->postModel->getAllWithUsers();
        
        // Enhance posts with user profile pictures, like counts, comment counts, and like status
        $enhancedPosts = [];
        foreach ($posts as $post) {
            $postUser = User::findById($post['user_id']);
            $post['user_profile_picture'] = $postUser['profile_picture'] ?? null;
            
            // Fix post image URL if exists
            if (!empty($post['image'])) {
                $post['image'] = $this->getPostImageUrl($post['image']);
            }
            
            // Add like and comment data
            $post['like_count'] = $this->postModel->getLikeCount($post['id']);
            $post['comment_count'] = $this->postModel->getCommentCount($post['id']);
            $post['is_liked'] = $this->postModel->isLikedByUser($user['id'], $post['id']);
            $post['comments'] = $this->postModel->getComments($post['id']);
            
            // Enhance comment user profile pictures
            foreach ($post['comments'] as &$comment) {
                if (!empty($comment['user_profile_picture'])) {
                    $comment['user_profile_picture'] = $this->getProfileImageUrl($comment['user_profile_picture']);
                }
            }
            
            $enhancedPosts[] = $post;
        }
        
        $this->view('posts/index.php', [
            'user' => $user, 
            'posts' => $enhancedPosts
        ]);
    }

    // New method for personalized feed
    public function feed() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }
        
        $posts = $this->postModel->getFeedPosts($user['id']);
        
        // Enhance posts with user profile pictures, like counts, comment counts, and like status
        $enhancedPosts = [];
        foreach ($posts as $post) {
            $postUser = User::findById($post['user_id']);
            $post['user_profile_picture'] = $postUser['profile_picture'] ?? null;
            
            // Fix post image URL if exists
            if (!empty($post['image'])) {
                $post['image'] = $this->getPostImageUrl($post['image']);
            }
            
            // Add like and comment data
            $post['like_count'] = $this->postModel->getLikeCount($post['id']);
            $post['comment_count'] = $this->postModel->getCommentCount($post['id']);
            $post['is_liked'] = $this->postModel->isLikedByUser($user['id'], $post['id']);
            $post['comments'] = $this->postModel->getComments($post['id']);
            
            // Enhance comment user profile pictures
            foreach ($post['comments'] as &$comment) {
                if (!empty($comment['user_profile_picture'])) {
                    $comment['user_profile_picture'] = $this->getProfileImageUrl($comment['user_profile_picture']);
                }
            }
            
            $enhancedPosts[] = $post;
        }
        
        $this->view('posts/feed.php', [
            'user' => $user, 
            'posts' => $enhancedPosts
        ]);
    }

    public function create() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }
        
        $this->view('posts/create.php', ['user' => $user]);
    }

    public function store() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $content = $_POST['content'] ?? '';
        $image = null;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate a safe filename
            $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                // Store just the filename in database
                $image = $fileName;
            }
        }

        if (!empty($content)) {
            $this->postModel->create($user['id'], $content, $image);
        }

        header('Location: /posts');
        exit;
    }

    public function delete() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        
        if ($postId) {
            // Verify the post belongs to the current user before deleting
            $post = $this->postModel->findById($postId);
            
            if ($post && $post['user_id'] == $user['id']) {
                $this->postModel->delete($postId, $user['id']);
                
                // Delete associated image file if exists
                if ($post['image']) {
                    $imagePath = $_SERVER['DOCUMENT_ROOT'] . $this->getPostImageUrl($post['image']);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }
        }

        header('Location: /posts');
        exit;
    }

    public function like() {
        $user = Session::get('user');
        if (!$user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $postId = $_POST['post_id'] ?? null;
        
        if ($postId) {
            $success = $this->postModel->likePost($user['id'], $postId);
            if ($success) {
                $likeCount = $this->postModel->getLikeCount($postId);
                $isLiked = $this->postModel->isLikedByUser($user['id'], $postId);
                echo json_encode([
                    'success' => true, 
                    'like_count' => $likeCount,
                    'is_liked' => $isLiked
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to like post']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
        }
    }

    public function comment() {
        $user = Session::get('user');
        if (!$user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $postId = $_POST['post_id'] ?? null;
        $content = trim($_POST['content'] ?? '');
        
        if ($postId && !empty($content)) {
            $success = $this->postModel->addComment($user['id'], $postId, $content);
            if ($success) {
                // Get the newly added comment with user info
                $comments = $this->postModel->getComments($postId);
                $newComment = end($comments);
                
                // Enhance profile picture URL
                if (!empty($newComment['user_profile_picture'])) {
                    $newComment['user_profile_picture'] = $this->getProfileImageUrl($newComment['user_profile_picture']);
                }
                
                $commentCount = $this->postModel->getCommentCount($postId);
                echo json_encode([
                    'success' => true, 
                    'comment' => $newComment,
                    'comment_count' => $commentCount
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid post ID or empty content']);
        }
    }

    public function deleteComment() {
        $user = Session::get('user');
        if (!$user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $commentId = $_POST['comment_id'] ?? null;
        
        if ($commentId) {
            $success = $this->postModel->deleteComment($commentId, $user['id']);
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid comment ID']);
        }
    }

    private function getProfileImageUrl($path) {
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
}