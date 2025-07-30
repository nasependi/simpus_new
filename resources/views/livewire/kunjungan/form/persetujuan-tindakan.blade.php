<div class="space-y-3">

    <div class="grid grid-cols-2 gap-2 items-center">
        <flux:input wire:model="state.nama_dokter" label="Nama Dokter" />
        <flux:input wire:model="state.nama_petugas_mendampingi" label="Petugas Mendampingi" />
    </div>
    <flux:input wire:model="state.nama_keluarga_pasien" label="Keluarga Pasien" />
    <div class="grid grid-cols-2 gap-2 items-center">
        <flux:input wire:model="state.tindakan_dilakukan" label="Tindakan Dilakukan" />
        <flux:input wire:model="state.konsekuensi_tindakan" label="Konsekuensi Tindakan" />
    </div>
    <div class="grid grid-cols-2 gap-2 items-center">
        <flux:input wire:model="state.tanggal_tindakan" type="date" label="Tanggal Tindakan" />
        <flux:input wire:model="state.jam_tindakan" type="time" label="Jam Tindakan" />
    </div>
    <div class="grid grid-cols-2 gap-2 justify-items-between">
        {{-- TTD Dokter --}}
        <div x-data="signaturePad(@entangle('state.ttd_dokter'))" class="w-full">
            <label class="block text-sm font-medium mb-1">Tanda Tangan Dokter</label>
            <div>
                <canvas x-ref="signature_canvas" class="border rounded shadow touch-none"></canvas>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <button type="button" class="text-sm text-green-600 hover:underline" @click="download()">Simpan</button>
                <button type="button" class="text-sm text-red-600 hover:underline" @click="clear()">Clear</button>
            </div>
        </div>

        {{-- TTD Pasien/Keluarga --}}
        <div x-data="signaturePad(@entangle('state.ttd_pasien_keluarga'))" class="w-full">
            <label class="block text-sm font-medium mb-1">Tanda Tangan Pasien / Keluarga</label>
            <div>
                <canvas x-ref="signature_canvas" class="border rounded shadow"></canvas>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <button type="button" class="text-sm text-green-600 hover:underline" @click="download()">Simpan</button>
                <button type="button" class="text-sm text-red-600 hover:underline" @click="clear()">Clear</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2 justify-items-between">
        {{-- TTD Saksi 1 --}}
        <div x-data="signaturePad(@entangle('state.saksi1'))" class="w-full">
            <label class="block text-sm font-medium mb-1">Tanda Tangan Saksi 1</label>
            <div>
                <canvas x-ref="signature_canvas" class="border rounded shadow touch-none"></canvas>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <button type="button" class="text-sm text-green-600 hover:underline" @click="download()">Simpan</button>
                <button type="button" class="text-sm text-red-600 hover:underline" @click="clear()">Clear</button>
            </div>
        </div>

        {{-- TTD Saksi --}}
        <div x-data="signaturePad(@entangle('state.saksi2'))" class="w-full">
            <label class="block text-sm font-medium mb-1">Tanda Tangan Saksi 2</label>
            <div>
                <canvas x-ref="signature_canvas" class="border rounded shadow"></canvas>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <button type="button" class="text-sm text-green-600 hover:underline" @click="download()">Simpan</button>
                <button type="button" class="text-sm text-red-600 hover:underline" @click="clear()">Clear</button>
            </div>
        </div>
    </div>

    <!-- <div class="grid grid-cols-2 gap-2 items-center">
        <flux:input wire:model="state.saksi1" label="Saksi 1" />
        <flux:input wire:model="state.saksi2" label="Saksi 2" />
    </div> -->

    <flux:select wire:model="state.persetujuan_penolakan" label="Persetujuan / Penolakan" required>
        <option value="">-- Pilih --</option>
        <option value="1">Setuju</option>
        <option value="0">Tolak</option>
    </flux:select>
    @error('state.persetujuan_penolakan')
    <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror
</div>