@extends('layouts.admin')

@section('title', 'Admin Users')
@section('page-title', 'Admin Users')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3">
        <h3 class="text-base font-semibold text-gray-800">All Admin Users</h3>

        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2">
                <input name="q" value="{{ request('q') }}" placeholder="Search users..." class="pl-3 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary w-48" />
                <button class="bg-primary text-white px-3 py-2 rounded-xl text-sm">Search</button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Name</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Email</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Joined</th>
                    <th class="text-left text-xs font-semibold text-gray-500 px-6 py-3 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-500">{{ $user->created_at->format('M j, Y') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="#" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg">View</a>
                            <a href="#" class="text-sm bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1.5 rounded-lg">Edit</a>
                            <form method="POST" action="#" class="inline">@csrf @method('DELETE')<button class="text-sm p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg">Delete</button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 border-t border-gray-50 flex items-center justify-between">
        <p class="text-xs text-gray-400">Showing {{ $users->count() }} of {{ $users->total() }} users</p>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>

@endsection
