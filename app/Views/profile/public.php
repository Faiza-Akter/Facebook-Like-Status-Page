<?php
$title = $profileUser['name'] . ' | AuthBoard';
ob_start();
?>
<div class="max-w-6xl mx-auto">
    <!-- Profile Header -->
    <div class="group relative mb-12">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
        <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-8 hover:shadow-2xl transition-all duration-500">
            <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-8 lg:space-y-0 lg:space-x-12">
                <!-- Profile Picture -->
                <div class="relative group/picture">
                    <?php if (!empty($profileUser['profile_picture'])): ?>
                        <div class="w-36 h-36 rounded-full border-4 border-white/80 shadow-2xl overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600 p-1 group-hover/picture:scale-105 transition-transform duration-500">
                            <img src="<?= htmlspecialchars($profileUser['profile_picture']) ?>" 
                                 alt="Profile Picture" 
                                 class="w-full h-full rounded-full object-cover">
                        </div>
                    <?php else: ?>
                        <div class="w-36 h-36 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-5xl shadow-2xl border-4 border-white/80 group-hover/picture:scale-105 transition-transform duration-500">
                            <?= strtoupper(substr($profileUser['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Profile Info -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
                        <div>
                            <h1 class="text-4xl font-bold text-slate-800 mb-2"><?= htmlspecialchars($profileUser['name']) ?></h1>
                            <p class="text-slate-600 text-lg font-light">Member since <?= date('F Y', strtotime($profileUser['created_at'])) ?></p>
                        </div>
                        
                        <!-- Follow Button -->
                        <?php if ($currentUser['id'] != $profileUser['id']): ?>
                            <div class="mt-4 lg:mt-0">
                                <?php if ($isFollowing): ?>
                                    <button onclick="unfollowUser(<?= $profileUser['id'] ?>)" 
                                            id="follow-btn"
                                            class="bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-8 py-3 rounded-2xl transition-all duration-300 font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Following
                                    </button>
                                <?php else: ?>
                                    <button onclick="followUser(<?= $profileUser['id'] ?>)" 
                                            id="follow-btn"
                                            class="bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white px-8 py-3 rounded-2xl transition-all duration-300 font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Follow
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 max-w-md mx-auto lg:mx-0">
                        <div class="text-center group/stat">
                            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-4 border border-blue-100/50 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                                <p class="text-2xl font-bold text-slate-800" id="post-count"><?= $profileUser['post_count'] ?? 0 ?></p>
                                <p class="text-sm text-slate-600 font-medium">Posts</p>
                            </div>
                        </div>
                        <a href="/profile/<?= $profileUser['id'] ?>/followers" class="text-center group/stat block">
                            <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-4 border border-purple-100/50 hover:border-purple-200 hover:shadow-lg transition-all duration-300">
                                <p class="text-2xl font-bold text-slate-800" id="follower-count"><?= $profileUser['follower_count'] ?? 0 ?></p>
                                <p class="text-sm text-slate-600 font-medium">Followers</p>
                            </div>
                        </a>
                        <a href="/profile/<?= $profileUser['id'] ?>/following" class="text-center group/stat block">
                            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-4 border border-blue-100/50 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                                <p class="text-2xl font-bold text-slate-800"><?= $profileUser['following_count'] ?? 0 ?></p>
                                <p class="text-sm text-slate-600 font-medium">Following</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Section -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Posts by <?= htmlspecialchars($profileUser['name']) ?></h2>
        
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
                    <p class="text-slate-600 text-lg mb-8 max-w-md mx-auto font-light"><?= htmlspecialchars($profileUser['name']) ?> hasn't shared anything yet.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="space-y-8">
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
                                    <button onclick="likePost(<?= $post['id'] ?>)" 
                                            class="flex items-center space-x-3 transition-all duration-300 p-3 rounded-2xl hover:bg-blue-50 group/action border border-transparent hover:border-blue-200 <?= $post['is_liked'] ? 'text-blue-600' : 'text-slate-600 hover:text-blue-600' ?>">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm group-hover/action:scale-110 transition-transform duration-300 <?= $post['is_liked'] ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gradient-to-br from-blue-500 to-blue-600' ?>">
                                            <svg class="w-5 h-5 text-white" fill="<?= $post['is_liked'] ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-sm font-semibold block">Like</span>
                                            <span class="text-xs text-slate-500 font-light">Show appreciation</span>
                                        </div>
                                    </button>
                                </div>
                                
                                <!-- Post Stats -->
                                <div class="flex items-center space-x-6 text-slate-500 text-sm">
                                    <span id="like-count-<?= $post['id'] ?>" class="flex items-center space-x-2 bg-blue-50 text-blue-600 px-3 py-2 rounded-xl font-medium">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                        </svg>
                                        <span><?= $post['like_count'] ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Follow/Unfollow functionality
async function followUser(userId) {
    try {
        const formData = new FormData();
        formData.append('user_id', userId);
        
        const response = await fetch('/profile/follow', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update follow button
            const followBtn = document.getElementById('follow-btn');
            followBtn.innerHTML = `
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Following
            `;
            followBtn.className = 'bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-8 py-3 rounded-2xl transition-all duration-300 font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20';
            followBtn.setAttribute('onclick', `unfollowUser(${userId})`);
            
            // Update follower count
            document.getElementById('follower-count').textContent = result.follower_count;
        } else {
            alert('Failed to follow user: ' + result.message);
        }
    } catch (error) {
        console.error('Error following user:', error);
        alert('An error occurred while following the user');
    }
}

async function unfollowUser(userId) {
    try {
        const formData = new FormData();
        formData.append('user_id', userId);
        
        const response = await fetch('/profile/unfollow', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update follow button
            const followBtn = document.getElementById('follow-btn');
            followBtn.innerHTML = `
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Follow
            `;
            followBtn.className = 'bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white px-8 py-3 rounded-2xl transition-all duration-300 font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20';
            followBtn.setAttribute('onclick', `followUser(${userId})`);
            
            // Update follower count
            document.getElementById('follower-count').textContent = result.follower_count;
        } else {
            alert('Failed to unfollow user: ' + result.message);
        }
    } catch (error) {
        console.error('Error unfollowing user:', error);
        alert('An error occurred while unfollowing the user');
    }
}

// Like functionality (simplified for public profile)
async function likePost(postId) {
    try {
        const formData = new FormData();
        formData.append('post_id', postId);
        
        const response = await fetch('/posts/like', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update like count
            const likeCountElement = document.getElementById(`like-count-${postId}`);
            if (likeCountElement) {
                likeCountElement.innerHTML = `
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                    </svg>
                    <span>${result.like_count}</span>
                `;
            }
            
            // Update like button appearance
            const likeButton = document.querySelector(`button[onclick="likePost(${postId})"]`);
            if (likeButton) {
                if (result.is_liked) {
                    likeButton.classList.add('text-blue-600');
                    likeButton.classList.remove('text-slate-600', 'hover:text-blue-600');
                    const svg = likeButton.querySelector('svg');
                    svg.setAttribute('fill', 'currentColor');
                } else {
                    likeButton.classList.remove('text-blue-600');
                    likeButton.classList.add('text-slate-600', 'hover:text-blue-600');
                    const svg = likeButton.querySelector('svg');
                    svg.setAttribute('fill', 'none');
                }
            }
        } else {
            alert('Failed to like post: ' + result.message);
        }
    } catch (error) {
        console.error('Error liking post:', error);
        alert('An error occurred while liking the post');
    }
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';