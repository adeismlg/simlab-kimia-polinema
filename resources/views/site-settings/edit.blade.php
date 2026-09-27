<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Kelola Landing Page" subtitle="Ubah konten halaman depan tanpa perlu sentuh kode">
            <a href="{{ route('landing') }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium px-4 py-2.5 rounded-lg">
                Lihat Halaman →
            </a>
        </x-page-header>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="p-3 bg-red-100 text-red-800 rounded text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('site-settings.update') }}" class="space-y-6">
            @csrf @method('PUT')

            <!-- Hero -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                <h3 class="font-semibold text-slate-800">Bagian Hero (Atas Halaman)</h3>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Badge Kecil</label>
                    <input type="text" name="hero_badge" value="{{ old('hero_badge', $settings->hero_badge) }}"
                           class="mt-1 block w-full rounded-lg border-slate-300 text-sm" placeholder="Contoh: Terakreditasi ISO/IEC 17025">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Judul Utama</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $settings->hero_title) }}"
                           class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Subjudul</label>
                    <textarea name="hero_subtitle" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                </div>
            </div>

            <!-- Features -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
                <h3 class="font-semibold text-slate-800">Kartu Layanan (4 kartu di halaman depan)</h3>

                @for ($i = 1; $i <= 4; $i++)
                    <div class="grid grid-cols-1 sm:grid-cols-[120px_1fr_1.5fr] gap-3 items-start pb-4 {{ $i < 4 ? 'border-b border-slate-100' : '' }}">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Ikon</label>
                            <select name="feature_{{ $i }}_icon" class="block w-full rounded-lg border-slate-300 text-sm">
                                @foreach ($iconOptions as $icon)
                                    <option value="{{ $icon }}" {{ old("feature_{$i}_icon", $settings->{"feature_{$i}_icon"}) === $icon ? 'selected' : '' }}>
                                        {{ $icon }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Judul</label>
                            <input type="text" name="feature_{{ $i }}_title"
                                   value="{{ old('feature_' . $i . '_title', $settings->{'feature_' . $i . '_title'}) }}"
                                   class="block w-full rounded-lg border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Deskripsi</label>
                            <input type="text" name="feature_{{ $i }}_description"
                                   value="{{ old('feature_' . $i . '_description', $settings->{'feature_' . $i . '_description'}) }}"
                                   class="block w-full rounded-lg border-slate-300 text-sm">
                        </div>
                    </div>
                @endfor
            </div>

            <!-- About -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                <h3 class="font-semibold text-slate-800">Bagian Tentang</h3>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Judul</label>
                    <input type="text" name="about_title" value="{{ old('about_title', $settings->about_title) }}"
                           class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea name="about_text" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">{{ old('about_text', $settings->about_text) }}</textarea>
                </div>
            </div>

            <!-- Kontak -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                <h3 class="font-semibold text-slate-800">Kontak & Footer</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Telepon</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Alamat</label>
                    <input type="text" name="contact_address" value="{{ old('contact_address', $settings->contact_address) }}"
                           class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Teks Footer (copyright)</label>
                    <input type="text" name="footer_text" value="{{ old('footer_text', $settings->footer_text) }}"
                           class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                </div>
            </div>

            <button class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow-sm">
                Simpan Perubahan
            </button>
        </form>
    </div>
</x-app-layout>
