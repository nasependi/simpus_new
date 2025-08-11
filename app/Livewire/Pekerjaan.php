<?php

namespace App\Livewire;

use App\Models\Pekerjaan as PekerjaanModel;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Pekerjaan extends Component
{
    use WithPagination;

    public $kode, $nama_pekerjaan, $editId, $deleteId;
    public $search = '';
    public $sortField = 'nama_pekerjaan';
    public $sortDirection = 'asc';

    protected $rules = [
        'kode' => 'required|max:2',
        'nama_pekerjaan' => 'required|max:30',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $data = PekerjaanModel::where('nama_pekerjaan', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.pekerjaan', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('pekerjaanModal')->show();
    }

    public function edit($id)
    {
        $item = PekerjaanModel::findOrFail($id);
        $this->editId = $item->id;
        $this->kode = $item->kode;
        $this->nama_pekerjaan = $item->nama_pekerjaan;

        Flux::modal('pekerjaanModal')->show();
    }

    public function save()
    {
        $this->validate();

        PekerjaanModel::updateOrCreate(
            ['id' => $this->editId],
            ['kode' => $this->kode, 'nama_pekerjaan' => $this->nama_pekerjaan]
        );

        Flux::modal('pekerjaanModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-pekerjaan')->show();
    }

    public function delete()
    {
        PekerjaanModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-pekerjaan')->close();
    }

    public function resetForm()
    {
        $this->reset(['kode', 'nama_pekerjaan', 'editId']);
    }
}
