<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\Anamnesis as AnamnesisModel;
use App\Models\Kunjungan;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class Anamnesis extends Component
{
    use WithPagination;

    public $kunjungan_id, $keluhan_utama, $riwayat_penyakit, $riwayat_alergi, $riwayat_pengobatan;
    public $editId, $deleteId;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $rules = [
        'kunjungan_id' => 'required|exists:kunjungan,id',
        'keluhan_utama' => 'required|string|max:255',
        'riwayat_penyakit' => 'required|string|max:255',
        'riwayat_alergi' => 'required|string|max:255',
        'riwayat_pengobatan' => 'required|string|max:255',
    ];

    protected $listeners = ['save-anamnesis' => 'save'];

    public function render()
    {
        $data = AnamnesisModel::with('kunjungan')
            ->where('keluhan_utama', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $kunjungans = Kunjungan::with('pasien')->orderBy('id', 'desc')->get();


        return view('livewire.kunjungan.form.anamnesis', compact('data', 'kunjungans'));
    }

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;

        // Cek apakah sudah ada data anamnesis sebelumnya
        $anamnesis = AnamnesisModel::where('kunjungan_id', $kunjungan_id)->first();
        if ($anamnesis) {
            // Mode edit
            $this->editId = $anamnesis->id;
            $this->keluhan_utama = $anamnesis->keluhan_utama;
            $this->riwayat_penyakit = $anamnesis->riwayat_penyakit;
            $this->riwayat_alergi = $anamnesis->riwayat_alergi;
            $this->riwayat_pengobatan = $anamnesis->riwayat_pengobatan;
        }
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

    public function create()
    {
        $this->resetForm();
        Flux::modal('anamnesisModal')->show();
    }

    public function edit($id)
    {
        $item = AnamnesisModel::findOrFail($id);
        $this->editId = $item->id;
        $this->kunjungan_id = $item->kunjungan_id;
        $this->keluhan_utama = $item->keluhan_utama;
        $this->riwayat_penyakit = $item->riwayat_penyakit;
        $this->riwayat_alergi = $item->riwayat_alergi;
        $this->riwayat_pengobatan = $item->riwayat_pengobatan;

        Flux::modal('anamnesisModal')->show();
    }

    public function save()
    {
        $this->validate();

        AnamnesisModel::updateOrCreate(
            ['kunjungan_id' => $this->kunjungan_id],
            [
                'kunjungan_id' => $this->kunjungan_id,
                'keluhan_utama' => $this->keluhan_utama,
                'riwayat_penyakit' => $this->riwayat_penyakit,
                'riwayat_alergi' => $this->riwayat_alergi,
                'riwayat_pengobatan' => $this->riwayat_pengobatan,
            ]
        );

        Flux::modal('anamnesisModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data Anamnesis berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-anamnesis')->show();
    }

    public function delete()
    {
        AnamnesisModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-anamnesis')->close();
    }

    public function resetForm()
    {
        $this->editId = null;

        // Ambil anamnesis berdasarkan kunjungan_id yang sedang aktif
        $anamnesis = AnamnesisModel::where('kunjungan_id', $this->kunjungan_id)->first();

        $this->keluhan_utama = $anamnesis->keluhan_utama ?? '';
        $this->riwayat_penyakit = $anamnesis->riwayat_penyakit ?? '';
        $this->riwayat_alergi = $anamnesis->riwayat_alergi ?? '';
        $this->riwayat_pengobatan = $anamnesis->riwayat_pengobatan ?? '';
    }
}
