<?php

namespace App\Livewire\Auth;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleCrud extends Component
{
    use WithPagination;

    public string $name = '';
    public array $selectedPermissions = [];
    public ?int $editId = null;
    public bool $showModal = false;

    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required|string|min:2|unique:roles,name',
    ];

    public function render()
    {
        $roles = Role::with('permissions')->orderBy($this->sortBy, $this->sortDirection)->paginate(10);
        $permissions = Permission::orderBy('name')->get();
        return view('livewire.auth.role-crud', compact('roles', 'permissions'));
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
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);

            Flux::toast('Role berhasil ditambahkan.', variant: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal Tambah', text: 'Terjadi kesalahan saat menambahkan role.', variant: 'danger');
        }
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:2|unique:roles,name,' . $this->editId,
        ]);

        try {
            $role = Role::findOrFail($this->editId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);

            Flux::toast('Role berhasil diupdate.', variant: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal Update', text: 'Terjadi kesalahan saat update role.', variant: 'danger');
        }
    }

    public function delete($id)
    {
        try {
            Role::destroy($id);
            Flux::toast('Role berhasil dihapus.', variant: 'success');
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal Hapus', text: 'Terjadi kesalahan saat hapus role.', variant: 'danger');
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
        $this->reset(['name', 'selectedPermissions', 'editId']);
    }
    
}