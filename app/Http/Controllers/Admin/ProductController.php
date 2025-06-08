<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            } elseif ($request->status === 'low_stock') {
                $query->where('stock_quantity', '<=', 5);
            }
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'stock':
                $query->orderBy('stock_quantity', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default: // latest
                $query->latest();
                break;
        }

        $products = $query->paginate(15)->withQueryString();

        // Get categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url|max:500',
            'stock_quantity' => 'required|integer|min:0|max:99999',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 2000 ký tự.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá không được âm.',
            'price.max' => 'Giá quá lớn.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'image_url.url' => 'URL hình ảnh không hợp lệ.',
            'image_url.max' => 'URL hình ảnh quá dài.',
            'stock_quantity.required' => 'Số lượng tồn kho là bắt buộc.',
            'stock_quantity.integer' => 'Số lượng phải là số nguyên.',
            'stock_quantity.min' => 'Số lượng không được âm.',
            'stock_quantity.max' => 'Số lượng quá lớn.',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image_url' => $request->image_url,
            'stock_quantity' => $request->stock_quantity,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');

        // Get some statistics
        $stats = [
            'total_orders' => 0, // Will implement when order system is complete
            'total_revenue' => 0, // Will implement when order system is complete
            'avg_rating' => 0, // Will implement when review system is added
            'views' => 0 // Will implement when view tracking is added
        ];

        return view('admin.products.show', compact('product', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url|max:500',
            'stock_quantity' => 'required|integer|min:0|max:99999',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 2000 ký tự.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá không được âm.',
            'price.max' => 'Giá quá lớn.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'image_url.url' => 'URL hình ảnh không hợp lệ.',
            'image_url.max' => 'URL hình ảnh quá dài.',
            'stock_quantity.required' => 'Số lượng tồn kho là bắt buộc.',
            'stock_quantity.integer' => 'Số lượng phải là số nguyên.',
            'stock_quantity.min' => 'Số lượng không được âm.',
            'stock_quantity.max' => 'Số lượng quá lớn.',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image_url' => $request->image_url,
            'stock_quantity' => $request->stock_quantity,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if product has orders (when order system is implemented)
        // if ($product->orders()->count() > 0) {
        //     return redirect()->route('admin.products.index')
        //                      ->with('error', 'Không thể xóa sản phẩm này vì đã có đơn hàng!');
        // }

        $productName = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Sản phẩm '{$productName}' đã được xóa thành công!");
    }
    /**
     * Export products to Excel
     */
    /**
     * Export products to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Product::with('category');

            // Apply filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            if ($request->filled('status')) {
                switch ($request->status) {
                    case 'active':
                        $query->where('is_active', true);
                        break;
                    case 'inactive':
                        $query->where('is_active', false);
                        break;
                    case 'featured':
                        $query->where('is_featured', true);
                        break;
                    case 'low_stock':
                        $query->where('stock_quantity', '<=', 5);
                        break;
                }
            }

            $products = $query->latest()->get();

            $filename = 'san_pham_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($products) {
                $file = fopen('php://output', 'w');

                // Add UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // CSV Headers
                fputcsv($file, [
                    'ID',
                    'Tên sản phẩm',
                    'Danh mục',
                    'Mô tả',
                    'Giá (VNĐ)',
                    'Tồn kho',
                    'Trọng lượng (kg)',
                    'Thể tích (m³)',
                    'Trạng thái',
                    'Nổi bật',
                    'Ngày tạo',
                    'Ngày cập nhật'
                ]);

                foreach ($products as $product) {
                    fputcsv($file, [
                        $product->id,
                        $product->name,
                        $product->category->name ?? 'Chưa phân loại',
                        strip_tags($product->description ?? ''),
                        number_format($product->price, 0, ',', '.'),
                        $product->stock_quantity ?? 0,
                        $product->weight ?? 0.5,
                        $product->volume ?? 0.01,
                        $product->is_active ? 'Hoạt động' : 'Tạm dừng',
                        $product->is_featured ? 'Có' : 'Không',
                        $product->created_at->format('d/m/Y H:i'),
                        $product->updated_at->format('d/m/Y H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Export products error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xuất file: ' . $e->getMessage());
        }
    }

    /**
     * Update product stock quantity
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0|max:99999',
            'action' => 'required|in:set,add,subtract'
        ]);

        $newStock = $product->stock_quantity;

        switch ($request->action) {
            case 'set':
                $newStock = $request->stock_quantity;
                break;
            case 'add':
                $newStock += $request->stock_quantity;
                break;
            case 'subtract':
                $newStock = max(0, $newStock - $request->stock_quantity);
                break;
        }

        $product->update(['stock_quantity' => $newStock]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật tồn kho thành công!',
            'new_stock' => $newStock
        ]);
    }

    /**
     * Toggle product featured status
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        $status = $product->is_featured ? 'nổi bật' : 'bình thường';

        return response()->json([
            'success' => true,
            'message' => "Đã chuyển sản phẩm sang trạng thái {$status}!",
            'is_featured' => $product->is_featured
        ]);
    }

    /**
     * Toggle product active status
     */
    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'hoạt động' : 'tạm dừng';

        return response()->json([
            'success' => true,
            'message' => "Đã chuyển sản phẩm sang trạng thái {$status}!",
            'is_active' => $product->is_active
        ]);
    }

    /**
     * Bulk actions for multiple products
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $productIds = $request->product_ids;
        $action = $request->action;
        $count = count($productIds);

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $productIds)->update(['is_active' => true]);
                $message = "Đã kích hoạt {$count} sản phẩm.";
                break;
            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['is_active' => false]);
                $message = "Đã tạm dừng {$count} sản phẩm.";
                break;
            case 'feature':
                Product::whereIn('id', $productIds)->update(['is_featured' => true]);
                $message = "Đã đặt {$count} sản phẩm làm nổi bật.";
                break;
            case 'unfeature':
                Product::whereIn('id', $productIds)->update(['is_featured' => false]);
                $message = "Đã bỏ nổi bật cho {$count} sản phẩm.";
                break;
            case 'delete':
                Product::whereIn('id', $productIds)->delete();
                $message = "Đã xóa {$count} sản phẩm.";
                break;
        }

        return redirect()->route('admin.products.index')
            ->with('success', $message);
    }
}
