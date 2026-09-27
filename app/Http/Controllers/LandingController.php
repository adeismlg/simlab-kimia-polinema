<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\Sample;
use App\Models\SiteSetting;

class LandingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::current();

        $stats = [
            'total_sampel' => Sample::where('status', 'selesai')->count(),
            'total_alat' => Instrument::count(),
            'total_parameter' => \App\Models\Parameter::where('aktif', true)->count(),
        ];

        return view('landing', compact('settings', 'stats'));
    }
}
