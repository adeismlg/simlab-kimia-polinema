<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}SIMLAB Kimia Polinema</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-700">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        <!-- Sidebar: desktop, permanently visible -->
        <aside class="hidden lg:block w-64 shrink-0">
            <div class="fixed inset-y-0 w-64">
                @include('layouts.partials.sidebar')
            </div>
        </aside>

        <!-- Sidebar: mobile drawer -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 lg:hidden">
            <div class="fixed inset-0 bg-slate-900/50" @click="sidebarOpen = false"></div>
            <div class="fixed inset-y-0 left-0 w-64" @click.away="sidebarOpen = false">
                @include('layouts.partials.sidebar')
            </div>
        </div>

        <!-- Main column -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Slim topbar: just menu toggle + user menu -->
            <header class="h-14 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500">
                    <x-icon name="menu" class="w-6 h-6" />
                </button>
                <div class="hidden lg:block text-xs text-slate-400">
                    SIMLAB Kimia Polinema
                </div>

                <div class="flex items-center gap-4">
                    <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                        {{ ucfirst(auth()->user()->tipe) }}
                    </span>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                            <span class="hidden md:block text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>
                            <x-icon name="chevron-down" class="w-4 h-4 text-slate-400 hidden md:block" />
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-slate-100 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page header: title + optional subtitle + actions, its own full-width row -->
            @isset($header)
                <div class="bg-white border-b border-slate-200 px-4 sm:px-6 py-5">
                    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        {!! $header !!}
                    </div>
                </div>
            @endisset

            <!-- Page content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
