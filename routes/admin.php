<?php

use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealController;
use App\Http\Controllers\Admin\paymentMethodController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminAIController;
use Illuminate\Support\Facades\Route;



// Public Admin Authentication Routes
Route::get('/admin-login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin-login', [AuthController::class, 'adminLogin']);
Route::post('/admin-logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['auth:admin', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

// AI Routes
    Route::get('/ai-insights', [AdminAIController::class, 'getInsights'])->name('ai.insights');
    Route::get('/dashboard-metrics', [AdminAIController::class, 'getDashboardMetrics'])->name('ai.dashboard-metrics');
    Route::get('/quick-stats', [AdminAIController::class, 'getQuickStats'])->name('ai.quick-stats');
    Route::get('/inventory-alerts', [AdminAIController::class, 'getInventoryAlerts'])->name('ai.inventory-alerts');
    Route::get('/performance-metrics', [AdminAIController::class, 'getPerformanceMetrics'])->name('ai.performance-metrics');
    Route::get('/recommendations', [AdminAIController::class, 'getRecommendations'])->name('ai.recommendations');
    Route::post('/product-insights', [AdminAIController::class, 'generateProductInsights'])->name('ai.product-insights');
    
    // Product AI Routes (using AIProductService)
    Route::post('/generate-description', [ProductController::class, 'generateDescription'])->name('ai.generate-description');
    Route::post('/generate-tags', [ProductController::class, 'generateTags'])->name('ai.generate-tags');
    // Category Routes
    Route::resource('categories', CategoryController::class);
    Route::post('category/upload', [CategoryController::class, 'upload'])->name('category.upload');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/category-add', [DashboardController::class, 'categoryAdd'])->name('category.add');


    // Product Routes
    Route::resource('products', ProductController::class);
    Route::get('/product-add', [ProductController::class, 'productAdd'])->name('product.add');

    // Payment Method Routes
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment.methods');
    Route::get('/order-confirm', [PaymentMethodController::class, 'orderConfirm'])->name('payment.confirm');
    Route::post('/payment-methods/update/{id}', [PaymentMethodController::class, 'updateStatus'])->name('payment.methods.update');
    Route::get('order/{id}', [PaymentMethodController::class, 'viewOrder'])->name('order.view');
    Route::post('order-confirm/{id}/approve', [PaymentMethodController::class, 'approvePayment'])->name('payment.approve');
    Route::post('order-confirm/{id}/reject', [PaymentMethodController::class, 'rejectPayment'])->name('payment.reject');

    // Deal Routes
    Route::resource('deals', DealController::class)->except(['show']);
    Route::get('/admin/deals/{id}', [DealController::class, 'show'])->name('deals.show');

    // Review Routes
    Route::get('/products-with-reviews', [AdminReviewController::class, 'products'])->name('reviews.products');
    Route::get('/product/{id}/reviews', [AdminReviewController::class, 'productReviews'])->name('reviews.product-reviews');
    Route::get('/review/{id}/edit', [AdminReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/review/{id}', [AdminReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/review/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');



});