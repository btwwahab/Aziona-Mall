<?php
// filepath: app/Http/Controllers/Admin/AdminAIController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminAIController extends Controller
{
    private $adminAIService;

    public function __construct(AdminAIService $adminAIService)
    {
        $this->adminAIService = $adminAIService;
    }

    public function getInsights(Request $request)
    {
        try {
           
            $insights = $this->adminAIService->generateBusinessInsights();
            
            return response()->json($insights);
            
        } catch (\Exception $e) {

            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate admin insights. Please try again later.',
                'insights' => 'AI service temporarily unavailable. Please check your store metrics manually.',
                'metrics' => [
                    'inventory_health' => [
                        'score' => 0,
                        'status' => 'unknown',
                        'healthy_items' => 0,
                        'total_items' => 0,
                        'low_stock_items' => 0,
                        'out_of_stock_items' => 0
                    ],
                    'sales_trend' => [
                        'trend' => 'stable',
                        'percentage_change' => 0,
                        'current_month' => 0,
                        'last_month' => 0,
                        'today_orders' => 0,
                        'weekly_orders' => 0
                    ],
                    'revenue_metrics' => [
                        'total_revenue' => 0,
                        'monthly_revenue' => 0,
                        'last_month_revenue' => 0,
                        'revenue_growth' => 0,
                        'average_order_value' => 0
                    ],
                    'performance_indicators' => [
                        'fulfillment_rate' => 0,
                        'rejection_rate' => 0,
                        'conversion_rate' => 0,
                        'category_utilization' => 0
                    ],
                    'recommendations' => []
                ],
                'data' => []
            ], 500);
        }
    }

    public function getDashboardMetrics(Request $request)
    {
        try {
            // Get business data directly from the service
            $businessData = $this->adminAIService->getBusinessData();
            
            return response()->json([
                'status' => 'success',
                'data' => $businessData
            ]);
            
        } catch (\Exception $e) {
            
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get dashboard metrics',
                'data' => []
            ], 500);
        }
    }

    public function generateProductInsights(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            
            if (!$productId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product ID is required'
                ], 400);
            }
            
            $insights = $this->adminAIService->generateProductSpecificInsights($productId);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product insights generated successfully',
                'insights' => $insights
            ]);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate product insights'
            ], 500);
        }
    }

    public function getQuickStats(Request $request)
    {
        try {
            $insights = $this->adminAIService->generateBusinessInsights();
            $businessData = $insights['data'] ?? [];
            
            return response()->json([
                'status' => 'success',
                'stats' => [
                    'total_products' => $businessData['products']['total'] ?? 0,
                    'total_orders' => $businessData['orders']['total'] ?? 0,
                    'total_revenue' => $businessData['revenue']['total'] ?? 0,
                    'total_customers' => $businessData['customers']['total'] ?? 0,
                    'low_stock_items' => $businessData['products']['low_stock'] ?? 0,
                    'pending_orders' => $businessData['orders']['pending'] ?? 0,
                    'today_orders' => $businessData['orders']['today'] ?? 0,
                    'monthly_revenue' => $businessData['revenue']['monthly'] ?? 0
                ]
            ]);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get quick stats',
                'stats' => []
            ], 500);
        }
    }

    public function getInventoryAlerts(Request $request)
    {
        try {
            $insights = $this->adminAIService->generateBusinessInsights();
            $businessData = $insights['data'] ?? [];
            
            $alerts = [];
            
            if (($businessData['products']['out_of_stock'] ?? 0) > 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'title' => 'Out of Stock',
                    'message' => "{$businessData['products']['out_of_stock']} products are out of stock",
                    'action' => 'Restock immediately'
                ];
            }
            
            if (($businessData['products']['low_stock'] ?? 0) > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'Low Stock',
                    'message' => "{$businessData['products']['low_stock']} products are running low",
                    'action' => 'Plan restocking'
                ];
            }
            
            if (($businessData['orders']['pending'] ?? 0) > 5) {
                $alerts[] = [
                    'type' => 'info',
                    'title' => 'Pending Orders',
                    'message' => "{$businessData['orders']['pending']} orders awaiting processing",
                    'action' => 'Review payments'
                ];
            }
            
            return response()->json([
                'status' => 'success',
                'alerts' => $alerts
            ]);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get inventory alerts',
                'alerts' => []
            ], 500);
        }
    }

    public function getPerformanceMetrics(Request $request)
    {
        try {
            $insights = $this->adminAIService->generateBusinessInsights();
            
            return response()->json([
                'status' => 'success',
                'metrics' => $insights['metrics'] ?? []
            ]);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get performance metrics',
                'metrics' => []
            ], 500);
        }
    }

    public function getRecommendations(Request $request)
    {
        try {
            $insights = $this->adminAIService->generateBusinessInsights();
            
            return response()->json([
                'status' => 'success',
                'recommendations' => $insights['metrics']['recommendations'] ?? []
            ]);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get recommendations',
                'recommendations' => []
            ], 500);
        }
    }
}