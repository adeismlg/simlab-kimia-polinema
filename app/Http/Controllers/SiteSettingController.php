<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteSettingController extends Controller
{
    private array $iconOptions = [
        'beaker', 'wrench', 'calendar', 'clipboard-check', 'chart', 'currency', 'users', 'home',
    ];

    private function authorizeAdmin(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if (method_exists($user, 'hasRole')) {
            abort_unless($user->hasRole('admin'), 403);
            return;
        }

        abort_unless($user->role === 'admin' || $user->is_admin ?? false, 403);
    }

    public function edit()
    {
        $this->authorizeAdmin();

        $settings = SiteSetting::current();
        $iconOptions = $this->iconOptions;

        return view('site-settings.edit', compact('settings', 'iconOptions'));
    }

    public function update(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'hero_badge' => ['required', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string', 'max:1000'],
            'about_title' => ['required', 'string', 'max:255'],
            'about_text' => ['required', 'string', 'max:2000'],

            'feature_1_icon' => ['required', 'in:' . implode(',', $this->iconOptions)],
            'feature_1_title' => ['required', 'string', 'max:255'],
            'feature_1_description' => ['required', 'string', 'max:500'],
            'feature_2_icon' => ['required', 'in:' . implode(',', $this->iconOptions)],
            'feature_2_title' => ['required', 'string', 'max:255'],
            'feature_2_description' => ['required', 'string', 'max:500'],
            'feature_3_icon' => ['required', 'in:' . implode(',', $this->iconOptions)],
            'feature_3_title' => ['required', 'string', 'max:255'],
            'feature_3_description' => ['required', 'string', 'max:500'],
            'feature_4_icon' => ['required', 'in:' . implode(',', $this->iconOptions)],
            'feature_4_title' => ['required', 'string', 'max:255'],
            'feature_4_description' => ['required', 'string', 'max:500'],

            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_address' => ['required', 'string', 'max:500'],
            'footer_text' => ['required', 'string', 'max:255'],
        ]);

        SiteSetting::current()->update($validated);

        return back()->with('success', 'Konten landing page berhasil diperbarui.');
    }
}
