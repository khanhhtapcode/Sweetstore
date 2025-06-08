<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\ChannelManager;
use App\Channels\CustomMailChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Sửa cách đăng ký channel - không dùng static
        $this->app->make(ChannelManager::class)->extend('custom', function () {
            return new CustomMailChannel();
        });
    }
}
