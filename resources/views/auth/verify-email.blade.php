<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <!-- Sweet Delights Logo -->
                <div class="mx-auto h-20 w-20 bg-gradient-to-r from-pink-500 to-orange-500 rounded-full flex items-center justify-center mb-6 shadow-lg">
                    <span class="text-3xl">🧁</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Xác thực email của bạn</h2>
                <p class="mt-2 text-sm text-gray-600 font-medium">Sweet Delights</p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-lg sm:px-10 border border-gray-200">

                <!-- Success Messages -->
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        🎉 Email xác thực đã được gửi thành công! Vui lòng kiểm tra hộp thư của bạn.
                    </div>
                @endif

                <!-- Error Messages -->
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="text-center">
                    <!-- Email Icon -->
                    <div class="mb-6">
                        <div class="mx-auto w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kiểm tra email của bạn</h3>

                    <div class="mb-6 text-sm text-gray-600 space-y-2">
                        <p>Cảm ơn bạn đã đăng ký tài khoản Sweet Delights! 🎉</p>
                        <p>Chúng tôi đã gửi link xác thực đến:</p>
                        <p class="font-semibold text-pink-600 bg-pink-50 py-2 px-3 rounded-md">
                            {{ auth()->user()->email }}
                        </p>
                        <p>Vui lòng click vào link trong email để hoàn tất đăng ký và bắt đầu khám phá thế giới bánh ngọt tuyệt vời của chúng tôi.</p>
                    </div>

                    <!-- Resend Button -->
                    <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
                        @csrf
                        <button type="submit"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-pink-600 to-orange-500 hover:from-pink-700 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition duration-150 ease-in-out transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Gửi lại email xác thực qua PHPMailer
                        </button>
                    </form>
                    <!-- Instructions -->
                    <div class="bg-amber-50 border border-amber-200 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800">Lưu ý quan trọng</h3>
                                <div class="mt-2 text-sm text-amber-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Kiểm tra cả hộp thư spam/junk</li>
                                        <li>Link xác thực có hiệu lực trong 60 phút</li>
                                        <li>Hiện tại dùng PHPMailer thay vì Laravel Mail</li>
                                        <li>Liên hệ hỗ trợ nếu không nhận được email</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('home') }}"
                           class="inline-flex justify-center items-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Về trang chủ
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2 px-4 text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:underline transition duration-150">
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Support Contact -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Cần hỗ trợ? Liên hệ
                    <a href="mailto:hkkhanhpro@gmail.com" class="text-pink-600 hover:text-pink-500 transition duration-150">
                        hkkhanhpro@gmail.com
                    </a>
                    hoặc
                    <a href="tel:0123456789" class="text-pink-600 hover:text-pink-500 transition duration-150">
                        0123 456 789
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
