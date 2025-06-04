<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products for customers
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search by name or description
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default: // latest
                $query->latest();
                break;
        }

        // Paginate results
        $products = $query->paginate(12)->withQueryString();

        // Get categories for filter sidebar
        $categories = Category::active()
            ->withCount(['products' => function($query) {
                $query->active();
            }])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        // Check if product is active
        if (!$product->is_active) {
            abort(404, 'Sản phẩm không tồn tại hoặc đã bị ẩn.');
        }

        // Load the category relationship
        $product->load('category');

        // Get related products from same category (excluding current product)
        $relatedProducts = Product::with('category')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display products by category
     */
    public function category(Category $category, Request $request)
    {
        // Check if category is active
        if (!$category->is_active) {
            abort(404, 'Danh mục không tồn tại hoặc đã bị ẩn.');
        }

        $query = Product::with('category')
            ->active()
            ->where('category_id', $category->id);

        // Search within category
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default: // latest
                $query->latest();
                break;
        }

        // Paginate results
        $products = $query->paginate(12)->withQueryString();

        return view('products.category', compact('products', 'category'));
    }
}
