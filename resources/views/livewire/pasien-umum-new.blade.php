<div class="p-4 sm:p-6">
    <flux:card class="shadow-lg rounded-xl border-0">
        {{-- Header Section --}}
        <div class="p-4 sm:p-6 border-b border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <flux:heading size="xl" class="text-neutral-900 dark:text-neutral-100">
                        Data Pasien Umum
                    </flux:heading>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Kelola data pasien puskesmas
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <flux:input 
                        wire:model.live="search" 
                        placeholder="Cari nama, NIK, atau No. RM..." 
                        class="w-full sm:w-64"
                        icon="magnifying-glass" />
                    <flux:button 
                        wire:click="create" 
                        variant="primary" 
                        icon="plus"
                        class="w-full sm:w-auto">
                        Tambah Pasien
                    </flux:button>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="overflow-hidden">
            {{-- Desktop Table View --}}
            <div class="hidden lg:block overflow-x-auto">
                <flux:table :paginate="$data">
                    <flux:table.columns>
                        <flux:table.column wire:click="sortBy('nama_lengkap')" class="cursor-pointer">
                            Nama Lengkap
                        </flux:table.column>
                        <flux:table.column>No Rekam Medis</flux:table.column>
                        <flux:table.column>NIK</flux:table.column>
                        <flux:table.column>Tanggal Lahir</flux:table.column>
                        <flux:table.column>Jenis Kelamin</flux:table.column>
                        <flux:table.column>No HP</flux:table.column>
                        <flux:table.column class="text-center">Aksi</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($data as $item)
                        <flux:table.row>
                            <flux:table.cell>
                                <div class="font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $item->nama_lengkap }}
                                </div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $item->pekerjaan?->nama_pekerjaan }}
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <span class="font-mono text-sm">{{ $item->no_rekamedis }}</span>
                            </flux:table.cell>
                            <flux:table.cell>
                                <span class="font-mono text-sm">{{ $item->nik }}</span>
                            </flux:table.cell>
                            <flux:table.cell>{{ $item->tanggal_lahir }}</flux:table.cell>
                            <flux:table.cell>{{ $item->jenisKelamin?->nama_jk }}</flux:table.cell>
                            <flux:table.cell>
                                <span class="font-mono text-sm">{{ $item->no_hp }}</span>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button 
                                        size="sm" 
                                        variant="ghost"
                                        icon="clipboard-document-list" 
                                        wire:click="modalKunjungan({{ $item->id }})"
                                        title="Pelayanan">
                                    </flux:button>
                                    <flux:button 
                                        size="sm" 
                                        variant="ghost"
                                        icon="pencil" 
                                        wire:click="edit({{ $item->id }})"
                                        title="Edit">
                                    </flux:button>
                                    <flux:button 
                                        size="sm" 
                                        variant="ghost"
                                        icon="trash" 
                                        wire:click="deleteConfirm({{ $item->id }})"
                                        title="Hapus">
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>

            {{-- Mobile Card View --}}
            <div class="lg:hidden space-y-4 p-4">
                @foreach ($data as $item)
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 shadow-sm hover:shadow-md transition-shadow">
                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-neutral-900 dark:text-neutral-100">
                                {{ $item->nama_lengkap }}
                            </h3>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">
                                {{ $item->pekerjaan?->nama_pekerjaan }}
                            </p>
                        </div>
                        <flux:badge size="sm" color="blue">
                            {{ $item->jenisKelamin?->nama_jk }}
                        </flux:badge>
                    </div>

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                        <div>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">No. RM</p>
                            <p class="font-mono font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $item->no_rekamedis }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">NIK</p>
                            <p class="font-mono font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $item->nik }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Tanggal Lahir</p>
                            <p class="font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $item->tanggal_lahir }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">No. HP</p>
                            <p class="font-mono font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $item->no_hp }}
                            </p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 pt-3 border-t border-neutral-200 dark:border-neutral-700">
                        <flux:button 
                            size="sm" 
                            variant="outline"
                            icon="clipboard-document-list" 
                            wire:click="modalKunjungan({{ $item->id }})"
                            class="flex-1">
                            Pelayanan
                        </flux:button>
                        <flux:button 
                            size="sm" 
                            variant="ghost"
                            icon="pencil" 
                            wire:click="edit({{ $item->id }})">
                        </flux:button>
                        <flux:button 
                            size="sm" 
                            variant="ghost"
                            icon="trash" 
                            wire:click="deleteConfirm({{ $item->id }})">
                        </flux:button>
                    </div>
                </div>
                @endforeach

                {{-- Mobile Pagination --}}
                <div class="mt-4">
                    {{ $data->links() }}
                </div>
            </div>
        </div>

        {{-- Modal Kunjungan --}}
        <flux:modal name="kunjunganModal" class="space-y-4 md:w-[90rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Pelayanan Kunjungan
            </flux:heading>

            <flux:input label="Nama" disabled wire:model="nama_lengkap" />
            <div class="grid grid-cols-3 gap-3">
                <flux:input wire:model="umur_tahun" label="Umur Tahun" type="number" required />
                <flux:input wire:model="umur_bulan" label="Umur Bulan" type="number" required />
                <flux:input wire:model="umur_hari" label="Umur Hari" type="number" required />
            </div>

            <flux:input wire:model="tanggal_kunjungan" label="Tanggal Kunjungan" type="date" required />

            <flux:select wire:model="poli_id" label="Poli" required>
                <flux:select.option value="">Pilih Poli</flux:select.option>
                @foreach ($poli as $pol)
                <flux:select.option value="{{ $pol->id }}">{{ $pol->nama }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model="carapembayaran_id" label="Cara Pembayaran" required>
                <flux:select.option value="">Pilih Cara Pembayaran</flux:select.option>
                @foreach ($cara_pembayaran as $em)
                <flux:select.option value="{{ $em->id }}">{{ $em->nama }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="saveKunjungan" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Rest of the modals remain the same... --}}
        {{-- Modal Tambah/Edit --}}
        <flux:modal name="pasienModal" class="space-y-4 w-3/4 !max-w-none overflow-y-auto max-h-[85vh]">
            {{-- Keep the existing modal content from line 95 onwards --}}
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
