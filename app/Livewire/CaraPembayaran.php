<?php

namespace App\Livewire;

use App\Models\CaraPembayaran as CaraPembayaranModel;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class CaraPembayaran extends Component
{
    use WithPagination;

    public $nama, $keterangan, $status = 1;
    public $editId = null;
    public $deleteId = null;
    public $search = '';
    public $sortField = 'nama';
    public $sortDirection = 'asc';

    protected $rules = [
        'nama' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        'status' => 'required|boolean',
    ];

    public function render()
    {
        $data = CaraPembayaranModel::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.cara-pembayaran', compact('data'));
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
        Flux::modal('caraPembayaranModal')->show();
    }

    public function edit($id)
    {
        $item = CaraPembayaranModel::findOrFail($id);
        $this->editId = $item->id;
        $this->nama = $item->nama;
        $this->keterangan = $item->keterangan;
        $this->status = $item->status;

        Flux::modal('caraPembayaranModal')->show();
    }

    public function save()
    {
        $this->validate();

        try {
            CaraPembayaranModel::updateOrCreate(
                ['id' => $this->editId],
                [
                    'nama' => $this->nama,
                    'keterangan' => $this->keterangan,
                    'status' => $this->status,
                ]
            );

            Flux::modal('caraPembayaranModal')->close();
            Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
            $this->resetForm();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal', text: 'Terjadi kesalahan saat menyimpan data.', variant: 'destructive');
        }
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-caraPembayaran')->show();
    }

    public function delete()
    {
        try {
            CaraPembayaranModel::findOrFail($this->deleteId)->delete();
            Flux::toast(heading: 'Terhapus', text: 'Data berhasil dihapus.', variant: 'success');
        } catch (\Exception $e) {
            Flux::toast(heading: 'Gagal', text: 'Gagal menghapus data.', variant: 'destructive');
        }

        Flux::modal('delete-caraPembayaran')->close();
    }

    public function resetForm()
    {
        $this->reset(['nama', 'keterangan', 'status', 'editId']);
        $this->status = 1;
    }
}
