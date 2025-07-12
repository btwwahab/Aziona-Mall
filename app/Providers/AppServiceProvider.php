<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\AIProductService;
use App\Services\AdminAIService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OrderService::class, function ($app) {
            return new OrderService();
        });

        // Register AIProductService as singleton
        $this->app->singleton(AIProductService::class, function ($app) {
            return new AIProductService();
        });

        // Register AdminAIService as singleton
        $this->app->singleton(AdminAIService::class, function ($app) {
            return new AdminAIService($app->make(AIProductService::class));
        });

        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
