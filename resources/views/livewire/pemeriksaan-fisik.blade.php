<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">

        {{-- Modal Tambah/Edit --}}


        <flux:input wire:model="gambar_anatomitubuh" label="Gambar Anatomi Tubuh" />
        <flux:input wire:model="denyut_jantung" label="Denyut Jantung" />

        <flux:autocomplete wire:model="tingkatkesadaran_id" label="Tingkat Kesadaran" :items="$daftarKesadaran"
            placeholder="Pilih tingkat kesadaran..." icon="magnifying-glass" value-field="id" text-field="keterangan" />

        <flux:input wire:model="pernapasan" label="Pernapasan" />
        <flux:input wire:model="sistole" label="Sistole" type="number" />
        <flux:input wire:model="diastole" label="Diastole" type="number" />
        <flux:input wire:model="suhu_tubuh" label="Suhu Tubuh" type="number" />

        @foreach (['kepala', 'mata', 'telinga', 'hidung', 'rambut', 'bibir', 'gigi_geligi', 'lidah', 'langit_langit', 'leher', 'tenggorokan', 'tonsil', 'dada', 'payudara', 'punggung', 'perut', 'genital', 'anus', 'lengan_atas', 'lengan_bawah', 'kuku_tangan', 'persendian_tangan', 'tungkai_atas', 'tungkai_bawah', 'jari_kaki', 'kuku_kaki', 'persendian_kaki'] as $field)
            <flux:textarea wire:model="{{ $field }}" label="{{ ucwords(str_replace('_', ' ', $field)) }}" />
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
