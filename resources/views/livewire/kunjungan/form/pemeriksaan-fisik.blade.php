<div class="space-y-4">

    {{-- Baris 1: Upload Gambar Anatomi + Autocomplete Tingkat Kesadaran --}}
    <div class="grid grid-cols-3 gap-4 items-start">
        {{-- Kolom 1: Upload Gambar --}}
        <div class="space-y-2">
            <label class="block text-sm font-medium">Gambar Anatomi Tubuh</label>

            <div class="flex items-center gap-2">
                <flux:input type="file" wire:model="gambar_anatomitubuh" label="" accept="image/*" />

                @if(!empty($form['gambar_anatomitubuh']))
                <a href="{{ Storage::url($form['gambar_anatomitubuh']) }}" target="_blank"
                    class="text-blue-500 underline text-xs truncate max-w-[160px]">
                    {{ basename($form['gambar_anatomitubuh']) }}
                </a>
                @endif
            </div>

            {{-- Preview gambar --}}
            @if($gambar_anatomitubuh)
            <img src="{{ $gambar_anatomitubuh->temporaryUrl() }}"
                alt="Preview"
                class="mt-2 w-32 h-32 object-cover border rounded shadow" />
            @endif

            {{-- Error jika file terlalu besar --}}
            @error('gambar_anatomitubuh')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kolom 2 & 3: Autocomplete Tingkat Kesadaran --}}
        <div class="col-span-2 relative">
            <flux:input type="text"
                wire:model.live.debounce.300ms="tingkat_kesadaran"
                label="Tingkat Kesadaran"
                placeholder="Pilih tingkat kesadaran..."
                icon="magnifying-glass" />

            @if(!empty($tingkatKesadaranOptions))
            <ul class="absolute bg-white border rounded w-full max-h-40 overflow-auto shadow z-10 mt-1">
                @foreach($tingkatKesadaranOptions as $option)
                <li class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer"
                    wire:click="selectTingkatKesadaran({{ $option['id'] }}, '{{ addslashes($option['keterangan']) }}')">
                    {{ $option['keterangan'] }}
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>

    {{-- Baris 2: Denyut Jantung & Pernapasan --}}
    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="form.denyut_jantung" label="Denyut Jantung" type="number" />
        <flux:input wire:model="form.pernapasan" label="Pernapasan" type="number" />
    </div>

    {{-- Baris 3: Tekanan Darah & Suhu Tubuh --}}
    <div class="grid grid-cols-3 gap-2">
        <flux:input wire:model="form.sistole" label="Sistole" type="number" />
        <flux:input wire:model="form.diastole" label="Diastole" type="number" />
        <flux:input wire:model="form.suhu_tubuh" label="Suhu Tubuh" type="number" />
    </div>

    {{-- Baris 4: Textarea Pemeriksaan Fisik --}}
    <div class="grid grid-cols-3 gap-3">
        @foreach([
        'kepala','mata','telinga','hidung','rambut','bibir','gigi_geligi','lidah','langit_langit','leher',
        'tenggorokan','tonsil','dada','payudara','punggung','perut','genital','anus',
        'lengan_atas','lengan_bawah','kuku_tangan','persendian_tangan','tungkai_atas','tungkai_bawah',
        'jari_kaki','kuku_kaki','persendian_kaki'
        ] as $field)
        <flux:textarea wire:model="form.{{ $field }}" label="{{ ucwords(str_replace('_', ' ', $field)) }}" />
        @endforeach
    </div>
</div>