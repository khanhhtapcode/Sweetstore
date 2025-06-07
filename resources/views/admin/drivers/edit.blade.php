<x-admin-layout>
    <x-slot name="header">
        Chỉnh Sửa Tài Xế: {{ $driver->name }} 🚗
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Cập Nhật Thông Tin Tài Xế</h3>
                <div class="mt-2 flex space-x-4">
                    <a href="{{ route('admin.drivers.show', $driver) }}" class="text-blue-600 hover:text-blue-900">
                        ← Quay lại chi tiết
                    </a>
                    <a href="{{ route('admin.drivers.index') }}" class="text-gray-600 hover:text-gray-900">
                        Danh sách tài xế
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.drivers.update', $driver) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

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
                            <label for="driver_code" class="block text-sm font-medium text-gray-700 mb-1">
                                Mã tài xế
                            </label>
                            <input type="text"
                                   name="driver_code"
                                   id="driver_code"
                                   value="{{ $driver->driver_code }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                                   readonly>
                            <p class="text-xs text-gray-500 mt-1">Mã tài xế không thể thay đổi</p>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $driver->name) }}"
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
                                   value="{{ old('email', $driver->email) }}"
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
                                   value="{{ old('phone', $driver->phone) }}"
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
                                      required>{{ old('address', $driver->address) }}</textarea>
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
                                <option value="active" {{ old('status', $driver->status) == 'active' ? 'selected' : '' }}>✅ Đang hoạt động</option>
                                <option value="busy" {{ old('status', $driver->status) == 'busy' ? 'selected' : '' }}>🚚 Đang bận</option>
                                <option value="inactive" {{ old('status', $driver->status) == 'inactive' ? 'selected' : '' }}>⏸️ Ngừng hoạt động</option>
                            </select>
                            @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($driver->currentOrders && $driver->currentOrders->count() > 0 && in_array(old('status', $driver->status), ['inactive']))
                                <p class="text-yellow-600 text-sm mt-1">⚠️ Tài xế đang có {{ $driver->currentOrders->count() }} đơn hàng chưa hoàn thành</p>
                            @endif
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
                                   value="{{ old('license_number', $driver->license_number) }}"
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
                                   value="{{ old('license_expiry', $driver->license_expiry ? $driver->license_expiry->format('Y-m-d') : '') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('license_expiry') border-red-500 @enderror"
                                   required>
                            @error('license_expiry')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($driver->is_license_expiring_soon || $driver->is_license_expired)
                                <p class="text-red-600 text-sm mt-1">
                                    ⚠️
                                    @if($driver->is_license_expired)
                                        Bằng lái đã hết hạn {{ abs($driver->days_to_license_expiry) }} ngày
                                    @else
                                        Bằng lái sẽ hết hạn trong {{ $driver->days_to_license_expiry }} ngày
                                    @endif
                                </p>
                            @endif
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
                                <option value="motorbike" {{ old('vehicle_type', $driver->vehicle_type) == 'motorbike' ? 'selected' : '' }}>🏍️ Xe máy</option>
                                <option value="small_truck" {{ old('vehicle_type', $driver->vehicle_type) == 'small_truck' ? 'selected' : '' }}>🚚 Xe tải nhỏ</option>
                                <option value="van" {{ old('vehicle_type', $driver->vehicle_type) == 'van' ? 'selected' : '' }}>🚐 Xe van</option>
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
                                   value="{{ old('vehicle_number', $driver->vehicle_number) }}"
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
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                                      placeholder="Thông tin bổ sung về tài xế...">{{ old('notes', $driver->notes) }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thông tin thống kê (chỉ hiển thị) -->
                        <div class="bg-gray-50 rounded-lg p-4 border">
                            <h5 class="text-sm font-semibold text-gray-800 mb-3">📊 Thống Kê Hiệu Suất</h5>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Tổng đơn hàng:</p>
                                    <p class="font-bold text-blue-600">{{ $driver->orders->count() ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Đã hoàn thành:</p>
                                    <p class="font-bold text-green-600">{{ $driver->total_deliveries ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Đang giao:</p>
                                    <p class="font-bold text-yellow-600">{{ $driver->currentOrders ? $driver->currentOrders->count() : 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Đánh giá:</p>
                                    <p class="font-bold text-purple-600">{{ $driver->formatted_rating ?? '0.0/5.0' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin cảnh báo -->
                @if($driver->is_license_expired || $driver->is_license_expiring_soon || ($driver->currentOrders && $driver->currentOrders->count() > 0))
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h5 class="text-sm font-semibold text-yellow-800 mb-2">⚠️ Lưu ý quan trọng:</h5>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            @if($driver->is_license_expired)
                                <li>• Bằng lái của tài xế đã hết hạn từ {{ abs($driver->days_to_license_expiry) }} ngày trước</li>
                            @elseif($driver->is_license_expiring_soon)
                                <li>• Bằng lái sẽ hết hạn trong {{ $driver->days_to_license_expiry }} ngày</li>
                            @endif
                            @if($driver->currentOrders && $driver->currentOrders->count() > 0)
                                <li>• Tài xế đang có {{ $driver->currentOrders->count() }} đơn hàng chưa hoàn thành</li>
                                <li>• Không nên đặt trạng thái "Ngừng hoạt động" khi còn đơn hàng đang giao</li>
                            @endif
                        </ul>
                    </div>
                @endif

                <!-- Nút hành động -->
                <div class="mt-6 flex space-x-4">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                        ✅ Cập Nhật
                    </button>

                    <a href="{{ route('admin.drivers.show', $driver) }}"
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                        ❌ Hủy
                    </a>

                    <button type="reset"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                        🔄 Reset
                    </button>

                    @if(!$driver->currentOrders || $driver->currentOrders->count() === 0)
                        <button type="button"
                                onclick="confirmDelete()"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                            🗑️ Xóa Tài Xế
                        </button>
                    @endif
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
                alert('⚠️ Cảnh báo: Bằng lái đã hết hạn!');
            } else if (selectedDate < thirtyDaysFromNow) {
                alert('⚠️ Cảnh báo: Bằng lái sẽ hết hạn trong vòng 30 ngày!');
            }
        });

        // Status change warning
        document.getElementById('status').addEventListener('change', function(e) {
            const currentOrders = {{ ($driver->currentOrders ? $driver->currentOrders->count() : 0) }};
            if (e.target.value === 'inactive' && currentOrders > 0) {
                alert('⚠️ Cảnh báo: Tài xế đang có ' + currentOrders + ' đơn hàng chưa hoàn thành. Hãy cân nhắc trước khi ngừng hoạt động!');
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

        // Delete confirmation
        function confirmDelete() {
            if (confirm('❌ Bạn có chắc chắn muốn xóa tài xế này?\n\n⚠️ Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.drivers.destroy", $driver) }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        }

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
