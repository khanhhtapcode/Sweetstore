@extends('layouts.admin')

@section('title', 'Product Details')
@section('page-title', 'Product Details')
@section('page-description', 'View detailed information about this product')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                        <div class="flex items-center space-x-4 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-circle w-2 h-2 mr-2"></i>
                            {{ ucfirst($product->status) }}
                        </span>
                            <span class="text-sm text-gray-500">
                            Created {{ $product->created_at->diffForHumans() }}
                        </span>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Product
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                        </form>
                        <a href="{{ route('admin.products.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Product Name</label>
                                <p class="text-lg font-medium text-gray-900">{{ $product->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                                <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-tag mr-2"></i>
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Price</label>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Stock Quantity</label>
                                <div class="flex items-center">
                                    <span class="text-lg font-medium text-gray-900 mr-2">{{ $product->stock_quantity }}</span>
                                    @if($product->stock_quantity <= 10)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Low Stock
                                    </span>
                                    @elseif($product->stock_quantity <= 50)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Medium Stock
                                    </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        In Stock
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Product Description</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($product->short_description)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Short Description</label>
                                <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $product->short_description }}</p>
                            </div>
                        @endif

                        @if($product->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">Full Description</label>
                                <div class="text-gray-700 bg-gray-50 p-4 rounded-lg prose max-w-none">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        @endif

                        @if(!$product->short_description && !$product->description)
                            <div class="text-center py-8">
                                <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No description available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sales Analytics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Sales Analytics</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <i class="fas fa-shopping-cart text-2xl text-blue-600 mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">{{ $product->orders_count ?? 0 }}</p>
                                <p class="text-sm text-gray-500">Total Orders</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <i class="fas fa-dollar-sign text-2xl text-green-600 mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format(($product->orders_count ?? 0) * $product->price, 2) }}</p>
                                <p class="text-sm text-gray-500">Total Revenue</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <i class="fas fa-eye text-2xl text-purple-600 mb-2"></i>
                                <p class="text-2xl font-bold text-gray-900">{{ $product->views_count ?? 0 }}</p>
                                <p class="text-sm text-gray-500">Views</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Product Image -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Product Image</h3>
                    </div>
                    <div class="p-6">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-64 object-cover rounded-lg border border-gray-200">
                        @else
                            <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500">No image uploaded</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Created</span>
                            <span class="text-sm font-medium text-gray-900">{{ $product->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $product->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Status</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                        </div>
                        <hr class="my-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Product ID</span>
                            <span class="text-sm font-mono text-gray-900">#{{ $product->id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Product
                        </a>

                        @if($product->status === 'active')
                            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700">
                                    <i class="fas fa-pause mr-2"></i>
                                    Deactivate Product
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                                    <i class="fas fa-play mr-2"></i>
                                    Activate Product
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.products.create') }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>
                            Add New Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
