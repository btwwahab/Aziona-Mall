<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Latest orders with pagination
        $orders = Order::latest()->paginate(10);

        // Total order counts by status
        $totalOrders = Order::count();
        $confirmedOrders = Order::whereIn('status', ['confirmed', 'paid', 'cash_on_delivery'])->count();
        $pendingOrders = Order::whereIn('status', ['awaiting_bank_transfer', 'pending'])->count();
        $rejectedOrders = Order::where('status', 'rejected')->count();

        // Current and previous month values
        $currentMonth = now()->month;
        $previousMonth = now()->subMonth()->month;

        // Helper function to count orders by month and status
        $countOrders = fn($month, $statuses) =>
            Order::whereMonth('created_at', $month)
                ->when(is_array($statuses), fn($q) => $q->whereIn('status', $statuses), fn($q) => $q->where('status', $statuses))
                ->count();

        $currentTotal = Order::whereMonth('created_at', $currentMonth)->count();
        $previousTotal = Order::whereMonth('created_at', $previousMonth)->count();

        $currentConfirmed = $countOrders($currentMonth, ['confirmed', 'paid', 'cash_on_delivery']);
        $previousConfirmed = $countOrders($previousMonth, ['confirmed', 'paid', 'cash_on_delivery']);

        $currentPending = $countOrders($currentMonth, ['awaiting_bank_transfer', 'pending']);
        $previousPending = $countOrders($previousMonth, ['awaiting_bank_transfer', 'pending']);

        $currentRejected = $countOrders($currentMonth, 'rejected');
        $previousRejected = $countOrders($previousMonth, 'rejected');

        // Percentage change
        $percentages = [
            'total' => $this->calculatePercentageChange($previousTotal, $currentTotal),
            'confirmed' => $this->calculatePercentageChange($previousConfirmed, $currentConfirmed),
            'pending' => $this->calculatePercentageChange($previousPending, $currentPending),
            'rejected' => $this->calculatePercentageChange($previousRejected, $currentRejected),
        ];

        // Monthly chart data by status
        $getMonthlyData = function ($statuses) {
            return Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->when(is_array($statuses), fn($q) => $q->whereIn('status', $statuses), fn($q) => $q->where('status', $statuses))
                ->groupBy('month')
                ->pluck('total', 'month')
                ->mapWithKeys(fn($val, $key) => [(int) $key => (int) $val])
                ->toArray();
        };

        $monthlyConfirmed = array_replace(array_fill(1, 12, 0), $getMonthlyData(['confirmed', 'paid', 'cash_on_delivery']));
        $monthlyPending = array_replace(array_fill(1, 12, 0), $getMonthlyData(['awaiting_bank_transfer', 'pending']));
        $monthlyRejected = array_replace(array_fill(1, 12, 0), $getMonthlyData('rejected'));

        // Get inventory data for AI insights
        $inventoryData = $this->getInventoryAnalysis();

        // Calculate revenue including COD orders
        $totalRevenue = Order::whereIn('status', ['confirmed', 'cash_on_delivery'])->sum('total');
        $monthlyRevenue = Order::whereIn('status', ['confirmed', 'cash_on_delivery'])
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        return view('admin.home', compact(
            'orders',
            'totalOrders',
            'confirmedOrders',
            'pendingOrders',
            'rejectedOrders',
            'monthlyConfirmed',
            'monthlyPending',
            'monthlyRejected',
            'percentages',
            'inventoryData',
            'totalRevenue',
            'monthlyRevenue'
        ));
    }


    private function calculatePercentageChange($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }


    public function productList()
    {
        return view('admin.admin-product.product-list');
    }

    public function categoryAdd()
    {
        return view('admin.admin-category.category-create');
    }

    private function getInventoryAnalysis()
    {
        try {
            $totalProducts = Product::count();
            $lowStockItems = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();
            $outOfStockItems = Product::where('stock', '<=', 0)->count();
            $totalStockValue = Product::sum('stock');

            return [
                'total_products' => $totalProducts,
                'low_stock_items' => $lowStockItems,
                'out_of_stock_items' => $outOfStockItems,
                'total_stock_value' => $totalStockValue,
                'status' => 'success'
            ];

        } catch (\Exception $e) {
            return [
                'total_products' => 0,
                'low_stock_items' => 0,
                'out_of_stock_items' => 0,
                'total_stock_value' => 0,
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
