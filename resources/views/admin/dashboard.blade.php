<x-admin-layout>
    <x-slot name="header">
        Admin Dashboard - Sweet Delights 🧁
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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

        <!-- Drivers Card - NEW -->
        <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
            <div class="flex items-center">
                <div class="text-orange-600 text-3xl mr-4">🚗</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tài Xế</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ \App\Models\Driver::count() }}</p>
                    <a href="{{ route('admin.drivers.index') }}" class="text-orange-500 hover:underline text-sm">
                        Quản lý tài xế →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Driver Status Overview - NEW -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tình Trạng Tài Xế</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                <div class="flex items-center">
                    <div class="text-green-600 text-2xl mr-3">✅</div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Sẵn Sàng</h4>
                        <p class="text-xl font-bold text-green-600">{{ \App\Models\Driver::where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                <div class="flex items-center">
                    <div class="text-yellow-600 text-2xl mr-3">🚚</div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Đang Bận</h4>
                        <p class="text-xl font-bold text-yellow-600">{{ \App\Models\Driver::where('status', 'busy')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                <div class="flex items-center">
                    <div class="text-red-600 text-2xl mr-3">❌</div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Không Hoạt Động</h4>
                        <p class="text-xl font-bold text-red-600">{{ \App\Models\Driver::where('status', 'inactive')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thao Tác Nhanh</h3>
        <div class="flex flex-wrap space-x-4 space-y-2">
            <a href="{{ route('admin.categories.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Thêm Danh Mục
            </a>
            <a href="{{ route('admin.products.create') }}"
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                + Thêm Sản Phẩm
            </a>
            <a href="{{ route('admin.drivers.create') }}"
               class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                + Thêm Tài Xế
            </a>
            <a href="{{ route('products.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" target="_blank">
                👁️ Xem Website
            </a>
        </div>
    </div>

    <!-- Orders needing driver assignment - NEW -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Đơn Hàng Cần Gán Tài Xế</h3>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @php
                $ordersNeedDriver = \App\Models\Order::where('status', 'ready')
                    ->whereNull('driver_id')
                    ->with('orderItems.product')
                    ->latest()
                    ->take(5)
                    ->get();
            @endphp

            @if($ordersNeedDriver->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn Hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách Hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng Tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời Gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao Tác</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($ordersNeedDriver as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                <div class="text-sm text-gray-500">{{ $order->orderItems->count() }} sản phẩm</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-blue-600">{{ number_format($order->total_amount) }}₫</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                <div class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    Gán Tài Xế →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center text-gray-500">
                    <div class="text-4xl mb-2">✅</div>
                    <div>Tất cả đơn hàng đã được gán tài xế</div>
                </div>
            @endif
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

        <!-- Biểu đồ Hiệu suất Tài xế - NEW -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🚗 Hiệu Suất Tài Xế</h3>
            <canvas id="driverPerformanceChart" height="150" style="max-width: 100%; margin: auto;"></canvas>
            <div id="driverPerformanceLegend" class="mt-4 text-sm text-gray-700 space-y-1"></div>
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

        // Biểu đồ hiệu suất tài xế - NEW
        let driverPerformanceCtx = document.getElementById('driverPerformanceChart').getContext('2d');
        fetch('/admin/dashboard/driver-performance')
            .then(res => res.json())
            .then(data => {
                const labels = data.map(item => item.driver.name);
                const completedOrders = data.map(item => item.completed_orders);
                const colors = [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ];

                new Chart(driverPerformanceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Đơn Hoàn Thành',
                            data: completedOrders,
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

                const legendContainer = document.getElementById('driverPerformanceLegend');
                legendContainer.innerHTML = labels.map((label, i) => `
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 rounded" style="background-color: ${colors[i]}"></div>
                        <span>${label}: <strong>${completedOrders[i]}</strong> đơn</span>
                    </div>
                `).join('');
            })
            .catch(error => console.error('Error fetching driver performance:', error));

        // Biểu đồ Đơn Hàng theo Trạng Thái
        let orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        fetch('/admin/dashboard/order-status-data')
            .then(res => res.json())
            .then(data => {
                // Mapping trạng thái sang tiếng Việt
                const statusMapping = {
                    'pending': 'Chờ xác nhận',
                    'confirmed': 'Đã xác nhận',
                    'preparing': 'Đang chuẩn bị',
                    'ready': 'Sẵn sàng',
                    'assigned': 'Đã gán tài xế',
                    'picked_up': 'Đã lấy hàng',
                    'delivering': 'Đang giao',
                    'delivered': 'Đã giao',
                    'cancelled': 'Đã hủy'
                };

                const labels = data.map(item => statusMapping[item.status] || item.status);
                const totals = data.map(item => item.total);

                // Màu sắc cho từng trạng thái
                const statusColors = {
                    'pending': 'rgba(255, 193, 7, 0.6)',
                    'confirmed': 'rgba(54, 162, 235, 0.6)',
                    'preparing': 'rgba(255, 159, 64, 0.6)',
                    'ready': 'rgba(75, 192, 192, 0.6)',
                    'assigned': 'rgba(153, 102, 255, 0.6)',
                    'picked_up': 'rgba(255, 99, 132, 0.6)',
                    'delivering': 'rgba(255, 206, 86, 0.6)',
                    'delivered': 'rgba(40, 167, 69, 0.6)',
                    'cancelled': 'rgba(220, 53, 69, 0.6)'
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
    </script>

</x-admin-layout>
