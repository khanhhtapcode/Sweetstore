<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailService;

class TestEmail extends Command
{
    protected $signature = 'test:email {email?}';
    protected $description = 'Test email sending';

    public function handle()
    {
        $email = $this->argument('email') ?: '25a4041555@hvnh.edu.vn';

        try {
            $emailService = new EmailService();
            $result = $emailService->sendEmail(
                $email,
                'Test Email từ Sweet Store',
                '<h1>🎉 Test thành công!</h1><p>PHPMailer hoạt động tốt!</p>'
            );

            $this->info("✅ Email sent successfully to: {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Email failed: " . $e->getMessage());
            return 1;
        }
    }
}
