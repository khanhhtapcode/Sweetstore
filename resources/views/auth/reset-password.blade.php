<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-pink-500 to-orange-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Sweet Delight</h2>
                <p class="text-lg text-pink-600 font-medium mb-8">Ngọt ngào mỗi khoảnh khắc</p>
            </div>

            <!-- Reset Password Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-pink-100">
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">Đặt Lại Mật Khẩu</h3>
                <div class="mb-6 text-sm text-gray-600 text-center bg-green-50 p-4 rounded-lg border border-green-200">
                    Tạo mật khẩu mới cho tài khoản Sweet Delight của bạn. Hãy chọn mật khẩu mạnh để bảo vệ tài khoản.
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <x-text-input id="email"
                                          class="block w-full pl-10 pr-3 py-3 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50"
                                          type="email"
                                          name="email"
                                          :value="old('email', $request->email)"
                                          placeholder="sweetdelight@example.com"
                                          required
                                          autofocus
                                          autocomplete="username"
                                          readonly />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Mật Khẩu Mới')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <x-text-input id="password"
                                          class="block w-full pl-10 pr-3 py-3 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                                          type="password"
                                          name="password"
                                          placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)"
                                          required
                                          autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Xác Nhận Mật Khẩu Mới')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <x-text-input id="password_confirmation"
                                          class="block w-full pl-10 pr-3 py-3 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                                          type="password"
                                          name="password_confirmation"
                                          placeholder="Nhập lại mật khẩu mới"
                                          required
                                          autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Yêu cầu mật khẩu:</h4>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Tối thiểu 8 ký tự</li>
                            <li>• Bao gồm chữ hoa và chữ thường</li>
                            <li>• Có ít nhất 1 số</li>
                            <li>• Có ít nhất 1 ký tự đặc biệt</li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-center pt-4">
                        <x-primary-button class="w-full bg-gradient-to-r from-green-500 to-teal-400 hover:from-green-600 hover:to-teal-500 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 focus:ring-4 focus:ring-green-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            {{ __('Đặt Lại Mật Khẩu') }}
                        </x-primary-button>
                    </div>
                </form>

                <!-- Back to Login -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-pink-600 hover:text-pink-800 font-medium transition duration-200">
                        ← Quay lại đăng nhập
                    </a>
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
