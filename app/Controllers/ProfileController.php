<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class ProfileController extends Controller {
    public function showProfile() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }
        
        // Get fresh user data including profile picture
        $userData = User::findById($user['id']);
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
}