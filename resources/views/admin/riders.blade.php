@extends('layouts.admin')

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
        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Pending Approval</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Active</p>
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
                <option value="Pending">Pending</option>
                <option value="Active">Active</option>
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
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Email</th>
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

                    <td class="px-6 py-4 text-sm text-gray-400">{{ $index + 1 }}</td>

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

                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600">{{ $rider['email'] }}</p>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($rider['created_at'])->format('M j, Y') }}</span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $rider['status'] === 'Active'    ? 'bg-green-100 text-green-700'  :
                              ($rider['status'] === 'Pending'   ? 'bg-yellow-100 text-yellow-700' :
                                                                  'bg-red-100 text-red-600') }}">
                            {{ $rider['status'] }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">

                            @if($rider['status'] === 'Pending')
                            <form action="{{ route('admin.riders.status', $rider['id']) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Active">
                                <button type="submit" class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                    Approve
                                </button>
                            </form>
                            @endif

                            @if($rider['status'] === 'Active')
                            <form action="{{ route('admin.riders.status', $rider['id']) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Suspended">
                                <button type="submit" class="text-xs bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                    Suspend
                                </button>
                            </form>
                            @endif

                            @if($rider['status'] === 'Suspended')
                            <form action="{{ route('admin.riders.status', $rider['id']) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Active">
                                <button type="submit" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                    Reactivate
                                </button>
                            </form>
                            @endif

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

@endsection

@section('scripts')
<script>
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
</script>
@endsection