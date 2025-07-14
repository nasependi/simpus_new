<div class="space-y-4">
    <div class="grid grid-cols-3 gap-3">
        <flux:input wire:model.defer="diagnosis_awal" label="Diagnosis Awal" />
        <flux:input wire:model.defer="diagnosis_primer" label="Diagnosis Primer" />
        <flux:input wire:model.defer="diagnosis_sekunder" label="Diagnosis Sekunder" />
    </div>

    <flux:button wire:click="save" variant="primary" class="w-full">Tambah Diagnosis</flux:button>

    @if (!empty($diagnosisList))
    <div class="mt-6 bg-white dark:bg-gray-900 border rounded shadow-sm p-4">
        <h4 class="font-semibold text-lg mb-4 text-gray-800 dark:text-white">Daftar Diagnosis</h4>

        <div class="overflow-x-auto rounded">
            <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-800 text-left">
                    <tr class="border-b dark:border-gray-700">
                        <th class="px-4 py-2">Diagnosis Awal</th>
                        <th class="px-4 py-2">Diagnosis Primer</th>
                        <th class="px-4 py-2">Diagnosis Sekunder</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @foreach ($diagnosisList as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
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
    </div>
    @endif

</div>