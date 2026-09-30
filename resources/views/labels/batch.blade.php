<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; margin: 0; padding: 10mm; }
        .grid { display: table; width: 100%; border-collapse: collapse; }
        .row { display: table-row; }
        .cell { display: table-cell; width: 50%; padding: 4mm; vertical-align: top; }
        .label {
            border: 1.5px solid #0f172a;
            border-radius: 4px;
            padding: 8px 10px;
        }
        .top-row { display: table; width: 100%; }
        .qr-cell { display: table-cell; width: 55px; vertical-align: top; }
        .qr-cell img { width: 50px; height: 50px; }
        .info-cell { display: table-cell; vertical-align: top; padding-left: 8px; }
        .kode { font-family: 'Courier New', monospace; font-size: 13px; font-weight: bold; }
        .nama { font-size: 10px; font-weight: bold; margin-top: 2px; }
        .meta { font-size: 8px; color: #444; margin-top: 3px; line-height: 1.4; }
        .divider { border-top: 1px dashed #999; margin: 5px 0; }
        .params { font-size: 7.5px; color: #333; }
    </style>
</head>
<body>
    <div class="grid">
        @foreach ($labels->chunk(2) as $pair)
            <div class="row">
                @foreach ($pair as $item)
                    @php $sample = $item['sample']; @endphp
                    <div class="cell">
                        <div class="label">
                            <div class="top-row">
                                <div class="qr-cell"><img src="{{ $item['qrDataUri'] }}" alt="QR"></div>
                                <div class="info-cell">
                                    <div class="kode">{{ $sample->kode_sampel }}</div>
                                    <div class="nama">{{ \Illuminate\Support\Str::limit($sample->nama_sampel, 24) }}</div>
                                    <div class="meta">
                                        {{ \Illuminate\Support\Str::limit($sample->user->name, 20) }}<br>
                                        {{ $sample->created_at->format('d-m-Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="params">
                                {{ $sample->parameters->count() }} parameter: {{ $sample->parameters->pluck('nama_parameter')->map(fn ($p) => \Illuminate\Support\Str::limit($p, 14))->join(', ') }}
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($pair->count() < 2)
                    <div class="cell"></div>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>
