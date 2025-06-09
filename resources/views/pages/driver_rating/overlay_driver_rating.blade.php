<style>
    /* CSS giữ nguyên */
    #driverRatingOverlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1000;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    #driverRatingOverlay.opacity-100 {
        opacity: 1;
    }

    #driverRatingContent {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 450px;
        padding: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        position: relative;
        transform: translateY(20px);
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        opacity: 0;
    }

    #driverRatingOverlay.opacity-100 #driverRatingContent {
        transform: translateY(0);
        opacity: 1;
    }

    #closeRatingOverlay {
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 24px;
        font-weight: bold;
        color: #6b7280;
        background: none;
        border: none;
        cursor: pointer;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    #closeRatingOverlay:hover {
        color: #1f2937;
        transform: scale(1.2);
    }

    #driverRatingContent h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        text-align: center;
        margin-bottom: 16px;
    }

    #driverRatingContent p.text-sm {
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 16px;
        text-align: center;
    }

    #driverRatingContent p.text-sm strong {
        color: #111827;
    }

    #driverRatingForm {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    #driverRatingForm label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
    }

    #driverRatingForm select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #f9fafb;
        font-size: 0.875rem;
        color: #1f2937;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    #driverRatingForm select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    #driverRatingForm textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #f9fafb;
        font-size: 0.875rem;
        color: #1f2937;
        resize: vertical;
        min-height: 80px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    #driverRatingForm textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    #driverRatingForm .flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
    }

    #driverRatingForm button[type="submit"] {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    #driverRatingForm button[type="submit"]:hover {
        background: linear-gradient(135deg, #1e40af, #1e3a8a);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    #driverRatingForm button[type="submit"]:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    #driverRatingForm button#driverRatingSkip {
        color: #6b7280;
        font-size: 0.875rem;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    #driverRatingForm button#driverRatingSkip:hover {
        color: #1f2937;
        text-decoration: underline;
    }

    #driverRatingContent p.text-center {
        color: #6b7280;
        font-size: 1rem;
        padding: 24px;
        text-align: center;
    }

    /* CSS cho hiển thị số sao trung bình */
    .average-rating {
        text-align: center;
        margin-bottom: 16px;
    }

    .average-rating .stars {
        color: #f59e0b;
        font-size: 1.25rem;
    }

    .average-rating .score {
        color: #1f2937;
        font-weight: 500;
        font-size: 0.875rem;
        margin-left: 8px;
    }

    @media (max-width: 640px) {
        #driverRatingContent {
            max-width: 90%;
            padding: 16px;
        }

        #driverRatingContent h3 {
            font-size: 1.25rem;
        }

        #driverRatingForm button[type="submit"] {
            padding: 10px 20px;
        }
    }
</style>

<div id="driverRatingOverlay" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center {{ $orderToRate ? '' : 'hidden' }}">
    <div id="driverRatingContent" class="bg-white rounded-xl w-full max-w-md shadow-lg relative p-6">
        <button id="closeRatingOverlay" onclick="closeDriverRatingOverlay()" class="absolute top-2 right-4 text-2xl font-bold text-gray-600 hover:text-black">×</button>

        <h3 class="text-xl font-semibold mb-4">Đánh giá tài xế giao hàng</h3>

        @if($orderToRate)
        <form id="driverRatingForm" method="POST" action="{{ route('driver-ratings.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="order_id" value="{{ $orderToRate->id }}">
            <input type="hidden" name="driver_id" value="{{ $orderToRate->driver->id }}">
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

            <p class="text-sm text-gray-700">
                Tài xế: <strong>{{ $orderToRate->driver?->name ?? 'Không rõ' }}</strong><br>
                Đơn hàng: <strong>{{ $orderToRate->order_number }}</strong>
            </p>

            <!-- Hiển thị số sao trung bình -->
            <div class="average-rating">
                @if($orderToRate->driver && $orderToRate->driver->average_rating)
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($orderToRate->driver->average_rating) ? '★' : '☆' }}</span>
                        @endfor
                    </span>
                    <span class="score">{{ number_format($orderToRate->driver->average_rating, 1) }}/5</span>
                @else
                    <span class="score">Chưa có đánh giá</span>
                @endif
            </div>

            <div class="relative">
                <label for="rating" class="block font-medium text-gray-700">Số sao:</label>
                <select name="rating" id="rating" required class="w-full mt-1 border rounded px-3 py-2 appearance-none">
                    <option value="">Chọn mức đánh giá</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} sao</option>
                    @endfor
                </select>
                <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
            </div>

            <div>
                <label for="comment" class="block font-medium text-gray-700">Nhận xét:</label>
                <textarea name="comment" id="comment" rows="3" placeholder="Tài xế thân thiện, giao nhanh..." class="w-full mt-1 border rounded px-3 py-2"></textarea>
            </div>
            <p class="text-sm text-gray-500">
                Đánh giá của bạn sẽ giúp chúng tôi cải thiện dịch vụ giao hàng.
            </p>
            <p class="text-sm text-gray-500">
                Nếu bạn không muốn đánh giá, có thể bỏ qua bước này và có thể đánh giá trong phần lịch sử đơn hàng.
            </p>
            <div class="flex justify-between items-center mt-4">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition">Gửi đánh giá</button>
                <button type="button" id="driverRatingSkip" onclick="skipDriverRating(event)" class="text-gray-500 hover:text-black">Bỏ qua</button>
            </div>
        </form>
        @else
        <div class="empty-state">
            <p class="text-center text-gray-600">Không có đơn hàng nào cần đánh giá.</p>
        </div>
        @endif
    </div>
</div>

<script>
    // Hàm đóng overlay đánh giá
    function closeDriverRatingOverlay() {
        const overlay = document.getElementById('driverRatingOverlay');
        overlay.classList.remove('opacity-100');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }

    // Hàm xử lý bỏ qua đánh giá
    // Hiển thị overlay nếu có đơn hàng cần đánh giá
    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('driverRatingOverlay');
        if (!overlay.classList.contains('hidden')) {
            setTimeout(() => overlay.classList.add('opacity-100'), 100);
        }

        // Xử lý gửi đánh giá qua AJAX
        document.getElementById('driverRatingForm')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Đóng overlay
                    closeDriverRatingOverlay();

                    // Hiển thị popup cảm ơn
                    const thankYouPopup = document.createElement('div');
                    thankYouPopup.id = 'thank-you-popup';
                    thankYouPopup.className = 'fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50';
                    thankYouPopup.innerHTML = `
                        <div class="bg-white rounded-lg p-6 max-w-md w-full text-center">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">✅ Cảm ơn bạn đã đánh giá!</h3>
                            <p class="text-gray-600 mb-4">Đánh giá của bạn giúp chúng tôi cải thiện dịch vụ.</p>
                            <div class="text-yellow-500 text-xl mb-4">
                                🌟 Điểm trung bình của tài xế: ${data.average_rating}/5
                            </div>
                            <a href="{{ route('products.index') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                🛍️ Tiếp tục mua sắm
                            </a>
                        </div>
                    `;
                    document.body.appendChild(thankYouPopup);

                    // Tự động đóng popup sau 5 giây
                    setTimeout(() => {
                        thankYouPopup.remove();
                    }, 5000);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
            });
        });
    });
</script>