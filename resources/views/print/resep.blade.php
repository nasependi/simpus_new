<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Resep Obat</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .resep-container {
            width: 100%;
            max-width: 800px;
            margin: 15px;
            padding: 15px;
        }

        .header-section {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .logo-area {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #4A90E2;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .logo-text {
            color: white;
            font-weight: bold;
            font-size: 24px;
        }

        .header-info {
            flex: 1;
        }

        .header-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 3px;
        }

        .header-details {
            font-size: 10px;
            line-height: 1.5;
        }

        .resep-title {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .info-item {
            margin-bottom: 3px;
        }

        .info-label {
            display: inline-block;
            width: 100px;
        }

        .resep {
            margin-bottom: 15px;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }

        .resep p {
            margin: 5px 0;
            line-height: 1.6;
        }

        .signature {
            margin: 30px 0 20px 0;
            text-align: left;
        }

        .patient-info {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }

        .patient-info p {
            margin: 5px 0;
        }

        .footer-section {
            text-align: right;
            font-size: 10px;
        }

        .signature-area {
            margin-top: 60px;
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

        <!-- Header Section with Logo -->
        <div class="header-section">
            <div class="logo-area">
                <div class="logo-text">R</div>
            </div>
            <div class="header-info">
                <div class="header-title">{{ $kunjungan->puskesmas->nama ?? 'UPT PUSKESMAS SELAAWI' }}</div>
                <div class="header-details">
                    Jl. Raya Selaawi No. 49, Desa Selaawi, Kecamatan Selaawi, Kabupaten Garut, Jawa Barat<br>
                    Telp. (0262) 431540 <br>
                    Email : puskesmas.selaawi@gmail.com 
                </div>
            </div>
        </div>

        <!-- Patient and Prescription Info Grid -->
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <span class="info-label">No. Resep</span>: {{ $kunjungan->id ?? '-' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Nama. Pasien</span>: {{ $kunjungan->pasien->nama_lengkap ?? '-' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Umur</span>: {{ $kunjungan->umur_tahun ?? $kunjungan->pasien->umur ?? '-' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Alamat</span>: {{ $kunjungan->pasien->alamat_lengkap ?? '-' }}
                </div>
                <div class="info-item">
                    <span class="info-label">No. Telp</span>: {{ $kunjungan->pasien->no_hp ?? '-' }}
                </div>
            </div>
            <div>
                <div class="info-item">
                    <span class="info-label">Kasir</span>: {{ $kunjungan->kasir ?? 'afif' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal</span>: {{ $kunjungan->created_at->format('d M Y H:i:s') ?? date('d M Y H:i:s') }}
                </div>
                <div class="info-item">
                    <span class="info-label">Apoteker</span>: {{ $kunjungan->apoteker ?? 'Afif Firmansyah, S.Farm., Apt' }}
                </div>
                <div class="info-item">
                    <span class="info-label">No.SIK/No.SIPA</span>: {{ $kunjungan->puskesmas->sipa ?? '441.82/1147-DPMPTSP/OL/2021' }}
                </div>
                <div class="info-item">
                    <span class="info-label">No.STRA</span>: {{ $kunjungan->puskesmas->stra ?? '-' }}
                </div>
            </div>
        </div>

        <!-- Daftar Obat (Format Resep Tradisional) -->
        <div class="resep">
            <div class="footer-section">
            <div>{{ $kunjungan->puskesmas->kota ?? 'Kab. Garut' }}, {{ $kunjungan->created_at->format('d M Y') ?? date('d M Y') }}</div>
        </div>
            @foreach($kunjungan->obatResep as $obat)
            <p>
                <strong>R/</strong> {{ $obat->nama_obat ?? $obat->obat->nama_obat ?? '-' }}
                {{ isset($obat->jumlah_obat) ? toRoman($obat->jumlah_obat) : '-' }}
            </p>
            @if($obat->aturan_tambahan || $obat->sediaan)
            <p style="margin-left: 20px;">
                {{ $obat->aturan_tambahan ?? '' }}
                @if($obat->sediaan)
                ({{ $obat->sediaan }})
                @endif
            </p>
            @endif
            @if($obat->catatan_resep)
            <p style="margin-left: 20px; font-style: italic;">{{ $obat->catatan_resep }}</p>
            @endif
            @endforeach
        </div>

        <!-- Tanda Tangan -->
        <!-- <div class="signature">
            <p>_________________________________</p>
        </div> -->

        <!-- Info Pasien -->
        <!-- <div class="patient-info">
            <p><strong>Pro:</strong> {{ $kunjungan->pasien->nama_lengkap }} 
                @if($kunjungan->pasien->bb_pasien)
                ({{ $kunjungan->pasien->bb_pasien }} kg)
                @endif
            </p>
            <p><strong>Umur:</strong> {{ $kunjungan->umur_tahun ?? $kunjungan->pasien->umur ?? '-' }} tahun</p>
        </div> -->

        <!-- Footer -->
        

    </div>

</body>

</html>