<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 8px 10px;
            width: 80mm;
            box-sizing: border-box;
        }
        .label {
            border: 1.5px solid #0f172a;
            border-radius: 4px;
            padding: 8px 10px;
        }
        .top-row { display: table; width: 100%; }
        .qr-cell { display: table-cell; width: 60px; vertical-align: top; }
        .qr-cell img { width: 55px; height: 55px; }
        .info-cell { display: table-cell; vertical-align: top; padding-left: 8px; }
        .kode {
            font-family: 'Courier New', monospace;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .nama { font-size: 11px; font-weight: bold; margin-top: 2px; }
        .meta { font-size: 8px; color: #444; margin-top: 4px; line-height: 1.4; }
        .divider { border-top: 1px dashed #999; margin: 6px 0; }
        .footer { font-size: 7px; color: #666; text-align: center; }
        .params { font-size: 8px; color: #333; }
    </style>
</head>
<body>
    <div class="label">
        <div class="top-row">
            <div class="qr-cell">
                <img src="{{ $qrDataUri }}" alt="QR">
            </div>
            <div class="info-cell">
                <div class="kode">{{ $sample->kode_sampel }}</div>
                <div class="nama">{{ \Illuminate\Support\Str::limit($sample->nama_sampel, 28) }}</div>
                <div class="meta">
                    Pemohon: {{ \Illuminate\Support\Str::limit($sample->user->name, 22) }}<br>
                    Tanggal: {{ $sample->created_at->format('d-m-Y H:i') }}
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="params">
            Parameter ({{ $sample->parameters->count() }}):
            {{ $sample->parameters->pluck('nama_parameter')->map(fn ($p) => \Illuminate\Support\Str::limit($p, 18))->join(', ') }}
        </div>

        <div class="divider"></div>

        <div class="footer">Laboratorium Kimia Polinema — Scan QR untuk lihat detail</div>
    </div>
</body>
</html>
