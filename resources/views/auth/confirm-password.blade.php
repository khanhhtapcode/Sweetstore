<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-pink-500 to-orange-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1V7l-7-5z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Sweet Delight</h2>
                <p class="text-lg text-pink-600 font-medium mb-8">Ngọt ngào mỗi khoảnh khắc</p>
            </div>

            <!-- Security Notice -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-pink-100">
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-pink-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-900 text-center mb-4">Xác Nhận Bảo Mật</h3>
                <div class="mb-6 text-sm text-gray-600 text-center bg-pink-50 p-4 rounded-lg border border-pink-200">
                    {{ __('Đây là khu vực bảo mật của ứng dụng. Vui lòng xác nhận mật khẩu trước khi tiếp tục truy cập vào tài khoản Sweet Delight của bạn.') }}
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

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

                    <div class="flex justify-center pt-4">
                        <x-primary-button class="w-full bg-gradient-to-r from-pink-500 to-orange-400 hover:from-pink-600 hover:to-orange-500 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 focus:ring-4 focus:ring-pink-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Xác Nhận') }}
                        </x-primary-button>
                    </div>
                </form>
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
