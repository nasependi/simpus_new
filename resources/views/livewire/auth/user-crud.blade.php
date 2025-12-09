<div>
    <flux:card class="">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Manajemen User</h2>
            <flux:button wire:click="openModal" variant="primary" icon="plus">Tambah User</flux:button>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Role</flux:table.column>
                <flux:table.column>Permissions</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($users as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell>{{ $user->name }}</flux:table.cell>
                    <flux:table.cell>{{ $user->email }}</flux:table.cell>
                    <flux:table.cell>{{ $user->roles->pluck('name')->join(', ') }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-wrap gap-1">
                            @foreach ($user->permissions as $perm)
                            <span class="text-xs bg-gray-700 px-2 py-1 rounded">{{ $perm->name }}</span>
                            @endforeach
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" icon="pencil" wire:click="edit({{ $user->id }})">Edit</flux:button>
                        <flux:button size="sm" icon="trash" variant="danger" wire:click="delete({{ $user->id }})" class="ml-2">Hapus</flux:button>
                    </flux:table.cell>
                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>

        <!-- Modal -->
        <flux:modal class="space-y-2 md:w-[50rem]" wire:model.self="showModal">
            <div class="space-y-6">
                <flux:heading size="lg">{{ $editId ? 'Edit' : 'Tambah' }} User</flux:heading>

                <flux:input label="Nama" wire:model.defer="name" />
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <flux:input label="Email" wire:model.defer="email" type="email" />
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <flux:input label="Password" wire:model.defer="password" type="password" placeholder="Boleh dikosongkan saat edit" />
                @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <div>
                    <label class="block mb-1 font-medium text-sm">Role</label>
                    <flux:select wire:model="role" placeholder="Pilih Role...">
                        @foreach ($roles as $roleItem)
                        <flux:select.option value="{{ $roleItem->name }}">
                            {{ $roleItem->name }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>

                    <div class="text-sm" wire:loading wire:target="role">
                        Memuat permission dari role...
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-sm font-medium mb-1">Permissions:</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach ($allPermissions as $permission)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" value="{{ $permission->name }}"
                                wire:model.defer="permissions"
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