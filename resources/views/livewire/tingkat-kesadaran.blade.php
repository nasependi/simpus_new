<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Tingkat Kesadaran</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari tingkat kesadaran..." icon="magnifying-glass" size="md" />
                <flux:button wire:click="create" variant="primary" icon="plus-circle">Tambah</flux:button>
            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column class="cursor-pointer" wire:click="sortBy('keterangan')">
                    <div class="flex items-center">
                        <span>Keterangan</span>
                        @if ($sortField === 'keterangan')
                            <x-icon :name="$sortDirection === 'asc' ? 'arrow-up' : 'arrow-down'" class="w-3 h-3 ml-1" />
                        @endif
                    </div>
                </flux:table.column>
                <flux:table.column class="cursor-pointer" wire:click="sortBy('nilai')">
                    <div class="flex items-center">
                        <span>Nilai</span>
                        @if ($sortField === 'nilai')
                            <x-icon :name="$sortDirection === 'asc' ? 'arrow-up' : 'arrow-down'" class="w-3 h-3 ml-1" />
                        @endif
                    </div>
                </flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                    <flux:table.row>
                        <flux:table.cell>{{ $item->keterangan }}</flux:table.cell>
                        <flux:table.cell>{{ $item->nilai }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" class="mr-2" />
                            <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="tingkatKesadaranModal" class="space-y-4 md:w-[50rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Tingkat Kesadaran
            </flux:heading>

            <flux:input wire:model="keterangan" label="Keterangan" required />
            <flux:input wire:model="nilai" label="Nilai" required />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-tingkat-kesadaran" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus Data Tingkat Kesadaran?</flux:heading>
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
