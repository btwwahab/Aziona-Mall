<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{

    public function shop(Request $request) {
        $query = Product::where('status', 1);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $sort = $request->get('sort', 'latest'); 
        
        if ($sort === 'low_to_high') {
            $query->orderBy('discount_price', 'asc');
        } elseif ($sort === 'high_to_low') {
            $query->orderBy('discount_price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc'); 
        }

        $products = $query->paginate(8)->appends($request->query());

        $categories = Category::where('status', 1)->get();

        $selectedCategory = $request->filled('category') ? Category::find($request->category) : null;

        return view('frontend.shop', compact('products', 'categories', 'selectedCategory'));
    }
    

    public function details($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $this->trackView($product->id);
        
        $product->updateTrendingScore();
        
        $relatedProduct = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        
        return view('frontend.details', compact('product', 'relatedProduct'));
    }

    public function trackView($productId)
    {
        $ip = request()->ip();
    
        $existingView = ProductView::where('product_id', $productId)
            ->where('ip_address', $ip)
            ->exists();
    
        if (!$existingView) {
            ProductView::create([
                'product_id' => $productId,
                'ip_address' => $ip,
            ]);
    
            // Increment the product's view count
            Product::where('id', $productId)->increment('views');
        }
    }

    public function purchase($productId)
    {
        $product = Product::findOrFail($productId);
        
       
        $product->increment('sales_count');
        $product->updateTrendingScore();
        
        
        
        return redirect()->back()->with('success', 'Product purchased successfully');
    }

    public function shopByCategory($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = Product::where('category_id', $category->id)->where('status', 1);

        // Sorting logic for category filtering
        $sort = $request->get('sort', 'latest'); // Default sorting
        if ($sort === 'low_to_high') {
            $query->orderBy('discount_price', 'asc');
        } elseif ($sort === 'high_to_low') {
            $query->orderBy('discount_price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->appends($request->query());
        $categories = Category::where('status', 1)->get();

        return view('frontend.shop', compact('category', 'categories', 'products'));
    }

    public function compare()
{
    // Get product IDs from session
    $compareIds = session()->get('compare', []);

    // Fetch the products
    $products = Product::whereIn('id', $compareIds)->get();

    return view('frontend.compare', compact('products'));
}

public function addToCompare($id)
{
    $compare = session()->get('compare', []);

    if (!in_array($id, $compare)) {
        $compare[] = $id;
        session()->put('compare', $compare);
        return redirect()->route('compare')->with('success', 'Product added to compare!');
    } else {
        return redirect()->route('compare')->with('info', 'Already in compare list!');
    }
}


public function removeFromCompare($id)
{
    $compare = session()->get('compare', []);
    $compare = array_diff($compare, [$id]);
    session()->put('compare', $compare);

    return back()->with('success', 'Product removed from compare!');
}


}

