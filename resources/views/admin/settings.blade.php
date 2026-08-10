@extends('layouts.admin')

@section('title', 'Settings')

@section('page-title', 'Settings')

@section('content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 text-sm px-5 py-3 rounded-xl mb-6">
    {{ session('success') }}
</div>
@endif

    <div class="max-w-3xl">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.store') }}" method="POST" class="bg-white p-6 rounded shadow">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                <input name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" class="w-full border rounded px-3 py-2" />
                @error('site_name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Support Email</label>
                <input name="support_email" value="{{ old('support_email', $settings['support_email'] ?? '') }}" class="w-full border rounded px-3 py-2" />
                @error('support_email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                <input name="timezone" value="{{ old('timezone', $settings['timezone'] ?? '') }}" class="w-full border rounded px-3 py-2" />
                @error('timezone') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div> -->

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Save Settings</button>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500">Cancel</a>
            </div>
        </form>
    </div>
@endsection
