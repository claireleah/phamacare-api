@extends('layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-green-700">{{ $stats['total'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Pharmacies</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-green-700">{{ $stats['active'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Active</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-green-700">{{ $stats['pending'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Pending</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-green-700">{{ $stats['suspended'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Suspended</p>
    </div>
</div>

{{-- Pharmacies Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-gray-800">Platform Pharmacies Report</h3>
            <p class="text-xs text-gray-400 mt-0.5">All pharmacies registered on the platform</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Pharmacy</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Owner</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Location</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Joined</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Subscription</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pharmacies as $pharmacy)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-800">{{ $pharmacy['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $pharmacy['email'] }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-700">{{ $pharmacy['owner_name'] }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600">{{ $pharmacy['location'] }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($pharmacy['created_at'])->format('M d, Y') }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ ($pharmacy['subscription']['status'] ?? 'Not Started') === 'Paid'    ? 'bg-green-100 text-green-700'  :
                              (($pharmacy['subscription']['status'] ?? 'Not Started') === 'Overdue' ? 'bg-red-100 text-red-600'      :
                                                                                                      'bg-gray-100 text-gray-500') }}">
                            {{ $pharmacy['subscription']['status'] ?? 'Not Started' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $pharmacy['status'] === 'Active'    ? 'bg-blue-100 text-blue-700'   :
                              ($pharmacy['status'] === 'Pending'   ? 'bg-yellow-100 text-yellow-700':
                                                                     'bg-red-100 text-red-600') }}">
                            {{ $pharmacy['status'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-sm text-gray-400">
                        No pharmacies found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection