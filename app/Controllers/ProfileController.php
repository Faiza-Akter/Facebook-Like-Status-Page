<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;
use App\Models\Post;

class ProfileController extends Controller {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    public function showProfile() {
        $currentUser = Session::get('user');
        if (!$currentUser) {
            header('Location: /login');
            exit;
        }
        
        // Get fresh user data including profile picture
        $userData = User::findById($currentUser['id']);
        if (!$userData) {
            echo "User not found.";
            return;
        }
        
        $this->view('auth/profile.php', ['user' => $userData]);
    }

    public function updateProfile() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($name) || empty($email)) {
            $_SESSION['error'] = 'Name and email are required.';
            header('Location: /profile');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email address.';
            header('Location: /profile');
            exit;
        }

        // Update profile information
        $success = User::updateProfile($user['id'], $name, $email);
        
        if ($success) {
            // Get updated user data
            $updatedUser = User::findById($user['id']);
            
            // Update session with new data
            Session::set('user', [
                'id' => $updatedUser['id'],
                'name' => $updatedUser['name'],
                'email' => $updatedUser['email'],
                'profile_picture' => $updatedUser['profile_picture'] ?? null
            ]);
            $_SESSION['success'] = 'Profile updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update profile.';
        }

        header('Location: /profile');
        exit;
    }

    public function updateProfilePicture() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        // Check if file was uploaded
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Please select a valid image file.';
            header('Location: /profile');
            exit;
        }

        $file = $_FILES['profile_picture'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($file['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = 'Invalid file type. Please upload JPEG, PNG, GIF, or WebP image.';
            header('Location: /profile');
            exit;
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $_SESSION['error'] = 'File size too large. Maximum size is 5MB.';
            header('Location: /profile');
            exit;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $targetPath = $uploadDir . $fileName;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Store the full URL path in database
            $profilePicture = '/assets/uploads/profiles/' . $fileName;
            
            // Update user profile picture in database
            $success = User::updateProfilePicture($user['id'], $profilePicture);
            
            if ($success) {
                // Get updated user data
                $updatedUser = User::findById($user['id']);
                
                // Update session
                Session::set('user', [
                    'id' => $updatedUser['id'],
                    'name' => $updatedUser['name'],
                    'email' => $updatedUser['email'],
                    'profile_picture' => $updatedUser['profile_picture']
                ]);
                $_SESSION['success'] = 'Profile picture updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update profile picture in database.';
                // Remove the uploaded file if database update failed
                unlink($targetPath);
            }
        } else {
            $_SESSION['error'] = 'Failed to upload profile picture.';
        }

        header('Location: /profile');
        exit;
    }

    // New method for public profile pages
    public function showPublicProfile($userId) {
        $currentUser = Session::get('user');
        if (!$currentUser) {
            header('Location: /login');
            exit;
        }

        $profileUser = User::getPublicProfile($userId);
        if (!$profileUser) {
            http_response_code(404);
            echo "User not found.";
            return;
        }

        // Get user's posts
        $posts = $this->postModel->getPostsByUser($userId);
        
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
            $post['is_liked'] = $this->postModel->isLikedByUser($currentUser['id'], $post['id']);
            $post['comments'] = $this->postModel->getComments($post['id']);
            
            // Enhance comment user profile pictures
            foreach ($post['comments'] as &$comment) {
                if (!empty($comment['user_profile_picture'])) {
                    $comment['user_profile_picture'] = $this->getProfileImageUrl($comment['user_profile_picture']);
                }
            }
            
            $enhancedPosts[] = $post;
        }

        // Check if current user is following this profile
        $isFollowing = User::isFollowing($currentUser['id'], $userId);

        $this->view('profile/public.php', [
            'profileUser' => $profileUser,
            'posts' => $enhancedPosts,
            'isFollowing' => $isFollowing,
            'currentUser' => $currentUser
        ]);
    }

    // Follow/unfollow actions
    public function follow() {
        $currentUser = Session::get('user');
        if (!$currentUser) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $userId = $_POST['user_id'] ?? null;
        
        if ($userId) {
            $success = User::follow($currentUser['id'], $userId);
            if ($success) {
                $profileUser = User::getPublicProfile($userId);
                echo json_encode([
                    'success' => true,
                    'follower_count' => $profileUser['follower_count'] ?? 0,
                    'is_following' => true
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to follow user']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        }
    }

    public function unfollow() {
        $currentUser = Session::get('user');
        if (!$currentUser) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $userId = $_POST['user_id'] ?? null;
        
        if ($userId) {
            $success = User::unfollow($currentUser['id'], $userId);
            if ($success) {
                $profileUser = User::getPublicProfile($userId);
                echo json_encode([
                    'success' => true,
                    'follower_count' => $profileUser['follower_count'] ?? 0,
                    'is_following' => false
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to unfollow user']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        }
    }

    // Followers/Following lists
    public function showFollowers($userId) {
        $currentUser = Session::get('user');
        if (!$currentUser) {
            header('Location: /login');
            exit;
        }

        $profileUser = User::findById($userId);
        if (!$profileUser) {
            http_response_code(404);
            echo "User not found.";
            return;
        }

        $followers = User::getFollowers($userId);
        
        $this->view('profile/followers.php', [
            'profileUser' => $profileUser,
            'followers' => $followers,
            'currentUser' => $currentUser
        ]);
    }

    public function showFollowing($userId) {
        $currentUser = Session::get('user');
        if (!$currentUser) {
            header('Location: /login');
            exit;
        }

        $profileUser = User::findById($userId);
        if (!$profileUser) {
            http_response_code(404);
            echo "User not found.";
            return;
        }

        $following = User::getFollowing($userId);
        
        $this->view('profile/following.php', [
            'profileUser' => $profileUser,
            'following' => $following,
            'currentUser' => $currentUser
        ]);
    }

    // User search
    public function searchUsers() {
        $currentUser = Session::get('user');
        if (!$currentUser) {
            header('Location: /login');
            exit;
        }

        $query = $_GET['q'] ?? '';
        $users = [];
        
        if (!empty($query)) {
            $users = User::searchUsers($query);
        }

        $this->view('profile/search.php', [
            'users' => $users,
            'query' => $query,
            'currentUser' => $currentUser
        ]);
    }

    // Helper methods for image URLs
    private function getPostImageUrl($path) {
        if (empty($path)) {
            return null;
        }
        
        if (strpos($path, '/assets/') === 0) {
            return $path;
        }
        
        if (strpos($path, '/') === false) {
            return '/assets/uploads/posts/' . $path;
        }
        
        return '/' . ltrim($path, '/');
    }

    private function getProfileImageUrl($path) {
        if (empty($path)) {
            return null;
        }

        if (strpos($path, '/assets/') === 0) {
            return $path;
        }

        if (strpos($path, '/') === false) {
            return '/assets/uploads/profiles/' . $path;
        }

        return '/' . ltrim($path, '/');
    }
}