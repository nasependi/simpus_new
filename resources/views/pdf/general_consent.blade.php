<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>General Consent</title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin: 10px 0 5px 0;
            font-weight: bold;
        }
        h2 {
            text-align: center;
            font-size: 12px;
            font-weight: normal;
            margin: 0 0 20px 0;
        }
        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 15px;
        }
        .info-row {
            margin: 5px 0;
        }
        .info-label {
            display: inline-block;
            width: 150px;
            font-weight: normal;
        }
        .section-title {
            font-weight: bold;
            margin: 15px 0 10px 0;
        }
        .consent-list {
            margin: 10px 0;
        }
        .consent-item {
            margin: 8px 0;
            padding-left: 5px;
        }
        .checkbox {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #000;
            text-align: center;
            line-height: 16px;
            margin-right: 8px;
            vertical-align: middle;
            font-size: 14px;
            font-weight: bold;
        }
        .consent-text {
            display: inline;
            text-align: justify;
            vertical-align: middle;
        }
        .footer-text {
            margin: 20px 0;
            text-align: justify;
            line-height: 1.6;
        }
        .signature-section {
            margin-top: 40px;
            width: 100%;
        }
        .signature-row {
            width: 100%;
        }
        .signature-box {
            display: inline-block;
            width: 48%;
            text-align: center;
            vertical-align: top;
        }
        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            width: 180px;
            margin-left: auto;
            margin-right: auto;
        }
        .signature-name {
            margin-top: 5px;
            font-weight: normal;
        }
    </style>
