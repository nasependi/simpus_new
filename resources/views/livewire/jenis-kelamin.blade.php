<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <!-- Heading -->
        <div class="flex justify-between">
            <flux:heading size="xl">Data Jenis Kelamin</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" class="py-2 " size="md" icon="magnifying-glass" placeholder="Search orders" />
                <flux:button wire:click="create" variant="primary" icon="plus-circle" class="py-2 px-4">Tambah</flux:button>
            </div>
        </div>

        <!-- Table -->
        <flux:table :paginate="$data" class="table-auto w-full rounded-lg shadow-sm">
            <flux:table.columns>
                <flux:table.column class="text-left px-2 py-3">
                    <span> kode </span>
                    {{-- <flux:button wire:click="sortBy('kode')" class="ml-2"/> --}}
                </flux:table.column>
                <flux:table.column class="text-left px-2 py-3">
                    <span> Nama Jenis Kelamin </span>
                    {{-- <flux:button wire:click="sortBy('nama_jk')" class="ml-2"/> --}}
                </flux:table.column>
                <flux:table.column class="text-center px-2 py-3">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                    <flux:table.row class="transition duration-300 ease-in-out">
                        <flux:table.cell class="px-2 py-3">{{ $item->kode }}</flux:table.cell>
                        <flux:table.cell class="px-2 py-3">{{ $item->nama_jk }}</flux:table.cell>
                        <flux:table.cell class="px-2 py-3">
                            <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" class="px-3 py-1 mr-2" />
                            <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" class="px-3 py-1" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal --}}
        <flux:modal name="jenisKelaminModal" class="space-y-4 md:w-[50rem]">
            <flux:heading class="text-lg font-semibold">{{ $editId ? 'Edit' : 'Tambah' }} Jenis Kelamin</flux:heading>

            <flux:input wire:model="kode" label="Kode" required class="w-full" />
            <flux:input wire:model="nama_jk" label="Nama Jenis Kelamin" required class="w-full" />

            <div class="flex justify-end gap-2 mt-4">
                <flux:modal.close>
                    <flux:button variant="ghost" class="px-4 py-2">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary" class="px-6 py-2">Simpan</flux:button>
            </div>
        </flux:modal>

        <flux:modal name="delete-post" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete project?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete this project.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" variant="danger">Delete project</flux:button>
                </div>
            </div>
        </flux:modal>

    </flux:card>
</div>
