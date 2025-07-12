<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\AIProductService;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::with('category')->latest()->paginate(10);
        return view('admin.admin-product.product-list', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
        public function create()
    {
       //
    }

    public function productAdd() {
        $categories = Category::where('status', 1)->get();
        return view('admin.admin-product.product-create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'tags' => 'required|string',
            'image_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image_back' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $imageFrontPath = $request->file('image_front')->store('products' , 'public');
            $imageBackPath = null;

            if($request->hasFile('image_back')) {
                $imageBackPath = $request->file('image_back')->store('products' , 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'description' => $request->description,
                'stock' => $request->stock,
                'brand' => $request->brand,
                'status' => $request->status,
                'tags' => $request->tags,
                'image_front' => $imageFrontPath,
                'image_back' => $imageBackPath
            ]);

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.admin-product.product-view', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('status' , 1)->get();
        return view('admin.admin-product.product-edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'tags' => 'nullable|string',
            'image_front' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'description' => $request->description,
                'stock' => $request->stock,
                'status' => $request->status,
                'tags' => $request->tags
            ];

            if($request->hasFile('image_front')) {
                $imageFrontPath = $request->file('image_front')->store('products' , 'public');
                $updateData['image_front'] = $imageFrontPath;
            }

            if($request->hasFile('image_back')) {
                $imageBackPath = $request->file('image_back')->store('products' , 'public');
                $updateData['image_back'] = $imageBackPath;
            }

            $product->update($updateData);

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


}
