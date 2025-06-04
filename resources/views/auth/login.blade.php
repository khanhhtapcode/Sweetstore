<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-gradient-to-r from-pink-500 to-orange-400 rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Sweet Delight</h1>
                <p class="text-lg text-pink-600 font-medium mb-2">Ngọt ngào mỗi khoảnh khắc</p>
                <p class="text-sm text-gray-500 mb-8">Đăng nhập để khám phá thế giới bánh ngọt tuyệt vời</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-pink-100">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">Chào Mừng Trở Lại!</h2>
                    <p class="text-gray-600">Đăng nhập vào tài khoản Sweet Delight của bạn</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <x-text-input id="email"
                                          class="block w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200"
                                          type="email"
                                          name="email"
                                          :value="old('email')"
                                          placeholder="sweetdelight@example.com"
                                          required
                                          autofocus
                                          autocomplete="username" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Mật Khẩu')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <x-text-input id="password"
                                          class="block w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200"
                                          type="password"
                                          name="password"
                                          placeholder="Nhập mật khẩu của bạn"
                                          required
                                          autocomplete="current-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox"
                                   class="rounded border-pink-300 text-pink-600 shadow-sm focus:ring-pink-500 h-4 w-4"
                                   name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Ghi nhớ đăng nhập') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-pink-600 hover:text-pink-800 font-medium transition duration-200"
                               href="{{ route('password.request') }}">
                                {{ __('Quên mật khẩu?') }}
                            </a>
                        @endif
                    </div>

                    <div class="pt-4">
                        <x-primary-button class="w-full bg-gradient-to-r from-pink-500 to-orange-400 hover:from-pink-600 hover:to-orange-500 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 focus:ring-4 focus:ring-pink-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            {{ __('Đăng Nhập') }}
                        </x-primary-button>
                    </div>
                </form>

                <!-- Register Link -->
                <div class="text-center mt-6 pt-6 border-t border-pink-100">
                    <p class="text-gray-600">
                        Chưa có tài khoản?
                        <a href="{{ route('register') }}" class="text-pink-600 hover:text-pink-800 font-medium transition duration-200">
                            Đăng ký ngay
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-sm text-gray-500">
                    © 2024 Sweet Delight. Tất cả quyền được bảo lưu.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
