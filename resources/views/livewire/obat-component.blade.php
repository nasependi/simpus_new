<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Obat</flux:heading>
            <div class="flex gap-2 items-center">
                <flux:input wire:model.live="search" placeholder="Cari obat..." icon="magnifying-glass" />
                <flux:button wire:click="create" variant="primary" icon="plus">Tambah</flux:button>
                <flux:button wire:click="showImportModal" icon="arrow-up-tray">Import</flux:button>
            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column class="cursor-pointer" wire:click="sortBy('nama_obat')">
                    <div class="flex items-center">
                        <span>Nama Obat</span>
                        @if ($sortField === 'nama_obat')
                        <x-icon :name="$sortDirection === 'asc' ? 'arrow-up' : 'arrow-down'" class="w-3 h-3 ml-1 text-muted-foreground" />
                        @endif
                    </div>
                </flux:table.column>
                <flux:table.column class="cursor-pointer" wire:click="sortBy('golongan')">
                    <div class="flex items-center">
                        <span>Golongan</span>
                        @if ($sortField === 'golongan')
                        <x-icon :name="$sortDirection === 'asc' ? 'arrow-up' : 'arrow-down'" class="w-3 h-3 ml-1 text-muted-foreground" />
                        @endif
                    </div>
                </flux:table.column>
                <flux:table.column class="cursor-pointer" wire:click="sortBy('sedian')">
                    <div class="flex items-center">
                        <span>Sediaan</span>
                        @if ($sortField === 'sediaan')
                        <x-icon :name="$sortDirection === 'asc' ? 'arrow-up' : 'arrow-down'" class="w-3 h-3 ml-1 text-muted-foreground" />
                        @endif
                    </div>
                </flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                <flux:table.row>
                    <flux:table.cell>{{ $item->nama_obat }}</flux:table.cell>
                    <flux:table.cell>{{ $item->golongan }}</flux:table.cell>
                    <flux:table.cell>{{ $item->sediaan }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" class="bg-grey-300" icon="pencil" wire:click="edit({{ $item->id }})">Edit</flux:button>
                        <flux:button size="sm" variant="danger" icon="trash" wire:click="deleteConfirm({{ $item->id }})" class="ml-2">Hapus</flux:button>
                    </flux:table.cell>
                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="obatModal" class="space-y-4 md:w-[50rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Obat
            </flux:heading>

            <flux:input wire:model="nama_obat" label="Nama Obat" required />
            <flux:input wire:model="golongan" label="Golongan" />
            <flux:input wire:model="sediaan" label="Sediaan" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-obat" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus Data Obat?</flux:heading>
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

        {{-- Modal Import Excel --}}
        <flux:modal name="importModal" class="md:w-[40rem]">
            <flux:heading class="text-lg font-semibold mb-4">
                Import Data Obat dari Excel
            </flux:heading>

            <div class="space-y-4">
                <div>
                    <flux:text class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">
                        Download template Excel terlebih dahulu, isi data obat, lalu upload file.
                    </flux:text>
                    <flux:button 
                        wire:click="downloadTemplate" 
                        variant="outline" 
                        icon="arrow-down-tray"
                        size="sm">
                        Download Template Excel
                    </flux:button>
                </div>

                <flux:separator />

                <div>
                    <flux:label>Upload File Excel</flux:label>
                    <input 
                        type="file" 
                        wire:model.live="importFile" 
                        accept=".xlsx,.xls,.csv"
                        class="mt-2 block w-full text-sm text-neutral-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-emerald-50 file:text-emerald-700
                            hover:file:bg-emerald-100
                            dark:file:bg-emerald-900/30 dark:file:text-emerald-300" />
                    
                    <div wire:loading wire:target="importFile" class="mt-2">
                        <flux:text class="text-sm text-blue-600 dark:text-blue-400">
                            ⏳ Mengupload file...
                        </flux:text>
                    </div>
                    
                    <flux:text class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                        Maksimal ukuran file: 2MB
                    </flux:text>
                    
                    @error('importFile') 
                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                @if($importFile)
                    <flux:text class="text-sm text-emerald-600 dark:text-emerald-400">
                        ✓ File siap diupload: {{ $importFile->getClientOriginalName() }}
                    </flux:text>
                @endif
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button 
                    wire:click="import" 
                    variant="primary"
                    wire:loading.attr="disabled"
                    wire:target="import"
                    :disabled="!$importFile">
                    <span wire:loading.remove wire:target="import">Import</span>
                    <span wire:loading wire:target="import">Importing...</span>
                </flux:button>
            </div>
        </flux:modal>
    </flux:card>
</div>