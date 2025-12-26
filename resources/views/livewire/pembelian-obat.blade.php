<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Pembelian Obat</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari no faktur..." icon="magnifying-glass" size="sm" />
                <flux:button wire:click="create" variant="primary" icon="plus-circle" size="sm">Tambah</flux:button>
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

        {{-- Modal Detail Pembelian - Nota Style --}}
        <flux:modal name="detailPembelianModal" class="w-full max-w-4xl">
            <div class=" p-8">
                @if(empty($detailPembelian))
                <p class="text-neutral-500 text-center py-6">Tidak ada detail untuk pembelian ini.</p>
                @else
                
                {{-- Header Nota --}}
                <div class="border-b-2 border-neutral-800 dark:border-neutral-200 pb-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-neutral-900 dark:text-neutral-100">NOTA PEMBELIAN OBAT</h1>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">SIMPUS - Sistem Informasi Puskesmas</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">No. Faktur</p>
                            <p class="text-2xl font-bold text-emerald-600">{{ $pembelian['no_faktur'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Informasi Pembelian --}}
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">INFORMASI PEMBELIAN</h3>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Tanggal:</span>
                                <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ \Carbon\Carbon::parse($pembelian['tanggal'] ?? now())->format('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Total Item:</span>
                                <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ count($detailPembelian) }} item</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Total Kuantitas:</span>
                                <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ array_sum(array_column($detailPembelian, 'kuantitas')) }} unit</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">RINGKASAN BIAYA</h3>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Harga Kotor:</span>
                                <span class="font-medium text-neutral-900 dark:text-neutral-100">Rp {{ number_format($pembelian['harga_beli_kotor'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @if(isset($pembelian['ppn']) && $pembelian['ppn'] > 0)
                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">PPN ({{ $pembelian['ppn'] }}%):</span>
                                <span class="font-medium text-neutral-900 dark:text-neutral-100">Rp {{ number_format(($pembelian['harga_beli_kotor'] * $pembelian['ppn'] / 100), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if(isset($pembelian['pph']) && $pembelian['pph'] > 0)
                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">PPH ({{ $pembelian['pph'] }}%):</span>
                                <span class="font-medium text-red-600">- Rp {{ number_format(($pembelian['harga_beli_kotor'] * $pembelian['pph'] / 100), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if(isset($pembelian['diskon']) && $pembelian['diskon'] > 0)
                            <div class="flex justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Diskon:</span>
                                <span class="font-medium text-red-600">- Rp {{ number_format($pembelian['diskon'], 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tabel Detail Item --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">DETAIL ITEM PEMBELIAN</h3>
                    <div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                        <table class="min-w-full text-sm">
                            <thead class="bg-neutral-100 dark:bg-neutral-800">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-300">No</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-300">Nama Obat</th>
                                    <th class="px-4 py-3 text-center font-semibold text-neutral-700 dark:text-neutral-300">Qty</th>
                                    <th class="px-4 py-3 text-right font-semibold text-neutral-700 dark:text-neutral-300">Harga Satuan</th>
                                    <th class="px-4 py-3 text-right font-semibold text-neutral-700 dark:text-neutral-300">Subtotal</th>
                                    <th class="px-4 py-3 text-center font-semibold text-neutral-700 dark:text-neutral-300">Kadaluarsa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach($detailPembelian as $index => $d)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                                    <td class="px-4 py-3 text-neutral-900 dark:text-neutral-100">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-neutral-900 dark:text-neutral-100 font-medium">{{ $d['nama_obat'] }}</td>
                                    <td class="px-4 py-3 text-center text-neutral-900 dark:text-neutral-100">{{ $d['kuantitas'] }}</td>
                                    <td class="px-4 py-3 text-right text-neutral-900 dark:text-neutral-100">
                                        Rp {{ number_format($d['harga_beli'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-neutral-900 dark:text-neutral-100 font-medium">
                                        Rp {{ number_format($d['jumlah'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-neutral-600 dark:text-neutral-400 text-xs">
                                        {{ \Carbon\Carbon::parse($d['kadaluarsa'])->format('d/m/Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Total Akhir --}}
                <div class="border-t-2 border-neutral-800 dark:border-neutral-200 pt-4">
                    <div class="flex justify-end">
                        <div class="w-80">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-neutral-600 dark:text-neutral-400">Subtotal:</span>
                                <span class="font-medium text-neutral-900 dark:text-neutral-100">
                                    Rp {{ number_format(array_sum(array_column($detailPembelian, 'jumlah')), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-t border-neutral-300 dark:border-neutral-600">
                                <span class="text-lg font-bold text-neutral-900 dark:text-neutral-100">TOTAL PEMBAYARAN:</span>
                                <span class="text-2xl font-bold text-emerald-600">
                                    Rp {{ number_format($pembelian['harga_beli_bersih'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Nota --}}
                <div class="mt-8 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                    <div class="flex justify-between items-center text-xs text-neutral-500 dark:text-neutral-400">
                        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
                        <p>Terima kasih atas pembelian Anda</p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-2 mt-6">
                    <flux:modal.close>
                        <flux:button variant="ghost">Tutup</flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" icon="printer" onclick="window.print()">Cetak Nota</flux:button>
                </div>

                @endif
            </div>

            {{-- Print Styling --}}
            <style>
                @media print {
                    /* Hide buttons */
                    button {
                        display: none !important;
                    }
                    
                    /* Full width */
                    body {
                        background: white !important;
                    }
                    
                    /* Black text and borders */
                    * {
                        color: #000 !important;
                        box-shadow: none !important;
                    }
                    
                    .border,
                    .border-t,
                    .border-b,
                    .border-t-2,
                    .border-b-2 {
                        border-color: #000 !important;
                    }
                    
                    table {
                        border-collapse: collapse;
                    }
                    
                    th, td {
                        border: 1px solid #000 !important;
                        padding: 6px !important;
                    }
                    
                    /* Page breaks */
                    table, tr {
                        page-break-inside: avoid;
                    }
                }
            </style>
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