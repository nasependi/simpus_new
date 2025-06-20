<div class="space-y-4">
    {{-- Form Input --}}
    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="state.tb_pasien" label="TB Pasien" />
        <flux:input wire:model="state.bb_pasien" label="BB Pasien" />
        <flux:input wire:model="state.id_resep" label="ID Resep" />
        <flux:input wire:model="state.nama_obat" label="Nama Obat" />
        <flux:input wire:model="state.id_obat" label="ID Obat" />
        <flux:input wire:model="state.sediaan" label="Sediaan" />
        <flux:input wire:model="state.jumlah_obat" label="Jumlah Obat" type="number" />
        <flux:input wire:model="state.metode_pemberian" label="Metode Pemberian" />
        <flux:input wire:model="state.dosis_diberikan" label="Dosis Diberikan" />
        <flux:input wire:model="state.unit" label="Unit" />
        <flux:input wire:model="state.frekuensi" label="Frekuensi" />
        <flux:input wire:model="state.aturan_tambahan" label="Aturan Tambahan" />
        <flux:input wire:model="state.catatan_resep" label="Catatan Resep" />
        <flux:input wire:model="state.dokter_penulis_resep" label="Dokter Penulis" />
        <flux:input wire:model="state.nomor_telepon_dokter" label="No. Telepon Dokter" />
        <flux:input wire:model="state.tanggal_penulisan_resep" type="date" label="Tanggal Penulisan" />
        <flux:input wire:model="state.jam_penulisan_resep" type="time" label="Jam Penulisan" />
        <flux:input wire:model="state.ttd_dokter" label="TTD Dokter" />
        <flux:input wire:model="state.status_resep" label="Status Resep" />
        <flux:input wire:model="state.pengkajian_resep" label="Pengkajian Resep" />
    </div>

    <flux:button wire:click="save" class="mt-4 w-full" variant="primary">Tambah Resep</flux:button>

    {{-- Table View --}}
    <div class="border rounded p-4 mt-6">
        <h3 class="font-semibold mb-2">Daftar Obat Resep</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Frekuensi</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($resep as $item)
                <tr class="border-t">
                    <td>{{ $item->nama_obat }}</td>
                    <td>{{ $item->jumlah_obat }}</td>
                    <td>{{ $item->frekuensi }}</td>
                    <td class="text-right">
                        <flux:button wire:click="delete({{ $item->id }})" variant="danger" size="sm">Hapus</flux:button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-2">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>