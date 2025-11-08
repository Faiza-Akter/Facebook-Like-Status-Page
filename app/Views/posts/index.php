<?php
$title = 'Posts | AuthBoard';
ob_start();
?>
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-12">
        <div class="text-center sm:text-left">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-700 bg-clip-text text-transparent mb-4">Community Posts</h1>
            <p class="text-slate-700 text-base font-light">Discover what others are sharing in our community</p>
        </div>
        <a href="/posts/create" 
           class="bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white px-8 py-4 rounded-2xl transition-all duration-300 font-semibold flex items-center shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Post
        </a>
    </div>

    <!-- Posts Feed -->
    <div class="space-y-8">
        <?php if (empty($posts)): ?>
            <!-- Empty State -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative text-center py-20 bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-12 hover:shadow-3xl transition-all duration-500">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-slate-800 mb-4">No posts yet</h3>
                    <p class="text-slate-600 text-lg mb-8 max-w-md mx-auto font-light">Be the first to share something amazing with the community!</p>
                    <a href="/posts/create" class="bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white px-8 py-4 rounded-2xl transition-all duration-300 inline-block font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20">
                        Create First Post
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <!-- Post Card -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/60 p-5 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                        <!-- Post Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <?php if ($post['user_profile_picture']): ?>
                                        <div class="w-14 h-14 rounded-full border-2 border-white shadow-lg overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 p-0.5 group/profile">
                                            <img src="<?= htmlspecialchars($post['user_profile_picture']) ?>" 
                                                 alt="Profile" 
                                                 class="w-full h-full rounded-full object-cover group-hover/profile:scale-110 transition-transform duration-300">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-base border-2 border-white shadow-lg group-hover:scale-105 transition-transform duration-300">
                                            <?= strtoupper(substr($post['user_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-lg"><?= htmlspecialchars($post['user_name']) ?></h3>
                                    <p class="text-slate-600 text-sm flex items-center mt-1 font-light">
                                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?= date('M j, Y g:i A', strtotime($post['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Delete Button (only show for post owner) -->
                            <?php if ($post['user_id'] == ($_SESSION['user']['id'] ?? null)): ?>
                            <form method="POST" action="/posts/delete" class="ml-4">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this post?')"
                                        class="text-slate-400 hover:text-red-500 transition-all duration-300 p-3 rounded-2xl hover:bg-red-50 border border-transparent hover:border-red-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Post Content -->
                        <div class="mb-6">
                            <p class="text-slate-800 leading-relaxed whitespace-pre-wrap text-lg font-light"><?= htmlspecialchars($post['content']) ?></p>
                        </div>
                        
                        <!-- Post Image -->
                        <?php if ($post['image']): ?>
                            <div class="mb-6 rounded-2xl overflow-hidden border border-slate-200/50 shadow-lg group/image">
                                <img src="<?= htmlspecialchars($post['image']) ?>" 
                                     alt="Post image" 
                                     class="w-full h-auto max-h-96 object-cover group-hover/image:scale-105 transition-transform duration-500 cursor-zoom-in">
                            </div>
                        <?php endif; ?>
                        
                        <!-- Post Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-slate-200/50">
                            <div class="flex space-x-8">
                                <button class="flex items-center space-x-3 text-slate-600 hover:text-blue-600 transition-all duration-300 p-3 rounded-2xl hover:bg-blue-50 group/action border border-transparent hover:border-blue-200">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm group-hover/action:scale-110 transition-transform duration-300">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-sm font-semibold block">Like</span>
                                        <span class="text-xs text-slate-500 font-light">Show appreciation</span>
                                    </div>
                                </button>
                                <button class="flex items-center space-x-3 text-slate-600 hover:text-purple-600 transition-all duration-300 p-3 rounded-2xl hover:bg-purple-50 group/action border border-transparent hover:border-purple-200">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm group-hover/action:scale-110 transition-transform duration-300">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-sm font-semibold block">Comment</span>
                                        <span class="text-xs text-slate-500 font-light">Join discussion</span>
                                    </div>
                                </button>
                            </div>
                            
                            <!-- Post Stats -->
                            <div class="flex items-center space-x-6 text-slate-500 text-sm">
                                <span class="flex items-center space-x-2 bg-blue-50 text-blue-600 px-3 py-2 rounded-xl font-medium">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                    </svg>
                                    <span>24</span>
                                </span>
                                <span class="flex items-center space-x-2 bg-purple-50 text-purple-600 px-3 py-2 rounded-xl font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span>8</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
