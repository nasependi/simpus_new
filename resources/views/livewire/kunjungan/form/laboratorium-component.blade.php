<div class="space-y-4">
    <flux:input wire:model.defer="form.nomor_pemeriksaan" label="Nomor Permintaan" required />

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggal_permintaan" label="Tanggal Permintaan" required />
        <flux:input type="time" wire:model.defer="form.jam_permintaan" label="Jam Permintaan" required />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model.defer="form.dokter_pengirim" label="Dokter Pengirim" required />
        <div>
            <label class="block text-sm font-medium mb-1 text-muted-foreground">No. Telepon Dokter</label>
            <div class="flex items-center border rounded-md px-3 py-2 shadow-sm">
                <span class="text-msm text-zinc-500 mr-2 select-none">+62</span>
                <input
                    type="text"
                    class="flex-1 bg-transparent outline-none border-none text-sm text-black dark:text-white ml-2"
                    placeholder="Contoh: 81234567890"
                    wire:model.defer="form.nomor_telepon_dokter_input" />
            </div>
        </div>

    </div>

    <div class="grid grid-cols-4 gap-2">
        <flux:input wire:model.defer="form.nama_fasilitas_pelayanan" label="Nama Fasilitas Pelayanan" required />
        <flux:input wire:model.defer="form.unit_pengirim" label="Unit Pengirim" required />
        <flux:select wire:model="form.prioritas_pemeriksaan" label="Prioritas Pemeriksaan">
            <flux:select.option value="1">CITO</flux:select.option>
            <flux:select.option value="0">NON CITO</flux:select.option>
        </flux:select>
        <flux:input wire:model.defer="form.diagnosis_masalah" label="Diagnosis/Masalah" required />
    </div>

    <flux:textarea wire:model.defer="form.catatan_permintaan" label="Catatan Permintaan" />

    <div class="grid grid-cols-2 gap-2">
        <flux:select wire:model.defer="form.metode_pengiriman" label="Metode Pengiriman" placeholder="Pilih metode pengiriman...">
            <flux:select.option>Penyerahan langsung</flux:select.option>
            <flux:select.option>Dikrim via surel</flux:select.option>
        </flux:select>
        <flux:select wire:model="form.asal_sumber_spesimen" label="Asal Sumber Spesimen Klinis">
            <flux:select.option>Darah</flux:select.option>
            <flux:select.option>Urin</flux:select.option>
            <flux:select.option>Feses</flux:select.option>
            <flux:select.option>Jaringan tubuh</flux:select.option>
        </flux:select>
    </div>

    <div class="grid grid-cols-4 gap-2">
        <flux:input wire:model.defer="form.lokasi_pengambilan_spesimen" label="Lokasi Pengambilan Spesimen" required />
        <flux:input wire:model.defer="form.jumlah_spesimen" label="Jumlah Spesimen" required />
        <flux:input wire:model.defer="form.volume_spesimen" label="Volume Spesimen" required />
        <flux:input wire:model.defer="form.metode_pengambilan_spesimen" label="Metode Pengambilan Spesimen" required />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggal_pengambilan_spesimen" label="Tanggal Pengambilan Spesimen" required />
        <flux:input type="time" wire:model.defer="form.jam_pengambilan_spesimen" label="Jam Pengambilan Spesimen" required />
    </div>

    <flux:input wire:model.defer="form.kondisi_spesimen" label="Kondisi Spesimen" required />
    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggal_fiksasi_spesimen" label="Tanggal Fiksasi Spesimen" />
        <flux:input type="time" wire:model.defer="form.jam_fiksasi_spesimen" label="Jam Fiksasi Spesimen" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model.defer="form.cairan_fiksasi" label="Cairan Fiksasi" />
        <flux:input wire:model.defer="form.volume_cairan_fiksasi" label="Volume Cairan Fiksasi" />
    </div>

    <div class="grid grid-cols-4 gap-2">
        <flux:input wire:model.defer="form.petugas_mengambil_spesimen" label="Petugas Mengambil Spesimen" />
        <flux:input wire:model.defer="form.petugas_mengantarkan_spesimen" label="Petugas Mengantarkan Spesimen" />
        <flux:input wire:model.defer="form.petugas_menerima_spesimen" label="Petugas Menerima Spesimen" />
        <flux:input wire:model.defer="form.petugas_menganalisis_spesimen" label="Petugas Menganalisis Spesimen" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model.defer="form.tanggal_pemeriksaan_spesimen" label="Tanggal Pemeriksaan Spesimen" />
        <flux:input type="time" wire:model.defer="form.jam_pemeriksaan_spesimen" label="Jam Pemeriksaan Spesimen" />
    </div>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-2">
            <flux:autocomplete
                wire:model="currentExam"
                label="Nama Pemeriksaan"
                placeholder="Ketik atau pilih pemeriksaan..."
                clearable>
                @foreach ($pemeriksaanLab as $lab)
                <flux:autocomplete.item value="{{ $lab->nama }}">
                    {{ $lab->nama }}
                </flux:autocomplete.item>
                @endforeach
            </flux:autocomplete>

            <flux:button
                wire:click="addExam"
                class="w-full"
                variant="primary"
                @disabled="empty($currentExam)">
                Tambah Pemeriksaan
            </flux:button>
        </div>
    </div>
    @if(count($selectedExams) > 0)
    <div class="border rounded-lg p-4 bg-white dark:bg-neutral-900 shadow-sm transition-all duration-300">
        <flux:heading size="sm" class="mb-3 text-gray-800 dark:text-gray-200">Daftar Pemeriksaan yang Dipilih</flux:heading>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-neutral-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300">
                        <th class="px-3 py-2 text-left">No</th>
                        <th class="px-3 py-2 text-left">Nama Pemeriksaan</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedExams as $index => $exam)
                    <tr class="border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                        <td class="px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2 font-medium">{{ $exam }}</td>
                        <td class="px-3 py-2 text-center">
                            <flux:button
                                wire:click="removeExam({{ $index }})"
                                size="xs"
                                variant="primary"
                                icon="x-mark"
                                class="transition-transform hover:scale-105" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="pt-4">
        <flux:button wire:click="saveAll" class="w-full" variant="primary">
            Simpan
        </flux:button>
    </div>
</div>