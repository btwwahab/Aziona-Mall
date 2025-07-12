<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
{
    View::composer('frontend.partials.header', function ($view) {
        try {
            $wishlistCount = auth()->check() ? Wishlist::where('user_id', auth()->id())->count() : 0;
            $cartCount = auth()->check() ? Cart::where('user_id', auth()->id())->count() : 0;
        } catch (\Exception $e) {
            $wishlistCount = 0;
            $cartCount = 0;
        }
        
        $view->with(compact('wishlistCount', 'cartCount'));
    });
    
}

}
