<?php

namespace App\Livewire;

use App\Models\PemeriksaanTindakan;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class PemeriksaanTindakanComponent extends Component
{
    use WithPagination;

    public $nama, $deskripsi, $editId, $deleteId;
    public $search = '';

    protected $rules = [
        'nama' => 'required|max:255|unique:pemeriksaan_tindakan,nama',
        'deskripsi' => 'nullable|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = PemeriksaanTindakan::where('nama', 'like', '%' . $this->search . '%')
            ->oldest()
            ->paginate(10);

        return view('livewire.pemeriksaan-tindakan-component', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('tindakanModal')->show();
    }

    public function edit($id)
    {
        $item = PemeriksaanTindakan::findOrFail($id);
        $this->editId = $item->id;
        $this->nama = $item->nama;
        $this->deskripsi = $item->deskripsi;

        Flux::modal('tindakanModal')->show();
    }

    public function save()
    {
        $rules = $this->rules;

        if ($this->editId) {
            $rules['nama'] = 'required|max:255|unique:pemeriksaan_tindakan,nama,' . $this->editId;
        }

        $this->validate($rules);

        PemeriksaanTindakan::updateOrCreate(
            ['id' => $this->editId],
            ['nama' => $this->nama, 'deskripsi' => $this->deskripsi]
        );

        Flux::modal('tindakanModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-tindakan')->show();
    }

    public function delete()
    {
        PemeriksaanTindakan::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-tindakan')->close();
    }

    public function resetForm()
    {
        $this->reset(['nama', 'deskripsi', 'editId']);
    }
}
