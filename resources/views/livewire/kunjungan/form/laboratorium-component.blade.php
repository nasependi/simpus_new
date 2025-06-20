<div class="space-y-4">
    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model.defer="form.nama_pemeriksaan" label="Nama Pemeriksaan" required />
        <flux:input wire:model.defer="form.nomor_pemeriksaan" label="Nomor Pemeriksaan" required />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggal_permintaan" label="Tanggal Permintaan" required />
        <flux:input type="time" wire:model.defer="form.jam_permintaan" label="Jam Permintaan" required />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model.defer="form.dokter_pengirim" label="Dokter Pengirim" required />
        <flux:input wire:model.defer="form.nomor_telepon_dokter" label="No. Telepon Dokter" required />
    </div>

    <div class="grid grid-cols-4 gap-2">
        <flux:input wire:model.defer="form.nama_fasilitas_pelayanan" label="Nama Fasilitas Pelayanan" required />
        <flux:input wire:model.defer="form.unit_pengirim" label="Unit Pengirim" required />
        <flux:select wire:model="form.prioritas_pemeriksaan" label="Prioritas Pemeriksaan">
            <flux:select.option value="1">CITO</flux:select.option>
            <flux:select.option value="0">NON CITO</flux:select.option>
        </flux:select>
        <flux:input wire:model.defer="form.diagnosis_masalah" label="Diagnosis/Masalah" required />
    </div>

    <flux:textarea wire:model.defer="form.catatan_permintaan" label="Catatan Permintaan" />

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model.defer="form.metode_pengiriman" label="Metode Pengiriman" required />
        <flux:select wire:model="form.asal_sumber_spesimen" label="Asal Sumber Spesimen Klinis">
            <flux:select.option>Darah</flux:select.option>
            <flux:select.option>Urin</flux:select.option>
            <flux:select.option>Feses</flux:select.option>
            <flux:select.option>Jaringan tubuh</flux:select.option>
        </flux:select>
    </div>

    <div class="grid grid-cols-4 gap-2">
        <flux:input wire:model.defer="form.lokasi_pengambilan_spesimen" label="Lokasi Pengambilan Spesimen" required />
        <flux:input wire:model.defer="form.jumlah_spesimen" label="Jumlah Spesimen" required />
        <flux:input wire:model.defer="form.volume_spesimen" label="Volume Spesimen" required />
        <flux:input wire:model.defer="form.metode_pengambilan_spesimen" label="Metode Pengambilan Spesimen" required />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggal_pengambilan_spesimen" label="Tanggal Pengambilan Spesimen" required />
        <flux:input type="time" wire:model.defer="form.jam_pengambilan_spesimen" label="Jam Pengambilan Spesimen" required />
    </div>

    <div class="grid grid-cols-3 gap-2">
        <flux:input wire:model.defer="form.kondisi_spesimen" label="Kondisi Spesimen" required />
        <flux:input type="date" wire:model.defer="form.tanggal_fiksasi_spesimen" label="Tanggal Fiksasi Spesimen" />
        <flux:input type="time" wire:model.defer="form.jam_fiksasi_spesimen" label="Jam Fiksasi Spesimen" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model.defer="form.cairan_fiksasi" label="Cairan Fiksasi" />
        <flux:input wire:model.defer="form.volume_cairan_fiksasi" label="Volume Cairan Fiksasi" />
    </div>

    <div class="grid grid-cols-4 gap-2">
        <flux:input wire:model.defer="form.petugas_mengambil_spesimen" label="Petugas Mengambil Spesimen" />
        <flux:input wire:model.defer="form.petugas_mengantarkan_spesimen" label="Petugas Mengantarkan Spesimen" />
        <flux:input wire:model.defer="form.petugas_menerima_spesimen" label="Petugas Menerima Spesimen" />
        <flux:input wire:model.defer="form.petugas_menganalisis_spesimen" label="Petugas Menganalisis Spesimen" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggal_pemeriksaan_spesimen" label="Tanggal Pemeriksaan Spesimen" />
        <flux:input type="time" wire:model.defer="form.jam_pemeriksaan_spesimen" label="Jam Pemeriksaan Spesimen" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model.defer="form.nilai_hasil_pemeriksaan" label="Nilai Hasil Pemeriksaan" />
        <flux:select wire:model="form.nilai_moral" label="Nilai Moral">
            <flux:select.option>Normal</flux:select.option>
            <flux:select.option>Tidak Normal</flux:select.option>
        </flux:select>
        <flux:input wire:model.defer="form.nilai_rujukan" label="Nilai Rujukan" />
        <flux:input wire:model.defer="form.nilai_kritis" label="Nilai Kritis" />
    </div>

    <flux:textarea wire:model.defer="form.interpretasi_hasil" label="Interpretasi Hasil" />

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model.defer="form.dokter_validasi" label="Dokter Validasi" />
        <flux:input wire:model.defer="form.dokter_interpretasi" label="Dokter Interpretasi" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggalpemeriksaan_keluar" label="Tanggal Pemeriksaan Keluar" />
        <flux:input type="time" wire:model.defer="form.jam_pemeriksaan_keluar" label="Jam Pemeriksaan Keluar" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggal_pemeriksaan_diterima" label="Tanggal Pemeriksaan Diterima" />
        <flux:input type="time" wire:model.defer="form.jam_pemeriksaan_diterima" label="Jam Pemeriksaan Diterima" />
    </div>

    <flux:input wire:model.defer="form.fasilitas_kesehatan_pemeriksaan" label="Fasilitas Kesehatan Pemeriksa" />

    <!-- <div class="pt-4">
        <flux:button wire:click="saveAll" class="w-full" variant="primary">
            Simpan
        </flux:button>
    </div> -->
</div>