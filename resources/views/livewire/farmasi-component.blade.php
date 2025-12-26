<div class="p-6" wire:poll.5s>
    <flux:card class="shadow-lg rounded-lg">
        <div class="flex justify-between mb-4">
            <flux:heading size="xl">Daftar Farmasi</flux:heading>
        </div>

        <flux:table :paginate="$kunjungan">
            <flux:table.columns>
                <flux:table.column>Tanggal Kunjungan</flux:table.column>
                <flux:table.column>Nama Pasien</flux:table.column>
                <flux:table.column>Nomor Rekam Medis</flux:table.column>
                <flux:table.column>Poli</flux:table.column>
                <flux:table.column>Status Resep</flux:table.column>
                <flux:table.column class="">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.columns>
                {{-- Tanggal --}}
                <flux:table.column>
                    <flux:input type="date" size="sm" wire:model.live="filterTanggal" />
                </flux:table.column>

                {{-- Nama Pasien --}}
                <flux:table.column>
                    <flux:input size="sm" wire:model.live="filterPasien" placeholder="Nama Pasien..." />
                </flux:table.column>

                {{-- No Rekam Medis --}}
                <flux:table.column>
                    <flux:input size="sm" wire:model.live="filterRekamMedis" placeholder="No Rekam Medis" />
                </flux:table.column>

                {{-- Poli --}}
                <flux:table.column>
                    <flux:select wire:model.live="filterPoli" size="sm" placeholder="Pilih Poli">
                        <flux:select.option value="">Semua Poli</flux:select.option>
                        @foreach ($poliList as $id => $nama)
                        <flux:select.option value="{{ $id }}">{{ $nama }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:table.column>

                {{-- Status Resep --}}
                <flux:table.column>
                    <flux:select wire:model.live="filterStatusResep" size="sm" placeholder="Pilih Status">
                        <flux:select.option value="">Semua</flux:select.option>
                        <flux:select.option value="pending">Pending</flux:select.option>
                        <flux:select.option value="selesai">Selesai</flux:select.option>
                    </flux:select>
                </flux:table.column>

            </flux:table.columns>

            <flux:table.rows>
                @forelse ($kunjungan as $index => $item)
                <flux:table.row>
                    <flux:table.cell>{{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('l, d F Y') }}</flux:table.cell>
                    <flux:table.cell>{{ $item->pasien->nama_lengkap ?? '-' }}</flux:table.cell>
                    <flux:table.cell>{{ $item->pasien->no_rekamedis ?? '-' }}</flux:table.cell>
                    <flux:table.cell>{{ $item->poli->nama ?? '-' }}</flux:table.cell>

                    {{-- Status Resep --}}
                    <flux:table.cell>
                        @if($item->obatResep && $item->obatResep->where('status_resep', 0)->count() > 0)
                        <flux:badge variant="secondary">-</flux:badge>
                        @else
                        <flux:badge variant="success">Sudah</flux:badge>
                        @endif
                    </flux:table.cell>

                    {{-- Aksi --}}
                    <flux:table.cell>
                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="gap-2">
                                @if ($item->obatResep && $item->obatResep->count() > 0)
                                <flux:button wire:click="showResep({{ $item->id }})" size="xs" icon="eye">
                                    Lihat Detail
                                </flux:button>
                                @else
                                <span class="text-gray-500 text-sm">Belum ada resep</span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if($item->obatResep && $item->obatResep->count() > 0)
                                <flux:button
                                    as="a"
                                    href="{{ route('resep.print', $item->id) }}"
                                    variant="outline"
                                    target="_blank"
                                    size="xs"
                                    icon="printer">
                                    Print Resep
                                </flux:button>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if($item->obatResep && $item->obatResep->count() > 0)
                                {{-- cek apakah semua status_resep sudah 1 --}}
                                @if($item->obatResep->where('status_resep', 0)->count() === 0)
                                <a href="{{ route('tiket.print', $item->id) }}" target="_blank">
                                    <flux:button variant="outline" size="xs" icon="printer">
                                        Print E-tiket
                                    </flux:button>
                                </a>
                                @endif
                                @endif
                            </div>

                        </div>
                    </flux:table.cell>
                </flux:table.row>
                @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center text-gray-500">
                        Tidak ada data
                    </flux:table.cell>
                </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $kunjungan->links() }}
        </div>

        {{-- Modal Lihat Detail Resep --}}
        <flux:modal name="resepDetailModal" class="w-full max-w-screen-xl h-[85vh] overflow-y-auto">
            <div class="p-6">

                {{-- Detail Resep Obat --}}
                @if(empty($selectedResep) || $selectedResep->count() === 0)
                <p class="text-gray-500 text-center py-6">Tidak ada resep untuk kunjungan ini.</p>
                @else
                {{-- Informasi Umum Pasien & Kunjungan --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                        <p class="text-sm text-gray-500">Nama Pasien</p>
                        <p class="font-semibold text-gray-800">
                            {{ $selectedResep->first()->kunjungan->pasien->nama_lengkap ?? '-' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                        <p class="text-sm text-gray-500">Tanggal Kunjungan</p>
                        <p class="font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($selectedResep->first()->kunjungan->tanggal_kunjungan)->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>

                {{-- Tabel Resep Obat --}}
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full text-sm border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 border">Nama Obat</th>
                                <th class="px-4 py-3 border">Jumlah</th>
                                <th class="px-4 py-2 text-left">Metode Pemberian</th>
                                <th class="px-4 py-2 text-left">Dosis yang Diberikan</th>
                                <th class="px-4 py-2 text-left">Aturan Tambahan</th>
                                <th class="px-4 py-2 text-left">Catatan Tambahan</th>
                                <th class="px-4 py-3 border">Status Resep</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($selectedResep as $obat)
                            <tr>
                                <td class="px-4 py-2 border">{{ $obat->nama_obat }}</td>
                                <td class="px-4 py-2 border">{{ $obat->jumlah_obat }}</td>
                                <td class="px-4 py-2 border">{{ $obat->metode_pemberian }}</td>
                                <td class="px-4 py-2 border">{{ $obat->dosis_diberikan }}</td>
                                <td class="px-4 py-2 border">{{ $obat->aturan_tambahan }}</td>
                                <td class="px-4 py-2 border">{{ $obat->catatan_resep }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <flux:button
                                        wire:click="updateStatusResep({{ $obat->id }})"
                                        variant="{{ $obat->status_resep == 0 ? 'outline' : 'primary' }}"
                                        size="xs"
                                        icon="arrow-path">
                                        {{ $obat->status_resep == 0 ? 'Pending' : 'Selesai' }}
                                    </flux:button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Tombol Tutup --}}
                <div class="flex justify-end mt-4">
                    <flux:modal.close>
                        <flux:button variant="ghost">Tutup</flux:button>
                    </flux:modal.close>
                </div>

            </div>
        </flux:modal>

    </flux:card>
</div>