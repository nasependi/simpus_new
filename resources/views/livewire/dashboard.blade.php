<div wire:poll.10s>
    {{-- Statistik Cards with Hover Effects --}}
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $cards = [
                [
                    'title' => 'Pasien Baru Hari Ini',
                    'value' => number_format($totalPasien),
                    'icon' => 'users',
                    'color' => 'blue',
                    'description' => 'Pasien terdaftar hari ini'
                ],
                [
                    'title' => 'Kunjungan Hari Ini',
                    'value' => $kunjunganHariIni,
                    'icon' => 'clipboard-list',
                    'color' => 'green',
                    'description' => 'Total kunjungan hari ini'
                ],
                [
                    'title' => 'Pembelian Obat Hari Ini',
                    'value' => $stokObatMenipis,
                    'icon' => 'medical-cross',
                    'color' => 'yellow',
                    'description' => 'Transaksi pembelian obat'
                ],
                [
                    'title' => 'Antrian Saat Ini',
                    'value' => $antrianSaatIni,
                    'icon' => 'queue-list',
                    'color' => 'purple',
                    'description' => 'Antrian saat ini'
                ],
            ];
        @endphp

        @foreach ($cards as $card)
        <flux:card
            size="sm"
            class="p-6 bg-gradient-to-br from-white to-neutral-50 dark:from-neutral-800 dark:to-neutral-900
                   rounded-2xl shadow-sm border border-neutral-200/50 dark:border-neutral-700/50
                   transform transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div>
                    <flux:text class="text-sm font-semibold text-neutral-500 dark:text-neutral-400 tracking-wide uppercase">
                        {{ $card['title'] }}
                    </flux:text>
                    <flux:heading size="xl" class="text-xl mt-2 font-extrabold text-{{ $card['color'] }}-600">
                        {{ $card['value'] }}
                    </flux:heading>
                </div>
                <div
                    class="flex items-center justify-center w-12 h-12 rounded-xl bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900/30 shadow-inner">
                    <flux:icon.{{ $card['icon'] }}
                        class="size-6 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400" />
                </div>
            </div>

            <div class="mt-5">
                <span class="text-sm text-neutral-600 dark:text-neutral-400">
                    {{ $card['description'] }}
                </span>
            </div>
        </flux:card>
        @endforeach
    </div>


    {{-- Charts Section --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <flux:card class="p-5">
            <flux:heading size="lg" class="mb-4">Trend Kunjungan</flux:heading>
            <div class="h-80" wire:ignore>
                <canvas id="chartKunjungan"></canvas>
            </div>
        </flux:card>

        <flux:card class="p-5">
            <flux:heading size="lg" class="mb-4">Distribusi Poli</flux:heading>
            <div class="h-80" wire:ignore>
                <canvas id="chartPoli"></canvas>
            </div>
        </flux:card>
    </div>

    {{-- Recent Activity with Live Updates --}}
    <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">Aktivitas Terbaru</flux:heading>
        </div>

        <flux:card class="divide-y dark:divide-neutral-700 shadow-md rounded-2xl overflow-hidden">
            @forelse($recentActivities as $activity)
            <div class="p-4 flex items-center gap-4 hover:bg-blue-50 dark:hover:bg-neutral-800/50 transition-all duration-300 cursor-pointer">
                <div class="flex-shrink-0">
                    <flux:avatar
                        size="md"
                        src="{{ $activity['avatar'] }}"
                        alt="{{ $activity['name'] }}" />
                </div>
                <div class="flex-1">
                    <p class="text-sm text-neutral-900 dark:text-neutral-100">
                        <span class="font-medium text-blue-600 dark:text-blue-400">{{ $activity['name'] }}</span>
                        {{ $activity['description'] }}
                    </p>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 flex items-center gap-1 mt-1">
                        <x-icon name="clock" class="w-3 h-3" />
                        {{ $activity['time'] }}
                    </p>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <p class="text-neutral-500 dark:text-neutral-400">Belum ada aktivitas terbaru</p>
            </div>
            @endforelse
        </flux:card>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>

@script
<script>
    initCharts();

    function initCharts() {
        // Trend Kunjungan Chart
        const ctx1 = document.getElementById('chartKunjungan');
        if (ctx1) {
            // Destroy existing chart if it exists
            const existingChart1 = Chart.getChart(ctx1);
            if (existingChart1) {
                existingChart1.destroy();
            }

            new Chart(ctx1.getContext('2d'), {
                type: 'line',
                data: {
                    labels: $wire.trendKunjunganLabels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: $wire.trendKunjunganData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Distribusi Poli Chart
        const ctx2 = document.getElementById('chartPoli');
        if (ctx2) {
            // Destroy existing chart if it exists
            const existingChart2 = Chart.getChart(ctx2);
            if (existingChart2) {
                existingChart2.destroy();
            }

            new Chart(ctx2.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: $wire.distribusiPoliLabels,
                    datasets: [{
                        data: $wire.distribusiPoliData,
                        backgroundColor: $wire.distribusiPoliColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        }
                    }
                }
            });
        }
    }
</script>
@endscript
