<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Penjualan Obat</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari no faktur..." icon="magnifying-glass" size="md" />
                <flux:button wire:click="create" variant="primary" icon="plus-circle">Tambah</flux:button>
            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column wire:click="sortBy('no_faktur')" class="cursor-pointer">No Faktur</flux:table.column>
                <flux:table.column wire:click="sortBy('jumlah_beli')" class="cursor-pointer">Jumlah Beli</flux:table.column>
                <flux:table.column wire:click="sortBy('harga_beli_bersih')" class="cursor-pointer">Harga Bersih</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                <flux:table.row>
                    <flux:table.cell>{{ $item->no_faktur }}</flux:table.cell>
                    <flux:table.cell>{{ $item->jumlah_beli }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($item->harga_beli_bersih, 0, ',', '.') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" class="mr-2" />
                        <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                    </flux:table.cell>
                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="penjualanModal" class="space-y-4 md:w-[50rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Penjualan Obat
            </flux:heading>

            <flux:input wire:model="no_faktur" label="No Faktur" required />
            <flux:input wire:model="jumlah_beli" label="Jumlah Beli" type="number" required />
            <flux:input wire:model="ppn" label="PPN" type="number" step="0.01" />
            <flux:input wire:model="pph" label="PPH" type="number" step="0.01" />
            <flux:input wire:model="diskon" label="Diskon" type="number" step="0.01" />
            <flux:input wire:model="harga_beli_kotor" label="Harga Beli Kotor" type="number" required />
            <flux:input wire:model="harga_beli_bersih" label="Harga Beli Bersih" type="number" required />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-penjualan" class="min-w-[22rem]">
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