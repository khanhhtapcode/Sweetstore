

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
                        <!-- Thông tin sản phẩm -->
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
                                <p class="text-3xl text-pink-600 font-bold">{{ $product->formatted_price }}</p>
                            </div>


                            <!-- Tình trạng kho -->
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


                            <!-- Mô tả sản phẩm -->
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900">Mô tả sản phẩm</h3>
                                <div class="mt-2 prose prose-sm text-gray-500">
                                    <p>{{ $product->description ?: 'Bánh ngọt tươi ngon được làm thủ công với nguyên liệu chất lượng cao.' }}</p>
                                </div>
                            </div>


                            <!-- Thêm vào giỏ hàng -->
                            <form class="mt-8">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <label for="quantity" class="text-sm font-medium text-gray-700 mr-3">Số lượng:</label>
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
                                </div>


                                <div class="mt-6 flex space-x-4">
                                    @if($product->stock_quantity > 0)
                                        <button type="submit"
                                                class="flex-1 bg-pink-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                            </svg>
                                            Thêm vào giỏ hàng
                                        </button>
                                    @else
                                        <button type="button"
                                                class="flex-1 bg-gray-300 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-gray-500 cursor-not-allowed"
                                                disabled>
                                            Hết hàng
                                        </button>
                                    @endif
                                </div>
                            </form>


                            <!-- Đánh giá trung bình -->
                            <div class="flex items-center w-full justify-center mb-10 mt-12">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if($i <= round($averageRating))
                                        <svg class="w-12 h-12 text-yellow-400 fill-current drop-shadow transition" viewBox="0 0 20 20">
                                            <polygon points="9.9,1.1 7.6,6.6 1.6,7.3 6.1,11.2 4.8,17.1 9.9,14.1 15,17.1 13.7,11.2 18.2,7.3 12.2,6.6"/>
                                        </svg>
                                    @else
                                        <svg class="w-12 h-12 text-gray-300 fill-current transition" viewBox="0 0 20 20">
                                            <polygon points="9.9,1.1 7.6,6.6 1.6,7.3 6.1,11.2 4.8,17.1 9.9,14.1 15,17.1 13.7,11.2 18.2,7.3 12.2,6.6"/>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-6 text-2xl text-gray-700 font-bold bg-yellow-50 px-4 py-2 rounded shadow">
                                    {{ number_format($averageRating ?? 0, 1) }}/5
                                </span>
                            </div>


                            <!-- Form đánh giá -->
                           
@if(auth()->check())
    <form action="{{ route('ratings.store', $product) }}" method="POST" class="mb-4" id="rating-form">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="rating" id="rating-value" value="0">
        <label class="block mb-2 font-medium">Đánh giá:</label>
        <div class="flex items-center mb-2" id="star-rating">
            @for($i = 1; $i <= 5; $i++)
                <svg data-star="{{ $i }}" class="w-8 h-8 text-gray-300 cursor-pointer transition" fill="currentColor" viewBox="0 0 20 20">
                    <polygon points="9.9,1.1 7.6,6.6 1.6,7.3 6.1,11.2 4.8,17.1 9.9,14.1 15,17.1 13.7,11.2 18.2,7.3 12.2,6.6"/>
                </svg>
            @endfor
        </div>
        <textarea name="comment" placeholder="Nhận xét của bạn..." class="block w-full mt-2"></textarea>
        <button type="submit" class="mt-2 bg-pink-600 text-white px-4 py-2 rounded">Gửi đánh giá</button>
    </form>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('#star-rating svg');
        const ratingInput = document.getElementById('rating-value');
        let currentRating = 0;


        stars.forEach((star, idx) => {
            star.addEventListener('mouseenter', () => {
                highlightStars(idx + 1);
            });
            star.addEventListener('mouseleave', () => {
                highlightStars(currentRating);
            });
            star.addEventListener('click', () => {
                currentRating = idx + 1;
                ratingInput.value = currentRating;
                highlightStars(currentRating);
            });
        });


        function highlightStars(rating) {
            stars.forEach((star, i) => {
                if (i < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
    });
</script>


                           
<!-- Danh sách nhận xét -->
@if($product->ratings->count())
    <div class="mt-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Nhận xét của khách hàng</h3>
        <div class="space-y-6">
            @foreach($product->ratings->sortByDesc('created_at') as $rating)
                <div class="bg-white border rounded-lg p-4 shadow-sm">
                    <div class="flex items-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                <polygon points="9.9,1.1 7.6,6.6 1.6,7.3 6.1,11.2 4.8,17.1 9.9,14.1 15,17.1 13.7,11.2 18.2,7.3 12.2,6.6"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-xs text-gray-500">
                            {{ $rating->user->name ?? 'Ẩn danh' }} - {{ $rating->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="text-gray-700 text-sm mb-2">
                        {{ $rating->comment }}
                    </div>


                    <!-- Danh sách trả lời -->
                    @if($rating->replies && $rating->replies->count())
                        <div class="ml-6 mt-2 space-y-2">
                            @foreach($rating->replies as $reply)
                                <div class="bg-gray-50 border rounded px-3 py-2 text-sm">
                                    <span class="font-semibold">{{ $reply->user->name ?? 'Ẩn danh' }}:</span>
                                    {{ $reply->reply }}
                                    <span class="text-xs text-gray-400 ml-2">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif


                    <!-- Form trả lời đánh giá -->
                    @if(auth()->check())
                        <form action="{{ route('ratings.replies.store', $rating->id) }}" method="POST" class="ml-6 mt-2 flex items-center space-x-2">
                            @csrf
                            <input type="text" name="reply" class="border rounded px-2 py-1 w-2/3" placeholder="Trả lời đánh giá này..." required>
                            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Gửi</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="mt-8 text-gray-500 italic">Chưa có nhận xét nào.</div>
@endif


                            <!-- Thông tin liên hệ -->
                            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Cần hỗ trợ?</h4>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p>📞 Hotline: 0123 456 789</p>
                                    <p>💬 Chat với chúng tôi hoặc gọi để được tư vấn</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Sản phẩm liên quan -->
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
                                                         class="w-full h-48 object-cover hover:scale-105 transition duration-300">
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
                                                    {{ $relatedProduct->formatted_price }}
                                                </span>
                                                @if($relatedProduct->stock_quantity > 0)
                                                    <button class="bg-pink-600 text-white px-3 py-1 rounded text-sm hover:bg-pink-700 transition duration-300">
                                                        Thêm vào giỏ
                                                    </button>
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
