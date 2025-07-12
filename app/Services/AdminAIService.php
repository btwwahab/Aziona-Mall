<?php
// filepath: c:\wamp64\www\wahab-e-comerce\app\Services\AdminAIService.php
namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminAIService
{
    private $aiProductService;

    public function __construct(AIProductService $aiProductService)
    {
        $this->aiProductService = $aiProductService;
    }

    public function generateBusinessInsights($businessData = [])
    {
        try {
            // Get comprehensive admin business data
            if (empty($businessData)) {
                $businessData = $this->getAdminBusinessData();
            }

            $prompt = $this->buildAdminInsightsPrompt($businessData);

            // Use the AIProductService to call the API
            $insights = $this->aiProductService->callGrokAPI($prompt);

            return $this->formatAdminInsights($insights, $businessData);

        } catch (\Exception $e) {
            return $this->getFallbackInsights($businessData);
        }
    }

    public function generateProductSpecificInsights($productId)
    {
        try {
            if (!$productId) {
                return 'Please provide a product ID for specific insights.';
            }

            $product = Product::find($productId);

            if (!$product) {
                return 'Product not found.';
            }

            // Fix the syntax error by extracting the category name first
            $categoryName = $product->category ? $product->category->name : 'Unknown';

            $prompt = "Analyze this product and provide insights:
        
                Product: {$product->name}
                Price: \${$product->price}
                Stock: {$product->stock}
                Category: {$categoryName}
                Status: {$product->status}

                Provide specific insights about:
                1. Pricing optimization
                2. Stock management
                3. Marketing opportunities
                4. Performance analysis

                Keep response under 150 words.";

            return $this->aiProductService->callGrokAPI($prompt);

        } catch (\Exception $e) {
            return 'Unable to generate product insights at this time.';
        }
    }

    private function getAdminBusinessData()
    {
        try {
            // Get orders table columns to check what exists
            $orderColumns = Schema::getColumnListing('orders');

            // Products Analysis
            $totalProducts = Product::count();
            $activeProducts = Product::where('status', 'active')->count();
            $lowStockProducts = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();
            $outOfStockProducts = Product::where('stock', '<=', 0)->count();

            // Get top selling products (check if sales_count column exists)
            $topSellingProducts = collect([]);
            if (Schema::hasColumn('products', 'sales_count')) {
                $topSellingProducts = Product::where('sales_count', '>', 0)
                    ->orderBy('sales_count', 'desc')
                    ->take(5)
                    ->get(['name', 'sales_count', 'stock', 'price']);
            } else {
                // Alternative: get first 5 products
                $topSellingProducts = Product::take(5)->get(['name', 'stock', 'price']);
            }

            // Orders Analysis
            $totalOrders = Order::count();
            $todayOrders = Order::whereDate('created_at', today())->count();
            $weeklyOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $monthlyOrders = Order::whereMonth('created_at', now()->month)->count();
            $lastMonthOrders = Order::whereMonth('created_at', now()->subMonth()->month)->count();

            // Order Status Analysis
            $pendingOrders = Order::where('status', 'awaiting_bank_transfer')->count();
            $confirmedOrders = Order::where('status', 'confirmed')->count();
            $rejectedOrders = Order::where('status', 'rejected')->count();
            $codOrders = Order::where('status', 'cash_on_delivery')->count();

            // Revenue Analysis - Check different possible column names
            $revenueData = $this->calculateRevenue();
            $totalRevenue = $revenueData['total'];
            $monthlyRevenue = $revenueData['monthly'];
            $lastMonthRevenue = $revenueData['last_month'];

            // Customer Analysis
            $totalCustomers = User::where('role', 'customer')->count();
            // If no customers with role, count unique emails from orders
            if ($totalCustomers === 0) {
                $totalCustomers = Order::distinct('email')->count();
            }

            $newCustomersThisMonth = User::where('role', 'customer')
                ->whereMonth('created_at', now()->month)
                ->count();

            // Category Analysis
            $totalCategories = Category::count();
            $categoriesWithProducts = Category::has('products')->count();

            // Performance Metrics
            $conversionRate = $totalCustomers > 0 ? ($totalOrders / $totalCustomers) * 100 : 0;
            $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

            return [
                'products' => [
                    'total' => $totalProducts,
                    'active' => $activeProducts,
                    'low_stock' => $lowStockProducts,
                    'out_of_stock' => $outOfStockProducts,
                    'top_selling' => $topSellingProducts
                ],
                'orders' => [
                    'total' => $totalOrders,
                    'today' => $todayOrders,
                    'weekly' => $weeklyOrders,
                    'monthly' => $monthlyOrders,
                    'last_month' => $lastMonthOrders,
                    'pending' => $pendingOrders,
                    'confirmed' => $confirmedOrders,
                    'rejected' => $rejectedOrders,
                    'cod' => $codOrders
                ],
                'revenue' => [
                    'total' => $totalRevenue,
                    'monthly' => $monthlyRevenue,
                    'last_month' => $lastMonthRevenue,
                    'average_order_value' => $averageOrderValue
                ],
                'customers' => [
                    'total' => $totalCustomers,
                    'new_this_month' => $newCustomersThisMonth,
                    'conversion_rate' => $conversionRate
                ],
                'categories' => [
                    'total' => $totalCategories,
                    'with_products' => $categoriesWithProducts
                ]
            ];

        } catch (\Exception $e) {
            return $this->getBasicBusinessData();
        }
    }

    private function calculateRevenue()
    {
        try {
            // Include both confirmed and cash_on_delivery orders in revenue calculation
            $totalRevenue = Order::whereIn('status', ['confirmed', 'cash_on_delivery'])->sum('total');

            $monthlyRevenue = Order::whereIn('status', ['confirmed', 'cash_on_delivery'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total');

            $lastMonthRevenue = Order::whereIn('status', ['confirmed', 'cash_on_delivery'])
                ->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->sum('total');

            return [
                'total' => (float) $totalRevenue,
                'monthly' => (float) $monthlyRevenue,
                'last_month' => (float) $lastMonthRevenue
            ];

        } catch (\Exception $e) {
            return [
                'total' => 0,
                'monthly' => 0,
                'last_month' => 0
            ];
        }
    }

    private function calculateRevenueFromItems($status, $period = 'all')
    {
        try {
            // Update to handle both confirmed and cash_on_delivery
            $statuses = is_array($status) ? $status : [$status];

            $query = Order::whereIn('status', $statuses);

            if ($period === 'month') {
                $query->whereMonth('created_at', now()->month);
            } elseif ($period === 'last_month') {
                $query->whereMonth('created_at', now()->subMonth()->month);
            }

            // Check if order_items table exists and has relationship
            if (Schema::hasTable('order_items')) {
                $orders = $query->with('orderItems')->get();

                $total = 0;
                foreach ($orders as $order) {
                    if ($order->orderItems) {
                        foreach ($order->orderItems as $item) {
                            $total += $item->quantity * $item->price;
                        }
                    }
                }

                return $total;
            } else {
                // Use the 'total' column from orders table
                return $query->sum('total');
            }

        } catch (\Exception $e) {
            return 0;
        }
    }

    private function buildAdminInsightsPrompt($data)
    {
        return "As an expert e-commerce business analyst, provide comprehensive insights for this admin dashboard:

🏪 STORE OVERVIEW:
• Products: {$data['products']['total']} total ({$data['products']['active']} active)
• Categories: {$data['categories']['total']} total
• Customers: {$data['customers']['total']} total

📦 INVENTORY STATUS:
• Low Stock: {$data['products']['low_stock']} items
• Out of Stock: {$data['products']['out_of_stock']} items
• Stock Health: " . $this->calculateStockHealth($data['products']) . "%

📊 SALES PERFORMANCE:
• Total Orders: {$data['orders']['total']}
• This Month: {$data['orders']['monthly']} orders
• Last Month: {$data['orders']['last_month']} orders
• Monthly Growth: " . $this->calculateGrowth($data['orders']['monthly'], $data['orders']['last_month']) . "%

💰 REVENUE ANALYSIS (Including Cash on Delivery):
• Total Revenue: $" . number_format($data['revenue']['total'], 2) . " (Confirmed + COD orders)
• Monthly Revenue: $" . number_format($data['revenue']['monthly'], 2) . "
• Average Order Value: $" . number_format($data['revenue']['average_order_value'], 2) . "

🎯 ORDER STATUS:
• Pending: {$data['orders']['pending']} | Confirmed: {$data['orders']['confirmed']} | Cash on Delivery: {$data['orders']['cod']} | Rejected: {$data['orders']['rejected']}

Provide 4-5 key actionable insights focusing on:
1. Inventory management priorities
2. Sales optimization opportunities  
3. Revenue growth strategies (including COD orders)
4. Customer retention tactics
5. Operational efficiency improvements

Format as bullet points. Keep under 200 words. Be specific and actionable.";
    }

    private function formatAdminInsights($insights, $data)
    {
        return [
            'status' => 'success',
            'insights' => $insights,
            'metrics' => [
                'inventory_health' => $this->calculateInventoryHealth($data['products']),
                'sales_trend' => $this->calculateSalesTrend($data['orders']),
                'revenue_metrics' => $this->calculateRevenueMetrics($data['revenue']),
                'performance_indicators' => $this->calculatePerformanceIndicators($data),
                'recommendations' => $this->generateAdminRecommendations($data)
            ],
            'data' => $data
        ];
    }

    private function calculateInventoryHealth($products)
    {
        $total = $products['total'];
        $problematic = $products['low_stock'] + $products['out_of_stock'];
        $healthy = $total - $problematic;

        $score = $total > 0 ? ($healthy / $total) * 100 : 0;

        $status = 'excellent';
        if ($score < 50)
            $status = 'critical';
        elseif ($score < 70)
            $status = 'warning';
        elseif ($score < 90)
            $status = 'good';

        return [
            'score' => round($score),
            'status' => $status,
            'healthy_items' => $healthy,
            'total_items' => $total,
            'low_stock_items' => $products['low_stock'],
            'out_of_stock_items' => $products['out_of_stock']
        ];
    }

    private function calculateSalesTrend($orders)
    {
        $current = $orders['monthly'];
        $previous = $orders['last_month'];

        $change = $previous > 0 ? (($current - $previous) / $previous) * 100 : 0;

        $trend = 'stable';
        if ($change > 10)
            $trend = 'up';
        elseif ($change < -10)
            $trend = 'down';

        return [
            'trend' => $trend,
            'percentage_change' => round($change, 1),
            'current_month' => $current,
            'last_month' => $previous,
            'today_orders' => $orders['today'],
            'weekly_orders' => $orders['weekly']
        ];
    }

    private function calculateRevenueMetrics($revenue)
    {
        $change = $revenue['last_month'] > 0
            ? (($revenue['monthly'] - $revenue['last_month']) / $revenue['last_month']) * 100
            : 0;

        return [
            'total_revenue' => $revenue['total'],
            'monthly_revenue' => $revenue['monthly'],
            'last_month_revenue' => $revenue['last_month'],
            'revenue_growth' => round($change, 1),
            'average_order_value' => $revenue['average_order_value']
        ];
    }

    private function calculatePerformanceIndicators($data)
    {
        $fulfillmentRate = $data['orders']['total'] > 0
            ? ($data['orders']['confirmed'] / $data['orders']['total']) * 100
            : 0;

        $rejectionRate = $data['orders']['total'] > 0
            ? ($data['orders']['rejected'] / $data['orders']['total']) * 100
            : 0;

        return [
            'fulfillment_rate' => round($fulfillmentRate, 1),
            'rejection_rate' => round($rejectionRate, 1),
            'conversion_rate' => round($data['customers']['conversion_rate'], 1),
            'category_utilization' => $data['categories']['total'] > 0
                ? round(($data['categories']['with_products'] / $data['categories']['total']) * 100, 1)
                : 0
        ];
    }

    private function generateAdminRecommendations($data)
    {
        $recommendations = [];

        // Inventory Recommendations
        if ($data['products']['out_of_stock'] > 0) {
            $recommendations[] = [
                'action' => 'Critical: Restock Items',
                'message' => "{$data['products']['out_of_stock']} products are out of stock. Immediate restocking required to prevent lost sales.",
                'priority' => 'high',
                'icon' => 'bx-error-circle',
                'color' => 'danger'
            ];
        }

        if ($data['products']['low_stock'] > 0) {
            $recommendations[] = [
                'action' => 'Low Stock Alert',
                'message' => "{$data['products']['low_stock']} products are running low. Plan restocking within 7 days.",
                'priority' => 'medium',
                'icon' => 'bx-time',
                'color' => 'warning'
            ];
        }

        // Order Processing Recommendations
        if ($data['orders']['pending'] > 5) {
            $recommendations[] = [
                'action' => 'Process Pending Orders',
                'message' => "{$data['orders']['pending']} orders are awaiting payment verification. Review and process promptly.",
                'priority' => 'high',
                'icon' => 'bx-clock',
                'color' => 'info'
            ];
        }

        // Sales Performance Recommendations
        if ($data['orders']['monthly'] < $data['orders']['last_month']) {
            $recommendations[] = [
                'action' => 'Boost Monthly Sales',
                'message' => "Sales are down from last month. Consider promotions or marketing campaigns.",
                'priority' => 'medium',
                'icon' => 'bx-trending-up',
                'color' => 'warning'
            ];
        }

        // Revenue Optimization
        if ($data['revenue']['average_order_value'] < 50) {
            $recommendations[] = [
                'action' => 'Increase Order Value',
                'message' => "Average order value is low. Implement upselling strategies or bundle offers.",
                'priority' => 'medium',
                'icon' => 'bx-dollar',
                'color' => 'info'
            ];
        }

        // Success Message
        if (empty($recommendations)) {
            $recommendations[] = [
                'action' => 'All Systems Optimal',
                'message' => "Your store is performing well! Continue monitoring key metrics.",
                'priority' => 'low',
                'icon' => 'bx-check-circle',
                'color' => 'success'
            ];
        }

        return $recommendations;
    }

    private function getFallbackInsights($data)
    {
        return [
            'status' => 'error',
            'insights' => 'AI service temporarily unavailable. Based on your data: ' .
                "You have {$data['products']['total']} products, {$data['orders']['total']} orders, " .
                "and {$data['products']['out_of_stock']} items out of stock. " .
                "Focus on inventory management and order processing.",
            'metrics' => [
                'inventory_health' => $this->calculateInventoryHealth($data['products']),
                'sales_trend' => $this->calculateSalesTrend($data['orders']),
                'revenue_metrics' => $this->calculateRevenueMetrics($data['revenue']),
                'performance_indicators' => $this->calculatePerformanceIndicators($data),
                'recommendations' => $this->generateAdminRecommendations($data)
            ],
            'data' => $data
        ];
    }

    private function getBasicBusinessData()
    {
        return [
            'products' => ['total' => 0, 'active' => 0, 'low_stock' => 0, 'out_of_stock' => 0, 'top_selling' => collect()],
            'orders' => ['total' => 0, 'today' => 0, 'weekly' => 0, 'monthly' => 0, 'last_month' => 0, 'pending' => 0, 'confirmed' => 0, 'rejected' => 0, 'cod' => 0],
            'revenue' => ['total' => 0, 'monthly' => 0, 'last_month' => 0, 'average_order_value' => 0],
            'customers' => ['total' => 0, 'new_this_month' => 0, 'conversion_rate' => 0],
            'categories' => ['total' => 0, 'with_products' => 0]
        ];
    }

    private function calculateStockHealth($products)
    {
        return $products['total'] > 0
            ? round((($products['total'] - $products['low_stock'] - $products['out_of_stock']) / $products['total']) * 100)
            : 0;
    }

    private function calculateGrowth($current, $previous)
    {
        return $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : 0;
    }

    private function getDataSummary($data)
    {
        return [
            'products_total' => $data['products']['total'],
            'orders_total' => $data['orders']['total'],
            'revenue_total' => $data['revenue']['total'],
            'customers_total' => $data['customers']['total']
        ];
    }

    /**
     * Get business data without generating insights
     */
    public function getBusinessData()
    {
        return $this->getAdminBusinessData();
    }
}