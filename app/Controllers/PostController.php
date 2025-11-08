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
        
        // Enhance posts with user profile pictures
        $enhancedPosts = [];
        foreach ($posts as $post) {
            $postUser = User::findById($post['user_id']);
            $post['user_profile_picture'] = $postUser['profile_picture'] ?? null;
            
            // Fix post image URL if exists
            if (!empty($post['image'])) {
                $post['image'] = $this->getPostImageUrl($post['image']);
            }
            
            $enhancedPosts[] = $post;
        }
        
        $this->view('posts/index.php', [
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
}