<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaCare Admin — @yield('title', 'Dashboard')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#217341',
                        'primary-dark': '#15803D',
                        sidebar: '#111827',
                    },
                    fontFamily: {
                         sans: ['Montserrat', 'Open Sans', 'Arial', 'sans-serif'],
                        }
                    // fontFamily: {
                    //     sans: ['Poppins', 'sans-serif'],
                    // }
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * { font-family: 'Montserrat', 'Open Sans', Arial, sans-serif; }
        /* * { font-family: 'Poppins', sans-serif; } */
        .nav-link { transition: all 0.2s ease; }
        .nav-link:hover { background-color: rgba(255,255,255,0.07); }
        .nav-link.active { background-color: #217341; color: white; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
    </style>

    @yield('styles')
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-60 bg-gray-900 flex flex-col fixed h-full z-20 shadow-xl">

        {{-- Logo --}}
        <div class="flex items-center px-3 py-3 border-b border-gray-700/50">
            <img src="{{ asset('images/logo3.png') }}" alt="PharmaCare Logo" class="w-20 h-20 object-contain">
            <div class="min-w-0">
                <p class="font-semibold text-white text-sm leading-tight">PharmaCare</p>
                <p class="text-xs text-gray-400">Admin Portal</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">

            <!-- <p class="text-xs font-medium text-gray-500 px-3 mb-3 uppercase tracking-wider">Main Menu</p> -->

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h7v7H3zM14 7h7v7h-7zM3 17h7v4H3zM14 17h7v4h-7z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.pharmacies') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 {{ request()->routeIs('admin.pharmacies*') ? 'active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Pharmacies
            </a>

            <a href="{{ route('admin.riders') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 {{ request()->routeIs('admin.riders*') ? 'active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Riders
            </a>

            <div class="pt-4 pb-2">
                <p class="text-xs font-medium text-gray-500 px-3 mb-3 uppercase tracking-wider">Analytics</p>
            </div>

            <a href="{{ route('admin.reports') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Reports
            </a>

            <div class="pt-4 pb-2">
                <p class="text-xs font-medium text-gray-500 px-3 mb-3 uppercase tracking-wider">System</p>
            </div>

            <!-- <a href="{{ route('admin.users') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Admin Users
            </a> -->

            <a href="{{ route('admin.settings') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-gray-700/50">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button></form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1 ml-60 flex flex-col min-h-screen overflow-hidden">

        {{-- Top Header --}}
        <header class="bg-green-800 border-b border-gray-100 px-8 py-2 flex items-center justify-between sticky top-0 z-10 shadow-sm">
            <div>
                <h1 class="text-lg font-semibold text-gray-1000">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-white mt-0.5">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="flex items-center gap-4">
                {{-- Notifications --}}
        

                {{-- Admin Profile --}}
                <div class="flex items-center gap-2.5 cursor-pointer">
                    <div class="hidden md:block">
                        <p class="text-sm font-medium text-gray-1000 leading-tight">Super Admin</p>
                        <!-- <p class="text-xs text-white">admin@pharmacare.com</p> -->
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto min-h-0">
            <div class="p-8 min-h-0">
                @yield('content')
            </div>
        </div>

        <footer class="sticky bottom-0 bg-green-800 border-t border-gray-100 px-8 py-4 flex items-center justify-between w-full z-10 shadow-sm">
            <p class="text-sm text-white font-medium">&copy; 2026 Pharmacare. All rights reserved.</p>
        </footer>
    </main>
    
</div>


@yield('scripts')
</body>
</html>
