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
    public $umur_tahun, $umur_bulan, $umur_hari;
    public $editId = null;
    public $deleteId = null;
    public $search = '';
    public $sortField = 'tanggal_kunjungan';
    public $sortDirection = 'desc';
    public $deletePasienNama = '';

    // Data referensi dropdown
    public $listPasien = [];
    public $listPoli = [];
    public $listCaraPembayaran = [];
    public $listDaftarKesadaran = [];
    public $status, $statusId = null;
    public $status_kunjungan = null; // Status yang dipilih dokter saat pemeriksaan

    public $filterTanggal;
    public $filterPasien;
    public $filterUmur;
    public $filterPoli = '';
    public $filterCara = '';
    protected $listeners = [
        'saveAll' => 'saveAll',
        'consent-saved' => '$refresh'
    ];



    protected $rules = [
        'pasien_id' => 'required|exists:pasien_umum,id',
        'poli_id' => 'required|exists:poli,id',
        'carapembayaran_id' => 'required|exists:cara_pembayaran,id',
        'tanggal_kunjungan' => 'required|date',
        'umur_tahun' => 'required|integer|min:0',
        'umur_bulan' => 'required|integer|min:0|max:11',
        'umur_hari' => 'required|integer|min:0|max:31',
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
        // Save status if doctor selected rujuk or rawat inap
        if ($this->status_kunjungan && $this->kunjungan_id) {
            KunjunganModel::where('id', $this->kunjungan_id)
                ->update(['status' => $this->status_kunjungan]);
            
            Flux::toast(
                heading: 'Status Diperbarui',
                text: 'Status pasien telah diubah menjadi ' . ($this->status_kunjungan === 'rujuk' ? 'Rujuk' : 'Rawat Inap'),
                variant: 'success'
            );
        }
        
        // Dispatch save events to all child components
        $this->dispatch('save-anamnesis');
        $this->dispatch('save-pemeriksaan-fisik');
        $this->dispatch('save-psikologis');
        $this->dispatch('save-spesialistik');
        $this->dispatch('save-persetujuan-tindakan');
        $this->dispatch('save-laboratorium');
        $this->dispatch('save-radiologi');
        $this->dispatch('save-terapi');
        $this->dispatch('save-obat-resep');
        $this->dispatch('save-diagnosis');
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
        
        // Load umur fields
        $this->umur_tahun = $item->umur_tahun;
        $this->umur_bulan = $item->umur_bulan;
        $this->umur_hari = $item->umur_hari;

        $this->loadReferences();
        Flux::modal('kunjunganModal')->show();
    }

    public function cetakConsent($kunjunganId)
    {
        $consent = GeneralConsent::with('kunjungan.pasien')
            ->where('kunjungan_id', $kunjunganId)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.general_consent', compact('consent'));

        // Stream PDF to browser for printing (not download)
        return response()->stream(
            function() use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="general-consent-' . $kunjunganId . '.pdf"',
            ]
        );
    }

    public function openModalPemeriksaan($id)
    {
        $this->kunjungan_id = $id;
        
        // Force refresh all child components
        $this->dispatch('$refresh');
        
        Flux::modal('modalPemeriksaan')->show();
    }

    public function openGeneralConsent($id)
    {
        $this->dispatch('open-modal-generalconsent', kunjungan_id: $id);
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
                    'umur_tahun' => $this->umur_tahun,
                    'umur_bulan' => $this->umur_bulan,
                    'umur_hari' => $this->umur_hari,
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
        $kunjungan = KunjunganModel::with('pasien')->findOrFail($id);
        $this->deleteId = $id;
        $this->deletePasienNama = $kunjungan->pasien->nama_lengkap ?? 'Tidak diketahui';
        Flux::modal('delete-kunjungan')->show();
    }

    public function openStatusModal($id)
    {
        $item = KunjunganModel::findOrFail($id);
        $this->statusId = $item->id;
        $this->status = $item->status;
        Flux::modal('statusModal')->show();
    }


    public function updateStatus()
    {
        $this->validate([
            'status' => 'required|in:rawat_jalan,rawat_inap,rujuk,pulang',
        ]);

        $item = KunjunganModel::findOrFail($this->statusId);
        $item->status = $this->status;
        $item->save();

        Flux::modal('statusModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
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
        $this->reset(['pasien_id', 'poli_id', 'carapembayaran_id', 'tanggal_kunjungan', 'umur_tahun', 'umur_bulan', 'umur_hari', 'editId']);
    }

    public function render()
    {
        $query = KunjunganModel::with(['pasien', 'poli', 'caraPembayaran', 'obatResep'])
            ->when($this->filterTanggal, fn($q) => $q->whereDate('tanggal_kunjungan', $this->filterTanggal))
            ->when($this->filterPasien, fn($q) => $q->whereHas('pasien', fn($p) => $p->where('nama_lengkap', 'like', '%' . $this->filterPasien . '%')))
            ->when($this->filterPoli, fn($q) => $q->where('poli_id', $this->filterPoli))
            ->when($this->filterCara, fn($q) => $q->where('carapembayaran_id', $this->filterCara))
            ->when($this->filterUmur, fn($q) => $q->where(function ($query) {
                $query->where('umur_tahun', 'like', "%{$this->filterUmur}%")
                    ->orWhere('umur_bulan', 'like', "%{$this->filterUmur}%")
                    ->orWhere('umur_hari', 'like', "%{$this->filterUmur}%");
            }))
            ->orderBy('id', 'desc'); // Newest first

        $data = $query->paginate(10);

        return view('livewire.kunjungan.kunjungan', [
            'data' => $data,
            'poliList' => \App\Models\Poli::pluck('nama', 'id')->toArray(),
            'caraPembayaranList' => \App\Models\CaraPembayaran::pluck('nama', 'id')->toArray(),
            'daftarKesadaran' => \App\Models\TingkatKesadaran::pluck('keterangan', 'id')->toArray(),
        ]);
    }
}
