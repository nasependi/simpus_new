<div>
    <flux:card>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Manajemen Role</h2>
            <flux:button wire:click="openModal" variant="primary">Tambah Role</flux:button>
        </div>
    
        <flux:table>
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Nama Role</flux:table.column>
                <flux:table.column>Permissions</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($roles as $role)
                    <flux:table.row :key="$role->id">
                        <flux:table.cell>{{ $role->name }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($role->permissions as $perm)
                                    <span class="text-xs bg-gray-700 px-2 py-1 rounded">{{ $perm->name }}</span>
                                @endforeach
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button size="sm" wire:click="edit({{ $role->id }})">Edit</flux:button>
                            <flux:button size="sm" variant="danger" wire:click="delete({{ $role->id }})" class="ml-2">Hapus</flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    
        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    
        <!-- Modal -->
        <flux:modal class="min-w-[25rem]" wire:model.self="showModal">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $editId ? 'Edit' : 'Tambah' }} Role</flux:heading>
                </div>
    
                <flux:input label="Nama Role" wire:model.defer="name" placeholder="Masukkan nama role" />
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    
                <div class="mt-4">
                    <p class="text-sm font-medium mb-1">Permissions:</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" value="{{ $permission->name }}"
                                       wire:model.defer="selectedPermissions"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
    
                <div class="flex justify-end space-x-2 mt-6">
                    <flux:button variant="filled" wire:click="closeModal">Batal</flux:button>
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