</head>
<body>

    <h1>GENERAL CONSENT</h1>
    <h2>Formulir Persetujuan Umum</h2>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Nama Pasien:</span>
            <span>{{ $consent->kunjungan->pasien->nama_lengkap ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">No. Rekam Medis:</span>
            <span>{{ $consent->kunjungan->pasien->no_rekamedis ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal:</span>
            <span>{{ \Carbon\Carbon::parse($consent->tanggal)->format('d-m-Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jam:</span>
            <span>{{ $consent->jam }}</span>
        </div>
    </div>

    <div class="section-title">Saya yang bertanda tangan di bawah ini:</div>
    <div class="info-row">
        <span class="info-label">Nama Penanggung Jawab:</span>
        <span>{{ $consent->penanggung_jawab }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Petugas Pemberi Penjelasan:</span>
        <span>{{ $consent->petugas_pemberi_penjelasan }}</span>
    </div>

    <div class="section-title">Dengan ini menyatakan persetujuan untuk:</div>

    <div class="consent-list">

        <div class="consent-item">
            <span class="checkbox">@if($consent->persetujuan_pasien) V @else X @endif</span>
            <span class="consent-text"><strong>Persetujuan Pasien</strong> - Saya @if(!$consent->persetujuan_pasien) tidak @endif memberikan persetujuan untuk mendapatkan pelayanan medis</span>
        </div>

        <div class="consent-item">
            <span class="checkbox">@if($consent->informasi_ketentuan_pembayaran) V @else X @endif</span>
            <span class="consent-text"><strong>Informasi Ketentuan Pembayaran</strong> - Saya @if(!$consent->informasi_ketentuan_pembayaran) belum @endif  mendapatkan informasi tentang ketentuan pembayaran</span>
        </div>

        <div class="consent-item">
            <span class="checkbox">@if($consent->informasi_hak_kewajiban) V @else X @endif</span>
            <span class="consent-text"><strong>Informasi Hak dan Kewajiban</strong> - Saya @if(!$consent->informasi_hak_kewajiban) belum @endif  mendapatkan informasi tentang hak dan kewajiban pasien</span>
        </div>

        <div class="consent-item">
            <span class="checkbox">@if($consent->informasi_tata_tertib_rs) V @else X @endif</span>
            <span class="consent-text"><strong>Informasi Tata Tertib RS</strong> - Saya @if(!$consent->informasi_tata_tertib_rs) belum @endif mendapatkan informasi tentang tata tertib rumah sakit</span>
        </div>

        <div class="consent-item">
            <span class="checkbox">@if($consent->kebutuhan_penerjemah_bahasa) V @else X @endif</span>
            <span class="consent-text"><strong>Kebutuhan Penerjemah Bahasa</strong> - Saya @if(!$consent->kebutuhan_penerjemah_bahasa) tidak @endif membutuhkan penerjemah bahasa</span>
        </div>

        <div class="consent-item">
            <span class="checkbox">@if($consent->kebutuhan_rohaniawan) V @else X @endif</span>
            <span class="consent-text"><strong>Kebutuhan Rohaniawan</strong> - Saya @if(!$consent->kebutuhan_rohaniawan) tidak @endif membutuhkan bantuan rohaniawan</span>
        </div>

        <div class="consent-item">
            <span class="checkbox">@if($consent->kerahasiaan_informasi) V @else X @endif</span>
            <span class="consent-text"><strong>Kerahasiaan Informasi</strong> - Saya @if(!$consent->kerahasiaan_informasi) tidak @endif memahami tentang kerahasiaan informasi medis</span>
        </div>

        <div class="consent-item">
            <span class="checkbox">@if($consent->pemeriksaan_ke_pihak_penjamin) V @else X @endif</span>
            <span class="consent-text"><strong>Pemeriksaan ke Pihak Penjamin</strong> - Saya @if(!$consent->pemeriksaan_ke_pihak_penjamin) tidak @endif menyetujui informasi medis diberikan kepada pihak penjamin</span>
        </div>

        <div class="consent-item">
            <span class="checkbox">@if($consent->pemeriksaan_diakses_peserta_didik) V @else X @endif</span>
            <span class="consent-text"><strong>Pemeriksaan Diakses Peserta Didik</strong> - Saya @if(!$consent->pemeriksaan_diakses_peserta_didik) tidak @endif menyetujui pemeriksaan dapat diakses oleh peserta didik untuk kepentingan pendidikan</span>
        </div>

        @if($consent->anggota_keluarga_dapat_akses)
        <div class="consent-item">
            <span class="checkbox">V</span>
            <span class="consent-text"><strong>Anggota Keluarga yang Dapat Mengakses Informasi:</strong> {{ $consent->anggota_keluarga_dapat_akses }}</span>
        </div>
        @endif

        <div class="consent-item">
            <span class="checkbox">@if($consent->akses_fasyankes_rujukan) V @else X @endif</span>
            <span class="consent-text"><strong>Akses Fasyankes Rujukan</strong> - Saya @if(!$consent->akses_fasyankes_rujukan) tidak @endif menyetujui informasi medis dapat diakses oleh fasilitas kesehatan rujukan</span>
        </div>
    </div>

    <div class="footer-text">
        Dengan menandatangani formulir ini, saya menyatakan bahwa saya telah membaca, memahami, dan menyetujui 
        seluruh isi dari formulir persetujuan umum ini. Saya memberikan persetujuan untuk mendapatkan pelayanan 
        medis dari Rumah Sakit sesuai dengan prosedur yang berlaku.
    </div>

    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-box">
                <div><strong>Penanggung Jawab</strong></div>
                @if($consent->ttd_penanggung_jawab)
                    <div style="margin-top: 10px;">
                        <img src="{{ $consent->ttd_penanggung_jawab }}" style="max-width: 200px; height: auto; border: 1px solid #ccc;" />
                    </div>
                @else
                    <div class="signature-line"></div>
                @endif
                <div class="signature-name">{{ $consent->penanggung_jawab }}</div>
            </div>
            <div class="signature-box">
                <div><strong>Petugas Pemberi Penjelasan</strong></div>
                @if($consent->ttd_petugas)
                    <div style="margin-top: 10px;">
                        <img src="{{ $consent->ttd_petugas }}" style="max-width: 200px; height: auto; border: 1px solid #ccc;" />
                    </div>
                @else
                    <div class="signature-line"></div>
                @endif
                <div class="signature-name">{{ $consent->petugas_pemberi_penjelasan }}</div>
            </div>
        </div>
    </div>

</body>
</html>
