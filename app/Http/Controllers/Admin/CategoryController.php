<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Nếu cần hiển thị danh sách categories cha thì uncomment dòng dưới
        // $categories = Category::where('parent_id', null)->get();
        // return view('admin.categories.create', compact('categories'));

        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'image_url.url' => 'URL hình ảnh không hợp lệ.',
            'image_url.max' => 'URL hình ảnh không được vượt quá 500 ký tự.',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['products' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // Load products count
        $category->loadCount('products');

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'image_url.url' => 'URL hình ảnh không hợp lệ.',
            'image_url.max' => 'URL hình ảnh không được vượt quá 500 ký tự.',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm không
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục này vì vẫn còn sản phẩm!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa thành công!');
    }
    /**
     * Export categories to Excel
     */
    /**
     * Export categories to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Category::withCount('products');

            // Apply filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status == 'active');
            }

            $categories = $query->latest()->get();

            $filename = 'danh_muc_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($categories) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($file, [
                    'ID',
                    'Tên danh mục',
                    'Mô tả',
                    'Số sản phẩm',
                    'Trạng thái',
                    'Ngày tạo'
                ]);

                foreach ($categories as $category) {
                    fputcsv($file, [
                        $category->id,
                        $category->name,
                        strip_tags($category->description ?? ''),
                        $category->products_count,
                        $category->is_active ? 'Hoạt động' : 'Tạm dừng',
                        $category->created_at->format('d/m/Y H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xuất file: ' . $e->getMessage());
        }
    }

}
