<x-admin-layout>
    <x-slot name="header">
        Thêm Tài Xế Mới 🚗
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Thông Tin Tài Xế Mới</h3>
                <div class="mt-2 flex space-x-4">
                    <a href="{{ route('admin.drivers.index') }}" class="text-gray-600 hover:text-gray-900">
                        ← Quay lại danh sách
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.drivers.store') }}" method="POST" class="p-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Thông tin cá nhân -->
                    <div class="space-y-4">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">👤 Thông Tin Cá Nhân</h4>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="Nguyễn Văn A"
                                   required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                   placeholder="example@email.com"
                                   required>
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                                   placeholder="0123456789"
                                   required>
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                Địa chỉ <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address"
                                      id="address"
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                                      placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Trạng thái <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                    id="status"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                                    required>
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>✅ Đang hoạt động</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>⏸️ Ngừng hoạt động</option>
                            </select>
                            @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Thông tin bằng lái và phương tiện -->
                    <div class="space-y-4">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">🚗 Thông Tin Bằng Lái & Phương Tiện</h4>

                        <div>
                            <label for="license_number" class="block text-sm font-medium text-gray-700 mb-1">
                                Số bằng lái xe <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="license_number"
                                   id="license_number"
                                   value="{{ old('license_number') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('license_number') border-red-500 @enderror"
                                   placeholder="123456789"
                                   required>
                            @error('license_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="license_expiry" class="block text-sm font-medium text-gray-700 mb-1">
                                Ngày hết hạn bằng lái <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   name="license_expiry"
                                   id="license_expiry"
                                   value="{{ old('license_expiry') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('license_expiry') border-red-500 @enderror"
                                   required>
                            @error('license_expiry')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Loại phương tiện <span class="text-red-500">*</span>
                            </label>
                            <select name="vehicle_type"
                                    id="vehicle_type"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_type') border-red-500 @enderror"
                                    required>
                                <option value="">-- Chọn loại xe --</option>
                                <option value="motorbike" {{ old('vehicle_type') == 'motorbike' ? 'selected' : '' }}>🏍️ Xe máy</option>
                                <option value="small_truck" {{ old('vehicle_type') == 'small_truck' ? 'selected' : '' }}>🚚 Xe tải nhỏ</option>
                                <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>🚐 Xe van</option>
                            </select>
                            @error('vehicle_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vehicle_number" class="block text-sm font-medium text-gray-700 mb-1">
                                Biển số xe <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="vehicle_number"
                                   id="vehicle_number"
                                   value="{{ old('vehicle_number') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_number') border-red-500 @enderror"
                                   placeholder="30A-12345"
                                   style="text-transform: uppercase;"
                                   required>
                            @error('vehicle_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Ghi chú
                            </label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                                      placeholder="Thông tin bổ sung về tài xế...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thông tin hướng dẫn -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <h5 class="text-sm font-semibold text-blue-800 mb-3">💡 Lưu ý</h5>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Mã tài xế sẽ được tạo tự động</li>
                                <li>• Bằng lái phải còn hiệu lực ít nhất 30 ngày</li>
                                <li>• Email và số điện thoại phải duy nhất</li>
                                <li>• Biển số xe phải đúng định dạng</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="mt-6 flex space-x-4">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                        ✅ Thêm Tài Xế
                    </button>

                    <a href="{{ route('admin.drivers.index') }}"
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                        ❌ Hủy
                    </a>

                    <button type="reset"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                        🔄 Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto format vehicle number
        document.getElementById('vehicle_number').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });

        // Validate license expiry date
        document.getElementById('license_expiry').addEventListener('change', function(e) {
            const selectedDate = new Date(e.target.value);
            const today = new Date();
            const thirtyDaysFromNow = new Date(today.getTime() + (30 * 24 * 60 * 60 * 1000));

            if (selectedDate < today) {
                alert('⚠️ Cảnh báo: Ngày hết hạn bằng lái không được là ngày quá khứ!');
                e.target.value = '';
            } else if (selectedDate < thirtyDaysFromNow) {
                alert('⚠️ Cảnh báo: Bằng lái sẽ hết hạn trong vòng 30 ngày!');
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['name', 'email', 'phone', 'address', 'license_number', 'license_expiry', 'vehicle_type', 'vehicle_number', 'status'];
            let isValid = true;

            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('❌ Vui lòng điền đầy đủ các trường bắt buộc!');
                document.querySelector('.border-red-500').focus();
            }
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            e.target.value = value;
        });

        // Email validation
        document.getElementById('email').addEventListener('blur', function(e) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (e.target.value && !emailRegex.test(e.target.value)) {
                e.target.classList.add('border-red-500');
                alert('❌ Định dạng email không hợp lệ!');
            } else {
                e.target.classList.remove('border-red-500');
            }
        });
    </script>
</x-admin-layout>
