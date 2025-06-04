<x-admin-layout>
    <x-slot name="header">
        Chỉnh Sửa Người Dùng: {{ $user->name }}
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Chỉnh Sửa Thông Tin Người Dùng</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Xem Chi Tiết
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Quay Lại
                        </a>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Có lỗi xảy ra:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- User Avatar and Basic Info -->
                <div class="mb-8 text-center">
                    <img class="h-20 w-20 rounded-full mx-auto border-4 border-gray-200"
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=80"
                         alt="{{ $user->name }}">
                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->email }}</h3>
                        <p class="text-sm text-gray-500">ID: #{{ $user->id }} • Tham gia: {{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Họ và tên *
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="Nhập họ và tên"
                               required>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="Nhập địa chỉ email"
                               required>
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($user->email_verified_at)
                            <p class="mt-1 text-sm text-green-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Email đã được xác thực
                            </p>
                        @else
                            <p class="mt-1 text-sm text-yellow-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Email chưa được xác thực
                            </p>
                        @endif
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Vai trò *
                        </label>
                        <select id="role"
                                name="role"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                                required
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                👤 Khách hàng
                            </option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                👑 Quản trị viên
                            </option>
                        </select>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <p class="mt-1 text-sm text-gray-500">Bạn không thể thay đổi vai trò của chính mình</p>
                        @endif
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Trạng thái tài khoản</label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio"
                                       id="active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                       class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <label for="active" class="ml-3 flex items-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        <span class="w-2 h-2 bg-green-400 rounded-full inline-block mr-2"></span>
                                        Kích hoạt
                                    </span>
                                    <span class="ml-2 text-xs text-gray-500">Người dùng có thể đăng nhập và sử dụng hệ thống</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio"
                                       id="inactive"
                                       name="is_active"
                                       value="0"
                                       {{ !old('is_active', $user->is_active) ? 'checked' : '' }}
                                       {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                       class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                                <label for="inactive" class="ml-3 flex items-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        <span class="w-2 h-2 bg-red-400 rounded-full inline-block mr-2"></span>
                                        Vô hiệu hóa
                                    </span>
                                    <span class="ml-2 text-xs text-gray-500">Tạm khóa tài khoản, không thể đăng nhập</span>
                                </label>
                            </div>
                        </div>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="is_active" value="{{ $user->is_active ? '1' : '0' }}">
                            <p class="mt-2 text-sm text-gray-500">Bạn không thể thay đổi trạng thái của chính mình</p>
                        @endif
                    </div>

                    <!-- Password Change -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Thay Đổi Mật Khẩu</h4>
                        <p class="text-sm text-gray-600 mb-4">Để trống nếu không muốn thay đổi mật khẩu</p>

                        <div class="space-y-4">
                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mật khẩu mới
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                       placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)">
                                @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Xác nhận mật khẩu mới
                                </label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Nhập lại mật khẩu mới">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Thông tin bổ sung</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Ngày tham gia:</span>
                                <span class="font-medium text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Đăng nhập cuối:</span>
                                <span class="font-medium text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Chưa có' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Tổng đơn hàng:</span>
                                <span class="font-medium text-gray-900">{{ $user->orders()->count() }} đơn</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Tổng chi tiêu:</span>
                                <span class="font-medium text-gray-900">{{ number_format($user->orders()->sum('total_amount')) }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Hủy
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Cập Nhật Thông Tin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show/hide password confirmation based on password input
        document.getElementById('password').addEventListener('input', function() {
            const confirmField = document.getElementById('password_confirmation');
            if (this.value.length > 0) {
                confirmField.required = true;
                confirmField.parentElement.classList.remove('hidden');
            } else {
                confirmField.required = false;
                confirmField.value = '';
            }
        });

        // Role change warning
        document.getElementById('role').addEventListener('change', function() {
            const currentRole = '{{ $user->role }}';
            const newRole = this.value;

            if (currentRole !== newRole) {
                const roleNames = {
                    'admin': 'Quản trị viên',
                    'user': 'Khách hàng'
                };

                if (!confirm(`Bạn có chắc muốn thay đổi vai trò từ "${roleNames[currentRole]}" thành "${roleNames[newRole]}"?`)) {
                    this.value = currentRole;
                }
            }
        });

        // Status change warning
        document.querySelectorAll('input[name="is_active"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const currentStatus = {{ $user->is_active ? 'true' : 'false' }};
                const newStatus = this.value === '1';

                if (currentStatus !== newStatus) {
                    const statusNames = {
                        true: 'Hoạt động',
                        false: 'Vô hiệu hóa'
                    };

                    if (!confirm(`Bạn có chắc muốn thay đổi trạng thái từ "${statusNames[currentStatus]}" thành "${statusNames[newStatus]}"?`)) {
                        document.querySelector(`input[name="is_active"][value="${currentStatus ? '1' : '0'}"]`).checked = true;
                    }
                }
            });
        });
    </script>
</x-admin-layout>
