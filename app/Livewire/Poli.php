<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Poli as Poliklinik;
use Livewire\Component;
use Livewire\WithPagination;

class Poli extends Component
{
    use WithPagination;

    public $nama, $keterangan, $status = true, $editId, $deleteId;
    public $search = '';
    public $sortField = 'nama';
    public $sortDirection = 'asc';

    protected $rules = [
        'nama' => 'required|string|max:50',
        'keterangan' => 'nullable|string|max:255',
        'status' => 'boolean',
    ];

    public function render()
    {
        $data = Poliklinik::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.poli', compact('data'));
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
        Flux::modal('poliModal')->show();
    }

    public function edit($id)
    {
        $poli = Poliklinik::findOrFail($id);
        $this->editId = $poli->id;
        $this->nama = $poli->nama;
        $this->keterangan = $poli->keterangan;
        $this->status = $poli->status;

        Flux::modal('poliModal')->show();
    }

    public function save()
    {
        $this->validate();

        Poliklinik::updateOrCreate(
            ['id' => $this->editId],
            ['nama' => $this->nama, 'keterangan' => $this->keterangan, 'status' => $this->status]
        );

        Flux::modal('poliModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-poli')->show();
    }

    public function delete()
    {
        Poliklinik::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-poli')->close();
    }

    public function resetForm()
    {
        $this->reset(['nama', 'keterangan', 'status', 'editId']);
    }
}
