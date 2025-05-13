<?php

namespace App\Livewire\Auth;

use Flux\Flux;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserCrud extends Component
{
    use WithPagination;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public ?string $role = null;
    public array $permissions = [];

    public ?int $editId = null;
    public bool $showModal = false;

    public function render()
    {
        $users = User::with('roles', 'permissions')->paginate(10);
        $roles = Role::all();
        $allPermissions = Permission::all();

        return view('livewire.auth.user-crud', compact('users', 'roles', 'allPermissions'));
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
        $this->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            if ($this->role) {
                $user->assignRole($this->role);
            }

            if (!empty($this->permissions)) {
                $user->syncPermissions($this->permissions);
            }

            Flux::toast('User berhasil ditambahkan.', variant: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal Tambah', text: 'Terjadi kesalahan saat menambahkan user.', variant: 'danger');
        }
    }

    public function edit($id)
    {
        $user = User::with('roles', 'permissions')->findOrFail($id);

        $this->editId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->roles->pluck('name')->first();
        $this->permissions = $user->permissions->pluck('name')->toArray();

        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email,' . $this->editId,
        ]);

        try {
            $user = User::findOrFail($this->editId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }

            $user->syncRoles([$this->role]);
            $user->syncPermissions($this->permissions);

            Flux::toast('User berhasil diupdate.', variant: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal Update', text: 'Terjadi kesalahan saat update user.', variant: 'danger');
        }
    }

    public function delete($id)
    {
        try {
            User::destroy($id);
            Flux::toast('User berhasil dihapus.', variant: 'success');
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal Hapus', text: 'Terjadi kesalahan saat hapus user.', variant: 'danger');
        }
    }

    public function updatedRole($value)
    {
        if ($value) {
            $role = Role::where('name', $value)->first();
            $this->permissions = $role?->permissions->pluck('name')->toArray() ?? [];
        }
    }

    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'role', 'permissions', 'editId']);
    }
}