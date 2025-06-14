<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>General Consent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 0;
        }
        .info {
            margin: 20px 0;
        }
        .label {
            font-weight: bold;
            width: 200px;
            display: inline-block;
        }
        .box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h1>General Consent</h1>
    <h2>Formulir Persetujuan Umum</h2>

    <div class="box">
        <div class="info"><span class="label">Nama Pasien:</span> {{ $consent->kunjungan->pasien->nama_lengkap ?? '-' }}</div>
        <div class="info"><span class="label">Tanggal:</span> {{ $consent->tanggal }}</div>
        <div class="info"><span class="label">Jam:</span> {{ $consent->jam }}</div>
        <div class="info"><span class="label">Penanggung Jawab:</span> {{ $consent->penanggung_jawab }}</div>
        <div class="info"><span class="label">Petugas Pemberi Penjelasan:</span> {{ $consent->petugas_pemberi_penjelasan }}</div>
    </div>

    <p style="margin-top: 40px;">Dengan ini menyatakan telah memahami informasi yang diberikan dan menyetujui untuk mendapatkan pelayanan medis dari Rumah Sakit sesuai prosedur yang berlaku.</p>

</body>
</html>
