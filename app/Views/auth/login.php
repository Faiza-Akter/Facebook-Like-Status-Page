<?php
$title = 'Login | AuthBoard';
ob_start();
?>
<div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-100/20 py-8 px-8">
    <div class="max-w-4xl w-full flex items-center justify-between gap-16">
        <!-- Left: Brand Section -->
        <div class="flex-1 max-w-md text-center">
            <div
                class="w-26 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-blue-500/20 mx-auto">
                <span class="text-white font-bold text-4xl">AuthBoard</span>
            </div>
            <h1 class="text-4xl font-bold text-slate-800 mb-4">Welcome to <span
                    class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">AuthBoard</span>
            </h1>
            <p class="text-slate-600 text-lg">Secure authentication platform for modern applications</p>
        </div>

        <!-- Right: Login Form -->
        <div class="flex-1 max-w-md">
            <div
                class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl border border-blue-100/50 p-8 transition-all duration-300">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Sign In</h2>
                    <p class="text-slate-600">Access your account</p>
                </div>

                <form method="POST" action="/login" class="space-y-6">
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
                        <div class="relative">
                            <input id="email" name="email" type="email" required
                                class="w-full px-4 py-3 text-base border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 bg-white transition-all duration-200 placeholder-slate-400"
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
                                class="w-full px-4 py-3 text-base border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 bg-white transition-all duration-200 placeholder-slate-400"
                                placeholder="Enter your password">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl active:scale-98 focus:ring-2 focus:ring-blue-500/20">
                        Sign In
                        <svg class="w-4 h-4 inline-block ml-2 -mr-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                    <p class="text-slate-600 text-sm">
                        Don't have an account?
                        <a href="/register"
                            class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">Sign
                            up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
