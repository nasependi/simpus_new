<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Resep Obat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 10px;
        }

        .resep-container {
            width: 100%;
            margin: auto;
            padding: 10px;
            border: 1px solid #000;
            box-sizing: border-box;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 10px;
        }

        .header p,
        .footer p {
            margin: 2px 0;
        }

        .alamat {
            margin-bottom: 10px;
            text-align: left;
        }

        .tanggal {
            text-align: right;
            margin-bottom: 10px;
        }

        .resep {
            margin-bottom: 10px;
        }

        .resep p {
            margin: 2px 0;
        }

        .signature {
            margin: 15px 0;
            text-align: left;
        }

        .patient-info {
            margin-top: 5px;
        }
    </style>
</head>

<body onload="window.print()" onafterprint="window.close()">

    @php
    // Fungsi konversi angka ke Romawi
    function toRoman($num) {
    $n = intval($num);
    $map = [
    'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
    'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
    'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4,
    'I' => 1
    ];
    $result = '';
    foreach ($map as $roman => $value) {
    while ($n >= $value) {
    $result .= $roman;
    $n -= $value;
    }
    }
    return $result;
    }
    @endphp

    <div class="resep-container">

        <!-- Header Dokter -->
        <div class="header">
            <p>dr. </p>
            <p>SIP. 11</p>
        </div>

        <!-- Alamat Pasien -->
        <div class="alamat">
            <p>Alamat rumah/praktek:</p>
            <p>{{ $kunjungan->pasien->alamat_lengkap }}</p>
            <hr>
        </div>

        <!-- Tanggal Resep -->
        <div class="tanggal">
            <p>{{ $kunjungan->created_at->translatedFormat('l, d F Y') }}</p>
        </div>

        <!-- Daftar Obat -->
        <div class="resep">
            @foreach($kunjungan->obatResep as $obat)
            <p>
                R/ {{ $obat->obat->nama_obat ?? '-' }}
                {{ isset($obat->jumlah_obat) ? toRoman($obat->jumlah_obat) : '-' }}
            </p>
            @if($obat->aturan_tambahan)
            <p>{{ $obat->aturan_tambahan }} ({{ $obat->sediaan ?? '' }})</p>
            @endif
            @if($obat->catatan_resep)
            <p>{{ $obat->catatan_resep }}</p>
            @endif
            @endforeach
        </div>

        <!-- Tanda Tangan -->
        <div class="signature">
            <p>________________________</p>
        </div>

        <!-- Info Pasien -->
        <div class="patient-info">
            <p>Pro: {{ $kunjungan->pasien->nama_lengkap }} ({{ $kunjungan->pasien->bb_pasien }} kg)</p>
            <p>Umur: {{ $kunjungan->umur_tahun ?? '' }} tahun</p>
        </div>

    </div>

</body>

</html>