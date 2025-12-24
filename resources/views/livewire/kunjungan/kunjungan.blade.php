<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Data Kunjungan</flux:heading>
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
                <flux:table.column>Status</flux:table.column>
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
                        @if (empty($item->status))
                        <flux:badge size="sm" color="gray"></flux:badge>
                        @elseif ($item->status === 'rawat_inap')
                        <flux:badge size="sm" color="red">Rawat Inap</flux:badge>
                        @elseif ($item->status === 'rujuk')
                        <flux:badge size="sm" color="yellow">Rujuk</flux:badge>
                        @elseif ($item->status === 'pulang')
                        <flux:badge size="sm" color="green">Pulang</flux:badge>
                        @else
                        <flux:badge size="sm"></flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($item->generalConsent)
                        <flux:tooltip content="Cetak General Consent">
                            <flux:button wire:click="cetakConsent({{ $item->id }})" icon="printer" label="Cetak Consent" class="mr-2" />
                        </flux:tooltip>
                        <flux:tooltip content="Pemeriksaan">
                            <flux:button wire:click="openModalPemeriksaan({{ $item->id }})" icon="funnel" label="Pemeriksaan" class="mr-2" />
                        </flux:tooltip>
                        @else
                        <flux:tooltip content="General Consent">
                            <flux:button wire:click="openGeneralConsent({{ $item->id }})" icon="clipboard" label="Consent" class="mr-2" />
                        </flux:tooltip>
                        @endif
                        <flux:button wire:click="edit({{ $item->id }})" icon="pencil" label="Edit" class="mr-2" />
                        <flux:button wire:click="deleteConfirm({{ $item->id }})" icon="trash" label="Hapus" variant="danger" />
                    </flux:table.cell>

                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{-- <livewire:General-Consent /> --}}
        <flux:modal
            name="modalPemeriksaan"
            class="w-full max-w-screen-xl h-[80vh] overflow-y-auto"
            :dismissible="false"
            wire:ignore.self>
            <flux:tab.group>
                <flux:tabs wire:model="tab">
                    <flux:tab name="awal">Asasment Awal</flux:tab>
                    <flux:tab name="pemeriksaanS">Pemeriksaan Specialistik</flux:tab>
                </flux:tabs>
                <flux:tab.panel name="awal">
                    @if ($kunjungan_id)
                    <flux:tab.group class="outline-2 outline-zinc-600! dark:outline-zinc-500! rounded-lg p-4 my-3">
                        <flux:tabs wire:model="tab">
                            <flux:tab name="anamnesis">Anamnesis</flux:tab>
                            <flux:tab name="fisik">Pemeriksaan Fisik</flux:tab>
                            <flux:tab name="psikologis">Pemeriksaan Psikologis, Sosial Ekonomi, Spiritual</flux:tab>
                        </flux:tabs>

                        <flux:tab.panel name="anamnesis">
                            @livewire('kunjungan.form.anamnesis', ['kunjungan_id' => $kunjungan_id], key('anamnesis-'.$kunjungan_id))
                        </flux:tab.panel>
                        <flux:tab.panel name="fisik">
                            @livewire('kunjungan.form.pemeriksaan-fisik', ['kunjungan_id' => $kunjungan_id], key('fisik-'.$kunjungan_id))
                        </flux:tab.panel>
                        <flux:tab.panel name="psikologis">
                            @livewire('kunjungan.form.pemeriksaan-psikologis', ['kunjungan_id' => $kunjungan_id], key('psikologis-'.$kunjungan_id))
                        </flux:tab.panel>
                    </flux:tab.group>
                    @endif
                </flux:tab.panel>
                <flux:tab.panel name="pemeriksaanS">
                    @if ($kunjungan_id)
                    <flux:tab.group class="outline-2 outline-zinc-600! dark:outline-zinc-500! rounded-lg p-4 my-3">
                        <flux:tabs wire:model="tab2">
                            <flux:tab name="spesialistik">Riwayat Penggunaan Obat</flux:tab>
                            <flux:tab name="account">Laboratorium</flux:tab>
                            <flux:tab name="billing">Radiologi</flux:tab>
                            <flux:tab name="diagnosis">Diagnosis</flux:tab>
                            <flux:tab name="profile">Penolakan Tindakan</flux:tab>
                            <flux:tab name="terapi">Tindakan</flux:tab>
                            <flux:tab name="obat">Resep Obat</flux:tab>
                        </flux:tabs>

                        <flux:tab.panel name="spesialistik" wire:key="panel-spesialistik-{{ $kunjungan_id }}">
                            <livewire:kunjungan.form.pemeriksaan-spesialistik
                                :kunjungan_id="$kunjungan_id"
                                wire:key="spesialistik-{{ $kunjungan_id }}" />
                        </flux:tab.panel>

                        <flux:tab.panel name="profile" wire:key="panel-profile-{{ $kunjungan_id }}">
                            <livewire:kunjungan.form.persetujuan-tindakan
                                :kunjungan_id="$kunjungan_id"
                                wire:key="tindakan-{{ $kunjungan_id }}" />
                        </flux:tab.panel>

                        <flux:tab.panel name="account" wire:key="panel-account-{{ $kunjungan_id }}">
                            <livewire:kunjungan.form.laboratorium-component
                                :kunjungan_id="$kunjungan_id"
                                wire:key="laboratorium-{{ $kunjungan_id }}" />
                        </flux:tab.panel>

                        <flux:tab.panel name="billing" wire:key="panel-billing-{{ $kunjungan_id }}">
                            <livewire:kunjungan.form.radiologi-component
                                :kunjungan_id="$kunjungan_id"
                                wire:key="radiologi-{{ $kunjungan_id }}" />
                        </flux:tab.panel>

                        <flux:tab.panel name="diagnosis" wire:key="panel-diagnosis-{{ $kunjungan_id }}">
                            <livewire:kunjungan.form.diagnosis-component
                                :kunjungan_id="$kunjungan_id"
                                wire:key="diagnosis-{{ $kunjungan_id }}" />
                        </flux:tab.panel>

                        <flux:tab.panel name="terapi" wire:key="panel-terapi-{{ $kunjungan_id }}">
                            <livewire:kunjungan.form.terapi-component
                                :kunjungan_id="$kunjungan_id"
                                wire:key="terapi-{{ $kunjungan_id }}" />
                        </flux:tab.panel>

                        <flux:tab.panel name="obat" wire:key="panel-obat-{{ $kunjungan_id }}">
                            <livewire:kunjungan.form.obat-resep-component
                                :kunjungan_id="$kunjungan_id"
                                wire:key="obat-{{ $kunjungan_id }}" />
                        </flux:tab.panel>

                    </flux:tab.group>
                    @endif
                </flux:tab.panel>
                
                {{-- Status Selection --}}
                <!-- <div class="border-t pt-4 mt-4">
                    <flux:select wire:model="status_kunjungan" label="Status Pasien (Opsional)" placeholder="Pilih status jika perlu rujuk/rawat inap">
                        <option value="">Rawat Jalan (Normal)</option>
                        <option value="rujuk">Rujuk ke RS Lain</option>
                        <option value="rawat_inap">Rawat Inap</option>
                    </flux:select>
                    <flux:text class="text-sm text-zinc-500 mt-1">
                        * Biarkan kosong untuk rawat jalan normal. Status akan otomatis "Pulang" setelah farmasi memberikan obat.
                    </flux:text>
                </div> -->
                
                <div class="flex justify-between">
                    <flux:modal.close>
                        <flux:button variant="ghost" variant="filled" class="text-zinc-700! hover:text-zinc-900! dark:text-zinc-300! dark:hover:text-white!">Kembali</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click.prevent="saveAll" onclick="syncAndSave()" variant="primary">Simpan Semua</flux:button>
                </div>
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

        <flux:modal name="statusModal" class="min-w-[25rem]">
            <flux:heading size="lg">Ubah Status Pasien</flux:heading>

            <flux:radio.group wire:model="status" label="Pilih Status Pasien">
                <flux:radio value="rawat_inap" label="Rawat Inap" />
                <flux:radio value="rujuk" label="Rujuk" />
                <flux:radio value="pulang" label="Pulang" />
            </flux:radio.group>
            @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            <div class="flex justify-end gap-2 mt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="updateStatus" variant="primary">Simpan</flux:button>
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
    <livewire:kunjungan.modal.general-consent />

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        function syncAndSave() {
            const event = new Event('sync-all-signatures');
            document.dispatchEvent(event);
            setTimeout(() => {
                Livewire.dispatch('syncSignaturesDone');
            }, 100); // Delay untuk memastikan sync() selesai
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('signaturePad', (value) => ({
                signaturePadInstance: null,
                value: value,
                init() {
                    this.signaturePadInstance = new SignaturePad(this.$refs.signature_canvas);

                    this.signaturePadInstance.addEventListener("endStroke", () => {
                        this.sync();
                    });

                    if (this.value) {
                        const img = new Image();
                        img.onload = () => {
                            const ctx = this.$refs.signature_canvas.getContext("2d");
                            ctx.clearRect(0, 0, this.$refs.signature_canvas.width, this.$refs.signature_canvas.height);
                            ctx.drawImage(img, 0, 0);
                        };
                        img.src = this.value;
                    }
                },
                sync() {
                    this.value = this.signaturePadInstance.toDataURL('image/png');
                },
                clear() {
                    this.signaturePadInstance.clear();
                    this.value = null;
                }
            }));
        });

        document.addEventListener('sync-all-signatures', () => {
            document.querySelectorAll('[x-data^="signaturePad"]').forEach(el => {
                if (el.__x && el.__x.$data?.sync) {
                    el.__x.$data.sync();
                }
            });
        });
    </script>

</div>