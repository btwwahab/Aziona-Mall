<?php

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ReviewwController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\AIChatController;
use App\Http\Controllers\AIRecommendationController;


// User site Auth Routes
Route::
        namespace('Auth')->group(function () {

            Route::get('/register-page', [AuthController::class, 'showUserLoginForm'])->name('login-signup');
            Route::post('/login', [AuthController::class, 'userLogin'])->name('user-login');
            Route::post('/register', [AuthController::class, 'userRegister'])->name('user-register');
            Route::post('/logout', [AuthController::class, 'userLogout'])->name('user-logout');

        });


// Frontend pages routes on user site
Route::
        namespace('User')->group(function () {

            Route::get('/', [HomeController::class, 'home'])->name('home');
            Route::get('account', [HomeController::class, 'account'])->name('account');
            Route::get('detail', [HomeController::class, 'productDetail'])->name('detail');
            Route::get('/order/view/{id}', [HomeController::class, 'viewOrder'])->name('order.view');
            Route::post('profile/update', [HomeController::class, 'updateProfile'])->name('profile.update.post');
            Route::post('/change-password', [HomeController::class, 'changePassword'])->name('user.change-password');

            // Product Routes
            Route::get('shop', [ProductController::class, 'shop'])->name('shop');
            Route::get('/category/{slug}', [ProductController::class, 'shopByCategory'])->name('shop-category');
            Route::get('/product/{slug}', [ProductController::class, 'details'])->name('product-details');
            Route::post('/product/purchase/{id}', [ProductController::class, 'purchase'])->name('product.purchase');
            Route::post('/add-to-compare/{id}', [ProductController::class, 'addToCompare'])->name('addToCompare');
            Route::get('/compare', [ProductController::class, 'compare'])->name('compare');
            Route::post('/remove-from-compare/{id}', [ProductController::class, 'removeFromCompare'])->name('removeFromCompare');

            // Cart Routes
            Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
            Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
            Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
            Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
            Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('cart.count');

            // Wishlist Routes
            Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
            Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
            Route::delete('/wishlist/{id}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
            Route::get('/wishlist-count', [WishlistController::class, 'wishlistCount'])->name('wishlist.count');

            // Checkout Routes
            Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout');
            Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
            Route::post('/checkout/temp-order', [CheckoutController::class, 'createTempOrder'])->name('checkout.tempOrder');
            Route::post('/checkout/stripe', [CheckoutController::class, 'stripeCheckout'])->name('checkout.stripe');

            // Order Routes
            Route::get('stripe/success', [OrderController::class, 'stripeSuccess'])->name('stripe.success');
            Route::get('stripe/cancel', [OrderController::class, 'stripeCancel'])->name('stripe.cancel');

            //Review Routes
            Route::post('/reviews', [ReviewwController::class, 'store'])->name('reviews.store');

            // Address Routes
            Route::post('/shipping-address', [AddressController::class, 'store'])->name('shipping-address.store');
            Route::post('/shipping-address/update', [AddressController::class, 'update'])->name('shipping-address.update');
            
        });

        // AI Routes (Public API)
Route::prefix('api')->group(function () {
    Route::post('/ai-chat', [AIChatController::class, 'chat'])->name('ai.chat');
    Route::get('/ai-recommendations', [AIRecommendationController::class, 'getRecommendations']);
    Route::post('/track-view', [AIRecommendationController::class, 'trackView'])->name('ai.track-view');
});

require base_path('routes/admin.php');

Route::get('/login', function() {
    return 'Login Page';
})->name('login');