<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\Kunjungan;
use App\Models\ObatResep;
use Livewire\WithPagination;

class FarmasiComponent extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedResep; // koleksi obat untuk kunjungan yg dipilih
    public $selectedStatusResep = []; // status resep per obat (id => status)
    public $filterTanggal, $filterPasien, $filterRekamMedis, $filterPoli, $filterStatusResep;


    protected $listeners = ['refreshComponent' => '$refresh'];

    public function showResep($kunjunganId)
    {
        $this->loadTableResep($kunjunganId);
        Flux::modal('resepDetailModal')->show();
    }

    private function loadTableResep($kunjunganId)
    {
        $kunjungan = Kunjungan::with(['obatResep', 'pasien'])->find($kunjunganId);

        if ($kunjungan) {
            $this->selectedResep = $kunjungan->obatResep;

            // isi array status resep per obat
            $this->selectedStatusResep = [];
            foreach ($kunjungan->obatResep as $obat) {
                $this->selectedStatusResep[$obat->id] = $obat->status_resep;
            }
        }
    }

    public function updateStatusResep($obatId)
    {
        if (isset($obatId)) {
            $obat = ObatResep::find($obatId);
            $status = $obat->status_resep == 0 ? 1 : 0;

            if ($obat) {
                $obat->status_resep = $status;
                $obat->update();
                $this->loadTableResep($obat->kunjungan_id);
                
                // Check if all obat resep for this kunjungan are completed
                $kunjungan = Kunjungan::with('obatResep')->find($obat->kunjungan_id);
                $allCompleted = $kunjungan->obatResep->every(function($item) {
                    return $item->status_resep == 1; // 1 = Sudah Diberikan
                });
                
                // Auto-update status kunjungan to "pulang" if all obat resep are completed
                if ($allCompleted && !in_array($kunjungan->status, ['rujuk', 'rawat_inap'])) {
                    $kunjungan->status = 'pulang';
                    $kunjungan->save();
                    
                    Flux::toast(
                        heading: 'Sukses',
                        text: 'Semua obat telah diberikan.',
                        variant: 'success'
                    );
                } else {
                    Flux::toast(heading: 'Sukses', text: 'Status obat berhasil diupdate.', variant: 'success');
                }
            }
        }
    }

    public function berikanObat($kunjunganId)
    {
        try {
            $kunjungan = Kunjungan::with('obatResep')->find($kunjunganId);

            if ($kunjungan && $kunjungan->obatResep->count() > 0) {
                // Update semua obat resep menjadi sudah diberikan
                foreach ($kunjungan->obatResep as $obat) {
                    // Update status resep - bisa berupa text atau boolean tergantung struktur DB
                    $obat->update(['status_resep' => 'Sudah Diberikan']);
                }
                
                // Auto-update status kunjungan ke "pulang" jika belum rujuk/rawat inap
                if (!in_array($kunjungan->status, ['rujuk', 'rawat_inap'])) {
                    $kunjungan->status = 'pulang';
                    $kunjungan->save();
                }
                
                Flux::toast(
                    heading: 'Berhasil',
                    text: 'Obat telah diberikan. Status pasien diubah menjadi Pulang.',
                    variant: 'success'
                );
                
                $this->emitSelf('refreshComponent');
            }
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Gagal',
                text: 'Terjadi kesalahan: ' . $e->getMessage(),
                variant: 'danger'
            );
        }
    }

    public function printResep($kunjunganId)
    {
        $kunjungan = Kunjungan::with(['pasien', 'obatResep'])->findOrFail($kunjunganId);
        return redirect()->route('resep.print', $kunjungan->id);
    }

    public function printTiket($kunjunganId)
    {
        $kunjungan = Kunjungan::with(['pasien', 'obatResep'])->findOrFail($kunjunganId);

        // Validasi: semua resep harus selesai
        if ($kunjungan->obatResep->where('status_resep', 0)->count() > 0) {
            Flux::toast(
                heading: 'Gagal',
                text: 'Masih ada resep yang pending, tidak bisa cetak E-tiket.',
                variant: 'danger'
            );
            return;
        }

        return redirect()->route('tiket.print', $kunjungan->id);
    }




    public function render()
    {
        $kunjungan = Kunjungan::with(['pasien', 'obatResep', 'poli'])
            ->whereHas('obatResep') // Hanya tampilkan kunjungan yang punya resep obat
            ->when($this->filterTanggal, function ($query) {
                $query->whereDate('tanggal_kunjungan', $this->filterTanggal);
            })
            ->when($this->filterPasien, function ($query) {
                $query->whereHas('pasien', function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->filterPasien . '%');
                });
            })
            ->when($this->filterRekamMedis, function ($query) {
                $query->whereHas('pasien', function ($q) {
                    $q->where('no_rekamedis', 'like', '%' . $this->filterRekamMedis . '%');
                });
            })
            ->when($this->filterPoli, function ($query) {
                $query->whereHas('poli', function ($q) {
                    $q->where('id', $this->filterPoli);
                });
            })
            ->when($this->filterStatusResep !== null && $this->filterStatusResep !== '', function ($query) {
                if ($this->filterStatusResep == 'pending') {
                    $query->whereHas('obatResep', function ($q) {
                        $q->where('status_resep', 0);
                    });
                } elseif ($this->filterStatusResep == 'selesai') {
                    $query->whereDoesntHave('obatResep', function ($q) {
                        $q->where('status_resep', 0);
                    });
                }
            })
            ->latest()
            ->paginate(10);

        $poliList = \App\Models\Poli::pluck('nama', 'id');

        return view('livewire.farmasi-component', [
            'kunjungan' => $kunjungan,
            'poliList'  => $poliList,
        ]);
    }
}
