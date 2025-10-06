<x-layouts.app :title="__('Dashboard')">
    {{-- Header with Animation --}}
    <!-- <div class="mb-6 animate-fade-in">
        <flux:heading size="2xl" class="font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
            Dashboard
        </flux:heading>
        <flux:text class="mt-2 text-neutral-600 dark:text-neutral-400 animate-fade-in-up">
            Selamat datang kembali <span class="animate-wave inline-block">👋</span>, berikut ringkasan datamu hari ini.
        </flux:text>
    </div> -->

    {{-- Statistik Cards with Hover Effects --}}
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
        [
        'title' => 'Total Pasien',
        'value' => '1,245',
        'percent' => '+8%',
        'icon' => 'users',
        'color' => 'blue',
        'description' => 'dari bulan lalu'
        ],
        [
        'title' => 'Kunjungan Hari Ini',
        'value' => '32',
        'percent' => '-5%',
        'icon' => 'clipboard-list',
        'color' => 'green',
        'description' => 'dibanding kemarin'
        ],
        [
        'title' => 'Stok Obat Menipis',
        'value' => '5',
        'icon' => 'medical-cross',
        'color' => 'yellow',
        'description' => 'perlu diperhatikan'
        ],
        [
        'title' => 'Antrian Saat Ini',
        'value' => '7',
        'icon' => 'queue-list',
        'color' => 'purple',
        'description' => 'sedang menunggu'
        ],
        ] as $card)
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

            @if(isset($card['percent']))
            <div class="mt-5 flex items-center gap-2">
                <flux:badge
                    variant="{{ str_starts_with($card['percent'], '+') ? 'success' : 'danger' }}"
                    size="sm"
                    class="font-medium">
                    {{ $card['percent'] }}
                </flux:badge>
                <span class="text-sm text-neutral-600 dark:text-neutral-400">
                    {{ $card['description'] }}
                </span>
            </div>
            @else
            <div class="mt-5">
                <span class="text-sm text-neutral-600 dark:text-neutral-400">
                    {{ $card['description'] }}
                </span>
            </div>
            @endif
        </flux:card>
        @endforeach
    </div>


    {{-- Charts Section --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <flux:card class="p-5">
            <flux:heading size="lg" class="mb-4">Trend Kunjungan</flux:heading>
            <div class="h-80">
                <canvas id="chartKunjungan"></canvas>
            </div>
        </flux:card>

        <flux:card class="p-5">
            <flux:heading size="lg" class="mb-4">Distribusi Poli</flux:heading>
            <div class="h-80">
                <canvas id="chartPoli"></canvas>
            </div>
        </flux:card>
    </div>

    {{-- Recent Activity with Live Updates --}}
    <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">Aktivitas Terbaru</flux:heading>
            <flux:button size="sm" variant="outline">Lihat Semua</flux:button>
        </div>

        <flux:card class="divide-y dark:divide-neutral-700 shadow-md rounded-2xl overflow-hidden">

            @foreach([
            ['name' => 'Admin', 'avatar' => 'https://i.pravatar.cc/40?img=1', 'description' => 'Menambahkan data pasien baru', 'time' => '2 menit lalu'],
            ['name' => 'Suster A', 'avatar' => 'https://i.pravatar.cc/40?img=2', 'description' => 'Menginput kunjungan harian', 'time' => '10 menit lalu'],
            ['name' => 'Dokter B', 'avatar' => 'https://i.pravatar.cc/40?img=3', 'description' => 'Memeriksa pasien poli umum', 'time' => '30 menit lalu'],
            ['name' => 'Admin', 'avatar' => 'https://i.pravatar.cc/40?img=4', 'description' => 'Mengupdate stok obat', 'time' => '1 jam lalu'],
            ] as $activity)
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
            @endforeach
        </flux:card>

    </div>
</x-layouts.app>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dummy data chart
    const ctx1 = document.getElementById('chartKunjungan').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            datasets: [{
                label: 'Kunjungan',
                data: [5, 8, 12, 7, 10],
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const ctx2 = document.getElementById('chartPoli').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Umum', 'Gigi', 'Anak', 'Bedah'],
            datasets: [{
                data: [30, 20, 25, 15],
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush