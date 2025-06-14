<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">

        {{-- Modal Tambah/Edit --}}


        <flux:input type="file" wire:model="gambar_anatomitubuh" label="Upload Gambar Anatomi Tubuh" accept="image/*" />
        <flux:input wire:model="form.denyut_jantung" label="Denyut Jantung" />

        <div class="relative">
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

        <flux:input wire:model="form.pernapasan" label="Pernapasan" />
        <flux:input wire:model="form.sistole" label="Sistole" type="number" />
        <flux:input wire:model="form.diastole" label="Diastole" type="number" />
        <flux:input wire:model="form.suhu_tubuh" label="Suhu Tubuh" type="number" />

        @foreach (['kepala', 'mata', 'telinga', 'hidung', 'rambut', 'bibir', 'gigi_geligi', 'lidah', 'langit_langit', 'leher', 'tenggorokan', 'tonsil', 'dada', 'payudara', 'punggung', 'perut', 'genital', 'anus', 'lengan_atas', 'lengan_bawah', 'kuku_tangan', 'persendian_tangan', 'tungkai_atas', 'tungkai_bawah', 'jari_kaki', 'kuku_kaki', 'persendian_kaki'] as $field)
            <flux:textarea wire:model="form.{{ $field }}" label="{{ ucwords(str_replace('_', ' ', $field)) }}" />
        @endforeach

        <div class="flex justify-end gap-2 mt-3">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:button wire:click="save" variant="primary">Simpan</flux:button>
        </div>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-pemeriksaan" class="min-w-[22rem]">
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
    </flux:card>
</div>
