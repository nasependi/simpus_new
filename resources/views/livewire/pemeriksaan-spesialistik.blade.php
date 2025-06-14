<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <flux:select wire:model="kunjungan_id" label="Pilih Kunjungan">
            <option value="">-- Pilih --</option>
            @foreach ($kunjungans as $k)
                <option value="{{ $k->id }}">Kunjungan #{{ $k->nama }}</option>
            @endforeach
        </flux:select>

        <flux:input wire:model="nama_obat" label="Nama Obat" required />
        <flux:input wire:model="dosis" label="Dosis" required />
        <flux:input wire:model="waktu_penggunaan" label="Waktu Penggunaan" required />
        <flux:input wire:model="rencana_rawat" label="Rencana Rawat" required />
        <flux:input wire:model="intruksi_medik" label="Instruksi Medik" required />

        <div class="flex justify-end gap-2 mt-3">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:button wire:click="save" variant="primary">Simpan</flux:button>
        </div>
    </flux:card>
</div>
