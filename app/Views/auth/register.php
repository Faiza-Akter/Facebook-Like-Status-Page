<?php
$title = 'Register | AuthBoard';
ob_start();
?>
<div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-purple-50/30 to-violet-100/20 py-8 px-8">
    <div class="max-w-4xl w-full flex items-center justify-between gap-16">
        <!-- Left: Brand Section -->
        <div class="flex-1 max-w-md text-center">
            <div
                class="w-26 h-20 bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-purple-500/20 mx-auto">
                <span class="text-white font-bold text-4xl">AuthBoard</span>
            </div>
            <h1 class="text-4xl font-bold text-slate-800 mb-4">Join <span
                    class="bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">AuthBoard</span>
            </h1>
            <p class="text-slate-600 text-lg">Get started with our secure authentication platform</p>
        </div>

        <!-- Right: Registration Form -->
        <div class="flex-1 max-w-md">
            <div
                class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl border border-purple-100/50 p-8 transition-all duration-300">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Create Account</h2>
                    <p class="text-slate-600">Join us today</p>
                </div>

                <form method="POST" action="/register" class="space-y-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                        <div class="relative">
                            <input id="name" name="name" type="text" required
                                class="w-full px-4 py-3 text-base border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 bg-white transition-all duration-200 placeholder-slate-400"
                                placeholder="Enter your full name">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
                        <div class="relative">
                            <input id="email" name="email" type="email" required
                                class="w-full px-4 py-3 text-base border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 bg-white transition-all duration-200 placeholder-slate-400"
                                placeholder="Enter your email">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                class="w-full px-4 py-3 text-base border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 bg-white transition-all duration-200 placeholder-slate-400"
                                placeholder="Create a password">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl active:scale-98 focus:ring-2 focus:ring-purple-500/20">
                        Create Account
                        <svg class="w-4 h-4 inline-block ml-2 -mr-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                    <p class="text-slate-600 text-sm">
                        Already have an account?
                        <a href="/login"
                            class="text-purple-600 hover:text-purple-800 font-semibold transition-colors duration-200">Sign
                            in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
