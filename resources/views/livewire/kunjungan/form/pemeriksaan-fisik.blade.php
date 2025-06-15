<div class="space-y-3">
    <div class="grid grid-cols-3 gap-2">
        <div class="col-span-1">
            <flux:input type="file" wire:model="gambar_anatomitubuh" label="Upload Gambar Anatomi Tubuh" accept="image/*" />
        </div>
        <div class="relative col-span-2">
            <flux:autocomplete wire:model.live.debounce.500ms="tingkat_kesadaran" label="Tingkat Kesadaran" placeholder="Pilih tingkat kesadaran..." icon="magnifying-glass" />
            @if ($tingkatKesadaranOptions)
                <div class="absolute bg-white border rounded w-full max-h-40 overflow-auto shadow z-10">
                    @foreach ($tingkatKesadaranOptions as $tingkatKesadaran)
                        <div class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer" wire:click="selectTingkatKesadaran({{ $tingkatKesadaran['id'] }}, '{{ addslashes($tingkatKesadaran['keterangan']) }}')">
                            {{ $tingkatKesadaran['keterangan'] }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="form.denyut_jantung" label="Denyut Jantung" />
        <flux:input wire:model="form.pernapasan" label="Pernapasan" />
    </div>
    <div class="grid grid-cols-3 gap-2">
        <flux:input wire:model="form.sistole" label="Sistole" type="number" />
        <flux:input wire:model="form.diastole" label="Diastole" type="number" />
        <flux:input wire:model="form.suhu_tubuh" label="Suhu Tubuh" type="number" />
    </div>

    <div class="grid grid-cols-3 gap-3">
        @foreach (['kepala', 'mata', 'telinga', 'hidung', 'rambut', 'bibir', 'gigi_geligi', 'lidah', 'langit_langit', 'leher', 'tenggorokan', 'tonsil', 'dada', 'payudara', 'punggung', 'perut', 'genital', 'anus', 'lengan_atas', 'lengan_bawah', 'kuku_tangan', 'persendian_tangan', 'tungkai_atas', 'tungkai_bawah', 'jari_kaki', 'kuku_kaki', 'persendian_kaki'] as $field)
            <flux:textarea wire:model="form.{{ $field }}" label="{{ ucwords(str_replace('_', ' ', $field)) }}" />
        @endforeach
    </div>

</div>
