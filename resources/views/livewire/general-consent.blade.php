<div>

    <flux:modal name="consentModal" class="space-y-4  w-full max-w-3xl">
        <flux:heading class="text-lg font-semibold">
            {{ $editId ? 'Edit' : 'Tambah' }} General Consent
        </flux:heading>

        <flux:input wire:model="nama_pasien" label="Pasien" />
        <div class="grid grid-cols-2 gap-2 items-center">
            <flux:input wire:model="tanggal" label="Tanggal" type="date" required />
            <flux:input wire:model="jam" label="Jam" type="time" required />
        </div>

        <div class="text-sm space-y-3">
            <!-- Judul -->
            <div class="grid grid-cols-[10px_1fr]">
                <div></div>
                <flux:switch wire:model="persetujuan_pasien" label="Persetujuan Pasien" />
            </div>

            <!-- a -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="informasi_ketentuan_pembayaran" label="Informasi Ketentuan Pembayaran" />
            </div>

            <!-- b -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="informasi_hak_kewajiban" label="Informasi tentang Hak dan Kewajiban Pasien" />
            </div>

            <!-- c -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="informasi_tata_tertib_rs" label="Informasi tentang Tata Tertib RS" />
            </div>

            <!-- d -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="kebutuhan_penerjemah_bahasa" label="Kebutuhan Penterjemah Bahasa" />
            </div>

            <!-- e -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="kebutuhan_rohaniawan" label="Kebutuhan Rohaniawan" />
            </div>
            
            <!-- f -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="kerahasiaan_informasi" label="Kerahasiaan Informasi" />
            </div>

            <!-- f.1 -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                    <div></div>
                    <flux:switch wire:model="pemeriksaan_ke_pihak_penjamin"
                        label="Hasil Pemeriksaan Penunjang dapat Diberikan kepada Pihak Penjamin" />
                </div>
            </div>

            <!-- f.2 -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                    <div></div>
                    <flux:switch wire:model="pemeriksaan_diakses_peserta_didik"
                        label="Hasil Pemeriksaan Penunjang dapat Diakses oleh Peserta Didik" />
                </div>
            </div>

            <!-- f.3 -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                    <div></div>
                    <flux:input wire:model="anggota_keluarga_dapat_akses"
                        label="Anggota Keluarga Lain yang dapat Diberikan Informasi Data-data Pasien" />
                </div>
            </div>

            <!-- f.4 -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                    <div></div>
                    <flux:switch wire:model="akses_fasyankes_rujukan" label="Fasyankes tertentu dalam rangka rujukan" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 items-center">
            <flux:input wire:model="penanggung_jawab" label="Penanggung Jawab" />
            <flux:input wire:model="petugas_pemberi_penjelasan" label="Petugas yang wPemberi Penjelasan" />
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:button wire:click="save" variant="primary">Simpan</flux:button>
        </div>
    </flux:modal>

    {{-- Modal Konfirmasi Hapus --}}
    <flux:modal name="delete-consent" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus Data?</flux:heading>
                <flux:text>Data yang dihapus tidak dapat dikembalikan.</flux:text>
            </div>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="danger">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
