<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings->hero_title }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-slate-700">

    <!-- Nav -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                    LK
                </div>
                <span class="font-semibold text-slate-800">SIMLAB Kimia <span class="text-emerald-600">Polinema</span></span>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                <a href="#layanan" class="hover:text-emerald-600">Layanan</a>
                <a href="#tentang" class="hover:text-emerald-600">Tentang</a>
                <a href="#kontak" class="hover:text-emerald-600">Kontak</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-b from-emerald-50 to-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 text-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 mb-5">
                {{ $settings->hero_badge }}
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">
                {{ $settings->hero_title }}
            </h1>
            <p class="mt-5 text-base sm:text-lg text-slate-500 max-w-2xl mx-auto">
                {{ $settings->hero_subtitle }}
            </p>
            <div class="mt-8 flex items-center justify-center gap-3">
                @auth
                    <a href="{{ route('samples.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-3 rounded-lg shadow-sm">
                        Daftarkan Sampel
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-3 rounded-lg shadow-sm">
                        Mulai Sekarang
                    </a>
                    <a href="#layanan" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium px-5 py-3 rounded-lg">
                        Pelajari Layanan
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="border-y border-slate-100 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-3xl font-extrabold text-emerald-600">{{ $stats['total_sampel'] }}+</p>
                <p class="text-sm text-slate-500 mt-1">Sampel Selesai Diuji</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-emerald-600">{{ $stats['total_alat'] }}</p>
                <p class="text-sm text-slate-500 mt-1">Alat Laboratorium</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-emerald-600">{{ $stats['total_parameter'] }}</p>
                <p class="text-sm text-slate-500 mt-1">Parameter Uji Tersedia</p>
            </div>
        </div>
    </section>

    <!-- Layanan -->
    <section id="layanan" class="py-20 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Layanan Kami</h2>
                <p class="mt-3 text-slate-500">Semua kebutuhan pengujian dan layanan laboratorium dalam satu sistem.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ($settings->features() as $feature)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center mb-4">
                            <x-icon :name="$feature['icon']" class="w-5 h-5 text-emerald-600" />
                        </div>
                        <h3 class="font-semibold text-slate-800">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-slate-500 mt-1.5">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Tentang -->
    <section id="tentang" class="py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $settings->about_title }}</h2>
            <p class="mt-4 text-slate-500 leading-relaxed">{{ $settings->about_text }}</p>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-gradient-to-r from-emerald-600 to-teal-600">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h2 class="text-2xl sm:text-3xl font-bold">Siap memulai pengujian sampel Anda?</h2>
            <p class="mt-3 text-emerald-50">Daftar sekarang dan pantau status pengujian secara real-time.</p>
            <a href="{{ auth()->check() ? route('samples.create') : route('register') }}"
               class="inline-flex items-center gap-2 bg-white text-emerald-700 text-sm font-medium px-6 py-3 rounded-lg mt-6 shadow-sm">
                Daftarkan Sampel Sekarang
            </a>
        </div>
    </section>

    <!-- Footer / Kontak -->
    <footer id="kontak" class="bg-slate-900 text-slate-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 sm:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-xs">
                        LK
                    </div>
                    <span class="font-semibold text-white">SIMLAB Kimia Polinema</span>
                </div>
                <p class="text-sm text-slate-400">Sistem Informasi Manajemen Laboratorium Kimia Politeknik Negeri Malang.</p>
            </div>

            <div>
                <h4 class="text-white text-sm font-semibold mb-3">Kontak</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li>{{ $settings->contact_email }}</li>
                    <li>{{ $settings->contact_phone }}</li>
                    <li>{{ $settings->contact_address }}</li>
                </ul>
            </div>

            <div>
                <h4 class="text-white text-sm font-semibold mb-3">Tautan</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="{{ route('login') }}" class="hover:text-white">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white">Daftar Akun</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 py-5 text-center text-xs text-slate-500">
            {{ $settings->footer_text }}
        </div>
    </footer>

</body>
</html>
