<div class="space-y-4">
    <!-- <flux:textarea
        label="Order notes"
        placeholder="No lettuce, tomato, or onion..." /> -->

    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="state.petugas" label="Petugas" />
        <flux:input type="date" wire:model="state.tanggal_pelaksanaan_tindakan" label="Tanggal Pelaksanaan" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:input type="time" wire:model="state.jam_mulai_tindakan" label="Jam Mulai" />
        <flux:input type="time" wire:model="state.jam_selesai_tindakan" label="Jam Selesai" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <flux:textarea wire:model="state.alat_medis" label="Alat Medis" />
        <flux:textarea wire:model="state.bmhp" label="BMHP" />
    </div>

    <div class="flex flex-row gap-3">
        <div class="basis-2/3">
            <flux:autocomplete
                wire:model="currentTindakan"
                placeholder="Ketik atau pilih tindakan..."
                clearable>
                @foreach ($pemeriksaanTindakanList as $tindakan)
                <flux:autocomplete.item value="{{ $tindakan->nama }}">
                    {{ $tindakan->nama }}
                </flux:autocomplete.item>
                @endforeach
            </flux:autocomplete>
        </div>
        <div class="basis-1/2">
            <flux:button
                wire:click="addTindakan"
                class="w-full"
                variant="primary"
                @disabled="empty($currentTindakan)">
                Tambah Tindakan
            </flux:button>
        </div>
    </div>

    @if(count($selectedTindakan) > 0)
    <div class="border rounded-lg p-4 bg-white dark:bg-neutral-900 shadow-sm transition-all duration-300 mt-3">
        <flux:heading size="sm" class="mb-3 text-gray-800 dark:text-gray-200">
            Daftar Tindakan yang Dipilih
        </flux:heading>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-neutral-100 dark:bg-neutral-800 text-gray-700 dark:text-gray-300">
                        <th class="px-3 py-2 text-left">No</th>
                        <th class="px-3 py-2 text-left">Nama Tindakan</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedTindakan as $index => $tindakan)
                    <tr class="border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                        <td class="px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2 font-medium">{{ $tindakan }}</td>
                        <td class="px-3 py-2 text-center">
                            <flux:button
                                wire:click="removeTindakan({{ $index }})"
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
</div>