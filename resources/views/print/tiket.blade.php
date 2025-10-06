<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Cetak Etiket Obat</title>
    <style>
        @page {
            size: 60mm 40mm;
            margin: 0;
        }

        body {
            font-family: "Arial", sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 5px;
        }

        .label {
            border: 1px solid #000;
            padding: 4px;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .row {
            margin-bottom: 2px;
        }

        .small {
            font-size: 10px;
        }

        hr {
            margin: 3px 0;
        }
    </style>
</head>

<body onload="window.print()">

    @foreach($kunjungan->obatResep as $obat)
    <div class="label">
        <!-- Nama Puskesmas -->
        <div class="center bold">{{ $kunjungan->puskesmas->nama ?? 'UPT PUSKESMAS' }}</div>

        <!-- Nama Pasien -->
        <div class="center small">{{ strtoupper($kunjungan->pasien->nama_lengkap) }}</div>

        <!-- Umur Pasien -->
        <div class="center small">{{ $kunjungan->pasien->umur ?? '-' }}</div>

        <hr>

        <!-- Aturan Tambahan + Catatan Tambahan -->
        <div class="center small">
            {{ $obat->aturan_tambahan ?? '' }} {{ $obat->catatan_tambahan ?? '' }}
        </div>

        <!-- Nama Obat + Sediaan -->
        <div class="center bold">
            {{ strtoupper($obat->nama_obat) }} {{ $obat->sediaan ?? '' }}
        </div>

        <!-- Jumlah Obat -->
        <div class="row">JUMLAH OBAT: {{ $obat->jumlah_obat }}</div>

        <!-- Exp Date Obat -->
        <div class="row">EXP DATE: {{ $obat->exp_date ?? '-' }}</div>
    </div>

    <!-- Page break jika masih ada obat -->
    @if(!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif
    @endforeach

</body>

</html>