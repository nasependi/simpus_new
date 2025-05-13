<?php

namespace App\Livewire;

use App\Models\Pendidikan as PendidikanModel;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Pendidikan extends Component
{
    use WithPagination;

    public $kode, $nama_pendidikan, $editId, $deleteId;
    public $search = '';
    public $sortField = 'nama_pendidikan';
    public $sortDirection = 'asc';

    protected $rules = [
        'kode' => 'required|max:1',
        'nama_pendidikan' => 'required|max:30',
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
        $data = PendidikanModel::where('nama_pendidikan', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.pendidikan', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('pendidikanModal')->show();
    }

    public function edit($id)
    {
        $item = PendidikanModel::findOrFail($id);
        $this->editId = $item->id;
        $this->kode = $item->kode;
        $this->nama_pendidikan = $item->nama_pendidikan;

        Flux::modal('pendidikanModal')->show();
    }

    public function save()
    {
        $this->validate();

        PendidikanModel::updateOrCreate(
            ['id' => $this->editId],
            ['kode' => $this->kode, 'nama_pendidikan' => $this->nama_pendidikan]
        );

        Flux::modal('pendidikanModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-pendidikan')->show();
    }

    public function delete()
    {
        PendidikanModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-pendidikan')->close();
    }

    public function resetForm()
    {
        $this->reset(['kode', 'nama_pendidikan', 'editId']);
    }
}