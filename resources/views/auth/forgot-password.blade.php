<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-pink-500 to-orange-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Sweet Delight</h2>
                <p class="text-lg text-pink-600 font-medium mb-8">Ngọt ngào mỗi khoảnh khắc</p>
            </div>

            <!-- Forgot Password Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-pink-100">
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-orange-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">Quên Mật Khẩu?</h3>
                <div class="mb-6 text-sm text-gray-600 text-center bg-orange-50 p-4 rounded-lg border border-orange-200">
                    {{ __('Không sao cả! Hãy cho chúng tôi biết địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn liên kết đặt lại mật khẩu để bạn có thể chọn mật khẩu mới cho tài khoản Sweet Delight.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <x-text-input id="email"
                                          class="block w-full pl-10 pr-3 py-3 border border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200"
                                          type="email"
                                          name="email"
                                          :value="old('email')"
                                          placeholder="sweetdelight@example.com"
                                          required
                                          autofocus />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <div class="flex items-center justify-center pt-4">
                        <x-primary-button class="w-full bg-gradient-to-r from-orange-500 to-pink-400 hover:from-orange-600 hover:to-pink-500 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 focus:ring-4 focus:ring-orange-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            {{ __('Gửi Liên Kết Đặt Lại Mật Khẩu') }}
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
