<?php

namespace App\Http\Controllers;

use App\Services\AIProductService;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    private $aiService;

    public function __construct(AIProductService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request)
    {
        $userMessage = $request->input('message');
        $context = $request->input('context', []);
        
        // Determine intent
        $intent = $this->determineIntent($userMessage);
        
        switch ($intent) {
            case 'product_search':
                return $this->handleProductSearch($userMessage);
            case 'order_status':
                return $this->handleOrderStatus($userMessage);
            case 'general_support':
                return $this->handleGeneralSupport($userMessage, $context);
            default:
                return $this->handleGeneralSupport($userMessage, $context);
        }
    }

    private function determineIntent($message)
    {
        $message = strtolower($message);
        
        if (preg_match('/\b(product|item|buy|purchase|search|find)\b/', $message)) {
            return 'product_search';
        }
        
        if (preg_match('/\b(order|status|delivery|shipped|tracking)\b/', $message)) {
            return 'order_status';
        }
        
        return 'general_support';
    }

    private function handleProductSearch($message)
    {
        // Extract product keywords
        $keywords = $this->extractKeywords($message);
        
        // Search products
        $products = Product::where('name', 'LIKE', "%{$keywords}%")
                          ->orWhere('description', 'LIKE', "%{$keywords}%")
                          ->limit(5)
                          ->get();
        
        if ($products->isEmpty()) {
            $response = "I couldn't find any products matching your search. Here are some popular items you might like:";
            $products = Product::orderBy('sales_count', 'desc')->limit(3)->get();
        } else {
            $response = "Here are some products I found for you:";
        }
        
        foreach ($products as $product) {
            $response .= "\n\n📦 **{$product->name}**\n";
            $response .= "💰 Price: \${$product->price}\n";
            $response .= "🔗 [View Product](/products/{$product->id})";
        }
        
        return response()->json(['response' => $response]);
    }

    private function handleOrderStatus($message)
    {
        // For authenticated users, get recent orders
        if (auth()->check()) {
            $orders = Order::where('user_id', auth()->id())
                          ->orderBy('created_at', 'desc')
                          ->limit(3)
                          ->get();
            
            if ($orders->isEmpty()) {
                $response = "You don't have any recent orders. Would you like to browse our products?";
            } else {
                $response = "Here are your recent orders:\n\n";
                foreach ($orders as $order) {
                    $response .= "📋 Order #{$order->id}\n";
                    $response .= "📅 Date: {$order->created_at->format('M d, Y')}\n";
                    $response .= "💰 Total: \${$order->total}\n";
                    $response .= "📦 Status: {$order->status}\n\n";
                }
            }
        } else {
            $response = "Please log in to check your order status, or provide your order number.";
        }
        
        return response()->json(['response' => $response]);
    }

    private function handleGeneralSupport($message, $context)
    {
        // Build context for AI
        $systemPrompt = "You are a helpful e-commerce customer service assistant. 
                        Help customers with their questions about products, orders, and general inquiries.
                        Be friendly, professional, and concise.";
        
        $contextString = '';
        if (!empty($context)) {
            $contextString = "Previous conversation:\n";
            foreach ($context as $msg) {
                $contextString .= "{$msg['sender']}: {$msg['content']}\n";
            }
        }
        
        $fullPrompt = $systemPrompt . "\n\n" . $contextString . "\n\nUser: " . $message;
        
        $response = $this->aiService->callGrokAPI($fullPrompt);
        
        return response()->json(['response' => $response]);
    }

    private function extractKeywords($message)
    {
        // Simple keyword extraction
        $words = explode(' ', strtolower($message));
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'i', 'want', 'need', 'looking', 'search', 'find'];
        
        return implode(' ', array_diff($words, $stopWords));
    }
}