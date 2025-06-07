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
                            <label for="name" class="block text-
