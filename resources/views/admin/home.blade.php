@extends('admin.admin-layout.master')

@section('title', 'Admin-Home')
@section('content')
    {{-- START Wrapper --}}

    {{-- Header --}}

    {{-- Right Sidebar --}}

    {{-- Sidebar Here --}}

    <!-- ==================================================== -->
    <!-- Start right Content here -->
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-fluid">

            <!-- Start here.... -->
            <div class="row">
                <div class="col-xxl-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-primary text-truncate mb-3" role="alert">
                                Monitor dashboard activities regularly to keep everything running smoothly.
                            </div>
                        </div>

                        <!-- Total Orders -->
                        <div class="col-md-6">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-md bg-soft-primary rounded">
                                                <iconify-icon icon="solar:cart-5-bold-duotone"
                                                    class="avatar-title fs-32 text-primary"></iconify-icon>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="text-muted mb-0 text-truncate">Total Orders</p>
                                            <h3 class="text-dark mt-1 mb-0">{{ number_format($totalOrders) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer py-2 bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                                    <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i>
                                        {{ $percentages['total'] }}%</span>
                                    {{-- <a href="#!" class="text-reset fw-semibold fs-12">View More</a> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Confirmed Orders -->
                        <div class="col-md-6">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-md bg-soft-primary rounded">
                                                <i class="bx bx-award avatar-title fs-24 text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="text-muted mb-0 text-truncate">Confirmed Orders</p>
                                            <h3 class="text-dark mt-1 mb-0">{{ number_format($confirmedOrders) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer py-2 bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                                    <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i>
                                        {{ $percentages['confirmed'] }}%</span>
                                    {{-- <a href="#!" class="text-reset fw-semibold fs-12">View More</a> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Pending Orders -->
                        <div class="col-md-6">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-md bg-soft-primary rounded">
                                                <i class="bx bxs-backpack avatar-title fs-24 text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="text-muted mb-0 text-truncate">Pending Orders</p>
                                            <h3 class="text-dark mt-1 mb-0">{{ number_format($pendingOrders) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer py-2 bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                                    <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i>
                                        {{ $percentages['pending'] }}%</span>
                                    {{-- <a href="#!" class="text-reset fw-semibold fs-12">View More</a> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Rejected Orders -->
                        <div class="col-md-6">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-md bg-soft-primary rounded">
                                                <i class="bx bx-dollar-circle avatar-title text-primary fs-24"></i>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="text-muted mb-0 text-truncate">Rejected Orders</p>
                                            <h3 class="text-dark mt-1 mb-0">{{ number_format($rejectedOrders) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer py-2 bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                                    <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i>
                                        {{ $percentages['rejected'] }}%</span>
                                    {{-- <a href="#!" class="text-reset fw-semibold fs-12">View More</a> --}}
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->
                </div> <!-- end col -->

                <div class="col-xxl-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Performance</h4>
                                <div>
                                    <button type="button" id="btnAll" class="btn btn-sm btn-outline-light">ALL</button>
                                    <button type="button" id="btn1M" class="btn btn-sm btn-outline-light">1M</button>
                                    <button type="button" id="btn6M" class="btn btn-sm btn-outline-light">6M</button>
                                    <button type="button" id="btn1Y"
                                        class="btn btn-sm btn-outline-light active">1Y</button>
                                </div>
                            </div>

                            <div dir="ltr">
                                <div id="dash-performance-chart" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

            <!-- AI Insights Section -->
            <div class="row">
                <div class="col-12">
                    <div id="ai-insights" class="ai-insights-container">
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="ai-loading">
                                    <div class="ai-loading-spinner"></div>
                                    <h6 class="mt-3 text-muted">Loading AI insights...</h6>
                                    <p class="text-muted small mb-0">Analyzing your business data</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">
                                    Recent Orders
                                </h4>

                                {{-- <a href="#!" class="btn btn-sm btn-soft-primary">
                                    <i class="bx bx-plus me-1"></i>Create Order
                                </a> --}}
                            </div>
                        </div>
                        <!-- end card body -->
                        <div class="table-responsive table-centered">
                            <table class="table mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-3">Order ID</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Customer Name</th>
                                        <th>Email ID</th>
                                        <th>Phone No.</th>
                                        <th>Address</th>
                                        <th>Payment Type</th>
                                        <th>Payment SS</th>
                                        <th>Status</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="ps-3">
                                                <a
                                                    href="{{ route('admin.payment.confirm', $order->id) }}">#{{ $order->id }}</a>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if ($order->items->isNotEmpty())
                                                    @php
                                                        $firstItem = $order->items->first();
                                                        $productImage =
                                                            $firstItem->product->image_front ?? 'default-image.jpg';
                                                    @endphp
                                                    <img src="{{ asset('storage/' . $productImage) }}"
                                                        alt="product-image" class="img-fluid avatar-sm">
                                                @else
                                                    <span>No product image</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="javascript:void(0)">{{ $order->name }}</a>
                                            </td>
                                            <td>{{ $order->email }}</td>
                                            <td>{{ $order->phone }}</td>
                                            <td>{{ $order->address }}</td>
                                            <td>{{ ucfirst($order->payment_method) }}</td>
                                            <td>
                                                @if ($order->payment_screenshot)
                                                    <img src="{{ asset('storage/' . $order->payment_screenshot) }}"
                                                        alt="Payment Screenshot"
                                                        style="max-width: 50px; max-height: 50px;">
                                                @else
                                                    <span>No Screenshot</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($order->status == 'awaiting_bank_transfer')
                                                    <span
                                                        class="badge bg-warning text-dark d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i> Verify
                                                        Payment
                                                    </span>
                                                @elseif ($order->status == 'confirmed')
                                                    <span
                                                        class="badge bg-success text-white d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i>
                                                        Payment Confirmed
                                                    </span>
                                                @elseif ($order->status == 'cash_on_delivery')
                                                    <span
                                                        class="badge bg-secondary text-white d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i> Cash
                                                        on Delivery
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-danger text-white d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i>
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.order.view', $order->id) }}"
                                                    class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- table responsive -->

                        <div class="card-footer border-top">
                            <div class="row g-3">
                                <div class="col-sm">
                                    <div class="text-muted">
                                        Showing
                                        <span class="fw-semibold">{{ $orders->firstItem() }}</span>
                                        to
                                        <span class="fw-semibold">{{ $orders->lastItem() }}</span>
                                        of
                                        <span class="fw-semibold">{{ $orders->total() }}</span>
                                        orders
                                    </div>
                                </div>

                                <div class="col-sm-auto">
                                    <ul class="pagination m-0">
                                        <!-- Previous Page Link -->
                                        @if ($orders->onFirstPage())
                                            <li class="page-item disabled">
                                                <a href="#" class="page-link"><i
                                                        class="bx bx-left-arrow-alt"></i></a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a href="{{ $orders->previousPageUrl() }}" class="page-link"><i
                                                        class="bx bx-left-arrow-alt"></i></a>
                                            </li>
                                        @endif

                                        <!-- Page Number Links -->
                                        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                            <li class="page-item {{ $page == $orders->currentPage() ? 'active' : '' }}">
                                                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                            </li>
                                        @endforeach

                                        <!-- Next Page Link -->
                                        @if ($orders->hasMorePages())
                                            <li class="page-item">
                                                <a href="{{ $orders->nextPageUrl() }}" class="page-link"><i
                                                        class="bx bx-right-arrow-alt"></i></a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <a href="#" class="page-link"><i
                                                        class="bx bx-right-arrow-alt"></i></a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div> <!-- end row -->

        </div>
        <!-- End Container Fluid -->

        {{-- Footer --}}

    </div>
    <!-- ==================================================== -->
    <!-- End Page Content -->
    <!-- ==================================================== -->


    <!-- END Wrapper -->
@endsection
@push('scripts')
    <script>
        // Chart data from backend
        window.chartOrdersData = {
            confirmed: @json(array_values($monthlyConfirmed)),
            pending: @json(array_values($monthlyPending)),
            rejected: @json(array_values($monthlyRejected))
        };

        // Single, Clean Admin Dashboard Controller
        class AdminDashboardController {
            constructor() {
                this.container = document.getElementById('ai-insights');
                this.init();
            }

            init() {
                if (this.container) {
                    this.loadAIInsights();
                }
            }

            loadAIInsights() {
                if (!this.container) return;

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const token = csrfToken ? csrfToken.getAttribute('content') : null;

                if (!token) {
                    console.error('CSRF token not found');
                    this.showError('CSRF token not found. Please refresh the page.');
                    return;
                }

                // Show loading state
                this.container.innerHTML = this.getLoadingHTML();

                fetch('/admin/ai-insights', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            this.displaySuccessfulInsights(data);
                        } else {
                            this.showFallback(data);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading AI insights:', error);
                        this.showError('Failed to load AI insights. Please try again later.');
                    });
            }

            // Add this to your existing JavaScript in the displaySuccessfulInsights method

            displaySuccessfulInsights(data) {
                const {
                    insights,
                    metrics,
                    data: businessData
                } = data;

                this.container.innerHTML = `
        <div class="card border-0 shadow-lg">
            <div class="card-header ai-head text-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-brain" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-white">AI Business Insights</h5>
                            <p class="mb-0 text-white-50 small">Powered by Advanced Analytics</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">
                            <i class="bx bx-check-circle me-1"></i>Live
                        </span>
                        <button class="btn btn-sm refresh" onclick="window.adminDashboard.loadAIInsights()">
                            <i class="bx bx-refresh me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <!-- Revenue Overview Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="bx bx-dollar-circle me-2"></i>Revenue Overview (Including COD)
                        </h6>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card bg-primary text-white border-0">
                                    <div class="card-body text-center py-3">
                                        <h4 class="card-titleee mb-1">$${this.formatNumber(businessData.revenue.total)}</h4>
                                        <small class="text-revenue">Total Revenue</small>
                                        <div class="mt-1">
                                            <small class="text-revenue" style="font-size: 0.75rem;">
                                                <i class="bx bx-info-circle me-1"></i>Confirmed + COD Orders
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card bg-success text-white border-0">
                                    <div class="card-body text-center py-3">
                                        <h4 class="card-titleee mb-1">$${this.formatNumber(businessData.revenue.monthly)}</h4>
                                        <small class="text-revenue">This Month</small>
                                        <div class="mt-1">
                                            <small class="text-revenue" style="font-size: 0.75rem;">
                                                <i class="bx bx-calendar me-1"></i>All Revenue Sources
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card bg-info text-white border-0">
                                    <div class="card-body text-center py-3">
                                        <h4 class="card-titleee mb-1">${businessData.orders.total}</h4>
                                        <small class="text-revenue">Total Orders</small>
                                        <div class="mt-1">
                                            <small class="text-revenue" style="font-size: 0.75rem;">
                                                <i class="bx bx-package me-1"></i>COD: ${businessData.orders.cod || 0}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card bg-warning text-white border-0">
                                    <div class="card-body text-center py-3">
                                        <h4 class="card-titleee mb-1">$${this.formatNumber(businessData.revenue.average_order_value)}</h4>
                                        <small class="text-revenue">Avg Order Value</small>
                                        <div class="mt-1">
                                            <small class="text-revenue" style="font-size: 0.75rem;">
                                                <i class="bx bx-trending-up me-1"></i>All Payment Methods
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rest of your existing code... -->
                
                <div class="row">
                    <!-- Left Column: AI Insights -->
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bx bx-bulb me-2"></i>Key Business Insights
                            </h6>
                            <div class="insight-text bg-light p-3 rounded border-start border-primary border-3">
                                ${this.formatInsights(insights)}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Quick Metrics -->
                    <div class="col-lg-4">
                        <div class="card bg-light border-0" style="margin-top: 36px;">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-bar-chart-alt-2 me-2"></i>Performance Metrics
                                </h6>
                                ${this.renderMetrics(metrics)}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Items -->
            ${this.generateActionItems(metrics.recommendations)}
            
            <!-- Footer -->
            <div class="card-footer bg-light border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bx bx-time me-1"></i>Updated just now
                    </small>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-dark me-2">
                            <i class="bx bx-data me-1"></i>
                            ${businessData.products.total} Products • ${businessData.orders.total} Orders • ${businessData.orders.cod || 0} COD
                        </span>
                    </div>
                </div>
            </div>
        </div>
    `;
            }

            renderMetrics(metrics) {
                return `
            <!-- Inventory Health -->
            <div class="metric-item mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Inventory Health</span>
                    <span class="badge bg-${this.getHealthStatusColor(metrics.inventory_health.status)} text-white">
                        ${metrics.inventory_health.score}%
                    </span>
                </div>
                <div class="progress progress-sm mb-2" style="height: 8px;">
                    <div class="progress-bar bg-${this.getHealthStatusColor(metrics.inventory_health.status)}" 
                         style="width: ${metrics.inventory_health.score}%"></div>
                </div>
                <small class="text-muted">
                    ${metrics.inventory_health.healthy_items}/${metrics.inventory_health.total_items} items healthy
                </small>
            </div>

            <!-- Sales Trend -->
            <div class="metric-item mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Sales Trend</span>
                    <span class="badge bg-${this.getSalesTrendColor(metrics.sales_trend.trend)} text-white">
                        <i class="bx bx${this.getSalesTrendIcon(metrics.sales_trend.trend)} me-1"></i>
                        ${Math.abs(metrics.sales_trend.percentage_change)}%
                    </span>
                </div>
                <div class="text-sm">
                    <span class="fw-semibold">${metrics.sales_trend.current_month}</span> orders this month
                    <br><span class="text-muted">vs ${metrics.sales_trend.last_month} last month</span>
                </div>
            </div>

            <!-- Revenue Growth -->
            <div class="metric-item mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Revenue Growth</span>
                    <span class="badge bg-${metrics.revenue_metrics.revenue_growth >= 0 ? 'success' : 'danger'} text-white">
                        ${metrics.revenue_metrics.revenue_growth >= 0 ? '+' : ''}${metrics.revenue_metrics.revenue_growth}%
                    </span>
                </div>
                <small class="text-muted">Monthly comparison</small>
            </div>

            <!-- Performance Indicators -->
            <div class="metric-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small">Fulfillment Rate</span>
                    <span class="text-primary fw-semibold">${metrics.performance_indicators.fulfillment_rate}%</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Conversion Rate</span>
                    <span class="text-success fw-semibold">${metrics.performance_indicators.conversion_rate}%</span>
                </div>
            </div>
        `;
            }

            generateActionItems(recommendations) {
                if (!recommendations || recommendations.length === 0) return '';

                const items = recommendations.map(rec => `
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="alert alert-${rec.color} border-0 py-2 px-3">
                    <div class="d-flex align-items-start">
                        <i class="bx ${rec.icon} me-2 mt-1"></i>
                        <div>
                            <strong class="small">${rec.action}</strong>
                            <div class="small mt-1 text-muted">${rec.message}</div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

                return `
            <div class="card-footer bg-white border-top">
                <h6 class="text-primary mb-3">
                    <i class="bx bx-task me-2"></i>Action Items & Recommendations
                </h6>
                <div class="row">
                    ${items}
                </div>
            </div>
        `;
            }

            // Improved AI insights formatting to handle different response formats
            formatInsights(insights) {
                // Handle both structured and unstructured responses
                let formattedText = insights;

                // Convert bullet points to HTML
                formattedText = formattedText
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // Bold text
                    .replace(/\* /g, '<i class="bx bx-check-circle text-success me-1"></i>') // Bullet points
                    .replace(/• /g, '<i class="bx bx-check-circle text-success me-1"></i>') // Alternative bullets
                    .replace(/\n\n/g, '</p><p>') // Paragraph breaks
                    .replace(/\n/g, '<br>') // Line breaks
                    .replace(/Here are.*?insights.*?:/gi,
                    '<strong>AI Analysis:</strong><br>') // Remove repetitive headers
                    .replace(/These insights.*?$/gi, ''); // Remove footer text

                // Wrap in paragraph tags if not already wrapped
                if (!formattedText.includes('<p>')) {
                    formattedText = `<p>${formattedText}</p>`;
                }

                return formattedText;
            }

            // Utility Functions
            formatNumber(num) {
                if (num >= 1000000) {
                    return (num / 1000000).toFixed(1) + 'M';
                } else if (num >= 1000) {
                    return (num / 1000).toFixed(1) + 'K';
                }
                return parseFloat(num).toFixed(2);
            }

            getHealthStatusColor(status) {
                const colors = {
                    'excellent': 'success',
                    'good': 'info',
                    'warning': 'warning',
                    'critical': 'danger'
                };
                return colors[status] || 'secondary';
            }

            getSalesTrendColor(trend) {
                const colors = {
                    'up': 'success',
                    'down': 'danger',
                    'stable': 'secondary'
                };
                return colors[trend] || 'secondary';
            }

            getSalesTrendIcon(trend) {
                const icons = {
                    'up': 's-up-arrow',
                    'down': 's-down-arrow',
                    'stable': '-minus'
                };
                return icons[trend] || '-minus';
            }

            getLoadingHTML() {
                return `
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="ai-loading">
                        <div class="ai-loading-spinner"></div>
                        <h6 class="mt-3 text-muted">Loading AI insights...</h6>
                        <p class="text-muted small mb-0">Analyzing your business data</p>
                    </div>
                </div>
            </div>
        `;
            }

            showError(message) {
                this.container.innerHTML = `
            <div class="card border-danger shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="text-danger mb-3">
                        <i class="bx bx-wifi-off" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-danger mb-2">Connection Error</h5>
                    <p class="text-muted mb-3">${message}</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="window.adminDashboard.loadAIInsights()">
                        <i class="bx bx-refresh me-1"></i>Try Again
                    </button>
                </div>
            </div>
        `;
            }

            showFallback(data) {
                this.container.innerHTML = `
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-secondary text-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-error-circle" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-white">Business Insights</h5>
                            <p class="mb-0 text-white-50 small">AI temporarily unavailable</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bx bx-info-circle me-2"></i>Quick Business Analysis
                        </h6>
                        <p class="mb-0">AI insights are temporarily unavailable. Your business data shows you have products and orders to manage. Please check the system logs or try again later.</p>
                    </div>
                </div>
                
                <div class="card-footer bg-light border-top py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bx bx-time me-1"></i>Fallback mode
                        </small>
                        <button class="btn btn-sm btn-outline-primary" onclick="window.adminDashboard.loadAIInsights()">
                            <i class="bx bx-refresh me-1"></i>Try Again
                        </button>
                    </div>
                </div>
            </div>
        `;
            }
        }

        // Initialize Dashboard Controller
        document.addEventListener('DOMContentLoaded', function() {
            window.adminDashboard = new AdminDashboardController();
        });
    </script>
@endpush
