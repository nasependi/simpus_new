<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TingkatKesadaran as TingkatKesadaranModel;
use Flux\Flux;

class TingkatKesadaran extends Component
{
    use WithPagination;

    public $keterangan, $nilai, $editId, $deleteId;
    public $search = '';
    public $sortField = 'keterangan';
    public $sortDirection = 'asc';

    protected $rules = [
        'keterangan' => 'required|max:50',
        'nilai' => 'required|max:10',
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
        $data = TingkatKesadaranModel::where('keterangan', 'like', '%' . $this->search . '%')
            ->orWhere('nilai', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.tingkat-kesadaran', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('tingkatKesadaranModal')->show();
    }

    public function edit($id)
    {
        $item = TingkatKesadaranModel::findOrFail($id);
        $this->editId = $item->id;
        $this->keterangan = $item->keterangan;
        $this->nilai = $item->nilai;

        Flux::modal('tingkatKesadaranModal')->show();
    }

    public function save()
    {
        $this->validate();

        TingkatKesadaranModel::updateOrCreate(
            ['id' => $this->editId],
            ['keterangan' => $this->keterangan, 'nilai' => $this->nilai]
        );

        Flux::modal('tingkatKesadaranModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-tingkat-kesadaran')->show();
    }

    public function delete()
    {
        TingkatKesadaranModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-tingkat-kesadaran')->close();
    }

    public function resetForm()
    {
        $this->reset(['keterangan', 'nilai', 'editId']);
    }
}
