<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Jenis Pemeriksaan Radiologi</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari jenis pemeriksaan..." icon="magnifying-glass" />
                <flux:button wire:click="create" variant="primary" icon="plus-circle">Tambah</flux:button>
            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column>Kode</flux:table.column>
                <flux:table.column>Nama Pemeriksaan</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                <flux:table.row>
                    <flux:table.cell>{{ $item->kode }}</flux:table.cell>
                    <flux:table.cell>{{ $item->nama }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" class="mr-2" />
                        <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                    </flux:table.cell>
                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="radiologiModal" class="space-y-4 md:w-[30rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Jenis Pemeriksaan
            </flux:heading>

            <flux:input wire:model="kode" label="Kode" required />
            <flux:input wire:model="nama" label="Nama Pemeriksaan" required />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

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