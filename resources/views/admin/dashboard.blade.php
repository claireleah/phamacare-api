@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ===== STATS CARDS ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">

    {{-- Total Pharmacies --}}
    <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 hover:border-primary/30 transition-all duration-200 cursor-pointer">
        <div class="mt-4">
            <p class="text-2xl font-bold text-green-700 group-hover:text-primary-dark transition-colors">{{ $stats['total_pharmacies'] }}</p>
            <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors">Total Pharmacies</p>
        </div>
    </div>

    <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 hover:border-primary/30 transition-all duration-200 cursor-pointer">
        <div class="mt-4">
            <p class="text-3xl font-bold text-green-700 group-hover:text-primary-dark transition-colors">{{ $stats['active_pharmacies'] }}</p>
            <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors">Active Pharmacies</p>
        </div>
    </div>

    <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 hover:border-primary/30 transition-all duration-200 cursor-pointer">
        <div class="mt-4">
            <p class="text-3xl font-bold text-green-700 group-hover:text-primary-dark transition-colors">{{ $stats['pending_pharmacies'] }}</p>
            <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors">Pending Approval</p>
        </div>
    </div>

    <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 hover:border-primary/30 transition-all duration-200 cursor-pointer">
        <div class="mt-4">
            <p class="text-2xl font-bold text-green-700 group-hover:text-primary-dark transition-colors">UGX {{ number_format($stats['monthly_revenue']) }}</p>
            <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors">Total Revenue</p>
        </div>
    </div>
</div>

{{-- ===== CHARTS ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-7">

    {{-- Sales Overview --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-semibold text-gray-800">Revenue Overview</h3>
                <p class="text-xs text-gray-400 mt-0.5">Platform revenue over the last 7 days</p>
            </div>
            <select class="text-xs text-gray-500 border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>This month</option>
            </select>
        </div>
        <canvas id="salesChart" height="110"></canvas>
    </div>
</div>

{{-- ===== TABLES ===== --}}
    <div class="grid grid-cols-1 gap-5">

        {{-- Recent Pharmacies --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">Recent Pharmacies</h3>
                <a href="#" class="text-xs text-primary hover:text-primary-dark font-medium transition-colors">View all </a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recentPharmacies as $pharmacy)
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="text-sm font-medium text-gray-800 leading-tight">{{ $pharmacy['name'] }}</p>
                            <p class="text-xs text-gray-400">{{ $pharmacy['location'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ $pharmacy['status'] === 'Active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $pharmacy['status'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    

@endsection

@section('scripts')
<script>
// ── Sales Line Chart ──────────────────────────────────────────
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: ['Jun 17', 'Jun 18', 'Jun 19', 'Jun 20', 'Jun 21', 'Jun 22', 'Jun 23'],
        datasets: [{
            label: 'Sales',
            data: [3200000, 4800000, 3900000, 6100000, 5200000, 7400000, 6800000],
            borderColor: '#16A34A',
            backgroundColor: (ctx) => {
                const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                gradient.addColorStop(0, 'rgba(22,163,74,0.15)');
                gradient.addColorStop(1, 'rgba(22,163,74,0.0)');
                return gradient;
            },
            borderWidth: 2.5,
            fill: true,
            tension: 0.45,
            pointBackgroundColor: '#16A34A',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' UGX ' + ctx.parsed.y.toLocaleString()
                }
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                ticks: {
                    callback: v => 'UGX ' + (v / 1000000).toFixed(1) + 'M',
                    font: { size: 11, family: 'Poppins' },
                    color: '#9CA3AF',
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    font: { size: 11, family: 'Poppins' },
                    color: '#9CA3AF',
                }
            }
        }
    }
});

// ── Order Donut Chart ─────────────────────────────────────────
const orderCtx = document.getElementById('orderChart').getContext('2d');
new Chart(orderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Confirmed', 'In Progress', 'Delivered'],
        datasets: [{
            data: [312, 415, 256, 251],
            backgroundColor: ['#FBBF24', '#3B82F6', '#FB923C', '#22C55E'],
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 6,
        }]
    },
    options: {
        cutout: '72%',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.label + ': ' + ctx.parsed
                }
            }
        }
    }
});
</script>
@endsection
