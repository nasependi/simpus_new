<div class="space-y-4">
    <flux:select wire:model="state.nama_pemeriksaan" label="Nama Pemeriksaan" placeholder="Pilih nama pemeriksaan...">
        <flux:select.option>X-ray</flux:select.option>
        <flux:select.option> CT Scan</flux:select.option>
        <flux:select.option>USG</flux:select.option>
        <flux:select.option>MRI</flux:select.option>
        <flux:select.option>Other</flux:select.option>
    </flux:select>
    <div class="grid grid-cols-2 gap-2">
        <flux:select wire:model="state.jenis_pemeriksaan" label="Jenis Pemeriksaan" placeholder="Pilih jenis...">
            @foreach ($listJenisPemeriksaanRadiologi as $item)
            <flux:select.option value="{{ $item->kode }}">
                {{ $item->kode }} - {{ $item->nama }}
            </flux:select.option>
            @endforeach
        </flux:select>
        <flux:input wire:model="state.nomor_pemeriksaan" label="Nomor Pemeriksaan" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model="state.tanggal_permintaan" label="Tanggal Permintaan" />
        <flux:input type="time" wire:model="state.jam_permintaan" label="Jam Permintaan" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="state.dokter_pengirim" label="Dokter Pengirim" />
        <div class="space-y-1">
            <label class="block text-sm font-medium text-white">No. Telepon Dokter</label>
            <div class="flex items-center border rounded-md px-3 py-2">
                <span class="text-sm text-zinc-500 select-none">+62</span>
                <input
                    type="text"
                    wire:model="state.nomor_telepon_dokter"
                    class="flex-1 bg-transparent outline-none border-none text-sm text-black dark:text-white ml-2"
                    placeholder="81234567890" />
            </div>
            @error('state.nomor_telepon_dokter')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="state.nama_fasilitas_radiologi" label="Nama Fasilitas Radiologi" />
        <flux:input wire:model="state.unit_pengirim_radiologi" label="Unit Pengirim" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="state.prioritas_pemeriksaan_radiologi" label="Prioritas Pemeriksaan" />
        <flux:input wire:model="state.diagnosis_kerja" label="Diagnosis Kerja" />
    </div>

    <flux:textarea wire:model="state.catatan_permintaan" label="Catatan Permintaan" />

    <div class="grid grid-cols-2 gap-2">
        <flux:select wire:model="state.metode_penyampaian_pemeriksaan" label="Metode Penyampaian Pemeriksaan">
            <option value="langsung">Penyerahan langsung (digital/cetak foto)</option>
            <option value="surel">Dikirim via surel</option>
        </flux:select>
        <flux:select wire:model="state.status_alergi" label="Status Alergi">
            <option value="">Ya</option>
            <option value="0">Tidak</option>
        </flux:select>
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="state.status_kehamilan" label="Status Kehamilan" />
        <flux:input wire:model="state.jenis_bahan_kontras" label="Jenis Bahan Kontras" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="date" wire:model="state.tanggal_pemeriksaan" label="Tanggal Pemeriksaan" />
        <flux:input type="time" wire:model="state.jam_pemeriksaan" label="Jam Pemeriksaan" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <div>
            <label class="block text-sm font-medium text-white mb-1">Foto Hasil</label>

            <div class="flex items-center gap-2">
                <flux:input type="file" wire:model="attachments" label="" multiple />
                {{-- Tampilkan file sebelumnya jika ada --}}
                @if (!empty($state['foto_hasil']))
                @php $foto = json_decode($state['foto_hasil'], true)[0] ?? null; @endphp
                @if ($foto)
                <a href="{{ Storage::url($foto) }}" target="_blank" class="text-blue-500 underline text-xs truncate max-w-[300px]">
                    {{ basename($foto) }}
                </a>
                @endif
                @endif
            </div>

            @error('attachments.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>


        <flux:input wire:model="state.nama_dokter_pemeriksaan" label="Nama Dokter Pemeriksa" />
    </div>

    <flux:textarea wire:model="state.interpretasi_radiologi" label="Interpretasi Radiologi" />
</div>