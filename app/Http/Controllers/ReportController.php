<?php

namespace App\Http\Controllers;

use App\Exports\SamplesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Halaman untuk memilih periode/filter laporan sebelum export.
     */
    public function index()
    {
        abort_unless(Auth::user()->hasAnyRole(['laboran', 'admin', 'kepala_lab']), 403);

        return view('reports.index');
    }

    /**
     * Export sampel ke Excel. Menerima filter opsional: status, q, month, year.
     */
    public function exportSamples(Request $request)
    {
        abort_unless(Auth::user()->hasAnyRole(['laboran', 'admin', 'kepala_lab']), 403);

        $export = new SamplesExport(
            status: $request->get('status'),
            search: $request->get('q'),
            month: $request->get('month'),
            year: $request->get('year'),
        );

        $filename = 'laporan-sampel-' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download($export, $filename);
    }
}
