<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TingkatKesadaran as TingkatKesadaranModel;

class TingkatKesadaran extends Component
{
    use WithPagination;

    public $keterangan, $nilai, $editId, $deleteId;
    public $search = '';

    // Default: urut nilai dari 0 ke atas
    public $sortField = 'nilai';
    public $sortDirection = 'asc';

    protected $rules = [
        'keterangan' => 'required|max:50',
        'nilai'      => 'required|max:10', // jika ingin angka saja, lihat catatan di bawah
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        // whitelist kolom yang boleh disort
        $allowed = ['keterangan', 'nilai'];
        if (! in_array($field, $allowed, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $query = TingkatKesadaranModel::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($qq) {
                    $qq->where('keterangan', 'like', '%' . $this->search . '%')
                        ->orWhere('nilai', 'like', '%' . $this->search . '%');
                });
            });

        // Terapkan sorting
        if ($this->sortField === 'nilai') {
            $dir = $this->sortDirection === 'desc' ? 'DESC' : 'ASC';
            $query->orderByRaw("CAST(nilai AS UNSIGNED) $dir");
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        // tie-breaker agar stabil
        $query->orderBy('id', 'asc');

        $data = $query->paginate(10);

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
        $this->editId    = $item->id;
        $this->keterangan = $item->keterangan;
        $this->nilai      = $item->nilai;

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
        $this->reset(['keterangan', 'nilai', 'editId', 'deleteId']);
    }
}
