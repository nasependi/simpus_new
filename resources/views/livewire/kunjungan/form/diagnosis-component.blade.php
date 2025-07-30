<div class="space-y-4">
    <div class="grid grid-cols-3 gap-3">
        <div class="relative">
            <flux:input wire:model.live="display_awal" label="Diagnosis Awal" />
            @if ($suggestionsAwal)
            <div class="absolute bg-white z-10 border rounded w-full max-h-40 overflow-auto shadow">
                @foreach ($suggestionsAwal as $item)
                <div class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer"
                    wire:click="selectAwal('{{ addslashes($item['value']) }}')">
                    {{ $item['label'] }}
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="relative">
            <flux:input wire:model.live="display_primer" label="Diagnosis Primer" />
            @if ($suggestionsPrimer)
            <div class="absolute bg-white z-10 border rounded w-full max-h-40 overflow-auto shadow">
                @foreach ($suggestionsPrimer as $item)
                <div class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer"
                    wire:click="selectPrimer('{{ addslashes($item['value']) }}')">
                    {{ $item['label'] }}
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="relative">
            <flux:input wire:model.live="display_sekunder" label="Diagnosis Sekunder" />
            @if ($suggestionsSekunder)
            <div class="absolute bg-white z-10 border rounded w-full max-h-40 overflow-auto shadow">
                @foreach ($suggestionsSekunder as $item)
                <div class="px-4 py-2 text-black hover:bg-gray-100 cursor-pointer"
                    wire:click="selectSekunder('{{ addslashes($item['value']) }}')">
                    {{ $item['label'] }}
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <flux:button wire:click="save" variant="primary" class="w-full">Tambah Diagnosis</flux:button>

    @if (!empty($diagnosisList))
    <div class="mt-6 bg-white dark:bg-gray-900 border rounded shadow-sm p-4">
        <h4 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">Daftar Diagnosis</h4>
        <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-800 text-left">
                <tr>
                    <th class="px-4 py-2">Diagnosis Awal</th>
                    <th class="px-4 py-2">Diagnosis Primer</th>
                    <th class="px-4 py-2">Diagnosis Sekunder</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @foreach ($diagnosisList as $item)
                <tr>
                    <td class="px-4 py-2">{{ $item['diagnosis_awal'] }}</td>
                    <td class="px-4 py-2">{{ $item['diagnosis_primer'] }}</td>
                    <td class="px-4 py-2">{{ $item['diagnosis_sekunder'] }}</td>
                    <td class="px-4 py-2">
                        <flux:button wire:click="delete({{ $item['id'] }})" size="sm" variant="danger">Hapus</flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>