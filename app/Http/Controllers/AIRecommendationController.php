<?php
// filepath: app/Http/Controllers/AIRecommendationController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Services\AIProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AIRecommendationController extends Controller
{
    private $aiService;

    public function __construct(AIProductService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function getRecommendations(Request $request)
    {
        try {
            $type = $request->get('type', 'general');
            $userId = Auth::id();

            $recommendations = [];
            
            switch ($type) {
                case 'general':
                    $recommendations = $this->getGeneralRecommendations($userId);
                    break;
                case 'trending':
                    $recommendations = $this->getTrendingRecommendations();
                    break;
                case 'related':
                    $recommendations = $this->getRelatedRecommendations($request);
                    break;
                case 'cart':
                    $recommendations = $this->getCartRecommendations($userId);
                    break;
                case 'personal':
                    $recommendations = $this->getPersonalizedRecommendations($userId);
                    break;
                default:
                    $recommendations = $this->getGeneralRecommendations($userId);
            }

            return response()->json([
                'status' => 'success',
                'recommendations' => $recommendations,
                'type' => $type
            ]);

        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load recommendations',
                'recommendations' => []
            ], 500);
        }
    }

    private function getGeneralRecommendations($userId)
    {
        try {
            
            // Your products use boolean status (true/false), not string ('active')
            $products = Product::with('category')
                ->where('status', true)  // Changed from 'active' to true
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->limit(4)
                ->get();

            return $products->map(function ($product) {
                return $this->formatProductRecommendation($product, 'Popular customers choice');
            })->toArray();

        } catch (\Exception $e) {
           
            return $this->getFallbackRecommendations();
        }
    }

    private function getTrendingRecommendations()
    {
        try {
            
            $products = Product::with('category')
                ->where('status', true)  // Changed from 'active' to true
                ->where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
            

            return $products->map(function ($product) {
                return $this->formatProductRecommendation($product, 'Trending now ');
            })->toArray();

        } catch (\Exception $e) {
           
            return $this->getFallbackRecommendations();
        }
    }

    private function getRelatedRecommendations($request)
    {
        try {
            $productId = $request->get('product_id');
            $currentProduct = Product::find($productId);
            
            if (!$currentProduct) {
                return $this->getGeneralRecommendations(null);
            }

            $products = Product::with('category')
                ->where('category_id', $currentProduct->category_id)
                ->where('id', '!=', $currentProduct->id)
                ->where('status', true)  // Changed from 'active' to true
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->limit(4)
                ->get();

            return $products->map(function ($product) {
                return $this->formatProductRecommendation($product, 'Related to your interest');
            })->toArray();

        } catch (\Exception $e) {
           
            return $this->getFallbackRecommendations();
        }
    }

    private function getCartRecommendations($userId)
    {
        try {
            $products = Product::with('category')
                ->where('status', true)  // Changed from 'active' to true
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->limit(4)
                ->get();

            return $products->map(function ($product) {
                return $this->formatProductRecommendation($product, 'Frequently bought together');
            })->toArray();

        } catch (\Exception $e) {
           
            return $this->getFallbackRecommendations();
        }
    }

    private function getPersonalizedRecommendations($userId)
    {
        try {
            if (!$userId) {
                return $this->getGeneralRecommendations(null);
            }

            $products = Product::with('category')
                ->where('status', true)  // Changed from 'active' to true
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->limit(4)
                ->get();

            return $products->map(function ($product) {
                return $this->formatProductRecommendation($product, 'Based on your interests');
            })->toArray();

        } catch (\Exception $e) {
           
            return $this->getFallbackRecommendations();
        }
    }

    private function formatProductRecommendation($product, $reason)
    {
        // Get image URL with fallback
        $imageUrl = $this->getProductImageUrl($product);
        
        // Get product URL
        $productUrl = route('product-details', $product->slug);
        
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => number_format($product->price, 2),
            'discount_price' => $product->discount_price ? number_format($product->discount_price, 2) : null,
            'image' => $imageUrl,
            'url' => $productUrl,
            'category' => $product->category ? $product->category->name : 'Uncategorized',
            'reason' => $reason,
            'stock' => $product->stock
        ];
    }

    private function getProductImageUrl($product)
    {
        // Check if image_front exists and is not null
        if ($product->image_front) {
            return asset('storage/' . $product->image_front);
        }
        
        // Check if image_back exists as fallback
        if ($product->image_back) {
            return asset('storage/' . $product->image_back);
        }
        
        // Return placeholder image
        return asset('assets/img/product-placeholder.jpg');
    }

    private function getFallbackRecommendations()
    {
        try {
            // Get any products without status filter as absolute fallback
            $products = Product::with('category')
                ->where('stock', '>', 0)
                ->limit(4)
                ->get();
            

            return $products->map(function ($product) {
                return $this->formatProductRecommendation($product, 'Recommended for you');
            })->toArray();

        } catch (\Exception $e) {
           
            return [];
        }
    }
}