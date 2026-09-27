@php
    $menu = [
        ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
        ['route' => 'samples.index', 'match' => 'samples.*', 'label' => 'Sampel Uji', 'icon' => 'beaker'],
        ['route' => 'practicum-schedules.index', 'match' => 'practicum-*', 'label' => 'Praktikum', 'icon' => 'calendar'],
        ['route' => 'instruments.index', 'match' => 'instruments.*|calibrations.*', 'label' => 'Alat & Kalibrasi', 'icon' => 'wrench'],
    ];
@endphp

<div class="h-full flex flex-col bg-white border-r border-slate-200">
    <div class="h-16 flex items-center gap-2 px-5 border-b border-slate-200 shrink-0">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
            LK
        </div>
        <div class="leading-tight">
            <p class="text-sm font-semibold text-slate-800">SIMLAB Kimia</p>
            <p class="text-xs text-slate-400">Politeknik Negeri Malang</p>
        </div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Menu Utama</p>
        @foreach ($menu as $item)
            @php $active = request()->routeIs(explode('|', $item['match'])); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                      {{ $active ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <x-icon :name="$item['icon']" class="w-5 h-5 {{ $active ? 'text-emerald-600' : 'text-slate-400' }}" />
                {{ $item['label'] }}
            </a>
        @endforeach

        @auth
            @if (auth()->user()->hasAnyRole(['laboran', 'admin', 'kepala_lab']))
                <p class="px-3 pt-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Administrasi</p>
                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('reports.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <x-icon name="chart" class="w-5 h-5 {{ request()->routeIs('reports.*') ? 'text-emerald-600' : 'text-slate-400' }}" />
                    Laporan
                </a>
            @endif
            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('users.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <x-icon name="users" class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-emerald-600' : 'text-slate-400' }}" />
                    Kelola User
                </a>
            @endif
        @endauth
    </nav>

    <div class="p-3 border-t border-slate-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                <x-icon name="logout" class="w-5 h-5 text-slate-400" />
                Keluar
            </button>
        </form>
    </div>
</div>
