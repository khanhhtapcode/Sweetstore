<x-admin-layout>
    <x-slot name="header">
        Admin Dashboard - Sweet Delights 🧁
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Categories Card -->
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <div class="flex items-center">
                <div class="text-blue-600 text-3xl mr-4">📁</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Danh Mục</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Category::count() }}</p>
                    <a href="{{ route('admin.categories.index') }}" class="text-blue-500 hover:underline text-sm">
                        Quản lý danh mục →
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
            <div class="flex items-center">
                <div class="text-green-600 text-3xl mr-4">🧁</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Sản Phẩm</h3>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\Product::count() }}</p>
                    <a href="{{ route('admin.products.index') }}" class="text-green-500 hover:underline text-sm">
                        Quản lý sản phẩm →
                    </a>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
            <div class="flex items-center">
                <div class="text-purple-600 text-3xl mr-4">📋</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Đơn Hàng</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Order::count() }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="text-purple-500 hover:underline text-sm">
                        Quản lý đơn hàng →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thao Tác Nhanh</h3>
        <div class="flex space-x-4">
            <a href="{{ route('admin.categories.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Thêm Danh Mục
            </a>
            <a href="{{ route('admin.products.create') }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                + Thêm Sản Phẩm
            </a>
            <a href="{{ route('products.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" target="_blank">
                👁️ Xem Website
            </a>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Sản Phẩm Mới Nhất</h3>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản
                            Phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh
                            Mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn
                            Kho</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(\App\Models\Product::with('category')->latest()->take(5)->get() as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $product->formatted_price }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                    {{ $product->stock_quantity <= 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Biểu đồ và Phân tích -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Doanh Thu -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Báo cáo Doanh Thu</h3>
            <div class="flex items-center space-x-2 mb-4">
                <input type="date" id="startDate" class="border rounded p-2 text-sm flex-1"
                    value="{{ \Carbon\Carbon::now()->startOfMonth()->toDateString() }}">
                <span>–</span>
                <input type="date" id="endDate" class="border rounded p-2 text-sm flex-1"
                    value="{{ \Carbon\Carbon::now()->endOfMonth()->toDateString() }}">
                <button id="filterBtn" class="bg-blue-500 text-white px-3 py-2 rounded text-sm">Lọc</button>
            </div>
            <canvas id="revenueChart" height="150"></canvas>
        </div>

        <!-- Biểu đồ Đơn Hàng theo Trạng Thái -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Đơn Hàng theo Trạng Thái</h3>
            <canvas id="orderStatusChart" height="150" style="max-width: 100%; margin: auto;"></canvas>
            <div id="orderStatusLegend" class="mt-4 text-sm text-gray-700 space-y-1"></div>
        </div>

        <!-- Top Sản Phẩm -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🍰 Top Sản Phẩm Bán Chạy</h3>
            <canvas id="topProductsChart" height="150" style="max-width: 100%; margin: auto;"></canvas>
            <div id="topProductsLegend" class="mt-4 text-sm text-gray-700 space-y-1"></div>
        </div>

        <!-- Top Khách Hàng VIP -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">👑 Top Khách Hàng VIP</h3>
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                <div class="flex-1">
                    <canvas id="topCustomersChart" width="300" height="150" class="mx-auto block"></canvas>
                </div>
                <div id="topCustomersLegend" class="mt-4 text-sm text-gray-700 space-y-1 md:mt-0 flex-1"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Doanh thu theo ngày
        let ctx = document.getElementById('revenueChart').getContext('2d');
        let revenueChart;

        function fetchRevenueData(startDate, endDate) {
            fetch(`/admin/dashboard/revenue-data?start_date=${startDate}&end_date=${endDate}`)
                .then(res => res.json())
                .then(data => {
                    let labels = data.map(item => item.date);
                    let totals = data.map(item => parseFloat(item.total));

                    if (revenueChart) {
                        revenueChart.destroy();
                    }

                    revenueChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Doanh Thu (VNĐ)',
                                data: totals,
                                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                });
        }

        // Mặc định
        let defaultStart = document.getElementById('startDate').value;
        let defaultEnd = document.getElementById('endDate').value;
        fetchRevenueData(defaultStart, defaultEnd);

        document.getElementById('filterBtn').addEventListener('click', () => {
            let start = document.getElementById('startDate').value;
            let end = document.getElementById('endDate').value;
            fetchRevenueData(start, end);
        });

        // Biểu đồ Đơn Hàng theo Trạng Thái
        let orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        fetch('/admin/dashboard/order-status-data')
            .then(res => res.json())
            .then(data => {
                // Mapping trạng thái sang tiếng Việt
                const statusMapping = {
                    'pending': 'Đang xử lý',
                    'confirmed': 'Đã xác nhận',
                    'preparing': 'Đang chuẩn bị',
                    'ready': 'Sẵn sàng',
                    'delivered': 'Đã giao',
                    'cancelled': 'Đã hủy'
                };

                const labels = data.map(item => statusMapping[item.status] || item.status);
                const totals = data.map(item => item.total);

                // Màu sắc cho từng trạng thái
                const statusColors = {
                    'pending': 'rgba(255, 193, 7, 0.6)',      // Vàng - Đang xử lý
                    'confirmed': 'rgba(54, 162, 235, 0.6)',   // Xanh dương - Đã xác nhận
                    'preparing': 'rgba(255, 159, 64, 0.6)',   // Cam - Đang chuẩn bị
                    'ready': 'rgba(75, 192, 192, 0.6)',       // Xanh lá nhạt - Sẵn sàng
                    'delivered': 'rgba(40, 167, 69, 0.6)',    // Xanh lá - Đã giao
                    'cancelled': 'rgba(220, 53, 69, 0.6)'     // Đỏ - Đã hủy
                };

                const colors = data.map(item => statusColors[item.status] || 'rgba(128, 128, 128, 0.6)');

                new Chart(orderStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Số Đơn Hàng',
                            data: totals,
                            backgroundColor: colors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Custom legend cho biểu đồ trạng thái đơn hàng
                const legendContainer = document.getElementById('orderStatusLegend');
                legendContainer.innerHTML = labels.map((label, i) =>
                    `<div class="flex items-center space-x-2">
                        <div class="w-4 h-4 rounded" style="background-color: ${colors[i]}"></div>
                        <span>${label}: <strong>${totals[i]}</strong> đơn</span>
                    </div>`
                ).join('');
            });

        // Top sản phẩm
        let topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        fetch('/admin/dashboard/top-products')
            .then(res => res.json())
            .then(data => {
                let labels = data.map(item => item.product.name);
                let totals = data.map(item => item.total_sold);
                let colors = [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ];

                new Chart(topProductsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Số Lượng Bán',
                            data: totals,
                            backgroundColor: colors
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                const legendContainer = document.getElementById('topProductsLegend');
                legendContainer.innerHTML = labels.map((label, i) => `
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 rounded" style="background-color: ${colors[i]}"></div>
                    <span>${label} (${totals[i]})</span>
                </div>
            `).join('');
            });

        // Top khách hàng
        const topCustomersCtx = document.getElementById('topCustomersChart').getContext('2d');
        fetch('/admin/dashboard/top-customers')
            .then(res => res.json())
            .then(data => {
                const labels = data.map(item => item.name);
                const totals = data.map(item => item.total_spent);
                const colors = [
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 205, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ];

                new Chart(topCustomersCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Tổng Chi Tiêu (VNĐ)',
                            data: totals,
                            backgroundColor: colors
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                const legendContainer = document.getElementById('topCustomersLegend');
                legendContainer.innerHTML = labels.map((label, i) => `
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 rounded" style="background-color: ${colors[i]}"></div>
                    <span>${label} - ${totals[i].toLocaleString('vi-VN')} VNĐ</span>
                </div>
            `).join('');
            });
    </script>

</x-admin-layout>