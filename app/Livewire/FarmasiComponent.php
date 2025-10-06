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
                Flux::toast(heading: 'Sukses', text: 'Status berhasil diupdate.', variant: 'success');
            }
        }
    }

    public function berikanObat($kunjunganId)
    {
        $kunjungan = Kunjungan::with('obatResep')->find($kunjunganId);

        if ($kunjungan) {
            foreach ($kunjungan->obatResep as $obat) {
                $obat->status_resep = 'Sudah Diberikan';
                $obat->save();
            }
            $this->emitSelf('refreshComponent');
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
            ->where('status', 'pulang')
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
