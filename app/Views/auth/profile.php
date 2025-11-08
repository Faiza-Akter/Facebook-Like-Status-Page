<?php
$title = 'Profile | AuthBoard';
ob_start();

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;

// Clear the messages after displaying
unset($_SESSION['success']);
unset($_SESSION['error']);
?>
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-700 bg-clip-text text-transparent mb-4">Profile Settings</h1>
        <p class="text-slate-700 text-xl font-light">Manage your account information and profile picture</p>
    </div>

    <!-- Success/Error Messages -->
    <?php if ($success): ?>
        <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200/50 rounded-2xl text-blue-700 backdrop-blur-sm shadow-lg">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-lg">Success!</p>
                    <p class="text-blue-600"><?= htmlspecialchars($success) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="mb-8 p-6 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200/50 rounded-2xl text-red-700 backdrop-blur-sm shadow-lg">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-lg">Error!</p>
                    <p class="text-red-600"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Profile Picture Section -->
        <div class="group relative flex flex-col">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-8 hover:shadow-2xl transition-all duration-500 flex-1">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Profile Picture</h2>
                        <p class="text-slate-600 font-light">Update your profile image</p>
                    </div>
                </div>
                
                <div class="flex flex-col items-center space-y-8">
                    <div class="relative group/picture">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <div class="w-36 h-36 rounded-full border-4 border-white/80 shadow-2xl overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 p-1 group-hover/picture:scale-105 transition-transform duration-500">
                                <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                                     alt="Profile Picture" 
                                     class="w-full h-full rounded-full object-cover">
                            </div>
                        <?php else: ?>
                            <div class="w-36 h-36 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-5xl shadow-2xl border-4 border-white/80 group-hover/picture:scale-105 transition-transform duration-500">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <form method="POST" action="/profile/picture" enctype="multipart/form-data" class="w-full" id="profilePictureForm">
                        <div class="space-y-6">
                            <div>
                                <label for="profile_picture" class="block text-base font-semibold text-slate-700 mb-4">
                                    Upload new profile picture
                                </label>
                                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 hover:border-blue-400 transition-all duration-300 bg-gradient-to-br from-blue-50/50 to-purple-50/50 cursor-pointer group/upload">
                                    <input type="file" 
                                           id="profile_picture" 
                                           name="profile_picture" 
                                           accept="image/jpeg,image/png,image/gif,image/webp"
                                           class="hidden"
                                           required>
                                    <div class="text-center group-hover/upload:scale-105 transition-transform duration-300">
                                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-3 group-hover/upload:text-blue-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="text-slate-700 font-semibold text-lg">Click to upload image</p>
                                        <p class="text-sm text-slate-600 font-light mt-2">JPEG, PNG, GIF, WebP (Max 5MB)</p>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-4 px-6 rounded-2xl font-bold text-lg transition-all duration-300 transform hover:-translate-y-1 shadow-xl hover:shadow-2xl border border-white/20 group/button">
                                <svg class="w-6 h-6 inline mr-3 group-hover/button:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Update Profile Picture
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Information Section -->
        <div class="group relative flex flex-col">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-blue-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-8 hover:shadow-2xl transition-all duration-500 flex-1">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Profile Information</h2>
                        <p class="text-slate-600 font-light">Update your personal details</p>
                    </div>
                </div>
                
                <form method="POST" action="/profile/update" class="space-y-8">
                    <div class="group/field">
                        <label for="name" class="block text-base font-semibold text-slate-700 mb-4">Full Name</label>
                        <div class="relative">
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="<?= htmlspecialchars($user['name']) ?>" 
                                   required
                                   class="w-full px-6 py-4 text-lg border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/50 backdrop-blur-sm shadow-sm hover:shadow-md group-hover/field:border-blue-300">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="group/field">
                        <label for="email" class="block text-base font-semibold text-slate-700 mb-4">Email Address</label>
                        <div class="relative">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>" 
                                   required
                                   class="w-full px-6 py-4 text-lg border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 bg-white/50 backdrop-blur-sm shadow-sm hover:shadow-md group-hover/field:border-purple-300">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white py-4 px-6 rounded-2xl font-bold 
                            text-lg transition-all duration-300 transform hover:-translate-y-1 shadow-xl hover:shadow-2xl border border-white/20 group/button ">
                        <svg class="w-6 h-6 inline mr-3 group-hover/button:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Account Stats -->
    <div class="mt-8 group relative">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
        <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-8 hover:shadow-2xl transition-all duration-500">
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Account Information</h2>
                    <p class="text-slate-600 font-light">Your account details and status</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl border border-blue-100/50 hover:border-blue-200 hover:shadow-lg transition-all duration-300 text-center group/stats">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-sm group-hover/stats:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 mb-2 font-medium">Member since</p>
                    <p class="font-bold text-slate-800 text-lg"><?= date('M j, Y', strtotime($user['created_at'] ?? 'now')) ?></p>
                </div>
                <div class="p-6 bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl border border-purple-100/50 hover:border-purple-200 hover:shadow-lg transition-all duration-300 text-center group/stats">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-sm group-hover/stats:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 mb-2 font-medium">Account ID</p>
                    <p class="font-bold text-slate-800 text-lg">#<?= $user['id'] ?></p>
                </div>
                <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl border border-blue-100/50 hover:border-blue-200 hover:shadow-lg transition-all duration-300 text-center group/stats">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-sm group-hover/stats:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 mb-2 font-medium">Status</p>
                    <p class="font-bold text-blue-600 text-lg">Active</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add file validation
document.getElementById('profilePictureForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('profile_picture');
    const file = fileInput.files[0];
    
    if (!file) {
        e.preventDefault();
        alert('Please select a profile picture.');
        return;
    }
    
    // Check file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        e.preventDefault();
        alert('File size must be less than 5MB.');
        return;
    }
    
    // Check file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        e.preventDefault();
        alert('Please select a valid image file (JPEG, PNG, GIF, or WebP).');
        return;
    }
});

// Click to upload for profile picture
document.querySelector('.group\\/upload').addEventListener('click', function() {
    document.getElementById('profile_picture').click();
});

// File input change handler
document.getElementById('profile_picture').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const fileName = this.files[0].name;
        const uploadContent = this.parentElement.querySelector('.group-hover\\/upload');
        if (uploadContent) {
            uploadContent.innerHTML = `
                <svg class="w-12 h-12 text-blue-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-slate-700 font-semibold text-lg">File selected</p>
                <p class="text-sm text-slate-600 font-light mt-2">${fileName}</p>
            `;
        }
    }
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
