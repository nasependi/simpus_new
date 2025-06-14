<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
            <flux:select wire:model="kunjungan_id" label="Pilih Kunjungan">
                <option value="">-- Pilih --</option>
                @foreach ($kunjungans as $k)
                    <option value="{{ $k->id }}">Kunjungan #{{ $k->nama }}</option>
                @endforeach
            </flux:select>

            <flux:input wire:model="keluhan_utama" label="Keluhan Utama" required />
            <flux:input wire:model="riwayat_penyakit" label="Riwayat Penyakit" required />
            <flux:input wire:model="riwayat_alergi" label="Riwayat Alergi" required />
            <flux:input wire:model="riwayat_pengobatan" label="Riwayat Pengobatan" required />

            <div class="flex justify-end gap-2 mt-3">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
    </flux:card>
</div>
