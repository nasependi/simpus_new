<div class="space-y-4">
    <div class="grid grid-cols-2 gap-2">
        <flux:autocomplete
            wire:model.defer="state.nama_tindakan"
            label="Nama Tindakan"
            placeholder="Ketik atau pilih tindakan..."
            clearable>
            @foreach ($pemeriksaanTindakanList as $tindakan)
            <flux:autocomplete.item value="{{ $tindakan->nama }}">
                {{ $tindakan->nama }}
            </flux:autocomplete.item>
            @endforeach
        </flux:autocomplete>

        <flux:select wire:model.live="state.id_obat" variant="listbox" searchable placeholder="Pilih Obat" label="Pilih Obat">
            @foreach ($obatResepList as $obat)
            <flux:select.option value="{{ $obat->id }}">
                {{ $obat->nama_obat }}
            </flux:select.option>
            @endforeach
        </flux:select>

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