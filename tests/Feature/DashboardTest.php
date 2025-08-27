<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});

public function index()
{
    $totalPasien = Patient::count();
    $lastMonthPatients = Patient::where('created_at', '>', now()->subMonth())->count();
    $percentPasien = $totalPasien > 0 ? round(($lastMonthPatients / $totalPasien) * 100, 1) : 0;
    
    $kunjunganHariIni = Visit::whereDate('created_at', today())->count();
    $kunjunganKemarin = Visit::whereDate('created_at', today()->subDay())->count();
    $percentKunjungan = $kunjunganKemarin > 0 ? 
        round((($kunjunganHariIni - $kunjunganKemarin) / $kunjunganKemarin) * 100, 1) : 0;
    
    $stokMenipis = Medicine::where('stock', '<', 10)->count();
    $antrianActive = Queue::where('status', 'waiting')->count();
    
    // Chart data for last 7 days
    $chartData = [
        'labels' => collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('d M')),
        'values' => Visit::where('created_at', '>', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count')
    ];
    
    // Poli distribution data
    $poliData = [
        'labels' => Poli::pluck('name'),
        'values' => Visit::selectRaw('poli_id, COUNT(*) as count')
            ->groupBy('poli_id')
            ->pluck('count')
    ];
    
    // Recent activities
    $recentActivities = Activity::with('user')
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard', compact(
        'totalPasien', 'percentPasien',
        'kunjunganHariIni', 'percentKunjungan',
        'stokMenipis', 'antrianActive',
        'chartData', 'poliData',
        'recentActivities'
    ));
}