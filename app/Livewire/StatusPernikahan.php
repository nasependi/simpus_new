<?php

namespace App\Livewire;

use App\Models\StatusPernikahan as StatusPernikahanModel;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class StatusPernikahan extends Component
{
    use WithPagination;

    public $kode, $status, $editId, $deleteId;
    public $search = '';
    public $sortField = 'status';
    public $sortDirection = 'asc';

    protected $rules = [
        'kode' => 'required|max:1',
        'status' => 'required|max:15',
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
        $data = StatusPernikahanModel::where('status', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.status-pernikahan', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('statusPernikahanModal')->show();
    }

    public function edit($id)
    {
        $item = StatusPernikahanModel::findOrFail($id);
        $this->editId = $item->id;
        $this->kode = $item->kode;
        $this->status = $item->status;

        Flux::modal('statusPernikahanModal')->show();
    }

    public function save()
    {
        $this->validate();

        StatusPernikahanModel::updateOrCreate(
            ['id' => $this->editId],
            ['kode' => $this->kode, 'status' => $this->status]
        );

        Flux::modal('statusPernikahanModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-status-pernikahan')->show();
    }

    public function delete()
    {
        StatusPernikahanModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-status-pernikahan')->close();
    }

    public function resetForm()
    {
        $this->reset(['kode', 'status', 'editId']);
    }
}