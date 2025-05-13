<?php

namespace App\Livewire\Auth;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Permission;

class PermissionCrud extends Component
{
    use WithPagination;

    public string $name = '';
    public $editId = null;
    public bool $showModal = false;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required|string|min:2|unique:permissions,name',
    ];

    public function render()
    {
        $permissions = Permission::orderBy($this->sortBy, $this->sortDirection)->paginate(10);
        return view('livewire.auth.permission-crud', compact('permissions'));
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function store()
    {
        $this->validate();
        try {
            Permission::create(['name' => $this->name]);
            Flux::toast('Permission berhasil ditambahkan.', variant: 'success');
            $this->closeModal();
        } catch (QueryException $e) {
            Flux::toast(
                heading: 'Gagal Menyimpan',
                text: 'Terjadi kesalahan saat menyimpan permission.',
                variant: 'danger'
            );
        }
        $this->closeModal();
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->editId = $permission->id;
        $this->name = $permission->name;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:2|unique:permissions,name,' . $this->editId,
        ]);
        try {
        Permission::findOrFail($this->editId)->update(['name' => $this->name]);
        Flux::toast('Permission berhasil diupdate.', variant: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Gagal Update',
                text: 'Terjadi kesalahan saat mengupdate permission.',
                variant: 'danger'
            );
        }
        $this->closeModal();
    }

    public function delete($id)
    {
        try {
        Permission::destroy($id);
        Flux::toast('Permission berhasil dihapus.', variant: 'success');
    } catch (\Exception $e) {
        Flux::toast(
            heading: 'Gagal Hapus',
            text: 'Terjadi kesalahan saat menghapus permission.',
            variant: 'danger'
        );
    }
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    private function resetForm()
    {
        $this->reset(['name', 'editId']);
    }

}