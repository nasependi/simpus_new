<div wire:poll.10s class="px-4 sm:px-6 pt-2 pb-4 sm:pb-6">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-neutral-800 dark:text-neutral-100">Dashboard</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Selamat datang di Sistem Informasi Manajemen Puskesmas</p>
    </div>

    {{-- Statistik Cards with Soft Colors --}}
    <div class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        @php
        $cards = [
        [
        'title' => 'Pasien Baru Hari Ini',
        'value' => number_format($totalPasien),
        'icon' => 'user-group',
        'color' => 'blue',
        'bgClass' => 'bg-blue-50 dark:bg-blue-900/20',
        'iconBgClass' => 'bg-blue-100 dark:bg-blue-900/40',
        'iconClass' => 'text-blue-600 dark:text-blue-400',
        'valueClass' => 'text-blue-700 dark:text-blue-400',
        'description' => 'Pasien terdaftar hari ini'
        ],
        [
        'title' => 'Kunjungan Hari Ini',
        'value' => $kunjunganHariIni,
        'icon' => 'clipboard-document-list',
        'color' => 'emerald',
        'bgClass' => 'bg-emerald-50 dark:bg-emerald-900/20',
        'iconBgClass' => 'bg-emerald-100 dark:bg-emerald-900/40',
        'iconClass' => 'text-emerald-600 dark:text-emerald-400',
        'valueClass' => 'text-emerald-700 dark:text-emerald-400',
        'description' => 'Total kunjungan hari ini'
        ],
        [
        'title' => 'Pembelian Obat Hari Ini',
        'value' => $stokObatMenipis,
        'icon' => 'shopping-cart',
        'color' => 'amber',
        'bgClass' => 'bg-amber-50 dark:bg-amber-900/20',
        'iconBgClass' => 'bg-amber-100 dark:bg-amber-900/40',
        'iconClass' => 'text-amber-600 dark:text-amber-400',
        'valueClass' => 'text-amber-700 dark:text-amber-400',
        'description' => 'Transaksi pembelian obat'
        ],
        [
        'title' => 'Antrian Saat Ini',
        'value' => $antrianSaatIni,
        'icon' => 'queue-list',
        'color' => 'purple',
        'bgClass' => 'bg-purple-50 dark:bg-purple-900/20',
        'iconBgClass' => 'bg-purple-100 dark:bg-purple-900/40',
        'iconClass' => 'text-purple-600 dark:text-purple-400',
        'valueClass' => 'text-purple-700 dark:text-purple-400',
        'description' => 'Antrian saat ini'
        ],
        ];
        @endphp

        @foreach ($cards as $card)
        <flux:card class="card-improved {{ $card['bgClass'] }} !border-{{ $card['color'] }}-200 dark:!border-{{ $card['color'] }}-800">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wide mb-2">
                        {{ $card['title'] }}
                    </p>
                    <p class="text-2xl sm:text-3xl font-bold {{ $card['valueClass'] }} mb-1">
                        {{ $card['value'] }}
                    </p>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        {{ $card['description'] }}
                    </p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-xl {{ $card['iconBgClass'] }} shadow-sm">
                        <flux:icon icon="{{ $card['icon'] }}" class="size-6 sm:size-7 {{ $card['iconClass'] }}" />
                    </div>
                </div>
            </div>
        </flux:card>
        @endforeach
    </div>

    {{-- Charts Section --}}
    <div class="grid gap-4 sm:gap-6 lg:grid-cols-2 mb-8">
        <flux:card class="card-improved p-5 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold text-neutral-800 dark:text-neutral-100 mb-4">Trend Kunjungan</h2>
            <div class="h-64 sm:h-80" wire:ignore>
                <canvas id="chartKunjungan"></canvas>
            </div>
        </flux:card>

        <flux:card class="card-improved p-5 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold text-neutral-800 dark:text-neutral-100 mb-4">Distribusi Poli</h2>
            <div class="h-64 sm:h-80" wire:ignore>
                <canvas id="chartPoli"></canvas>
            </div>
        </flux:card>
    </div>

    {{-- Recent Activity --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg sm:text-xl font-bold text-neutral-800 dark:text-neutral-100">Aktivitas Terbaru</h2>
        </div>

        <flux:card class="card-improved divide-y divide-neutral-100 dark:divide-neutral-700 overflow-hidden">
            @forelse($recentActivities as $activity)
            <div class="p-4 flex items-center gap-3 sm:gap-4 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-all duration-200 cursor-pointer">
                <div class="flex-shrink-0">
                    <flux:avatar
                        size="md"
                        src="{{ $activity['avatar'] }}"
                        alt="{{ $activity['name'] }}" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-neutral-900 dark:text-neutral-100 truncate">
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $activity['name'] }}</span>
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