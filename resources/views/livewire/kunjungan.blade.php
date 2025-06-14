<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Kunjungan</flux:heading>
            <div class="flex gap-4 items-center">

            </div>
        </div>

        <flux:table :paginate="$data">
            <flux:table.columns>
                <flux:table.column class="cursor-pointer" wire:click="sortBy('tanggal_kunjungan')">
                    <div class="flex items-center">
                        <span>Tanggal Kunjungan</span>
                    </div>
                </flux:table.column>
                <flux:table.column class="cursor-pointer" wire:click="sortBy('pasien_id')">
                    <div class="flex items-center">
                        <span>Nama Pasien</span>
                    </div>
                </flux:table.column>
                <flux:table.column>Poli</flux:table.column>
                <flux:table.column>Cara Pembayaran</flux:table.column>
                <flux:table.column>Umur</flux:table.column>
                <flux:table.column>Aksi</flux:table.column>
            </flux:table.columns>
            <flux:table.columns>
                <flux:table.column>
                    <flux:input type="date" size="sm" wire:model.live="filterTanggal" placeholder="Cari berdasarkan..." />
                </flux:table.column>
                <flux:table.column>
                    <flux:input size="sm" wire:model.live="filterPasien" placeholder="Cari berdasarkan..." />
                </flux:table.column>
                <flux:table.column>
                    <flux:select wire:model.live="filterPoli" size="sm" placeholder="Pilih Poli">
                        <flux:select.option value="">Semua Poli</flux:select.option>
                        @foreach ($poliList as $id => $nama)
                            <flux:select.option value="{{ $id }}">{{ $nama }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:table.column>

                <flux:table.column>
                    <flux:select wire:model.live="filterCara" size="sm" placeholder="Pilih Cara Bayar">
                        <flux:select.option value="">Semua Cara</flux:select.option>
                        @foreach ($caraPembayaranList as $id => $nama)
                            <flux:select.option value="{{ $id }}">{{ $nama }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:table.column>

                <flux:table.column>
                    <flux:input size="sm" wire:model.live="filterUmur" placeholder="Cari berdasarkan..." />
                </flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($data as $item)
                    <flux:table.row>
                        <flux:table.cell>{{ $item->tanggal_kunjungan->format('d-m-Y') }}</flux:table.cell>
                        <flux:table.cell>{{ $item->pasien->nama_lengkap ?? '-' }}</flux:table.cell>
                        <flux:table.cell>{{ $item->poli->nama ?? '-' }}</flux:table.cell>
                        <flux:table.cell>{{ $item->caraPembayaran->nama ?? '-' }}</flux:table.cell>
                        <flux:table.cell>
                            {{ $item->umur_tahun }} th, {{ $item->umur_bulan }} bln, {{ $item->umur_hari }} hr
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($item->generalConsent)
                                <flux:button wire:click="cetakConsent({{ $item->id }})" icon="printer" label="Cetak Consent" class="mr-2" />
                                <flux:button wire:click="openModalkunjungan({{ $item->id }})" icon="user" label="Pemeriksaan" class="mr-2" />
                            @else
                                <flux:button wire:click="$dispatch('open-modal-generalconsent', { kunjungan_id: {{ $item->id }} })" icon="clipboard" label="Consent" class="mr-2" />
                            @endif

                            <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" class="mr-2" />
                            <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                        </flux:table.cell>

                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- <livewire:General-Consent /> --}}
        <flux:modal name="modalPemeriksaan" class="w-full max-w-screen-xl max-h-[80vh] overflow-y-auto p-6">
            <flux:tab.group>
                <flux:tabs wire:model="tab">
                    <flux:tab name="awal">Asasment Awal</flux:tab>
                    <flux:tab name="pemeriksaan">Pemeriksaan Specialistik</flux:tab>
                </flux:tabs>

                <flux:tab.panel name="awal">
                    @if ($kunjungan_id)
                        @livewire('anamnesis', ['kunjungan_id' => $kunjungan_id])
                        @livewire('pemeriksaan-fisik', ['kunjungan_id' => $kunjungan_id])
                        @livewire('pemeriksaan-psikologis', ['kunjungan_id' => $kunjungan_id])
                    @endif
                </flux:tab.panel>
                <flux:tab.panel name="pemeriksaan">
                    @if ($kunjungan_id)
                        @livewire('pemeriksaan-spesialistik', ['kunjungan_id' => $kunjungan_id])
                        @livewire('pemeriksaan.form-persetujuan-tindakan', ['kunjungan_id' => $kunjungan_id])
                    @endif

                    {{-- @livewire('pemeriksaan.form-persetujuan-tindakan', ['k_id' => $kunjungan_id]) --}}
                </flux:tab.panel>
            </flux:tab.group>
        </flux:modal>

        {{-- Modal Tambah/Edit --}}
        <flux:modal name="kunjunganModal" class="space-y-4 md:w-[50rem]">
            <flux:heading class="text-lg font-semibold">
                {{ $editId ? 'Edit' : 'Tambah' }} Kunjungan
            </flux:heading>

            <flux:select wire:model="pasien_id" variant="listbox" placeholder="Pilih Pasien">
                @foreach ($listPasien as $pasien)
                    <flux:select.option value="{{ $pasien->id }}">{{ $pasien->nama_lengkap }}</flux:select.option>
                @endforeach
            </flux:select>
            @error('pasien_id')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror

            <flux:select wire:model="poli_id" variant="listbox" placeholder="Pilih Poli">
                @foreach ($listPoli as $poli)
                    <flux:select.option value="{{ $poli->id }}">{{ $poli->nama }}</flux:select.option>
                @endforeach
            </flux:select>
            @error('poli_id')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror

            <flux:select wire:model="carapembayaran_id" variant="listbox" placeholder="Pilih Cara Pembayaran">
                @foreach ($listCaraPembayaran as $cara)
                    <flux:select.option value="{{ $cara->id }}">{{ $cara->nama }}</flux:select.option>
                @endforeach
            </flux:select>
            @error('carapembayaran_id')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror

            <flux:input wire:model="tanggal_kunjungan" label="Tanggal Kunjungan" type="date" required />

            <div class="grid grid-cols-3 gap-4">
                <flux:input wire:model="umur_tahun" label="Umur (Tahun)" type="number" min="0" />
                <flux:input wire:model="umur_bulan" label="Umur (Bulan)" type="number" min="0" />
                <flux:input wire:model="umur_hari" label="Umur (Hari)" type="number" min="0" />
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </flux:modal>

        {{-- Modal Konfirmasi Hapus --}}
        <flux:modal name="delete-kunjungan" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus Data Kunjungan?</flux:heading>
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
    <livewire:general-consent />
</div>
