<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Pembelian Obat</flux:heading>
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
                        <flux:tooltip content="Detail Pembelian Obat">
                            <flux:button wire:click="showDetail({{ $item->id }})" icon="document-text" label="Detail" class="mr-2" />
                        </flux:tooltip>
                        <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                    </flux:table.cell>

                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="pembelianModal" class="w-full max-w-screen-xl h-[80vh] overflow-y-auto">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Pembelian Obat
            </flux:heading>

            {{-- Input Umum --}}
            <flux:input wire:model="no_faktur" label="No Faktur" required />
            <flux:input wire:model="ppn" label="PPN" type="number" step="0.01" />
            <flux:input wire:model="pph" label="PPH" type="number" step="0.01" />
            <flux:input wire:model="diskon" label="Diskon" type="number" step="0.01" />
            <flux:input wire:model="harga_beli_kotor" label="Harga Beli Kotor" type="number" required />
            <flux:input wire:model="harga_beli_bersih" label="Harga Beli Bersih" type="number" required />

            <hr class="my-4">

            {{-- Input Detail Obat (hanya nama obat + jumlah) --}}
            <div class="grid grid-cols-6 gap-2 items-end">
                <flux:autocomplete wire:model="obat_id" label="Pilih Obat" placeholder="Cari obat...">
                    @foreach($obatList as $obat)
                    <flux:autocomplete.item value="{{ $obat->id }}">
                        {{ $obat->nama_obat }}
                    </flux:autocomplete.item>
                    @endforeach
                </flux:autocomplete>

                <flux:input wire:model.live.debounce.300ms="kuantitas" label="Kuantitas" type="number" min="1" />
                <flux:input wire:model.live.debounce.300ms="harga_beli" label="Harga Beli" type="number" min="0" />
                <flux:input wire:model="jumlah" label="Jumlah" readonly />
                <flux:input wire:model="kadaluarsa" label="Kadaluarsa" type="date" />

                <flux:button wire:click.prevent="addItem" variant="primary">Tambah Item</flux:button>
            </div>

            {{-- Tabel Item --}}
            @if(!empty($detailItems))
            <table class="min-w-full mt-4 border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 py-1">Nama Obat</th>
                        <th class="px-2 py-1">Kuantitas</th>
                        <th class="px-2 py-1">Harga Beli</th>
                        <th class="px-2 py-1">Jumlah</th>
                        <th class="px-2 py-1">Kadaluarsa</th>
                        <th class="px-2 py-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailItems as $index => $item)
                    <tr>
                        <td class="px-2 py-1">{{ $item['nama_obat'] }}</td>
                        <td class="px-2 py-1">{{ $item['kuantitas'] }}</td>
                        <td class="px-2 py-1">{{ number_format($item['harga_beli'], 0, ',', '.') }}</td>
                        <td class="px-2 py-1">{{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                        <td class="px-2 py-1">{{ \Carbon\Carbon::parse($item['kadaluarsa'])->format('d-m-Y') }}</td>
                        <td class="px-2 py-1">
                            <flux:button wire:click="removeItem({{ $index }})" variant="danger" size="sm">Hapus</flux:button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @endif

            <div class="flex justify-end gap-2 mt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-pembelian" class="min-w-[22rem]">
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