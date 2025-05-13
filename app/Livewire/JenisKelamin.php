<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JenisKelamin as JenisKelaminModel;

class JenisKelamin extends Component
{
    use WithPagination;

    public $kode, $nama_jk, $editId, $deleteId;
    public $modal = false;

    protected $rules = [
        'kode' => 'required|max:1',
        'nama_jk' => 'required|max:30',
    ];

    public $search = '';
    public $sortField = 'nama_jk';
    public $sortDirection = 'asc';

    protected $listeners = ['searchUpdated'];

    public function mount()
    {
        $this->search = '';
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'dsc';
        }
    }

    public function render()
    {
        $data = JenisKelaminModel::
            where('nama_jk', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.jenis-kelamin', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        // $this->modal = true;
        Flux::modal('jenisKelaminModal')->show();
    }

    public function edit($id)
    {
        Flux::modal('jenisKelaminModal')->show();
        $jk = JenisKelaminModel::findOrFail($id);
        $this->editId = $jk->id;
        $this->kode = $jk->kode;
        $this->nama_jk = $jk->nama_jk;
        $this->modal = true;
    }

    public function save()
    {
        $this->validate();

        JenisKelaminModel::updateOrCreate(
            ['id' => $this->editId],
            ['kode' => $this->kode, 'nama_jk' => $this->nama_jk]
        );

        Flux::modal('jenisKelaminModal')->close();
        Flux::toast(
            heading: 'Changes saved',
            text: 'Your changes have been saved.',
            variant: 'success',
        );
        $this->resetForm();
    }

    public function deleteConfirm ($id){
        $this->deleteId = $id;
        Flux::modal('delete-post')->show(); 
    }

    public function delete()
    {
        JenisKelaminModel::findOrFail($this->deleteId)->delete();
        Flux::toast(variant: 'success', heading:'Hapus data', text:'Data sudah terhapus.');
        Flux::modal('delete-post')->close();
    }

    public function resetForm()
    {
        $this->reset(['kode', 'nama_jk', 'editId']);
    }
}