<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
      // Step 1: List of Products which have Reviews
      public function products()
      {
          $products = Product::has('reviews')->withCount('reviews')->get();
          return view('admin.admin-review.admin-review-product', compact('products'));
      }
  
      // Step 2: List of Reviews of a Selected Product
      public function productReviews($id)
      {
          $product = Product::with('reviews')->findOrFail($id);
          return view('admin.admin-review.admin-review-view', compact('product'));
      }
  
      public function edit($id)
      {
          $review = Review::findOrFail($id);
          return view('admin.admin-review.admin-review-edit', compact('review'));
      }
  
      public function update(Request $request, $id)
      {
          $review = Review::findOrFail($id);
  
          $request->validate([
              'name' => 'required|string|max:255',
              'email' => 'required|email',
              'comment' => 'required|string',
              'rating' => 'required|integer|min:1|max:5',
          ]);
  
          $review->update($request->only('name', 'email', 'comment', 'rating'));
  
          return redirect()->route('admin.reviews.product-reviews', $review->product_id)
          ->with('success', 'Review updated successfully.');
        }
  
      public function destroy($id)
      {
          $review = Review::findOrFail($id);
          $product_id = $review->product_id;
  
          $review->delete();
  
          return redirect()->route('admin.reviews.product-reviews', $product_id)->with('success', 'Review deleted successfully.');
      }
}
