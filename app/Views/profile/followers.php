<?php
$title = $profileUser['name'] . "'s Followers | AuthBoard";
ob_start();
?>
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-700 bg-clip-text text-transparent mb-4">
            <?= htmlspecialchars($profileUser['name']) ?>'s Followers
        </h1>
        <p class="text-slate-700 text-xl font-light">People following <?= htmlspecialchars($profileUser['name']) ?></p>
    </div>

    <!-- Followers List -->
    <?php if (empty($followers)): ?>
        <!-- Empty State -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <div class="relative text-center py-20 bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-12 hover:shadow-3xl transition-all duration-500">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-4">No followers yet</h3>
                <p class="text-slate-600 text-lg mb-8 max-w-md mx-auto font-light">
                    <?= htmlspecialchars($profileUser['name']) ?> doesn't have any followers yet.
                </p>
                <a href="/search/users" class="bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white px-8 py-4 rounded-2xl transition-all duration-300 inline-block font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20">
                    Find Users to Follow
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Followers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($followers as $follower): ?>
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/60 p-6 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 text-center">
                        <!-- User Avatar -->
                        <a href="/profile/<?= $follower['id'] ?>" class="block mb-4 group/avatar">
                            <?php if (!empty($follower['profile_picture'])): ?>
                                <div class="w-20 h-20 rounded-full border-2 border-white shadow-lg overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 p-0.5 mx-auto group-hover/avatar:scale-105 transition-transform duration-300">
                                    <img src="<?= htmlspecialchars($follower['profile_picture']) ?>" 
                                         alt="Profile" 
                                         class="w-full h-full rounded-full object-cover">
                                </div>
                            <?php else: ?>
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl border-2 border-white shadow-lg mx-auto group-hover/avatar:scale-105 transition-transform duration-300">
                                    <?= strtoupper(substr($follower['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </a>
                        
                        <!-- User Info -->
                        <a href="/profile/<?= $follower['id'] ?>" class="block group/name">
                            <h3 class="font-bold text-slate-800 text-lg mb-2 group-hover/name:text-blue-600 transition-colors duration-200"><?= htmlspecialchars($follower['name']) ?></h3>
                        </a>
                        
                        <p class="text-slate-600 text-sm mb-4 font-light">
                            Joined <?= date('M Y', strtotime($follower['created_at'])) ?>
                        </p>
                        
                        <!-- View Profile Button -->
                        <a href="/profile/<?= $follower['id'] ?>" 
                           class="inline-block bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white px-6 py-2 rounded-xl transition-all duration-300 font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border border-white/20">
                            View Profile
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';