🎯 Laravel Starter Project

Một project Laravel được cấu hình sẵn với:

- ✅ Laravel 10+
- ⚡ Laravel Breeze (Authentication scaffolding)
- 🎨 Tailwind CSS
- 🔧 Laravel Mix (Webpack)

---

## 📦 Yêu cầu hệ thống

Trước khi bắt đầu, hãy đảm bảo bạn đã cài đặt:

| Thành phần       | Phiên bản tối thiểu |
|------------------|---------------------|
| PHP              | >= 8.1              |
| Composer         | ✅                  |
| Node.js & NPM    | Node >= 16.x        |
| MySQL / MariaDB  | ✅                  |

---

## 🚀 Bắt đầu nhanh

### 1. 📥 Clone dự án

```bash
git clone https://github.com/khanhhtapcode/Sweetstore.git
cd Sweetstore
```

### 2. 📚 Cài đặt PHP dependencies
```bash
composer install
```
### 3. ⚙️ Cấu hình môi trường
Tạo file .env từ bản mẫu:
```bash

cp .env.example .env
Chỉnh sửa các thông số kết nối cơ sở dữ liệu trong .env:
env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sweetstore
DB_USERNAME=root
DB_PASSWORD=
```
### 4. 🔑 Generate app key
   ```bash
   php artisan key:generate
```
### 5. 🧱 Chạy migration
   ```bash
   php artisan migrate
```
### 6. 📦 Cài đặt Node Modules
```bash
npm install
```
### 7. ⚒️ Biên dịch frontend
Phát triển:
```bash
npm run dev
```
### 🌐 Khởi chạy ứng dụng
```bash
php artisan serve
```
Truy cập: http://127.0.0.1:8000/
### ✅ Kiểm tra
- ✔️ Trang chủ hoạt động
- ✔️ Đăng ký / Đăng nhập
- ✔️ Giao diện Tailwind
- ✔️ Tài sản frontend được biên dịch
### 🛠️ Troubleshooting
❌ Lỗi "class not found": chạy lại composer install hoặc composer dump-autoload

❌ Lỗi Node: kiểm tra phiên bản Node >= 16

❌ Không nhận CSS: đảm bảo đã chạy npm run dev
### © Bản quyền
- Tác giả: Family Guys
- License: MIT

