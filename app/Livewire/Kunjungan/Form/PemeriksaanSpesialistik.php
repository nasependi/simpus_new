<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\Kunjungan;
use App\Models\PemeriksaanSpesialistik as PemeriksaanSpesialistikModel;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class PemeriksaanSpesialistik extends Component
{
    use WithPagination;

    public $kunjungan_id, $nama_obat, $dosis, $waktu_penggunaan, $rencana_rawat, $intruksi_medik;
    public $editId, $deleteId;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['save-spesialistik' => 'save'];

    protected $rules = [
        'kunjungan_id' => 'required|exists:kunjungan,id',
        'nama_obat' => 'required|string|max:255',
        'dosis' => 'required|string|max:255',
        'waktu_penggunaan' => 'required|string|max:255',
        'rencana_rawat' => 'required|string|max:255',
        'intruksi_medik' => 'required|string|max:255',
    ];

    public function render()
    {
        $data = PemeriksaanSpesialistikModel::with('kunjungan')
            ->where('nama_obat', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $kunjungans = Kunjungan::orderBy('id', 'desc')->get();

        return view('livewire.kunjungan.form.pemeriksaan-spesialistik', compact('data', 'kunjungans'));
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
        Flux::modal('pemeriksaanSpesialistikModal')->show();
    }

    public function edit($id)
    {
        $item = PemeriksaanSpesialistikModel::findOrFail($id);
        $this->editId = $item->id;
        $this->kunjungan_id = $item->kunjungan_id;
        $this->nama_obat = $item->nama_obat;
        $this->dosis = $item->dosis;
        $this->waktu_penggunaan = $item->waktu_penggunaan;
        $this->rencana_rawat = $item->rencana_rawat;
        $this->intruksi_medik = $item->intruksi_medik;

        Flux::modal('pemeriksaanSpesialistikModal')->show();
    }

    public function save()
    {
        $this->validate();

        PemeriksaanSpesialistikModel::updateOrCreate(
            ['id' => $this->editId],
            [
                'kunjungan_id' => $this->kunjungan_id,
                'nama_obat' => $this->nama_obat,
                'dosis' => $this->dosis,
                'waktu_penggunaan' => $this->waktu_penggunaan,
                'rencana_rawat' => $this->rencana_rawat,
                'intruksi_medik' => $this->intruksi_medik,
            ]
        );

        Flux::modal('pemeriksaanSpesialistikModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-pemeriksaanSpesialistik')->show();
    }

    public function delete()
    {
        PemeriksaanSpesialistikModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-pemeriksaanSpesialistik')->close();
    }

    public function resetForm()
    {
        $this->reset([
            'editId',
            'kunjungan_id',
            'nama_obat',
            'dosis',
            'waktu_penggunaan',
            'rencana_rawat',
            'intruksi_medik'
        ]);
    }
}
