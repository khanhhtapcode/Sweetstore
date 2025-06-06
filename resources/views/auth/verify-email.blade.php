<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực email - Sweet Delights</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #fef7f0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #ec4899, #f97316);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #ec4899, #f97316);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .cupcake {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
            color: #856404;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="cupcake">🧁</div>
        <h1>Sweet Delights</h1>
        <p>Ngọt ngào mỗi khoảnh khắc</p>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="greeting">
            Xin chào <strong>{{ $user->name }}</strong>! 👋
        </div>

        <div class="message">
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Sweet Delights</strong>! 🎉</p>

            <p>Để hoàn tất quá trình đăng ký và bắt đầu khám phá thế giới bánh ngọt tuyệt vời của chúng tôi, vui lòng xác thực địa chỉ email bằng cách nhấp vào nút bên dưới:</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="button">
                ✅ Xác Thực Email Ngay
            </a>
        </div>

        <div class="warning">
            <strong>⚠️ Lưu ý quan trọng:</strong>
            <ul style="margin: 10px 0 0 20px; padding: 0;">
                <li>Link xác thực có hiệu lực trong 60 phút</li>
                <li>Nếu bạn không tạo tài khoản này, vui lòng bỏ qua email</li>
                <li>Không chia sẻ link này với bất kỳ ai</li>
            </ul>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; color: #666; font-size: 14px;">
            <p>Nếu nút không hoạt động, bạn có thể sao chép và dán link sau vào trình duyệt:</p>
            <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace;">
                {{ $verificationUrl }}
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Sweet Delights</strong> - Bánh ngọt tươi ngon mỗi ngày</p>
        <p>📧 hkkhanhpro@gmail.com | 📞 0123 456 789</p>
        <p style="margin-top: 15px; font-size: 12px; color: #999;">
            © 2024 Sweet Delights. Tất cả quyền được bảo lưu.
        </p>
    </div>
</div>
</body>
</html>
