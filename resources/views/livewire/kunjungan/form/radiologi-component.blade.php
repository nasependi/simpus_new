<div class="space-y-4">
    {{-- Nama Pemeriksaan pakai Autocomplete --}}
    <flux:autocomplete
        wire:model="state.nama_pemeriksaan"
        label="Nama Pemeriksaan"
        placeholder="Ketik atau pilih nama pemeriksaan...">
        <flux:autocomplete.item value="X-ray">X-ray</flux:autocomplete.item>
        <flux:autocomplete.item value="CT Scan">CT Scan</flux:autocomplete.item>
        <flux:autocomplete.item value="USG">USG</flux:autocomplete.item>
        <flux:autocomplete.item value="MRI">MRI</flux:autocomplete.item>
        <flux:autocomplete.item value="Other">Other</flux:autocomplete.item>
    </flux:autocomplete>

    <flux:input wire:model="state.nomor_pemeriksaan" label="Nomor Pemeriksaan" />

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
        <div>
            <label class="block text-sm font-medium text-white mb-1">Foto Hasil</label>
            <div class="flex items-center gap-2">
                <flux:input type="file" wire:model="attachments" multiple />
                @if (!empty($state['foto_hasil']))
                @php $foto = json_decode($state['foto_hasil'], true)[0] ?? null; @endphp
                @if ($foto)
                <a href="{{ Storage::url($foto) }}" target="_blank"
                    class="text-blue-500 underline text-xs truncate max-w-[300px]">
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

    <div class="grid grid-cols-2 gap-2">
        <div class="space-y-4">
            <flux:autocomplete
                wire:model="currentExam"
                label="Jenis Pemeriksaan"
                placeholder="Pilih jenis pemeriksaan..."
                searchable>
                @foreach ($listJenisPemeriksaanRadiologi as $item)
                <flux:autocomplete.item value="{{ $item->kode }}">
                    {{ $item->kode }} - {{ $item->nama }}
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

    {{-- Selected Examinations Table --}}
    @if(count($selectedExams) > 0)
    <div class="border rounded-lg p-4 bg-white dark:bg-neutral-900 shadow-sm">
        <flux:heading size="sm" class="mb-2 text-gray-800 dark:text-gray-200">
            Daftar Pemeriksaan yang Dipilih
        </flux:heading>
        <div class="space-y-2">
            @foreach($selectedExams as $index => $examCode)
            @php
            $exam = $listJenisPemeriksaanRadiologi->firstWhere('kode', $examCode);
            @endphp
            <div class="flex items-center justify-between p-2 bg-neutral-50 dark:bg-neutral-800 rounded">
                <span class="text-sm">{{ $examCode }} {{ $exam->nama ?? '' }}</span>
                <flux:button
                    wire:click="removeExam({{ $index }})"
                    size="xs"
                    variant="danger"
                    icon="x-mark" />
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>