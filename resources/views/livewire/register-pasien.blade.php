<div>
    <div class="p-6">
        <flux:card class="shadow-lg rounded-lg">
            <div class="flex justify-between mb-4">
                <flux:heading size="xl">Data Pasien Umum</flux:heading>
                <div class="flex gap-4 items-center">
                    <flux:input wire:model.live="search" placeholder="Cari pasien..." icon="magnifying-glass" size="md" />
                    <flux:button wire:click="modalShow()" variant="primary" icon="plus-circle">Tambah</flux:button>
                    <flux:modal.trigger name="edit-profile">
                        <flux:button>Edit profile</flux:button>
                    </flux:modal.trigger>
                    <flux:button wire:click="modalShow">
                        Save changes
                    </flux:button>
                </div>
            </div>

            <flux:table :paginate="$data">
                <flux:table.columns>
                    <!-- Define columns here similar to agama CRUD -->
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($data as $pasien)
                    <flux:table.row>
                        <flux:table.cell>{{ $pasien->nama_lengkap }}</flux:table.cell>
                        <flux:table.cell>{{ $pasien->no_rekamedis }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button wire:click="edit({{ $pasien->id }})" icon="pencil" label="Edit" class="mr-2" />
                            <flux:button wire:click="deleteConfirm({{ $pasien->id }})" icon="trash" label="Hapus" variant="danger" />
                        </flux:table.cell>
                    </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            {{-- Modal Tambah/Edit --}}

        </flux:card>
        <flux:modal name="pasienUmumModal" class="md:w-[30rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Pasien
            </flux:heading>

            <!-- Input fields for pasien -->
            <flux:input wire:model="nama_lengkap" label="Nama Lengkap" required />
            <!-- Repeat for other fields like no_rekamedis, nik, etc. -->

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-pasienUmum" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus Data Pasien?</flux:heading>
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



        <flux:modal name="edit-profile" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update profile</flux:heading>
                    <flux:text class="mt-2">Make changes to your personal details.</flux:text>
                </div>

                <flux:input label="Name" placeholder="Your name" />

                <flux:input label="Date of birth" type="date" />

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>