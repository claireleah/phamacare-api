<!-- @extends('layouts.admin')

@section('title', 'Riders')
@section('page-title', 'Riders')

@section('content')

{{-- ===== STATS CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Riders</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Active</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-gray-400">{{ $stats['inactive'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Inactive</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-red-500">{{ $stats['suspended'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Suspended</p>
    </div>
</div>

{{-- ===== TABLE CARD ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Table Header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="text-base font-semibold text-gray-800">All Riders</h3>

        <div class="flex items-center gap-3">
            {{-- Search --}}
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    id="searchInput"
                    type="text"
                    placeholder="Search rider..."
                    class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary w-52"
                />
            </div>

            {{-- Filter --}}
            <select
                id="statusFilter"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 text-gray-600">
                <option value="all">All Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Suspended">Suspended</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full" id="riderTable">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">#</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Rider</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Pharmacy</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Location</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Deliveries</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Earnings</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Joined</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="tableBody">
                @foreach($riders as $index => $rider)
                <tr class="hover:bg-gray-50/60 transition-colors rider-row"
                    data-status="{{ $rider['status'] }}"
                    data-name="{{ strtolower($rider['name']) }}">

                    {{-- # --}}
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $index + 1 }}</td>

                    {{-- Rider name + contact --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 text-sm font-bold">{{ substr($rider['name'], 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $rider['name'] }}</p>
                                <p class="text-xs text-gray-400">{{ $rider['phone'] }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Pharmacy --}}
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-700">{{ $rider['pharmacy'] }}</p>
                    </td>

                    {{-- Location --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 text-sm text-gray-600">
                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $rider['location'] }}
                        </div>
                    </td>

                    {{-- Deliveries --}}
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-gray-700">{{ $rider['deliveries'] }}</span>
                    </td>

                    {{-- Earnings --}}
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-gray-700">UGX {{ number_format($rider['earnings']) }}</span>
                    </td>

                    {{-- Joined --}}
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-500">{{ $rider['joined'] }}</span>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $rider['status'] === 'Active'    ? 'bg-green-100 text-green-700'  :
                              ($rider['status'] === 'Inactive'  ? 'bg-gray-100 text-gray-500'    :
                                                                  'bg-red-100 text-red-600') }}">
                            {{ $rider['status'] }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">

                            {{-- Suspend (only Active) --}}
                            @if($rider['status'] === 'Active')
                            <button
                                onclick="confirmAction('suspend', '{{ $rider['name'] }}')"
                                class="text-xs bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                Suspend
                            </button>
                            @endif

                            {{-- Reactivate (Suspended or Inactive) --}}
                            @if($rider['status'] === 'Suspended' || $rider['status'] === 'Inactive')
                            <button
                                onclick="confirmAction('reactivate', '{{ $rider['name'] }}')"
                                class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                Reactivate
                            </button>
                            @endif

                            {{-- View --}}
                            <button class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                View
                            </button>

                            {{-- Delete --}}
                            <button
                                onclick="confirmAction('delete', '{{ $rider['name'] }}')"
                                class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Empty state --}}
        <div id="emptyState" class="hidden py-16 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <p class="text-gray-400 text-sm">No riders found</p>
        </div>
    </div>

    {{-- Table Footer --}}
    <div class="px-6 py-3 border-t border-gray-50">
        <p class="text-xs text-gray-400" id="rowCount">Showing {{ count($riders) }} riders</p>
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
// ── Search & Filter ───────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);

function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const rows   = document.querySelectorAll('.rider-row');
    let visible  = 0;

    rows.forEach(row => {
        const nameMatch   = row.dataset.name.includes(search);
        const statusMatch = status === 'all' || row.dataset.status === status;
        const show        = nameMatch && statusMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('emptyState').classList.toggle('hidden', visible > 0);
    document.getElementById('rowCount').textContent = `Showing ${visible} riders`;
}

// ── Confirm Modal ─────────────────────────────────────────────
const modalConfig = {
    suspend:    { title: 'Suspend Rider',    color: 'bg-yellow-100', iconColor: 'text-yellow-600', btnColor: 'bg-yellow-500 hover:bg-yellow-600' },
    reactivate: { title: 'Reactivate Rider', color: 'bg-blue-100',   iconColor: 'text-blue-600',   btnColor: 'bg-blue-500 hover:bg-blue-600'    },
    delete:     { title: 'Delete Rider',     color: 'bg-red-100',    iconColor: 'text-red-600',    btnColor: 'bg-red-500 hover:bg-red-600'      },
};

function confirmAction(action, name) {
    const cfg = modalConfig[action];
    document.getElementById('modalIcon').className  = `w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 ${cfg.color}`;
    document.getElementById('modalIcon').innerHTML  = `<svg class="w-6 h-6 ${cfg.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>`;
    document.getElementById('modalTitle').textContent   = cfg.title;
    document.getElementById('modalMessage').textContent = `Are you sure you want to ${action} "${name}"? This action cannot be undone.`;
    const btn = document.getElementById('modalConfirmBtn');
    btn.className   = `flex-1 py-2.5 text-sm font-medium text-white rounded-xl transition-colors ${cfg.btnColor}`;
    btn.textContent = cfg.title;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection -->
