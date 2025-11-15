<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AuthBoard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        secondary: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                        }
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%)',
                        'gradient-secondary': 'linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%)',
                        'gradient-light': 'linear-gradient(135deg, #f0f9ff 0%, #faf5ff 100%)',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50/30 to-purple-50/20 min-h-screen">
    <!-- Navigation -->
    <?php if (!empty($_SESSION['user'])): ?>
        <nav class="bg-gradient-to-r from-blue-600 to-purple-700 shadow-2xl border-b border-white/20 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo and Main Navigation -->
                    <div class="flex items-center space-x-12">
                        <!-- Logo -->
                        <div class="flex items-center space-x-3 group">
                            <div
                                class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300 border border-white/30">
                                <span class="text-white font-bold text-lg">AB</span>
                            </div>
                            <span class="text-2xl font-bold text-white">AuthBoard</span>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden lg:flex space-x-8">
                            <a href="/dashboard"
                                class="relative text-white/90 hover:text-white transition-all duration-300 font-semibold py-2 group/nav">
                                <span class="relative z-10">Dashboard</span>
                                <div
                                    class="absolute bottom-0 left-0 w-0 h-1 bg-white group-hover/nav:w-full transition-all duration-300 rounded-full">
                                </div>
                            </a>
                            <a href="/posts"
                                class="relative text-white/90 hover:text-white transition-all duration-300 font-semibold py-2 group/nav">
                                <span class="relative z-10">Posts</span>
                                <div
                                    class="absolute bottom-0 left-0 w-0 h-1 bg-white group-hover/nav:w-full transition-all duration-300 rounded-full">
                                </div>
                            </a>
                            <a href="/posts/create"
                                class="relative text-white/90 hover:text-white transition-all duration-300 font-semibold py-2 group/nav">
                                <span class="relative z-10">Create Post</span>
                                <div
                                    class="absolute bottom-0 left-0 w-0 h-1 bg-white group-hover/nav:w-full transition-all duration-300 rounded-full">
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- Add these to the existing navigation links -->
                    <a href="/posts/feed"
                        class="relative text-white/90 hover:text-white transition-all duration-300 font-semibold py-2 group/nav">
                        <span class="relative z-10">Your Feed</span>
                        <div
                            class="absolute bottom-0 left-0 w-0 h-1 bg-white group-hover/nav:w-full transition-all duration-300 rounded-full">
                        </div>
                    </a>
                    <a href="/search/users"
                        class="relative text-white/90 hover:text-white transition-all duration-300 font-semibold py-2 group/nav">
                        <span class="relative z-10">Find Users</span>
                        <div
                            class="absolute bottom-0 left-0 w-0 h-1 bg-white group-hover/nav:w-full transition-all duration-300 rounded-full">
                        </div>
                    </a>
                    <!-- User Menu -->
                    <div class="flex items-center space-x-6">
                        <!-- User Info -->
                        <div class="flex items-center space-x-4 group">
                            <?php if ($_SESSION['user']['profile_picture']): ?>
                                <div
                                    class="w-10 h-10 rounded-full border-2 border-white/50 shadow-lg overflow-hidden group-hover:scale-105 transition-transform duration-300 group-hover:border-white">
                                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile"
                                        class="w-full h-full object-cover">
                                </div>
                            <?php else: ?>
                                <div
                                    class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white font-bold text-sm border-2 border-white/50 shadow-lg group-hover:scale-105 transition-transform duration-300 group-hover:border-white">
                                    <?= strtoupper(substr($_SESSION['user']['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="hidden md:block text-right">
                                <p class="text-sm font-semibold text-white">
                                    <?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>
                                </p>
                                <p class="text-xs text-white/80 font-light">Online</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3">
                            <a href="/profile"
                                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-5 py-2.5 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl font-semibold text-sm border border-white/30 hover:border-white/50">
                                Profile
                            </a>
                            <a href="/logout"
                                class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white px-5 py-2.5 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl font-semibold text-sm border border-white/20 hover:border-white/40">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
        <main class="min-h-[calc(100vh-200px)]">
            <?= $content ?? '' ?>
        </main>

        <!-- Footer -->
        <footer class="mt-16 pt-8 border-t border-slate-200/50">
            <div class="text-center">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-white font-bold text-lg">AB</span>
                </div>
                <p class="text-slate-700 font-medium">AuthBoard - A modern authentication and social platform</p>
                <p class="text-slate-500 text-sm mt-2 font-light">Secure • Reliable • Professional</p>
            </div>
        </footer>
    </div>
</body>

</html>