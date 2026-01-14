<div class="px-4 sm:px-6 pt-2 pb-4 sm:pb-6">
    <flux:card class="card-improved">
        {{-- Header Section --}}
        <div class="pb-4 border-b border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <flux:heading size="xl" class="text-neutral-900 dark:text-neutral-100">
                        Data Pasien Umum
                    </flux:heading>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Kelola data pasien umum
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <flux:input
                        wire:model.live="search"
                        size="sm"
                        placeholder="Cari nama, NIK, atau No. RM..."
                        class="w-full sm:w-64"
                        icon="magnifying-glass" />
                    <flux:button
                        wire:click="create"
                        variant="primary"
                        size="sm"
                        icon="plus"
                        class="w-full sm:w-auto">
                        Tambah Pasien
                    </flux:button>
                </div>
            </div>
        </div>

        <flux:table :paginate="$data" class="p-2">
            <flux:table.columns>
                <flux:table.column wire:click="sortBy('nama_lengkap')" class="cursor-pointer">
                    Nama Lengkap
                    @if ($sortField === 'nama_lengkap')
                    {{-- <x-icon :name="$sortDirection === 'asc' ? 'arrow-up' : 'arrow-down'" class="w-3 h-3 text-muted-foreground ml-1" /> --}}
                    @endif
                </flux:table.column>
                <flux:table.column>No Rekam Medis</flux:table.column>
                <flux:table.column>NIK</flux:table.column>
                <flux:table.column>Tanggal Lahir</flux:table.column>
                <flux:table.column>Jenis Kelamin</flux:table.column>
                <flux:table.column>No HP</flux:table.column>
                <flux:table.column>Pelayanan</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>

            </flux:table.columns>

            <flux:table.rows>
                @foreach ($data as $item)
                <flux:table.row>
                    <flux:table.cell>{{ $item->nama_lengkap }}</flux:table.cell>
                    <flux:table.cell>{{ $item->no_rekamedis }}</flux:table.cell>
                    <flux:table.cell>{{ $item->nik }}</flux:table.cell>
                    <flux:table.cell>{{ $item->tanggal_lahir }}</flux:table.cell>
                    <flux:table.cell>{{ $item->jenisKelamin?->nama_jk }}</flux:table.cell>
                    <flux:table.cell>{{ $item->no_hp }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" wire:click="modalKunjungan({{ $item->id }})">Pelayanan</flux:button>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button size="sm" class="bg-grey-300" icon="pencil" wire:click="edit({{ $item->id }})">Edit</flux:button>
                        <flux:button size="sm" variant="danger" icon="trash" wire:click="deleteConfirm({{ $item->id }})" class="ml-2">Hapus</flux:button>
                    </flux:table.cell>
                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- Modal Kunjungan (Moved outside loop) --}}
        <flux:modal name="kunjunganModal" class="space-y-4 md:w-[90rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Pelayanan Kunjungan
            </flux:heading>

            <flux:input label="Nama" disabled wire:model="nama_lengkap" />
            <div class="grid grid-cols-3 gap-3">
                <flux:input wire:model="umur_tahun" label="Umur Tahun" type="number" disabled required />
                <flux:input wire:model="umur_bulan" label="Umur Bulan" type="number" disabled required />
                <flux:input wire:model="umur_hari" label="Umur Hari" type="number" disabled required />
            </div>

            <flux:input wire:model="tanggal_kunjungan" label="Tanggal Kunjungan" type="date" required />

            <flux:select wire:model="poli_id" label="Poli" required>
                <flux:select.option value="">Pilih Poli</flux:select.option>
                @foreach ($poli as $pol)
                <flux:select.option value="{{ $pol->id }}">{{ $pol->nama }}</flux:select.option>
                @endforeach
            </flux:select>

            <div>
                <flux:select wire:model.live="carapembayaran_id" label="Cara Pembayaran" required>
                    <flux:select.option value="">Pilih Cara Pembayaran</flux:select.option>
                    @foreach ($cara_pembayaran as $em)
                    <flux:select.option value="{{ $em->id }}">{{ $em->nama }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Show input field if "Asuransi Lainnya" is selected - inline with select --}}
            @if($carapembayaran_id && $cara_pembayaran->where('id', $carapembayaran_id)->first()?->nama && str_contains(strtolower($cara_pembayaran->where('id', $carapembayaran_id)->first()->nama), 'lainnya'))
            <div>
                <flux:input wire:model="carapembayaran_lainnya" label="Sebutkan Asuransi/Pembayaran" placeholder="Masukkan nama asuransi..." required />
            </div>
            @endif

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="saveKunjungan" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="pasienModal" class="space-y-4 w-3/4 !max-w-none overflow-y-auto max-h-[85vh]">
            <flux:heading class="text-lg font-semibold" size="lg">
                {{ $editId ? 'Edit' : 'Tambah' }} Pasien
            </flux:heading>

            @if ($halaman === 1)
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="nama_lengkap" label="Nama Lengkap" maxlength="30" required />
                <flux:input wire:model="no_rekamedis" label="No Rekam Medis" required />
                <flux:input wire:model="nik" label="NIK" maxlength="16" required />
                <!-- <flux:input wire:model="paspor" label="Paspor" /> -->
                <flux:input wire:model="tempat_lahir" label="Tempat Lahir" required />
                <div class="grid grid-cols-3 gap-3 items-center">
                    <flux:input type="number" wire:model.live="umur" label="Umur" />
                    <flux:select wire:model.live="hitungan" label="Per" placeholder="Choose industry...">
                        <flux:select.option value="hari">Hari</flux:select.option>
                        <flux:select.option value="bulan">Bulan</flux:select.option>
                        <flux:select.option value="tahun">Tahun</flux:select.option>
                    </flux:select>
                    <flux:date-picker wire:model="tanggal_lahir" label="Tanggal Lahir" required />
                </div>
                <flux:input wire:model="ibu_kandung" label="Nama Ibu Kandung" required />
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
                <div>
                    <flux:select wire:model.live="pekerjaan_id" label="Pekerjaan" required>
                        <flux:select.option value="">Pilih Pekerjaan</flux:select.option>
                        @foreach ($pekerjaan as $pek)
                        <flux:select.option value="{{ $pek->id }}">{{ $pek->nama_pekerjaan }}
                        </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Show input field if "Lainnya" is selected - inline with select --}}
                @if($pekerjaan_id && $pekerjaan->where('id', $pekerjaan_id)->first()?->nama_pekerjaan && str_contains(strtolower($pekerjaan->where('id', $pekerjaan_id)->first()->nama_pekerjaan), 'lainnya'))
                <div>
                    <flux:input wire:model="pekerjaan_lainnya" label="Sebutkan Pekerjaan" placeholder="Masukkan pekerjaan Anda..." required />
                </div>
                @endif
                <flux:select wire:model="statusnikah_id" label="Status Pernikahan" required>
                    <flux:select.option value="">Pilih Status Pernikahan</flux:select.option>
                    @foreach ($status_pernikahan as $sn)
                    <flux:select.option value="{{ $sn->id }}">{{ $sn->status }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="suku" label="Suku" required />
                <flux:input wire:model="bahasa_dikuasai" label="Bahasa yang dikuasai" required />
                <flux:input wire:model="no_hp" label="No Handphone" required />
            </div>
            @endif
            @if ($halaman === 2)
            <flux:textarea wire:model="alamat_lengkap" label="Alamat Lengkap" required />
            <div class="grid grid-cols-2 gap-4">

                <div class="grid grid-cols-3 gap-3 items-center">
                    <flux:input wire:model="rt" label="RT" required />
                    <flux:input wire:model="rw" label="RW" required />
                    <flux:input wire:model="no_rumah" label="Nomor Rumah" required />
                </div>
                {{-- PROVINSI --}}
                <flux:autocomplete
                    wire:model.live.debounce.500ms="search_provinsi"
                    label="Provinsi"
                    placeholder="Ketik nama provinsi...">
                    @if ($provinsiOptions)
                    @foreach ($provinsiOptions as $provinsi)
                    <flux:autocomplete.item wire:click="selectProvinsi({{ $provinsi['id'] }}, '{{ $provinsi['name'] }}')">
                        {{ $provinsi['name'] }}
                    </flux:autocomplete.item>
                    @endforeach
                    @endif
                </flux:autocomplete>

                {{-- KABUPATEN --}}
                @if ($prov_id)
                <flux:autocomplete
                    wire:model.live.debounce.500ms="search_kabupaten"
                    label="Kabupaten/Kota"
                    placeholder="Ketik nama kabupaten...">
                    @if ($kabupatenOptions)
                    @foreach ($kabupatenOptions as $kabupaten)
                    <flux:autocomplete.item wire:click="selectKabupaten({{ $kabupaten['id'] }}, '{{ $kabupaten['name'] }}')">
                        {{ $kabupaten['name'] }}
                    </flux:autocomplete.item>
                    @endforeach
                    @endif
                </flux:autocomplete>
                @endif

                {{-- KECAMATAN --}}
                @if ($kab_id)
                <flux:autocomplete
                    wire:model.live.debounce.300ms="search_kecamatan"
                    label="Kecamatan"
                    placeholder="Ketik nama kecamatan...">
                    @if ($kecamatanOptions)
                    @foreach ($kecamatanOptions as $kecamatan)
                    <flux:autocomplete.item wire:click="selectKecamatan({{ $kecamatan['id'] }}, '{{ $kecamatan['name'] }}')">
                        {{ $kecamatan['name'] }}
                    </flux:autocomplete.item>
                    @endforeach
                    @endif
                </flux:autocomplete>
                @endif

                {{-- KELURAHAN --}}
                @if ($kec_id)
                <flux:autocomplete
                    wire:model.live.debounce.300ms="search_kelurahan"
                    label="Kelurahan/Desa"
                    placeholder="Ketik nama kelurahan...">
                    @if ($kelurahanOptions)
                    @foreach ($kelurahanOptions as $kelurahan)
                    <flux:autocomplete.item wire:click="selectKelurahan({{ $kelurahan['id'] }}, '{{ $kelurahan['name'] }}')">
                        {{ $kelurahan['name'] }}
                    </flux:autocomplete.item>
                    @endforeach
                    @endif
                </flux:autocomplete>
                @endif

                <flux:input wire:model="kodepos_id" label="Kode Pos" />

                <flux:input wire:model="alamat_domisili" label="Alamat Domisili" required />
                <div class="grid grid-cols-2 gap-3 item-center">
                    <flux:input wire:model="domisili_rt" label="Domisili RT" required />
                    <flux:input wire:model="domisili_rw" label="Domisili RW" required />
                </div>

                <flux:field variant="inline">
                    <flux:checkbox wire:model.live="sama_domisili" />
                    <flux:label>Samakan dengan alamat yang sudah diisi</flux:label>
                </flux:field>

                @if (!$sama_domisili)
                {{-- DOMISILI PROVINSI --}}
                <flux:autocomplete
                    wire:model.live.debounce.300ms="search_domisili_prov"
                    label="Provinsi Domisili"
                    placeholder="Ketik nama provinsi...">
                    @if ($domisiliProvinsiOptions)
                    @foreach ($domisiliProvinsiOptions as $provinsi)
                    <flux:autocomplete.item wire:click="selectDomisiliProvinsi({{ $provinsi['id'] }}, '{{ $provinsi['name'] }}')">
                        {{ $provinsi['name'] }}
                    </flux:autocomplete.item>
                    @endforeach
                    @endif
                </flux:autocomplete>

                {{-- DOMISILI KABUPATEN --}}
                @if ($domisili_prov_id)
                <flux:autocomplete
                    wire:model.live.debounce.300ms="search_domisili_kab"
                    label="Kabupaten/Kota Domisili"
                    placeholder="Ketik nama kabupaten...">
                    @if ($domisiliKabupatenOptions)
                    @foreach ($domisiliKabupatenOptions as $kabupaten)
                    <flux:autocomplete.item wire:click="selectDomisiliKabupaten({{ $kabupaten['id'] }}, '{{ $kabupaten['name'] }}')">
                        {{ $kabupaten['name'] }}
                    </flux:autocomplete.item>
                    @endforeach
                    @endif
                </flux:autocomplete>
                @endif

                {{-- DOMISILI KECAMATAN --}}
                @if ($domisili_kab_id)
                <flux:autocomplete
                    wire:model.live.debounce.300ms="search_domisili_kec"
                    label="Kecamatan Domisili"
                    placeholder="Ketik nama kecamatan...">
                    @if ($domisiliKecamatanOptions)
                    @foreach ($domisiliKecamatanOptions as $kecamatan)
                    <flux:autocomplete.item wire:click="selectDomisiliKecamatan({{ $kecamatan['id'] }}, '{{ $kecamatan['name'] }}')">
                        {{ $kecamatan['name'] }}
                    </flux:autocomplete.item>
                    @endforeach
                    @endif
                </flux:autocomplete>
                @endif

                {{-- DOMISILI KELURAHAN --}}
                @if ($domisili_kec_id)
                <flux:autocomplete
                    wire:model.live.debounce.300ms="search_domisili_kel"
                    label="Kelurahan/Desa Domisili"
                    placeholder="Ketik nama kelurahan...">
                    @if ($domisiliKelurahanOptions)
                    @foreach ($domisiliKelurahanOptions as $kelurahan)
                    <flux:autocomplete.item wire:click="selectDomisiliKelurahan({{ $kelurahan['id'] }}, '{{ $kelurahan['name'] }}')">
                        {{ $kelurahan['name'] }}
                    </flux:autocomplete.item>
                    @endforeach
                    @endif
                </flux:autocomplete>
                @endif

                @endif
                <flux:input wire:model="domisili_kodepos" label="Kode Pos Domisili" required />
                <flux:input wire:model="domisili_negara" label="Negara Domisili" required />
                @endif

                <div class="flex justify-between">
                    <div class="flex gap-3">
                        @if ($halaman === 1)
                        <flux:button wire:click="next" wire:key="btn-next-1" class="hover:cursor-pointer">Selanjutnya</flux:button>
                        @elseif ($halaman === 2)
                        <flux:button wire:click="back" wire:key="btn-back-2" class="hover:cursor-pointer">Sebelumnya</flux:button>
                        @endif
                    </div>
                    <div class="flex justify-end gap-2">
                        <flux:modal.close>
                            <flux:button variant="ghost">Batal</flux:button>
                        </flux:modal.close>
    
                        @if ($halaman === 2)
                        <flux:button wire:click="save" variant="primary">Simpan</flux:button>
                        @endif
                    </div>
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