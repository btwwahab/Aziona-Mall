<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;

class AIOrderService
{
    private $aiService;

    public function __construct(AIProductService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function analyzeOrderPatterns()
    {
        $orders = Order::with('orderItems.product')
                      ->where('created_at', '>=', now()->subDays(30))
                      ->get();

        $orderData = $orders->map(function ($order) {
            return [
                'total' => $order->total,
                'items_count' => $order->orderItems->count(),
                'products' => $order->orderItems->pluck('product.name')->toArray(),
                'customer_type' => $order->user ? 'registered' : 'guest'
            ];
        });

        $prompt = "Analyze these order patterns and provide insights:\n" 
                . json_encode($orderData->toArray()) 
                . "\n\nProvide insights on:\n"
                . "1. Popular product combinations\n"
                . "2. Average order value trends\n"
                . "3. Customer behavior patterns\n"
                . "4. Recommendations for inventory management";

        return $this->aiService->callGrokAPI($prompt);
    }

    public function generateOrderRecommendations($userId)
    {
        $userOrders = Order::where('user_id', $userId)
                          ->with('orderItems.product')
                          ->get();

        $purchaseHistory = $userOrders->flatMap(function ($order) {
            return $order->orderItems->pluck('product.name');
        })->unique()->toArray();

        $allProducts = Product::pluck('name', 'id')->toArray();

        $prompt = "Based on this customer's purchase history: " . implode(', ', $purchaseHistory) 
                . "\n\nFrom available products: " . implode(', ', $allProducts)
                . "\n\nRecommend 5 products they might like and explain why.";

        return $this->aiService->callGrokAPI($prompt);
    }
}