
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
            font-size: 8px;
            margin: 0;
            padding: 3px;
        }

        .label {
            border: 2px solid #000;
            padding: 3px;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 1px;
        }

        .subheader {
            text-align: center;
            font-size: 5px;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 2px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 6px;
            margin-bottom: 2px;
        }

        .patient-name {
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            margin: 3px 0;
        }

        .medicine-instruction {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin: 3px 0;
            line-height: 1.1;
        }

        .additional-info {
            text-align: center;
            font-size: 7px;
            margin-top: 2px;
            line-height: 1.2;
        }
    </style>
</head>

<body onload="window.print()">

    @foreach($kunjungan->obatResep as $obat)
    <div class="label">
        <!-- Header: Nama Puskesmas -->
        <div class="header">{{ $kunjungan->puskesmas->nama ?? 'UPT PUSKESMAS SELAAWI' }}</div>
        
        <!-- Subheader: Alamat, Apoteker, SIPA -->
        <div class="subheader">
            {{ $kunjungan->puskesmas->alamat ?? 'Jl. Raya Selaawi No. 49, Desa Selaawi, Kecamatan Selaawi, Kabupaten Garut, Jawa Barat' }}<br>
            Apoteker : {{ $kunjungan->dokter->nama ?? 'Budi, S. Farm, Apt.' }}<br>
            SIPA : {{ $kunjungan->puskesmas->sipa ?? '120/PER/XII/2017' }}
        </div>

        <div class="divider"></div>

        <!-- Info Resep dan Tanggal -->
        <div class="info-row">
            <span>No Resep : {{ $kunjungan->id ?? '-' }}</span>
            <span>tanggal : {{ $kunjungan->created_at->format('d M Y') ?? date('d M Y') }}</span>
        </div>

        <!-- Nama Pasien (Prominent) -->
        <div class="patient-name">{{ $kunjungan->pasien->nama_lengkap ?? 'Nama Pasien' }}</div>

        <!-- Instruksi Obat (Bold, Large, Prominent) -->
        <div class="medicine-instruction">
            {{ $obat->catatan_resep ?? 'Sehari 3 x 1 sendok teh' }}
        </div>

        <!-- Info Tambahan -->
        <div class="additional-info">
            {{ $obat->aturan_tambahan ?? 'Kocok dahulu, dihabiskan, antibiotik' }}
            {{ $obat->catatan_tambahan ?? 'Semoga lekas sembuh' }}<br>
        </div>
    </div>

    <!-- Page break jika masih ada obat -->
    @if(!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif
    @endforeach

</body>

</html>