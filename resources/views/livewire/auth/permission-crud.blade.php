<div>
    <flux:card class="">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Daftar Permission</h2>
            <flux:button wire:click="openModal" variant="primary">Tambah Permission</flux:button>
        </div>
    
        <flux:table>
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Nama</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($permissions as $permission)
                    <flux:table.row :key="$permission->id">
                        <flux:table.cell>{{ $permission->name }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button size="sm" class="bg-grey-300" wire:click="edit({{ $permission->id }})">Edit</flux:button>
                            <flux:button size="sm" variant="danger" wire:click="delete({{ $permission->id }})" class="ml-2">Hapus</flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    
        <div class="mt-4">
            {{ $permissions->links() }}
        </div>
    
        <flux:modal class="min-w-[25rem]" wire:model.self="showModal">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $editId ? 'Edit' : 'Tambah' }} Permission</flux:heading>
                </div>
                <flux:input label="Nama Permission" wire:model.defer="name" placeholder="Masukkan nama permission" />
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                <div class="flex justify-end space-x-2">
                    <flux:button class="bg-grey-300" wire:click="closeModal">Batal</flux:button>
                    @if ($editId)
                        <flux:button variant="primary" wire:click="update">Update</flux:button>
                    @else
                        <flux:button variant="primary" wire:click="store">Simpan</flux:button>
                    @endif
                </div>
            </div>
        </flux:modal>
    </flux:card>
</div>
