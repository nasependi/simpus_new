<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Pasien Umum</flux:heading>
            <div class="flex gap-4 items-center">
                <flux:input wire:model.live="search" placeholder="Cari pasien..." size="md" />
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
                <flux:table.column>Provinsi</flux:table.column>
                <flux:table.column>Jenis Kelamin</flux:table.column>
                <flux:table.column>Agama</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>

            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                    <flux:table.row>
                        <flux:table.cell>{{ $item->nama_lengkap }}</flux:table.cell>
                        <flux:table.cell>{{ $item->nik }}</flux:table.cell>
                        <flux:table.cell>{{ $item->province->name }}</flux:table.cell>
                        <flux:table.cell>{{ $item->jenisKelamin->nama_jk }}</flux:table.cell>
                        <flux:table.cell>{{ $item->agama->nama_agama }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" />
                            <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus"
                                variant="danger" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="pasienModal" class="space-y-4 w-3/4 !max-w-none overflow-y-auto max-h-[85vh]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Pasien
            </flux:heading>

            <div class="grid grid-cols-2 gap-4">
                @if ($halaman === 1)
                    <flux:input wire:model="nama_lengkap" label="Nama Lengkap" required />
                    <flux:input wire:model="no_rekamedis" label="No Rekam Medis" required />
                    <flux:input wire:model="nik" label="NIK" required />
                    <flux:input wire:model="paspor" label="Paspor" />
                    <div class="grid grid-cols-3 items-center">
                        <flux:input type="number" wire:model.live="umur" label="Umur" />
                        <flux:select wire:model.live="hitungan" label="Per" placeholder="Choose industry...">
                            <flux:select.option value="hari">Hari</flux:select.option>
                            <flux:select.option value="bulan">Bulan</flux:select.option>
                            <flux:select.option value="tahun">Tahun</flux:select.option>
                        </flux:select>
                        <flux:date-picker wire:model="tanggal_lahir" label="Tanggal Lahir" required />
                    </div>
                    <flux:input wire:model="ibu_kandung" label="Nama Ibu Kandung" required />
                @endif
                @if ($halaman === 2)
                    <flux:input wire:model="suku" label="Suku" required />
                    <flux:input wire:model="rt" label="rt" required />
                    <flux:input wire:model="rw" label="rw" required />
                    <flux:input wire:model="alamat_domisili" label="alamat domisili" required />
                    <flux:input wire:model="domisili_rt" label="domisil rt" required />
                    <flux:input wire:model="domisili_rw" label="domisil rw" required />
                    <flux:input wire:model="no_rumah" label="no_rumah" required />
                    <flux:input wire:model="no_hp" label="no_hp" required />
                    <flux:input wire:model="bahasa_dikuasai" label="Bahasa yang dikuasai" required />
                    <flux:input wire:model="tempat_lahir" label="Tempat Lahir" required />
                @endif
                @if ($halaman === 3)

                    <!-- PROVINSI -->
                    <div class="">
                        <flux:input wire:model.live.debounce.500ms="search_provinsi" label="Provinsi"
                            placeholder="Ketik nama provinsi..." autocomplete="off" />
                        @if ($provinsiOptions)
                            <div class="absolute bg-white border rounded w-full max-h-40 overflow-auto shadow">
                                @foreach ($provinsiOptions as $provinsi)
                                    <div class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer"
                                        wire:click="selectProvinsi({{ $provinsi['id'] }}, '{{ $provinsi['name'] }}')">
                                        {{ $provinsi['name'] }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <!-- KABUPATEN -->
                    @if ($prov_id)
                        <div class="">
                            <flux:input wire:model.live.debounce.500ms="search_kabupaten" label="Kabupaten/Kota"
                                placeholder="Ketik nama kabupaten..." autocomplete="off" />
                            @if ($kabupatenOptions)
                                <div class="absolute bg-white border rounded w-full max-h-40 overflow-auto shadow">
                                    @foreach ($kabupatenOptions as $kabupaten)
                                        <div class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer"
                                            wire:click="selectKabupaten({{ $kabupaten['id'] }}, '{{ $kabupaten['name'] }}')">
                                            {{ $kabupaten['name'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                    <!-- KECAMATAN -->
                    @if ($kab_id)
                        <div class="">
                            <flux:input wire:model.live.debounce.500ms="search_kecamatan" label="Kecamatan"
                                placeholder="Ketik nama kecamatan..." autocomplete="off" />
                            @if ($kecamatanOptions)
                                <div class="relative z-50">
                                    <div class="absolute bg-white border rounded w-full max-h-40 overflow-auto shadow">
                                        @foreach ($kecamatanOptions as $kecamatan)
                                            <div class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer"
                                                wire:click="selectKecamatan({{ $kecamatan['id'] }}, '{{ $kecamatan['name'] }}')">
                                                {{ $kecamatan['name'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- KELURAHAN -->
                    @if ($kec_id)
                        <div class="">
                            <flux:input wire:model.live.debounce.500ms="search_kelurahan" label="Kelurahan/Desa"
                                placeholder="Ketik nama kelurahan..." autocomplete="off" />
                            @if ($kelurahanOptions)
                                <div class="relative z-50">
                                    <div class="absolute bg-white border rounded w-full max-h-40 overflow-auto shadow">
                                        @foreach ($kelurahanOptions as $kelurahan)
                                            <div class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer"
                                                wire:click="selectKelurahan({{ $kelurahan['id'] }}, '{{ $kelurahan['name'] }}')">
                                                {{ $kelurahan['name'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <flux:select wire:model="jk_id" label="Jenis Kelamin" required>
                        <flux:select.option value="">Pilih Jenis Kelamin</flux:select.option>
                        @foreach ($jenis_kelamin as $i)
                            <flux:select.option value="{{ $i->id }}">{{ $i->nama_jk }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="agama_id" label="Agama" required>
                        <flux:select.option value="">Pilih Agama</flux:select.option>
                        @foreach ($agama as $a)
                            <flux:select.option value="{{ $a->id }}">{{ $a->nama_agama }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="pendidikan_id" label="Pendidikan" required>
                        <flux:select.option value="">Pilih Pendidikan</flux:select.option>
                        @foreach ($pendidikan as $pen)
                            <flux:select.option value="{{ $pen->id }}">{{ $pen->nama_pendidikan }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="pekerjaan_id" label="Pekerjaan" required>
                        <flux:select.option value="">Pilih Pekerjaan</flux:select.option>
                        @foreach ($pekerjaan as $pek)
                            <flux:select.option value="{{ $pek->id }}">{{ $pek->nama_pekerjaan }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="statusnikah_id" label="Status Pernikahan" required>
                        <flux:select.option value="">Pilih Status Pernikahan</flux:select.option>
                        @foreach ($status_pernikahan as $sn)
                            <flux:select.option value="{{ $sn->id }}">{{ $sn->status }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:textarea wire:model="alamat_lengkap" label="Alamat Lengkap" required />
                @endif
            </div>
            <div class="flex justify-between">
                <div class="ss">
                    @if ($halaman === 1)
                        <flux:button wire:click="next" class="hover:cursor-pointer">Selanjutnya</flux:button>
                    @elseif ($halaman === 2)
                        <flux:button wire:click="back" class="hover:cursor-pointer">Sebelumnya</flux:button>
                        <flux:button wire:click="next" class="hover:cursor-pointer">Selanjutnya</flux:button>
                    @elseif ($halaman === 3)
                        <flux:button wire:click="back" class="hover:cursor-pointer">Sebelumnya</flux:button>
                    @endif
                </div>
                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">Batal</flux:button>
                    </flux:modal.close>

                    @if ($halaman === 3)
                        <flux:button wire:click="save" variant="primary">Simpan</flux:button>
                    @endif
                </div>
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
