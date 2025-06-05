<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                        <!-- Product Image -->
                        <div class="flex flex-col">
                            <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-96 object-center object-cover">
                                @else
                                    <div class="w-full h-96 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                                        <span class="text-6xl">🧁</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product info -->
                        <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                            <div class="flex items-center space-x-3 mb-4">
                                @if($product->category)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                                @if($product->is_featured)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        ⭐ Nổi bật
                                    </span>
                                @endif
                            </div>

                            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>

                            <div class="mt-3">
                                <h2 class="sr-only">Thông tin sản phẩm</h2>
                                <p class="text-3xl text-pink-600 font-bold">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                            </div>

                            <!-- Stock Status -->
                            <div class="mt-4">
                                @if($product->stock_quantity > 0)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-green-700 font-medium">
                                            Còn {{ $product->stock_quantity }} sản phẩm
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-red-700 font-medium">Hết hàng</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900">Mô tả sản phẩm</h3>
                                <div class="mt-2 prose prose-sm text-gray-500">
                                    <p>{{ $product->description ?: 'Bánh ngọt tươi ngon được làm thủ công với nguyên liệu chất lượng cao.' }}</p>
                                </div>
                            </div>

                            <!-- Add to Cart -->
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="flex items-center space-x-4">
                                    <label for="quantity" class="text-sm font-medium text-gray-700">Số lượng:</label>
                                    <div class="flex items-center border border-gray-300 rounded-md">
                                        <button type="button"
                                                class="px-3 py-2 text-gray-600 hover:text-gray-800 focus:outline-none"
                                                onclick="decreaseQuantity()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number"
                                               id="quantity"
                                               name="quantity"
                                               value="1"
                                               min="1"
                                               max="{{ $product->stock_quantity }}"
                                               class="w-16 px-3 py-2 text-center border-0 focus:ring-0 focus:outline-none">
                                        <button type="button"
                                                class="px-3 py-2 text-gray-600 hover:text-gray-800 focus:outline-none"
                                                onclick="increaseQuantity()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    @if($product->stock_quantity > 0)
                                        <button type="submit"
                                                class="w-full bg-green-500 text-white py-3 rounded-md hover:bg-green-600 transition duration-300">
                                            Thêm vào giỏ hàng - {{ number_format($product->price, 0, ',', '.') }}đ
                                        </button>
                                    @else
                                        <button type="button"
                                                class="w-full bg-gray-300 text-gray-500 py-3 rounded-md cursor-not-allowed"
                                                disabled>
                                            Hết hàng
                                        </button>
                                    @endif
                                </div>
                            </form>

                            <!-- Contact Info -->
                            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Cần hỗ trợ?</h4>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p>📞 Hotline: 0123 456 789</p>
                                    <p>💬 Chat với chúng tôi hoặc gọi để được tư vấn</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Products -->
                    @if($relatedProducts->count() > 0)
                        <div class="mt-16">
                            <h2 class="text-2xl font-bold text-gray-900 mb-8">Sản phẩm cùng loại</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach($relatedProducts as $relatedProduct)
                                    <div class="bg-white border rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition duration-300">
                                        <a href="{{ route('products.show', $relatedProduct) }}">
                                            <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                                                @if($relatedProduct->image_url)
                                                    <img src="{{ $relatedProduct->image_url }}"
                                    alt="{{ $relatedProduct->name }}"
                                    class="w-full h-96 object-center object-cover">
                                                @else
                                                    <div class="w-full h-48 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                                                        <span class="text-4xl">🧁</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900 mb-2">
                                                <a href="{{ route('products.show', $relatedProduct) }}" class="hover:text-pink-600 transition duration-300">
                                                    {{ $relatedProduct->name }}
                                                </a>
                                            </h3>
                                            <div class="flex items-center justify-between">
                                                <span class="text-lg font-bold text-pink-600">
                                                    {{ number_format($relatedProduct->price, 0, ',', '.') }}đ
                                                </span>
                                                @if($relatedProduct->stock_quantity > 0)
                                                    <form action="{{ route('cart.add') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition duration-300">
                                                            Thêm vào giỏ
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-red-600">Hết hàng</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const max = parseInt(quantityInput.getAttribute('max'));
            const current = parseInt(quantityInput.value);

            if (current < max) {
                quantityInput.value = current + 1;
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const min = parseInt(quantityInput.getAttribute('min'));
            const current = parseInt(quantityInput.value);

            if (current > min) {
                quantityInput.value = current - 1;
            }
        }
    </script>
</x-app-layout>