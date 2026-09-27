<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\PracticumSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PracticumScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = PracticumSchedule::with(['dosen', 'instrument', 'bookings'])
            ->where('tanggal', '>=', now()->subDays(7));

        if ($search = $request->get('q')) {
            $query->where('nama_praktikum', 'like', "%{$search}%");
        }

        if ($tanggal = $request->get('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        }

        $schedules = $query->orderBy('tanggal')->paginate(15)->withQueryString();

        return view('practicum-schedules.index', compact('schedules'));
    }

    public function create()
    {
        abort_unless(Auth::user()->hasAnyRole(['dosen', 'admin']), 403);

        $instruments = Instrument::where('status', 'tersedia')->get();

        return view('practicum-schedules.create', compact('instruments'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->hasAnyRole(['dosen', 'admin']), 403);

        $validated = $request->validate([
            'nama_praktikum' => ['required', 'string', 'max:255'],
            'instrument_id' => ['nullable', 'exists:instruments,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'catatan' => ['nullable', 'string'],
        ]);

        PracticumSchedule::create($validated + ['dosen_id' => Auth::id()]);

        return redirect()->route('practicum-schedules.index')
            ->with('success', 'Jadwal praktikum berhasil dibuat.');
    }

    public function show(PracticumSchedule $practicumSchedule)
    {
        $practicumSchedule->load(['dosen', 'instrument', 'bookings.user']);

        return view('practicum-schedules.show', ['schedule' => $practicumSchedule]);
    }
}
