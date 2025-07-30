<?php

namespace App\Livewire\Kunjungan;

use Flux\Flux;
use App\Models\Poli;
use Livewire\Component;
use App\Models\PasienUmum;
use Livewire\WithPagination;
use App\Models\GeneralConsent;
use App\Models\TingkatKesadaran;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Kunjungan as KunjunganModel;
use App\Models\CaraPembayaran as CaraPembayaranModel;

class Kunjungan extends Component
{
    use WithPagination;

    public $pasien_id, $poli_id, $carapembayaran_id, $tanggal_kunjungan, $umur, $kunjungan_id, $tingkatkesadaran_id;
    public $editId = null;
    public $deleteId = null;
    public $search = '';
    public $sortField = 'tanggal_kunjungan';
    public $sortDirection = 'desc';

    // Data referensi dropdown
    public $listPasien = [];
    public $listPoli = [];
    public $listCaraPembayaran = [];
    public $listDaftarKesadaran = [];

    public $filterTanggal;
    public $filterPasien;
    public $filterUmur;
    public $filterPoli = '';
    public $filterCara = '';
    protected $listeners = ['saveAll' => 'saveAll'];



    protected $rules = [
        'pasien_id' => 'required|exists:pasien_umum,id',
        'poli_id' => 'required|exists:poli,id',
        'carapembayaran_id' => 'required|exists:cara_pembayaran,id',
        'tanggal_kunjungan' => 'required|date',
        'umur' => 'required|integer|min:0',
    ];

    public function mount()
    {
        $this->loadReferences();
    }

    public function loadReferences()
    {
        $this->listPasien = PasienUmum::orderBy('nama_lengkap')->get();
        $this->listPoli = Poli::orderBy('nama')->get();
        $this->listCaraPembayaran = CaraPembayaranModel::orderBy('nama')->get();
        $this->listDaftarKesadaran = TingkatKesadaran::orderBy('keterangan')->get();
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function saveAll()
    {
        // $this->dispatch('save-anamnesis');
        // $this->dispatch('save-pemeriksaan-fisik');
        // $this->dispatch('save-psikologis');
        // $this->dispatch('save-spesialistik');
        $this->dispatch('save-persetujuan-tindakan');
        // $this->dispatch('save-laboratorium');
        // $this->dispatch('save-radiologi');
        // $this->dispatch('save-terapi');
        // $this->dispatch('save-obat-resep');
        // $this->dispatch('save-diagnosis');
    }

    public function updated($property)
    {
        $this->resetPage(); // agar pagination tetap sinkron
    }

    public function modalKunjungan($id)
    {
        $this->resetForm();
        $this->loadReferences();
        Flux::modal('kunjunganModal')->show();
    }

    public function edit($id)
    {
        $item = KunjunganModel::findOrFail($id);
        $this->editId = $item->id;
        $this->pasien_id = $item->pasien_id;
        $this->poli_id = $item->poli_id;
        $this->carapembayaran_id = $item->carapembayaran_id;
        $this->tanggal_kunjungan = $item->tanggal_kunjungan->format('Y-m-d');
        $this->umur = $item->umur;

        $this->loadReferences();
        Flux::modal('kunjunganModal')->show();
    }

    public function cetakConsent($kunjunganId)
    {
        $consent = GeneralConsent::with('kunjungan.pasien')
            ->where('kunjungan_id', $kunjunganId)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.general_consent', compact('consent'));

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'general-consent-' . $kunjunganId . '.pdf'
        );
    }

    public function openModalPemeriksaan($id)
    {
        $this->kunjungan_id = $id;
        Flux::modal('modalPemeriksaan')->show();
    }

    public function save()
    {
        $this->validate();

        try {
            KunjunganModel::updateOrCreate(
                ['id' => $this->editId],
                [
                    'pasien_id' => $this->pasien_id,
                    'poli_id' => $this->poli_id,
                    'carapembayaran_id' => $this->carapembayaran_id,
                    'tanggal_kunjungan' => $this->tanggal_kunjungan,
                    'umur' => $this->umur,
                ]
            );

            Flux::modal('kunjunganModal')->close();
            Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
            $this->resetForm();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal', text: 'Terjadi kesalahan saat menyimpan data.', variant: 'destructive');
        }
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-kunjungan')->show();
    }

    public function delete()
    {
        try {
            KunjunganModel::findOrFail($this->deleteId)->delete();
            Flux::toast(heading: 'Terhapus', text: 'Data berhasil dihapus.', variant: 'success');
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal', text: 'Gagal menghapus data.', variant: 'destructive');
        }

        Flux::modal('delete-kunjungan')->close();
    }

    public function resetForm()
    {
        $this->reset(['pasien_id', 'poli_id', 'carapembayaran_id', 'tanggal_kunjungan', 'umur', 'editId']);
    }

    public function render()
    {
        $query = KunjunganModel::with(['pasien', 'poli', 'caraPembayaran'])
            ->when($this->filterTanggal, fn($q) => $q->whereDate('tanggal_kunjungan', $this->filterTanggal))
            ->when($this->filterPasien, fn($q) => $q->whereHas('pasien', fn($p) => $p->where('nama_lengkap', 'like', '%' . $this->filterPasien . '%')))
            ->when($this->filterPoli, fn($q) => $q->where('poli_id', $this->filterPoli))
            ->when($this->filterCara, fn($q) => $q->where('carapembayaran_id', $this->filterCara))
            ->when($this->filterUmur, fn($q) => $q->where(function ($query) {
                $query->where('umur_tahun', 'like', "%{$this->filterUmur}%")
                    ->orWhere('umur_bulan', 'like', "%{$this->filterUmur}%")
                    ->orWhere('umur_hari', 'like', "%{$this->filterUmur}%");
            }))
            ->orderBy($this->sortField, $this->sortDirection);

        $data = $query->paginate(10);

        return view('livewire.kunjungan.kunjungan', [
            'data' => $data,
            'poliList' => \App\Models\Poli::pluck('nama', 'id')->toArray(),
            'caraPembayaranList' => \App\Models\CaraPembayaran::pluck('nama', 'id')->toArray(),
            'daftarKesadaran' => \App\Models\TingkatKesadaran::pluck('keterangan', 'id')->toArray(),
        ]);
    }
}
