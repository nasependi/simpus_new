<div class="space-y-4">
    {{-- Form Input --}}
    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="state.tb_pasien" label="TB Pasien" />
        <flux:input wire:model="state.bb_pasien" label="BB Pasien" />
        <div class="grid grid-cols-2 gap-2">
            <flux:select wire:model.live="state.id_obat" variant="listbox" searchable placeholder="Pilih Obat" label="Pilih Obat">
                @foreach ($obatList as $obat)
                <flux:select.option value="{{ $obat->id }}">
                    {{ $obat->nama_obat }}
                </flux:select.option>
                @endforeach
            </flux:select>
            <flux:input wire:model="stok_obat" label="Stok Obat" disabled />
        </div>
        <flux:input wire:model.live="state.jumlah_obat" label="Jumlah Obat" type="number" />
        <flux:input wire:model="state.metode_pemberian" label="Metode Pemberian" />
        <flux:input wire:model="state.dosis_diberikan" label="Dosis Diberikan" />
        <flux:input wire:model="state.unit" label="Unit" />
        <flux:input wire:model="state.frekuensi" label="Frekuensi" />
        <flux:input wire:model="state.aturan_tambahan" label="Aturan Tambahan" />
    </div>
    <flux:textarea wire:model="state.catatan_resep" label="Catatan Resep" />

    <flux:button wire:click="save" class="mt-4 w-full" variant="primary">Tambah Resep</flux:button>

    {{-- Table View --}}
    <div class="border rounded p-4 mt-6 bg-white dark:bg-gray-900 shadow-sm">
        <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">Daftar Obat Resep</h3>

        <div class="overflow-x-auto rounded">
            <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                <thead>
                    <tr class="border-b dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                        <th class="px-4 py-2 text-left">Nama Obat</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                        <th class="px-4 py-2 text-left">Metode Pemberian</th>
                        <th class="px-4 py-2 text-left">Dosis yang Diberikan</th>
                        <th class="px-4 py-2 text-left">Aturan Tambahan</th>
                        <th class="px-4 py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse ($resep as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="px-4 py-2">{{ $item->nama_obat }}</td>
                        <td class="px-4 py-2">{{ $item->jumlah_obat }}</td>
                        <td class="px-4 py-2">{{ $item->metode_pemberian}}</td>
                        <td class="px-4 py-2">{{ $item->dosis_diberikan }}</td>
                        <td class="px-4 py-2">{{ $item->aturan_tambahan }}</td>
                        <td class="px-4 py-2 text-right">
                            <flux:button wire:click="delete({{ $item->id }})" variant="danger" size="sm">
                                Hapus
                            </flux:button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>