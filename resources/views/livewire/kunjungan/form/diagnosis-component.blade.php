<div class="space-y-4">
    <div class="grid grid-cols-3 gap-3">
        <flux:input wire:model.defer="diagnosis_awal" label="Diagnosis Awal" />
        <flux:input wire:model.defer="diagnosis_primer" label="Diagnosis Primer" />
        <flux:input wire:model.defer="diagnosis_sekunder" label="Diagnosis Sekunder" />
    </div>

    <flux:button wire:click="save" variant="primary" class="w-full">Tambah Diagnosis</flux:button>

    @if (!empty($diagnosisList))
    <div class="">
        <h4 class="font-semibold mb-2">Daftar Diagnosis</h4>
        <table class="min-w-full text-sm border">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-3 py-2 border">Awal</th>
                    <th class="px-3 py-2 border">Primer</th>
                    <th class="px-3 py-2 border">Sekunder</th>
                    <th class="px-3 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($diagnosisList as $item)
                <tr>
                    <td class="px-3 py-2 border">{{ $item['diagnosis_awal'] }}</td>
                    <td class="px-3 py-2 border">{{ $item['diagnosis_primer'] }}</td>
                    <td class="px-3 py-2 border">{{ $item['diagnosis_sekunder'] }}</td>
                    <td class="px-3 py-2 border">
                        <flux:button wire:click="delete({{ $item['id'] }})" size="sm" variant="danger">Hapus</flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>