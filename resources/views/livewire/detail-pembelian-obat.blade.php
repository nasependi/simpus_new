<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Detail Pembelian Obat</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari no faktur..." icon="magnifying-glass" size="md" />
                <flux:button wire:click="create" variant="primary" icon="plus-circle">Tambah</flux:button>
            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column wire:click="sortBy('pembelian_id')" class="cursor-pointer">No Faktur</flux:table.column>
                <flux:table.column>Obat</flux:table.column>
                <flux:table.column wire:click="sortBy('kuantitas')" class="cursor-pointer">Kuantitas</flux:table.column>
                <flux:table.column wire:click="sortBy('harga_beli')" class="cursor-pointer">Harga Beli</flux:table.column>
                <flux:table.column wire:click="sortBy('jumlah')" class="cursor-pointer">Jumlah</flux:table.column>
                <flux:table.column wire:click="sortBy('kadaluarsa')" class="cursor-pointer">Kadaluarsa</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                <flux:table.row>
                    <flux:table.cell>{{ $item->pembelian->no_faktur ?? '-' }}</flux:table.cell>
                    <flux:table.cell>{{ $item->obat->nama_obat ?? '-' }}</flux:table.cell>
                    <flux:table.cell>{{ $item->kuantitas }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($item->harga_beli, 0, ',', '.') }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($item->jumlah, 0, ',', '.') }}</flux:table.cell>
                    <flux:table.cell>{{ \Carbon\Carbon::parse($item->kadaluarsa)->format('d-m-Y') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" class="mr-2" />
                        <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                    </flux:table.cell>
                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="detailPembelianModal" class="space-y-4 md:w-[50rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Detail Pembelian
            </flux:heading>

            <flux:select wire:model="pembelian_id" label="No Faktur" placeholder="Pilih Pembelian" required>
                @foreach($pembelianList as $id => $faktur)
                <option value="{{ $id }}">{{ $faktur }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model="obat_id" label="Obat" placeholder="Pilih Obat" required>
                @foreach($obatList as $id => $nama)
                <option value="{{ $id }}">{{ $nama }}</option>
                @endforeach
            </flux:select>

            <flux:input wire:model="kuantitas" label="Kuantitas" type="number" required />
            <flux:input wire:model="harga_beli" label="Harga Beli" type="number" required />
            <flux:input wire:model="jumlah" label="Jumlah" type="number" required />
            <flux:input wire:model="kadaluarsa" label="Kadaluarsa" type="date" required />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-detail-pembelian" class="min-w-[22rem]">
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