<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Faskes;
use Flux\Flux;

class FaskesComponent extends Component
{
    use WithPagination;

    public $nama_faskes, $no_telp, $alamat, $email;
    public $editId, $deleteId, $search = '';
    public $sortField = 'nama_faskes';
    public $sortDirection = 'asc';

    protected $rules = [
        'nama_faskes' => 'required|max:100',
        'no_telp'     => 'required|max:20',
        'alamat'      => 'required|max:255',
        'email'       => 'required|email|max:100',
    ];

    public function render()
    {
        $data = Faskes::where('nama_faskes', 'like', "%{$this->search}%")
            ->orWhere('no_telp', 'like', "%{$this->search}%")
            ->orWhere('alamat', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.faskes-component', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('faskesModal')->show();
    }

    public function edit($id)
    {
        $item = Faskes::findOrFail($id);
        $this->editId      = $item->id;
        $this->nama_faskes = $item->nama_faskes;
        $this->no_telp     = $item->no_telp;
        $this->alamat      = $item->alamat;
        $this->email       = $item->email;

        Flux::modal('faskesModal')->show();
    }

    public function save()
    {
        $this->validate();

        Faskes::updateOrCreate(
            ['id' => $this->editId],
            [
                'nama_faskes'   => $this->nama_faskes,
                'no_telp'       => $this->no_telp,
                'alamat'        => $this->alamat,
                'email'         => $this->email,

                // otomatis isi
                'no_surat_izin' => $this->editId ? (Faskes::find($this->editId)->no_surat_izin ?? 'DUMMY-SI') : 'DUMMY-SI',
                'website'       => $this->editId ? (Faskes::find($this->editId)->website ?? 'https://example.com') : 'https://example.com',
            ]
        );

        Flux::modal('faskesModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-faskes')->show();
    }

    public function delete()
    {
        Faskes::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-faskes')->close();
    }

    public function resetForm()
    {
        $this->reset(['nama_faskes', 'no_telp', 'alamat', 'email', 'editId']);
    }
}
