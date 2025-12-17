<?php

namespace App\Livewire;

use App\Models\Kunjungan;
use App\Models\PasienUmum;
use App\Models\DetailPembelianObatModel;
use App\Models\DetailPenjualanObatModel;
use App\Models\Obat;
use App\Models\Poli;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalPasien;
    public $kunjunganHariIni;
    public $stokObatMenipis;
    public $antrianSaatIni;
    
    public $trendKunjunganLabels = [];
    public $trendKunjunganData = [];
    
    public $distribusiPoliLabels = [];
    public $distribusiPoliData = [];
    public $distribusiPoliColors = [];
    
    public $recentActivities = [];

    public function mount()
    {
        $this->loadStatistics();
        $this->loadChartData();
        $this->loadRecentActivities();
    }

    public function loadStatistics()
    {
        // Total Pasien Hari Ini (yang terdaftar hari ini)
        $this->totalPasien = PasienUmum::whereDate('created_at', Carbon::today())->count();
        
        // Kunjungan Hari Ini
        $this->kunjunganHariIni = Kunjungan::whereDate('tanggal_kunjungan', Carbon::today())->count();
        
        // Pembelian Obat Hari Ini
        $this->stokObatMenipis = $this->getPembelianObatHariIni();
        
        // Antrian Saat Ini (kunjungan hari ini)
        $this->antrianSaatIni = $this->kunjunganHariIni;
    }

    private function getPembelianObatHariIni()
    {
        // Hitung total pembelian obat hari ini
        return DetailPembelianObatModel::whereHas('pembelian', function($query) {
            $query->whereDate('created_at', Carbon::today());
        })->count();
    }

    public function loadChartData()
    {
        // Trend Kunjungan - 7 hari terakhir
        $days = [];
        $counts = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days[] = $date->isoFormat('dddd'); // Nama hari dalam bahasa Indonesia
            $counts[] = Kunjungan::whereDate('tanggal_kunjungan', $date)->count();
        }
        
        $this->trendKunjunganLabels = $days;
        $this->trendKunjunganData = $counts;
        
        // Distribusi Poli
        $poliData = Kunjungan::select('poli_id', DB::raw('count(*) as total'))
            ->groupBy('poli_id')
            ->with('poli')
            ->get();
        
        $colors = [
            'rgb(59, 130, 246)',   // Blue
            'rgb(16, 185, 129)',   // Green
            'rgb(245, 158, 11)',   // Yellow
            'rgb(239, 68, 68)',    // Red
            'rgb(168, 85, 247)',   // Purple
            'rgb(236, 72, 153)',   // Pink
            'rgb(14, 165, 233)',   // Sky
            'rgb(251, 146, 60)',   // Orange
        ];
        
        $labels = [];
        $data = [];
        $chartColors = [];
        
        foreach ($poliData as $index => $item) {
            $labels[] = $item->poli ? $item->poli->nama : 'Tidak Diketahui';
            $data[] = $item->total;
            $chartColors[] = $colors[$index % count($colors)];
        }
        
        $this->distribusiPoliLabels = $labels;
        $this->distribusiPoliData = $data;
        $this->distribusiPoliColors = $chartColors;
    }

    public function loadRecentActivities()
    {
        // Ambil 5 kunjungan terbaru
        $recentKunjungan = Kunjungan::with(['pasien', 'poli'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $activities = [];
        
        foreach ($recentKunjungan as $kunjungan) {
            $activities[] = [
                'name' => $kunjungan->pasien ? $kunjungan->pasien->nama_lengkap : 'Pasien',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($kunjungan->pasien ? $kunjungan->pasien->nama_lengkap : 'P') . '&background=random',
                'description' => 'Kunjungan ke ' . ($kunjungan->poli ? $kunjungan->poli->nama : 'Poli'),
                'time' => $kunjungan->created_at->diffForHumans(),
            ];
        }
        
        $this->recentActivities = $activities;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
