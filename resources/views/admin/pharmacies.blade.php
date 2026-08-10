@extends('layouts.admin')

@section('title', 'Pharmacies')
@section('page-title', 'Pharmacies')

@section('content')

{{-- ===== STATS CARDS ===== --}}
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
        <p class="text-sm text-gray-500 mt-1">Pending Approval</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-green-700">{{ $stats['suspended'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Suspended</p>
    </div>
</div>

{{-- ===== TABLE CARD ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Table Header: title + search + filter --}}
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="text-base font-semibold text-gray-800">All Pharmacies</h3>

        <div class="flex items-center gap-3">
            {{-- Search --}}
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    id="searchInput"
                    type="text"
                    placeholder="Search pharmacy..."
                    class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary w-52"
                />
            </div>

            <a href="{{ route('admin.pharmacies.export') }}"
                class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl font-medium">
                    Export CSV
            </a>

            {{-- Filter --}}
            <select
                id="statusFilter"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 text-gray-600">
                <option value="all">All Status</option>
                <option value="Active">Active</option>
                <option value="Pending">Pending</option>
                <option value="Suspended">Suspended</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full" id="pharmacyTable">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Pharmacy</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Owner</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Location</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Subscription</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Joined</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="tableBody">
                @foreach($pharmacies as $index => $pharmacy)
                <tr class="hover:bg-gray-50/60 transition-colors pharmacy-row" data-status="{{ $pharmacy['status'] }}" data-name="{{ strtolower($pharmacy['name']) }}">

                    <!-- {{-- # --}}
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $index + 1 }}</td> -->

                    {{-- Pharmacy name + email --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <!-- <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-green-700 text-sm font-bold">{{ substr($pharmacy['name'], 0, 1) }}</span>
                            </div> -->
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $pharmacy['name'] }}</p>
                                <p class="text-xs text-gray-400">{{ $pharmacy['email'] }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Owner --}}
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-700">{{ $pharmacy['owner_name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $pharmacy['phone'] }}</p>
                    </td>

                    {{-- Location --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 text-sm text-gray-600">
                            {{ $pharmacy['location'] }}
                        </div>
                    </td>

                    {{-- Subscription --}}
                    <td class="px-6 py-4">
                        @php $subscription =  $pharmacy['subscription']['status'] ?? 'Not Started'; @endphp
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $subscription === 'Paid'      ? 'bg-green-100 text-green-700' :
                              ($subscription === 'Overdue'   ? 'bg-red-100 text-red-600'    :
                                                               'bg-gray-100 text-gray-500') }}">
                            {{ $subscription }}
                        </span>
                    </td>

                    
                    {{-- Joined --}}
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($pharmacy['created_at'])->format('M d, Y') }}</span>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $pharmacy['status'] === 'Active'    ? 'bg-green-100 text-green-700'  :
                              ($pharmacy['status'] === 'Pending'   ? 'bg-yellow-100 text-yellow-700':
                                                                     'bg-red-100 text-red-600') }}">
                            {{ $pharmacy['status'] }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">

                            {{-- Approve (only for Pending) --}}
                            @if($pharmacy['status'] === 'Pending')
                            <form method="POST" action="{{ route('admin.pharmacies.status', $pharmacy['id']) }}">
                                @csrf
                                <input type="hidden" name="status" value="Active">
                                <button type="submit"
                                    class="text-xs bg-green-500 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-medium transition-colors">
                                    Approve
                                </button>
                            </form>
                            @endif

                            {{-- Suspend (only for Active) --}}
                            @if($pharmacy['status'] === 'Active')
                            <form method="POST" action="{{ route('admin.pharmacies.status', $pharmacy['id']) }}">
                                @csrf
                                <input type="hidden" name="status" value="Suspended">
                                <button type="submit"
                                    class="text-xs bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                    Suspend
                                </button>
                            </form>
                            @endif

                            {{-- Reactivate (only for Suspended) --}}
                            @if($pharmacy['status'] === 'Suspended')
                            <form method="POST" action="{{ route('admin.pharmacies.status', $pharmacy['id']) }}">
                                @csrf
                                <input type="hidden" name="status" value="Active">
                                <button type="submit"
                                    class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                    Reactivate
                                </button>
                            </form>
                            @endif

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.pharmacies.destroy', $pharmacy['id']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                    Delete
                                </button>
                            </form>
                            
                            
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Empty state --}}
        <div id="emptyState" class="hidden py-16 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
            </svg>
            <p class="text-gray-400 text-sm">No pharmacies found</p>
        </div>
    </div>

    {{-- Table Footer --}}
    <div class="px-6 py-3 border-t border-gray-50 flex items-center justify-between">
        <p class="text-xs text-gray-400" id="rowCount">Showing {{ count($pharmacies) }} pharmacies</p>
    </div>
