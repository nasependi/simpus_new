<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Agama as AgamaModel;
use Flux\Flux;

class Agama extends Component
{
    use WithPagination;

    public $kode, $nama_agama, $editId, $deleteId;
    public $search = '';
    public $sortField = 'nama_agama';
    public $sortDirection = 'asc';

    protected $rules = [
        'kode' => 'required|max:1',
        'nama_agama' => 'required|max:30',
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
        $data = AgamaModel::where('nama_agama', 'like', '%' . $this->search . '%')
            ->orderByRaw('CAST(kode AS UNSIGNED) ASC') // << urut 0,1,2,3,...
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.agama', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('agamaModal')->show();
    }

    public function edit($id)
    {
        $agama = AgamaModel::findOrFail($id);
        $this->editId = $agama->id;
        $this->kode = $agama->kode;
        $this->nama_agama = $agama->nama_agama;

        Flux::modal('agamaModal')->show();
    }

    public function save()
    {
        $this->validate();

        AgamaModel::updateOrCreate(
            ['id' => $this->editId],
            ['kode' => $this->kode, 'nama_agama' => $this->nama_agama]
        );

        Flux::modal('agamaModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-agama')->show();
    }

    public function delete()
    {
        AgamaModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-agama')->close();
    }

    public function resetForm()
    {
        $this->reset(['kode', 'nama_agama', 'editId']);
    }
}
