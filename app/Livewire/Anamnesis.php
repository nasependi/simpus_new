<?php

namespace App\Livewire;

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

    public function render()
    {
        $data = AnamnesisModel::with('kunjungan')
            ->where('keluhan_utama', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $kunjungans = Kunjungan::orderBy('id', 'desc')->get();

        return view('livewire.anamnesis', compact('data', 'kunjungans'));
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
            ['id' => $this->editId],
            [
                'kunjungan_id' => $this->kunjungan_id,
                'keluhan_utama' => $this->keluhan_utama,
                'riwayat_penyakit' => $this->riwayat_penyakit,
                'riwayat_alergi' => $this->riwayat_alergi,
                'riwayat_pengobatan' => $this->riwayat_pengobatan,
            ]
        );

        Flux::modal('anamnesisModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
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
        $this->reset(['editId', 'kunjungan_id', 'keluhan_utama', 'riwayat_penyakit', 'riwayat_alergi', 'riwayat_pengobatan']);
    }
}
