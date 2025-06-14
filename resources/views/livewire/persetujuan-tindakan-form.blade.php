<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">

        <flux:input wire:model="state.nama_petugas_mendampingi" label="Petugas Mendampingi" />
        <flux:input wire:model="state.nama_keluarga_pasien" label="Keluarga Pasien" />
        <flux:input wire:model="state.tindakan_dilakukan" label="Tindakan Dilakukan" />
        <flux:input wire:model="state.konsekuensi_tindakan" label="Konsekuensi Tindakan" />
        <flux:input wire:model="state.tanggal_tindakan" type="date" label="Tanggal Tindakan" />
        <flux:input wire:model="state.jam_tindakan" type="time" label="Jam Tindakan" />
        <flux:input wire:model="state.ttd_dokter" label="TTD Dokter" />
        <flux:input wire:model="state.ttd_pasien_keluarga" label="TTD Pasien/Keluarga" />
        <flux:input wire:model="state.saksi1" label="Saksi 1" />
        <flux:input wire:model="state.saksi2" label="Saksi 2" />

        <flux:select wire:model="state.persetujuan_penolakan" label="Persetujuan / Penolakan" required>
            <option value="">-- Pilih --</option>
            <option value="1">Setuju</option>
            <option value="0">Tolak</option>
        </flux:select>
        @error('state.persetujuan_penolakan')
            <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror

        <div class="flex justify-end gap-2 mt-3">
            <flux:button variant="ghost">Batal</flux:button>

            <flux:button wire:click="test" variant="primary">
                Simpan
            </flux:button>
        </div>

    </flux:card>
</div>
