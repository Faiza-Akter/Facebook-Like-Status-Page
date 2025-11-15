<?php
$title = 'Search Users | AuthBoard';
ob_start();
?>
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-700 bg-clip-text text-transparent mb-4">Find Users</h1>
        <p class="text-slate-700 text-xl font-light">Discover and connect with other users</p>
    </div>

    <!-- Search Form -->
    <div class="group relative mb-12">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
        <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-8 hover:shadow-2xl transition-all duration-500">
            <form method="GET" action="/search/users" class="space-y-6">
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="<?= htmlspecialchars($query) ?>" 
                           placeholder="Search by name or email..."
                           class="w-full px-6 py-4 text-lg border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/50 backdrop-blur-sm shadow-sm hover:shadow-md pl-14">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-5">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white py-4 px-6 rounded-2xl font-bold text-lg transition-all duration-300 transform hover:-translate-y-1 shadow-xl hover:shadow-2xl border border-white/20">
                    Search Users
                </button>
            </form>
        </div>
    </div>

    <!-- Search Results -->
    <?php if (!empty($query)): ?>
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">
                Search Results for "<?= htmlspecialchars($query) ?>"
                <span class="text-slate-600 text-lg font-normal">(<?= count($users) ?> users found)</span>
            </h2>
            
            <?php if (empty($users)): ?>
                <!-- No Results -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative text-center py-16 bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-12 hover:shadow-3xl transition-all duration-500">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-4">No users found</h3>
                        <p class="text-slate-600 text-lg mb-8 max-w-md mx-auto font-light">Try searching with different keywords</p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Users Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($users as $user): ?>
                        <div class="group relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                            <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/60 p-6 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 text-center">
                                <!-- User Avatar -->
                                <a href="/profile/<?= $user['id'] ?>" class="block mb-4 group/avatar">
                                    <?php if (!empty($user['profile_picture'])): ?>
                                        <div class="w-20 h-20 rounded-full border-2 border-white shadow-lg overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 p-0.5 mx-auto group-hover/avatar:scale-105 transition-transform duration-300">
                                            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                                                 alt="Profile" 
                                                 class="w-full h-full rounded-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl border-2 border-white shadow-lg mx-auto group-hover/avatar:scale-105 transition-transform duration-300">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                
                                <!-- User Info -->
                                <a href="/profile/<?= $user['id'] ?>" class="block group/name">
                                    <h3 class="font-bold text-slate-800 text-lg mb-2 group-hover/name:text-blue-600 transition-colors duration-200"><?= htmlspecialchars($user['name']) ?></h3>
                                </a>
                                
                                <p class="text-slate-600 text-sm mb-4 font-light">
                                    Joined <?= date('M Y', strtotime($user['created_at'])) ?>
                                </p>
                                
                                <!-- View Profile Button -->
                                <a href="/profile/<?= $user['id'] ?>" 
                                   class="inline-block bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white px-6 py-2 rounded-xl transition-all duration-300 font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border border-white/20">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';