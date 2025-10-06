<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Faskes</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari faskes..." icon="magnifying-glass" size="md" />
                <flux:button wire:click="create" variant="primary" icon="plus">Tambah</flux:button>
            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column>Nama</flux:table.column>
                <flux:table.column>No Telp</flux:table.column>
                <flux:table.column>Alamat</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                <flux:table.row>
                    <flux:table.cell>{{ $item->nama_faskes }}</flux:table.cell>
                    <flux:table.cell>{{ $item->no_telp }}</flux:table.cell>
                    <flux:table.cell>{{ $item->alamat }}</flux:table.cell>
                    <flux:table.cell>{{ $item->email }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" class="mr-2" />
                        <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                    </flux:table.cell>
                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="faskesModal" class="space-y-4 md:w-[50rem]">
            <flux:heading class="text-lg font-semibold">{{ $editId ? 'Edit' : 'Tambah' }} Faskes</flux:heading>

            <flux:input wire:model="nama_faskes" label="Nama Faskes" required />
            <flux:input wire:model="no_telp" label="No Telp" required />
            <flux:input wire:model="alamat" label="Alamat" required />
            <flux:input wire:model="email" label="Email" type="email" required />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-faskes" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus Data Faskes?</flux:heading>
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