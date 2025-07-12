<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Order;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

class HomeController extends Controller
{
    public function home()
    {

        $userCategories = Category::where('status', 1)->get();

        $userProducts = Product::where('status', 1)->get();

        $newArrivals = Product::where('status', 1)
            ->latest()
            ->take(4)
            ->get();

        // Hot Releases - New products marked as hot with discounts
        $hotReleases = Product::where('status', 1)
            ->whereNotNull('discount_price')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Deals - Products with biggest discount percentage
        $deals = Product::where('status', 1)
            ->whereNotNull('discount_price')
            ->whereRaw('discount_price < price')
            ->orderByRaw('((price - discount_price) / price) DESC')
            ->take(3)
            ->get();

        // Top Selling - Products with highest sales count
        $topSelling = Product::where('status', 1)
            ->where('sales_count', '>', 0)
            ->orderBy('sales_count', 'desc')
            ->take(3)
            ->get();

        // Trendy - Products with high views and recent sales
        $trendy = Product::where('status', 1)
            ->withCount([
                'views as total_views' => function ($query) {
                    $query->select(DB::raw('COUNT(DISTINCT ip_address)'));
                },
                'orderItems as total_sales' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                }
            ])
            ->orderByDesc('total_sales')
            ->orderByDesc('total_views')
            ->take(3)
            ->get();


        $userdeals = Deal::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->get();


        return view('frontend.home', compact('userCategories', 'userProducts', 'newArrivals', 'hotReleases', 'deals', 'topSelling', 'trendy', 'userdeals'));
    }

    public function account()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->latest()->get();

        $orderAddress = Address::where('user_id', Auth::id())->latest()->first();

        return view('frontend.accounts', compact('orders', 'user', 'orderAddress'));
    }

    public function viewOrder($id)
    {
        $order = Order::with(['items.product'])->where('user_id', auth()->id())->findOrFail($id);
        return view('frontend.order-view', compact('order'));
    }

    public function showProfileUpdateForm()
    {
        $user = Auth::user();
        return view('frontend.update-profile', compact('user'));
    }

    public function updateProfile(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];

    if ($request->hasFile('profile_image')) {
        // Delete old profile image if exists
        if ($user->profile_image && file_exists(public_path('storage/' . $user->profile_image))) {
            unlink(public_path('storage/' . $user->profile_image));
        }

        // Save new image using store() method
        $imagePath = $request->file('profile_image')->store('profile', 'public');

        // Save only "profile/filename.jpg" in database
        $user->profile_image = $imagePath;
    }

    $user->save();

    return redirect()->route('account')->with('success', 'Profile updated successfully!');
}

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }



}
