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
                        <flux:button size="sm" class="bg-grey-300" icon="document-text" wire:click="showDetail({{ $item->id }})" class="mr-2">Detail Pembelian</flux:button>
                        <flux:button size="sm" class="bg-grey-300" icon="pencil" wire:click="edit({{ $item->id }})">Edit</flux:button>
                        <flux:button size="sm" variant="danger" icon="trash" wire:click="deleteConfirm({{ $item->id }})" class="ml-2">Hapus</flux:button>
                    </flux:table.cell>

                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit Pembelian --}}
        <flux:modal name="pembelianModal" class="w-full max-w-screen-xl h-[85vh] overflow-y-auto">
            <div class="p-3 space-y-3">
                {{-- Header --}}
                <div class="flex justify-between items-center border-b pb-3">
                    <flux:heading class="text-lg font-semibold">
                        {{ $editId ? 'Edit' : 'Tambah' }} Pembelian Obat
                    </flux:heading>
                </div>

                {{-- Informasi Umum + Total --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="no_faktur" label="No Faktur" required />
                        <flux:input wire:model="tanggal" type="date" label="Tanggal Pembelian"
                            value="{{ now()->format('Y-m-d') }}" required />
                    </div>
                    <div class="flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800 rounded-lg shadow p-6">
                        <span class="text-sm text-gray-500">Total Pembelian</span>
                        <span class="text-4xl font-bold text-green-600">
                            Rp {{ number_format($harga_beli_bersih ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Input Tambah Item --}}
                <div class="grid grid-cols-6 gap-2 items-end border rounded-lg p-4 bg-gray-50 dark:bg-gray-900">
                    <flux:autocomplete wire:model="obat_id" label="Pilih Obat" placeholder="Cari obat...">
                        @foreach($obatList as $obat)
                        <flux:autocomplete.item value="{{ $obat->id }}">{{ $obat->nama_obat }}</flux:autocomplete.item>
                        @endforeach
                    </flux:autocomplete>

                    <flux:input wire:model.live="kuantitas" label="Kuantitas" type="number" min="1" />
                    <flux:input wire:model.live="harga_beli" label="Harga Beli" type="number" min="0" />
                    <flux:input wire:model="jumlah" label="Jumlah" readonly />
                    <flux:input wire:model="kadaluarsa" label="Kadaluarsa" type="date" />

                    <flux:button wire:click.prevent="addItem" variant="primary" size="sm">+ Tambah Item</flux:button>
                </div>

                {{-- Tabel Detail Item --}}
                @if(!empty($detailItems))
                <div class="overflow-x-auto border rounded-lg shadow">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-3 py-2 text-left">Nama Obat</th>
                                <th class="px-3 py-2 text-center">Kuantitas</th>
                                <th class="px-3 py-2 text-right">Harga Beli</th>
                                <th class="px-3 py-2 text-right">Jumlah</th>
                                <th class="px-3 py-2 text-center">Kadaluarsa</th>
                                <th class="px-3 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($detailItems as $index => $item)
                            <tr>
                                <td class="px-3 py-2">{{ $item['nama_obat'] }}</td>
                                <td class="px-3 py-2 text-center">{{ $item['kuantitas'] }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($item['harga_beli'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-center">{{ \Carbon\Carbon::parse($item['kadaluarsa'])->format('d-m-Y') }}</td>
                                <td class="px-3 py-2 text-center">
                                    <flux:button wire:click="removeItem({{ $index }})" variant="danger" size="sm">✕</flux:button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Input Tambahan (PPN, PPH, Diskon, dll) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <flux:input wire:model="harga_beli_kotor" label="Harga Beli Kotor" readonly />
                        <flux:input wire:model="ppn" label="PPN (%)" type="number" step="0.01" />
                        <flux:input wire:model="pph" label="PPH (%)" type="number" step="0.01" />
                        <flux:input wire:model="diskon" label="Diskon (Rp)" type="number" step="0.01" />
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-2 border-t pt-4">
                    <flux:modal.close>
                        <flux:button variant="ghost">Batal</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="save" variant="primary">💾 Simpan</flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- Modal Detail Pembelian --}}
        <flux:modal name="detailPembelianModal" class="w-full max-w-screen-xl h-[85vh] overflow-y-auto">
            <div class="p-6">

                {{-- Detail Item Pembelian --}}
                @if(empty($detailPembelian))
                <p class="text-gray-500 text-center py-6">Tidak ada detail untuk pembelian ini.</p>
                @else
                {{-- Informasi Umum Pembelian --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                        <p class="text-sm text-gray-500">No Faktur</p>
                        <p class="font-semibold text-gray-800">{{ $pembelian['no_faktur'] ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                        <p class="text-sm text-gray-500">Total Harga Bersih</p>
                        <p class="font-semibold text-green-600">
                            Rp {{ number_format($pembelian['harga_beli_bersih'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full text-sm border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 border">Nama Obat</th>
                                <th class="px-4 py-3 border text-center">Kuantitas</th>
                                <th class="px-4 py-3 border text-right">Harga Beli</th>
                                <th class="px-4 py-3 border text-right">Jumlah</th>
                                <th class="px-4 py-3 border text-center">Kadaluarsa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($detailPembelian as $d)
                            <tr class="">
                                <td class="px-4 py-2 border">{{ $d['nama_obat'] }}</td>
                                <td class="px-4 py-2 border text-center">{{ $d['kuantitas'] }}</td>
                                <td class="px-4 py-2 border text-right">
                                    Rp {{ number_format($d['harga_beli'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 border text-right">
                                    Rp {{ number_format($d['jumlah'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 border text-center">{{ $d['kadaluarsa'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold">
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-right border">Total</td>
                                <td class="px-4 py-2 text-right border">
                                    Rp {{ number_format(array_sum(array_column($detailPembelian, 'jumlah')), 0, ',', '.') }}
                                </td>
                                <td class="border"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
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