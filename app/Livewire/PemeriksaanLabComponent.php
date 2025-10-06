<?php

namespace App\Livewire;

use App\Models\PemeriksaanLab;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class PemeriksaanLabComponent extends Component
{
    use WithPagination;

    public $nama, $deskripsi, $editId, $deleteId;
    public $search = '';

    protected $rules = [
        'nama' => 'required|max:255|unique:pemeriksaan_lab,nama',
        'deskripsi' => 'nullable|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = PemeriksaanLab::where('nama', 'like', '%' . $this->search . '%')
            ->oldest()
            ->paginate(10);

        return view('livewire.pemeriksaan-lab-component', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('labModal')->show();
    }

    public function edit($id)
    {
        $item = PemeriksaanLab::findOrFail($id);
        $this->editId = $item->id;
        $this->nama = $item->nama;
        $this->deskripsi = $item->deskripsi;

        Flux::modal('labModal')->show();
    }

    public function save()
    {
        $rules = $this->rules;

        if ($this->editId) {
            $rules['nama'] = 'required|max:255|unique:pemeriksaan_lab,nama,' . $this->editId;
        }

        $this->validate($rules);

        PemeriksaanLab::updateOrCreate(
            ['id' => $this->editId],
            ['nama' => $this->nama, 'deskripsi' => $this->deskripsi]
        );

        Flux::modal('labModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-lab')->show();
    }

    public function delete()
    {
        PemeriksaanLab::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-lab')->close();
    }

    public function resetForm()
    {
        $this->reset(['nama', 'deskripsi', 'editId']);
    }
}
