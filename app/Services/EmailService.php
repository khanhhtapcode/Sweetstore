<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setupSMTP();
    }

    private function setupSMTP()
    {
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = env('MAIL_HOST', 'smtp.gmail.com');
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = env('MAIL_USERNAME');
            $this->mail->Password   = env('MAIL_PASSWORD');
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = env('MAIL_PORT', 587);

            // Charset
            $this->mail->CharSet = 'UTF-8';

            // Default sender
            $this->mail->setFrom(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME', 'Sweet Store')
            );

        } catch (Exception $e) {
            throw new \Exception("SMTP setup failed: {$this->mail->ErrorInfo}");
        }
    }

    /**
     * Send verification email
     */
    public function sendVerificationEmail($userEmail, $userName, $verificationUrl)
    {
        try {
            // Recipients
            $this->mail->addAddress($userEmail, $userName);

            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Xác thực tài khoản Sweet Store';

            $htmlBody = $this->getVerificationEmailTemplate($userName, $verificationUrl);
            $this->mail->Body = $htmlBody;

            // Plain text version
            $this->mail->AltBody = "Xin chào {$userName},\n\nVui lòng click vào link sau để xác thực tài khoản:\n{$verificationUrl}";

            $result = $this->mail->send();

            // Clear recipients for next email
            $this->mail->clearAddresses();

            return $result;

        } catch (Exception $e) {
            \Log::error("Email sending failed: " . $this->mail->ErrorInfo);
            throw new \Exception("Email không thể gửi: {$this->mail->ErrorInfo}");
        }
    }

    /**
     * Send reset password email
     */
    public function sendResetPasswordEmail($userEmail, $userName, $resetUrl)
    {
        try {
            $this->mail->addAddress($userEmail, $userName);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Đặt lại mật khẩu Sweet Store';

            $htmlBody = $this->getResetPasswordTemplate($userName, $resetUrl);
            $this->mail->Body = $htmlBody;
            $this->mail->AltBody = "Xin chào {$userName},\n\nVui lòng click vào link sau để đặt lại mật khẩu:\n{$resetUrl}";

            $result = $this->mail->send();
            $this->mail->clearAddresses();

            return $result;

        } catch (Exception $e) {
            \Log::error("Reset password email failed: " . $this->mail->ErrorInfo);
            throw new \Exception("Email không thể gửi: {$this->mail->ErrorInfo}");
        }
    }

    /**
     * Send custom email
     */
    public function sendEmail($to, $subject, $body, $toName = '')
    {
        try {
            $this->mail->addAddress($to, $toName);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            $result = $this->mail->send();
            $this->mail->clearAddresses();

            return $result;

        } catch (Exception $e) {
            \Log::error("Custom email failed: " . $this->mail->ErrorInfo);
            throw new \Exception("Email không thể gửi: {$this->mail->ErrorInfo}");
        }
    }

    /**
     * Email verification template
     */
    private function getVerificationEmailTemplate($userName, $verificationUrl)
    {
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #333;'>Xin chào {$userName}!</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại Sweet Store.</p>
            <p>Vui lòng click vào nút bên dưới để xác thực tài khoản của bạn:</p>

            <div style='text-align: center; margin: 30px 0;'>
                <a href='{$verificationUrl}'
                   style='background-color: #007bff; color: white; padding: 12px 30px;
                          text-decoration: none; border-radius: 5px; display: inline-block;'>
                    Xác thực tài khoản
                </a>
            </div>

            <p>Hoặc copy và paste link sau vào trình duyệt:</p>
            <p style='word-break: break-all; color: #007bff;'>{$verificationUrl}</p>

            <hr style='margin: 30px 0;'>
            <p style='color: #666; font-size: 12px;'>
                Email này được gửi tự động, vui lòng không reply.<br>
                © Sweet Store 2025
            </p>
        </div>";
    }

    /**
     * Reset password template
     */
    private function getResetPasswordTemplate($userName, $resetUrl)
    {
        return "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
        <h2 style='color: #333;'>Đặt lại mật khẩu</h2>
        <p>Xin chào {$userName},</p>
        <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản Sweet Store của bạn.</p>
        <p>Vui lòng click vào nút bên dưới để đặt lại mật khẩu:</p>

        <div style='text-align: center; margin: 30px 0;'>
            <a href='{$resetUrl}'
               style='background-color: #dc3545; color: white; padding: 12px 30px;
                      text-decoration: none; border-radius: 5px; display: inline-block;'>
                Đặt lại mật khẩu
            </a>
        </div>

        <p>Link này sẽ hết hạn sau 60 phút.</p>
        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>

        <hr style='margin: 30px 0;'>
        <p style='color: #666; font-size: 12px;'>
            Email này được gửi tự động, vui lòng không reply.<br>
            © Sweet Store 2025
        </p>
    </div>";
    }
}
