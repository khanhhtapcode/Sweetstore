<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Driver;
use Carbon\Carbon;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'driver_code' => 'DR001',
                'name' => 'Nguyễn Văn Minh',
                'email' => 'minh.driver@sweetstore.com',
                'phone' => '0123456789',
                'address' => '123 Đường Láng, Phường Láng Thượng, Quận Đống Đa, Hà Nội',
                'license_number' => 'B123456789',
                'license_expiry' => Carbon::now()->addYears(2),
                'vehicle_type' => 'motorbike',
                'vehicle_number' => '30A-12345',
                'status' => 'active',
                'rating' => 4.8,
                'total_deliveries' => 156,
                'notes' => 'Tài xế có kinh nghiệm, giao hàng nhanh và đúng giờ',
                'last_active_at' => Carbon::now()->subMinutes(15),
            ],
            [
                'driver_code' => 'DR002',
                'name' => 'Trần Thị Hoa',
                'email' => 'hoa.driver@sweetstore.com',
                'phone' => '0987654321',
                'address' => '456 Phố Huế, Phường Phúc Tân, Quận Hoàn Kiếm, Hà Nội',
                'license_number' => 'B987654321',
                'license_expiry' => Carbon::now()->addYears(3),
                'vehicle_type' => 'small_truck',
                'vehicle_number' => '30B-67890',
                'status' => 'active',
                'rating' => 4.9,
                'total_deliveries' => 203,
                'notes' => 'Tài xế chuyên nghiệp, am hiểu đường phố Hà Nội',
                'last_active_at' => Carbon::now()->subHours(2),
            ],
            [
                'driver_code' => 'DR003',
                'name' => 'Lê Văn Đức',
                'email' => 'duc.driver@sweetstore.com',
                'phone' => '0345678901',
                'address' => '789 Giải Phóng, Phường Bách Khoa, Quận Hai Bà Trưng, Hà Nội',
                'license_number' => 'B345678901',
                'license_expiry' => Carbon::now()->addMonths(6), // Sắp hết hạn
                'vehicle_type' => 'motorbike',
                'vehicle_number' => '30C-11111',
                'status' => 'busy',
                'rating' => 4.6,
                'total_deliveries' => 89,
                'notes' => 'Tài xế mới, đang trong thời gian thử việc',
                'last_active_at' => Carbon::now()->subMinutes(5),
            ],
            [
                'driver_code' => 'DR004',
                'name' => 'Phạm Thị Lan',
                'email' => 'lan.driver@sweetstore.com',
                'phone' => '0456789012',
                'address' => '321 Cầu Giấy, Phường Dịch Vọng, Quận Cầu Giấy, Hà Nội',
                'license_number' => 'B456789012',
                'license_expiry' => Carbon::now()->addYears(1),
                'vehicle_type' => 'van',
                'vehicle_number' => '30D-22222',
                'status' => 'active',
                'rating' => 4.7,
                'total_deliveries' => 134,
                'notes' => 'Chuyên giao hàng các đơn hàng lớn, có kinh nghiệm với bánh kem',
                'last_active_at' => Carbon::now()->subHours(1),
            ],
            [
                'driver_code' => 'DR005',
                'name' => 'Hoàng Văn Thành',
                'email' => 'thanh.driver@sweetstore.com',
                'phone' => '0567890123',
                'address' => '654 Nguyễn Trãi, Phường Thanh Xuân Trung, Quận Thanh Xuân, Hà Nội',
                'license_number' => 'B567890123',
                'license_expiry' => Carbon::now()->subDays(10), // Đã hết hạn
                'vehicle_type' => 'motorbike',
                'vehicle_number' => '30E-33333',
                'status' => 'inactive',
                'rating' => 4.2,
                'total_deliveries' => 67,
                'notes' => 'Tạm ngừng hoạt động do bằng lái hết hạn',
                'last_active_at' => Carbon::now()->subDays(5),
            ],
            [
                'driver_code' => 'DR006',
                'name' => 'Vũ Thị Mai',
                'email' => 'mai.driver@sweetstore.com',
                'phone' => '0678901234',
                'address' => '987 Tây Sơn, Phường Trung Liệt, Quận Đống Đa, Hà Nội',
                'license_number' => 'B678901234',
                'license_expiry' => Carbon::now()->addDays(20), // Sắp hết hạn
                'vehicle_type' => 'small_truck',
                'vehicle_number' => '30F-44444',
                'status' => 'active',
                'rating' => 4.5,
                'total_deliveries' => 178,
                'notes' => 'Tài xế có kinh nghiệm lâu năm, quen thuộc khu vực nội thành',
                'last_active_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'driver_code' => 'DR007',
                'name' => 'Đặng Văn Hùng',
                'email' => 'hung.driver@sweetstore.com',
                'phone' => '0789012345',
                'address' => '159 Khương Thượng, Phường Khương Thượng, Quận Đống Đa, Hà Nội',
                'license_number' => 'B789012345',
                'license_expiry' => Carbon::now()->addYears(4),
                'vehicle_type' => 'motorbike',
                'vehicle_number' => '30G-55555',
                'status' => 'busy',
                'rating' => 4.9,
                'total_deliveries' => 245,
                'notes' => 'Tài xế xuất sắc, luôn nhận được đánh giá cao từ khách hàng',
                'last_active_at' => Carbon::now()->subMinutes(2),
            ],
            [
                'driver_code' => 'DR008',
                'name' => 'Bùi Thị Ngọc',
                'email' => 'ngoc.driver@sweetstore.com',
                'phone' => '0890123456',
                'address' => '753 Kim Mã, Phường Kim Mã, Quận Ba Đình, Hà Nội',
                'license_number' => 'B890123456',
                'license_expiry' => Carbon::now()->addMonths(18),
                'vehicle_type' => 'van',
                'vehicle_number' => '30H-66666',
                'status' => 'active',
                'rating' => 4.4,
                'total_deliveries' => 92,
                'notes' => 'Chuyên giao hàng khu vực Ba Đình và Tây Hồ',
                'last_active_at' => Carbon::now()->subHours(3),
            ]
        ];

        foreach ($drivers as $driverData) {
            Driver::create($driverData);
        }

        $this->command->info('✅ Đã tạo ' . count($drivers) . ' tài xế mẫu thành công!');
    }
}
