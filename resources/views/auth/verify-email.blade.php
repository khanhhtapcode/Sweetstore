<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-pink-500 to-orange-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Sweet Delight</h2>
                <p class="text-lg text-pink-600 font-medium mb-8">Ngọt ngào mỗi khoảnh khắc</p>
            </div>

            <!-- Email Verification -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-pink-100">
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">Xác Thực Email</h3>
                <div class="mb-6 text-sm text-gray-600 text-center bg-blue-50 p-4 rounded-lg border border-blue-200">
                    {{ __('Cảm ơn bạn đã đăng ký tài khoản Sweet Delight! Trước khi bắt đầu, bạn có thể xác thực địa chỉ email bằng cách nhấp vào liên kết chúng tôi vừa gửi cho bạn không? Nếu bạn không nhận được email, chúng tôi sẽ vui lòng gửi cho bạn một email khác.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 font-medium text-sm text-green-600 text-center bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span class="font-semibold">Email đã được gửi!</span>
                        </div>
                        {{ __('Một liên kết xác thực mới đã được gửi đến địa chỉ email bạn đã cung cấp khi đăng ký.') }}
                    </div>
                @endif

                <div class="flex flex-col space-y-4">
                    <!-- Resend Verification Email -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <x-primary-button class="w-full bg-gradient-to-r from-blue-500 to-purple-400 hover:from-blue-600 hover:to-purple-500 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 focus:ring-4 focus:ring-blue-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('Gửi Lại Email Xác Thực') }}
                        </x-primary-button>
                    </form>

                    <!-- Logout -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">hoặc</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200 border border-gray-300">
                            <svg class="h-5 w-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            {{ __('Đăng Xuất') }}
                        </button>
                    </form>
                </div>

                <!-- Help Text -->
                <div class="mt-6 text-center">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-yellow-800">Mẹo hữu ích</span>
                        </div>
                        <p class="text-xs text-yellow-700">
                            Kiểm tra thư mục spam/junk nếu bạn không thấy email xác thực.
                            Email có thể mất vài phút để được gửi đến.
                        </p>
                    </div>
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
