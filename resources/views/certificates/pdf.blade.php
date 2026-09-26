<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Hasil Uji - {{ $sample->kode_sampel }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #222; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 0; }
        .header p { margin: 2px 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background: #f0f0f0; }
        .info-table td { border: none; padding: 2px 0; }
        .signature { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SERTIFIKAT HASIL UJI</h1>
        <p>Laboratorium Kimia — Politeknik Negeri Malang</p>
    </div>

    <table class="info-table">
        <tr><td width="150">Kode Sampel</td><td>: {{ $sample->kode_sampel }}</td></tr>
        <tr><td>Nama Sampel</td><td>: {{ $sample->nama_sampel }}</td></tr>
        <tr><td>Pemohon</td><td>: {{ $sample->user->name }} ({{ $sample->user->instansi ?? '-' }})</td></tr>
        <tr><td>Tanggal Selesai</td><td>: {{ $sample->updated_at->format('d F Y') }}</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Parameter Uji</th>
                <th>Metode</th>
                <th>Hasil</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sample->parameters as $parameter)
                <tr>
                    <td>{{ $parameter->nama_parameter }}</td>
                    <td>{{ $parameter->metode_uji ?? '-' }}</td>
                    <td>{{ $parameter->pivot->hasil ?? '-' }}</td>
                    <td>{{ $parameter->pivot->satuan_hasil ?? $parameter->satuan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>Malang, {{ now()->format('d F Y') }}</p>
        <p>Disetujui oleh,</p>
        <br><br><br>
        <p><strong>{{ $sample->testResult->approver->name ?? '-' }}</strong></p>
        <p>Kepala Laboratorium</p>
    </div>
</body>
</html>
