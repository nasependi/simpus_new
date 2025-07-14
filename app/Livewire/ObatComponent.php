<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Obat;
use Flux\Flux;

class ObatComponent extends Component
{
    use WithPagination;

    public $nama_obat, $golongan, $sediaan, $editId, $deleteId;
    public $search = '';
    public $sortField = 'nama_obat';
    public $sortDirection = 'asc';

    protected $rules = [
        'nama_obat' => 'required|max:100',
        'golongan' => 'nullable|max:50',
        'sediaan' => 'nullable|max:50', // Assuming 'sedian' is a nullable field
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
        $data = Obat::where('nama_obat', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.obat-component', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('obatModal')->show();
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $this->editId = $obat->id;
        $this->nama_obat = $obat->nama_obat;
        $this->golongan = $obat->golongan;
        $this->sediaan = $obat->sediaan ?? ''; // Assuming 'sedian' is a nullable field

        Flux::modal('obatModal')->show();
    }

    public function save()
    {
        $this->validate();

        Obat::updateOrCreate(
            ['id' => $this->editId],
            ['nama_obat' => $this->nama_obat, 'golongan' => $this->golongan, 'sediaan' => $this->sediaan]
        );

        Flux::modal('obatModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-obat')->show();
    }

    public function delete()
    {
        Obat::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-obat')->close();
    }

    public function resetForm()
    {
        $this->reset(['nama_obat', 'golongan', 'sediaan']);
    }
}
