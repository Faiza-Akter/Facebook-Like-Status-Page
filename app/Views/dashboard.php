<?php
$title = 'Dashboard | AuthBoard';
ob_start();
?>
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="text-center mb-16">
        <div class="w-28 h-28 mx-auto mb-8 rounded-full overflow-hidden border-4 border-white/80 shadow-2xl bg-gradient-to-br from-blue-500 to-purple-600 p-1">
            <?php if ($user['profile_picture']): ?>
                <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                     alt="Profile" 
                     class="w-full h-full rounded-full object-cover">
            <?php else: ?>
                <div class="w-full h-full bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-3xl">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>
        <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-700 bg-clip-text text-transparent mb-6">Welcome Back, <?= htmlspecialchars($user['name']) ?>!</h1>
        <p class="text-xl text-slate-700 font-light">Here's what's happening with your account today</p>
    </div>

        <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <!-- Profile Complete Card -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/60 p-8 text-center hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 h-full flex flex-col">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Profile Complete</h3>
                <p class="text-slate-600 font-light mb-4">Your account is active and ready</p>
                <div class="mt-auto">
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full w-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Community Posts Card -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-blue-600 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/60 p-8 text-center hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 h-full flex flex-col">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Community Posts</h3>
                <p class="text-slate-600 font-light mb-4">Share your thoughts with community</p>
                <div class="mt-auto">
                    <div class="flex justify-center items-center space-x-1">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-sm text-slate-500 font-medium">Live updates</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Card -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/60 p-8 text-center hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 h-full flex flex-col">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Activity</h3>
                <p class="text-slate-600 font-light mb-4">Stay connected with others</p>
                <div class="mt-auto">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Active now
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Grid - Equal Height Cards -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Profile Information -->
        <div class="group relative flex flex-col">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/60 p-8 hover:shadow-2xl transition-all duration-500 flex-1">
                <div class="flex items-center mb-8">
                    <?php if (!empty($user['profile_picture'])): ?>
                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-lg mr-4">
                            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                                 alt="Profile" 
                                 class="w-full h-full object-cover">
                        </div>
                    <?php else: ?>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg border-2 border-white shadow-lg mr-4">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Profile Information</h2>
                        <p class="text-slate-600 font-light">Your personal details</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center p-5 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl border border-blue-100/50 hover:border-blue-200 hover:shadow-md transition-all duration-300 group/item">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-sm group-hover/item:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600 font-medium">Full Name</p>
                            <p class="font-bold text-slate-800 text-lg"><?= htmlspecialchars($user['name']) ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-5 bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl border border-purple-100/50 hover:border-purple-200 hover:shadow-md transition-all duration-300 group/item">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-sm group-hover/item:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600 font-medium">Email Address</p>
                            <p class="font-bold text-slate-800 text-lg"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="group relative flex flex-col">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-blue-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/60 p-8 hover:shadow-2xl transition-all duration-500 flex-1">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Quick Actions</h2>
                    <p class="text-slate-600 font-light">Get things done faster</p>
                </div>
                
                <div class="space-y-4">
                    <a href="/posts" class="flex items-center p-5 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl border border-blue-100/50 hover:border-blue-300 hover:shadow-lg transition-all duration-300 group/item transform hover:-translate-y-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-sm group-hover/item:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v3m0-3a2 2 0 012-2h2a2 2 0 012 2m-6 5v6m4-3H9"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800 text-lg group-hover/item:text-blue-600 transition-colors duration-200">View All Posts</h3>
                            <p class="text-slate-600 font-light">See what everyone is sharing</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover/item:text-blue-500 transform group-hover/item:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="/posts/create" class="flex items-center p-5 bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl border border-purple-100/50 hover:border-purple-300 hover:shadow-lg transition-all duration-300 group/item transform hover:-translate-y-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-sm group-hover/item:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800 text-lg group-hover/item:text-purple-600 transition-colors duration-200">Create New Post</h3>
                            <p class="text-slate-600 font-light">Share your thoughts with others</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover/item:text-purple-500 transform group-hover/item:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
