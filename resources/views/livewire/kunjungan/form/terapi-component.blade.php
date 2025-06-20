<div class="space-y-4">
    <div class="grid grid-cols-2 gap-2">
        <flux:select wire:model="state.obat_id" label="Obat Resep">
            <option value="">-- Pilih Obat --</option>
            @foreach ($obatResepList as $obat)
            <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
            @endforeach
        </flux:select>

        <flux:input wire:model="state.nama_tindakan" label="Nama Tindakan" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="state.petugas" label="Petugas" />
        <flux:input type="date" wire:model="state.tanggal_pelaksanaan_tindakan" label="Tanggal Pelaksanaan" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="time" wire:model="state.jam_mulai_tindakan" label="Jam Mulai" />
        <flux:input type="time" wire:model="state.jam_selesai_tindakan" label="Jam Selesai" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:textarea wire:model="state.alat_medis" label="Alat Medis" />
        <flux:textarea wire:model="state.bmhp" label="BMHP" />
    </div>
</div>