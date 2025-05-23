<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Bayi Baru Lahir</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari nama bayi..." icon="magnifying-glass"
                    size="md" />
                @can('tambah')
                    <flux:button wire:click="create" variant="primary" icon="plus-circle">Tambah</flux:button>
                @endcan
            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column>Nama Bayi</flux:table.column>
                <flux:table.column>NIK Ibu</flux:table.column>
                <flux:table.column>No. Rekam Medis</flux:table.column>
                <flux:table.column>Tempat Lahir</flux:table.column>
                <flux:table.column>Tanggal Lahir</flux:table.column>
                <flux:table.column>Jam Lahir</flux:table.column>
                <flux:table.column>Jenis Kelamin</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                    <flux:table.row>
                        <flux:table.cell>{{ $item->nama_bayi }}</flux:table.cell>
                        <flux:table.cell>{{ $item->nik_ibuk }}</flux:table.cell>
                        <flux:table.cell>{{ $item->no_rekamedis }}</flux:table.cell>
                        <flux:table.cell>{{ $item->tempat_lahir }}</flux:table.cell>
                        <flux:table.cell>{{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('l, d F Y') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->jam_lahir)->format('H:i') }} WIB
                        </flux:table.cell>
                        <flux:table.cell>{{ $item->jenisKelamin->nama_jk }}</flux:table.cell>
                        <flux:table.cell>
                            @can('edit')
                                <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit"
                                    class="mr-2" />
                            @endcan
                            @can('hapus')
                                <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus"
                                    variant="danger" />
                            @endcan
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="bayiModal" class="space-y-4 md:w-[50rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Bayi Baru Lahir
            </flux:heading>

            <flux:input wire:model="nama_bayi" label="Nama Bayi" required />
            <flux:input wire:model="nik_ibuk" label="NIK Ibuk" maxlength="16" required />
            <flux:input wire:model="no_rekamedis" label="No. Rekam Medis" required />
            <flux:input wire:model="tempat_lahir" label="Tempat Lahir" required />
            <flux:input type="date" wire:model="tanggal_lahir" label="Tanggal Lahir" required />
            <flux:input type="time" wire:model="jam_lahir" label="Jam Lahir" required />
            <flux:select wire:model="jk_id" label="Jenis Kelamin" required>
                <flux:select.option value="">Pilih Jenis Kelamin</flux:select.option>
                @foreach ($jenis_kelamin as $i)
                    <flux:select.option value="{{ $i->id }}">{{ $i->nama_jk }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-bayi" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus Data Bayi?</flux:heading>
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
