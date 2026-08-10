@extends('layouts.admin')

@section('title', 'Subscriptions')
@section('page-title', 'Subscriptions & Billing')

@section('content')

{{-- ===== STATS CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
            </svg>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Pharmacies</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Active</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <p class="text-3xl font-bold text-red-500">{{ $stats['overdue'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Overdue</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-xl font-bold text-gray-800">UGX {{ number_format($stats['monthly_revenue']) }}</p>
        <p class="text-sm text-gray-500 mt-1">Monthly Revenue</p>
    </div>
</div>

{{-- ===== OVERDUE ALERT ===== --}}
@if($stats['overdue'] > 0)
<div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 mb-7 flex items-center gap-3">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <p class="text-sm text-red-700 font-medium">
        {{ $stats['overdue'] }} {{ $stats['overdue'] === 1 ? 'pharmacy has' : 'pharmacies have' }} overdue payments. Send reminders or suspend their access.
    </p>
</div>
@endif

{{-- ===== TABLE ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-gray-800">Subscription Overview</h3>
            <p class="text-xs text-gray-400 mt-0.5">UGX 200,000 per pharmacy per month</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Search --}}
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="searchInput" type="text" placeholder="Search pharmacy..."
                    class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary w-52"/>
            </div>

            {{-- Filter --}}
            <select id="statusFilter"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 text-gray-600">
                <option value="all">All Status</option>
                <option value="Active">Active</option>
                <option value="Overdue">Overdue</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <!-- <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">No</th> -->
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Pharmacy</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Monthly Fee</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Start Date</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Next Billing</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Months Paid</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($subscriptions as $index => $sub)
                <tr class="hover:bg-gray-50/60 transition-colors sub-row"
                    data-status="{{ $sub['status'] }}"
                    data-name="{{ strtolower($sub['pharmacy']) }}">

                    <!-- <td class="px-6 py-4 text-sm text-gray-400">{{ $index + 1 }}</td> -->

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <!-- <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-green-700 text-sm font-bold">{{ substr($sub['pharmacy'], 0, 1) }}</span>
                            </div> -->
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $sub['pharmacy'] }}</p>
                                <p class="text-xs text-gray-400">{{ $sub['location'] }}</p>
                            </div>
                        </div>
                    </td>

                    

                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-gray-800">UGX {{ number_format($sub['amount']) }}</span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-500">{{ $sub['start_date'] }}</span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-sm {{ $sub['status'] === 'Overdue' ? 'text-red-500 font-semibold' : 'text-gray-500' }}">
                            {{ $sub['next_billing'] }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-gray-700">{{ $sub['paid_months'] }} months</span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $sub['status'] === 'Active'    ? 'bg-green-100 text-green-700'  :
                              ($sub['status'] === 'Overdue'   ? 'bg-red-100 text-red-600'      :
                                                                'bg-gray-100 text-gray-500') }}">
                            {{ $sub['status'] }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">

                            {{-- Send Reminder (Overdue only) --}}
                            @if($sub['status'] === 'Overdue')
                            <button
                                type="button"
                                data-action="remind"
                                data-name="{{ $sub['pharmacy'] }}"
                                class="text-xs action-btn bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-1.5 rounded-lg font-medium transition-colors whitespace-nowrap">
                                Remind
                            </button>
                            @endif

                            {{-- View History --}}
                            <button class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg font-medium transition-colors whitespace-nowrap">
                                History
                            </button>

                            {{-- Suspend (Active only) --}}
                            @if($sub['status'] === 'Active')
                            <button
                                type="button"
                                data-action="suspend"
                                data-name="{{ $sub['pharmacy'] }}"
                                class="p-1.5 action-btn text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div id="emptyState" class="hidden py-16 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <p class="text-gray-400 text-sm">No subscriptions found</p>
        </div>
    </div>

    <div class="px-6 py-3 border-t border-gray-50">
        <p class="text-xs text-gray-400" id="rowCount">Showing {{ count($subscriptions) }} subscriptions</p>
    </div>
</div>

{{-- ===== CONFIRM MODAL ===== --}}
<div id="confirmModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div id="modalIcon" class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4"></div>
        <h3 id="modalTitle" class="text-base font-semibold text-gray-800 text-center mb-2"></h3>
        <p id="modalMessage" class="text-sm text-gray-500 text-center mb-6"></p>
        <div class="flex gap-3">
            <button onclick="closeModal()" class="flex-1 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancel</button>
            <button id="modalConfirmBtn" class="flex-1 py-2.5 text-sm font-medium text-white rounded-xl transition-colors">Confirm</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const actionButtons = document.querySelectorAll('.action-btn');
const emptyState = document.getElementById('emptyState');
const rowCount = document.getElementById('rowCount');
const rows = document.querySelectorAll('.sub-row');
const confirmModal = document.getElementById('confirmModal');

if (searchInput) searchInput.addEventListener('input', filterTable);
if (statusFilter) statusFilter.addEventListener('change', filterTable);

actionButtons.forEach(button => {
    button.addEventListener('click', function() {
        confirmAction(this.dataset.action, this.dataset.name);
    });
});

function filterTable() {
    const search = searchInput ? searchInput.value.toLowerCase() : '';
    const status = statusFilter ? statusFilter.value : 'all';
    let visible = 0;

    rows.forEach(row => {
        const nameMatch = row.dataset.name ? row.dataset.name.includes(search) : false;
        const statusMatch = status === 'all' || row.dataset.status === status;
        const show = nameMatch && statusMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    if (emptyState) emptyState.classList.toggle('hidden', visible > 0);
    if (rowCount) rowCount.textContent = `Showing ${visible} subscriptions`;
}

const modalConfig = {
    remind:  { title: 'Send Payment Reminder', color: 'bg-orange-100', iconColor: 'text-orange-600', btnColor: 'bg-orange-500 hover:bg-orange-600' },
    suspend: { title: 'Suspend Subscription',  color: 'bg-red-100',    iconColor: 'text-red-600',    btnColor: 'bg-red-500 hover:bg-red-600'       },
};

function confirmAction(action, name) {
    const cfg = modalConfig[action];
    const modalIcon = document.getElementById('modalIcon');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');

    if (modalIcon) {
        modalIcon.className = `w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 ${cfg.color}`;
        modalIcon.innerHTML = `<svg class="w-6 h-6 ${cfg.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>`;
    }

    if (modalTitle) modalTitle.textContent = cfg.title;
    if (modalMessage) modalMessage.textContent = `Are you sure you want to ${action === 'remind' ? 'send a payment reminder to' : 'suspend'} "${name}"?`;
    if (modalConfirmBtn) {
        modalConfirmBtn.className = `flex-1 py-2.5 text-sm font-medium text-white rounded-xl transition-colors ${cfg.btnColor}`;
        modalConfirmBtn.textContent = cfg.title;
    }

    if (confirmModal) confirmModal.classList.remove('hidden');
}

function closeModal() {
    if (confirmModal) confirmModal.classList.add('hidden');
}

if (confirmModal) {
    confirmModal.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
}

filterTable();
</script>
@endsection