</div>

{{-- ===== CONFIRM MODAL ===== --}}
<div id="confirmModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div id="modalIcon" class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4"></div>
        <h3 id="modalTitle" class="text-base font-semibold text-gray-800 text-center mb-2"></h3>
        <p id="modalMessage" class="text-sm text-gray-500 text-center mb-6"></p>
        <div class="flex gap-3">
            <button onclick="closeModal()" class="flex-1 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                Cancel
            </button>
            <button id="modalConfirmBtn" class="flex-1 py-2.5 text-sm font-medium text-white rounded-xl transition-colors">
                Confirm
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Wrap listeners to ensure DOM elements exist, and run initial filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);

    // Initial run to set empty state and row count
    filterTable();

    // Close modal on backdrop click
    const confirmModalEl = document.getElementById('confirmModal');
    if (confirmModalEl) {
        confirmModalEl.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    }
});

function filterTable() {
    const searchEl = document.getElementById('searchInput');
    const statusEl = document.getElementById('statusFilter');
    const search = searchEl ? searchEl.value.toLowerCase() : '';
    const status = statusEl ? statusEl.value : 'all';
    const rows   = document.querySelectorAll('.pharmacy-row');
    let visible  = 0;

    rows.forEach(row => {
        const nameMatch   = row.dataset.name && row.dataset.name.includes(search);
        const statusMatch = status === 'all' || row.dataset.status === status;
        const show        = nameMatch && statusMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    const emptyState = document.getElementById('emptyState');
    if (emptyState) emptyState.classList.toggle('hidden', visible > 0);
    const rowCountEl = document.getElementById('rowCount');
    if (rowCountEl) rowCountEl.textContent = `Showing ${visible} pharmacies`;
}

// ── Confirm Modal ─────────────────────────────────────────────
const modalConfig = {
    approve:    { title: 'Approve Pharmacy',    color: 'bg-green-100',  iconColor: 'text-green-600',  btnColor: 'bg-green-500 hover:bg-green-600',  msg: 'are you sure you want to approve' },
    suspend:    { title: 'Suspend Pharmacy',    color: 'bg-yellow-100', iconColor: 'text-yellow-600', btnColor: 'bg-yellow-500 hover:bg-yellow-600', msg: 'are you sure you want to suspend' },
    reactivate: { title: 'Reactivate Pharmacy', color: 'bg-blue-100',   iconColor: 'text-blue-600',   btnColor: 'bg-blue-500 hover:bg-blue-600',    msg: 'are you sure you want to reactivate' },
    delete:     { title: 'Delete Pharmacy',     color: 'bg-red-100',    iconColor: 'text-red-600',    btnColor: 'bg-red-500 hover:bg-red-600',      msg: 'are you sure you want to permanently delete' },
};

function confirmAction(action, name) {
    const cfg = modalConfig[action];
    const iconEl = document.getElementById('modalIcon');
    if (iconEl) {
        iconEl.className    = `w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 ${cfg.color}`;
        iconEl.innerHTML    = `<svg class="w-6 h-6 ${cfg.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>`;
    }
    const titleEl = document.getElementById('modalTitle');
    if (titleEl) titleEl.textContent   = cfg.title;
    const msgEl = document.getElementById('modalMessage');
    if (msgEl) msgEl.textContent = `Are you sure you want to ${action} "${name}"? This action cannot be undone.`;
    const btn = document.getElementById('modalConfirmBtn');
    if (btn) {
        btn.className = `flex-1 py-2.5 text-sm font-medium text-white rounded-xl transition-colors ${cfg.btnColor}`;
        btn.textContent = cfg.title;
    }
    const confirmModal = document.getElementById('confirmModal');
    if (confirmModal) confirmModal.classList.remove('hidden');
}

function closeModal() {
    const confirmModal = document.getElementById('confirmModal');
    if (confirmModal) confirmModal.classList.add('hidden');
}
</script>
@endsection
