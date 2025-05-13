<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Pasien Umum</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari pasien..."  size="md" />
                <flux:button wire:click="create" variant="primary" icon="plus-circle">Tambah</flux:button>
            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column wire:click="sortBy('nama_lengkap')" class="cursor-pointer">
                    Nama Lengkap
                    @if ($sortField === 'nama_lengkap')
                        {{-- <x-icon :name="$sortDirection === 'asc' ? 'arrow-up' : 'arrow-down'" class="w-3 h-3 text-muted-foreground ml-1" /> --}}
                    @endif
                </flux:table.column>
                <flux:table.column>NIK</flux:table.column>
                <flux:table.column>Jenis Kelamin</flux:table.column>
                <flux:table.column>Agama</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                    <flux:table.row>
                        <flux:table.cell>{{ $item->nama_lengkap }}</flux:table.cell>
                        <flux:table.cell>{{ $item->nik }}</flux:table.cell>
                        {{-- <flux:table.cell>{{ $item->jenisKelamin->nama }}</flux:table.cell>
                        <flux:table.cell>{{ $item->agama->nama_agama }}</flux:table.cell> --}}
                        <flux:table.cell>
                            <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" />
                            <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="pasienModal" class="space-y-4 md:w-[60rem] overflow-y-auto max-h-[85vh]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Pasien
            </flux:heading>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="nama_lengkap" label="Nama Lengkap" required />
                <flux:input wire:model="no_rekamedis" label="No Rekam Medis" required />
                <flux:input wire:model="nik" label="NIK" required />
                <flux:input wire:model="paspor" label="Paspor" />
                <flux:input wire:model="ibu_kandung" label="Nama Ibu Kandung" required />
                <flux:input wire:model="tempat_lahir" label="Tempat Lahir" required />
                {{-- <flux:input wire:model="tanggal_lahir" label="Tanggal Lahir" type="date" required /> --}}

                <flux:select wire:model="jk_id" label="Jenis Kelamin" :options="$jks->pluck('nama', 'id')" required />
                <flux:select wire:model="agama_id" label="Agama" :options="$agamas->pluck('nama_agama', 'id')" required />
                {{-- <flux:input wire:model="suku" label="Suku" />
                <flux:input wire:model="bahasa_dikuasai" label="Bahasa Dikuasai" />
                <flux:input wire:model="alamat_lengkap" label="Alamat Lengkap" required /> --}}
                {{-- ... Tambahkan input lain sesuai kebutuhan --}}
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-pasien" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus Pasien?</flux:heading>
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
