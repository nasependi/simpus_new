<?php

namespace App\Livewire;

use App\Models\JenisPemeriksaanRadiologi;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class JenisPemeriksaanRadiologiComponent extends Component
{
    use WithPagination;

    public $kode, $nama, $editId, $deleteId;
    public $search = '';

    protected $rules = [
        'kode' => 'required|max:50|unique:jenis_pemeriksaan_radiologi,kode',
        'nama' => 'required|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = JenisPemeriksaanRadiologi::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('kode', 'like', '%' . $this->search . '%')
            ->oldest() // ini otomatis order by created_at DESC
            ->paginate(10);

        return view('livewire.jenis-pemeriksaan-radiologi-component', compact('data'));
    }


    public function create()
    {
        $this->resetForm();
        Flux::modal('radiologiModal')->show();
    }

    public function edit($id)
    {
        $item = JenisPemeriksaanRadiologi::findOrFail($id);
        $this->editId = $item->id;
        $this->kode = $item->kode;
        $this->nama = $item->nama;

        Flux::modal('radiologiModal')->show();
    }

    public function save()
    {
        $rules = $this->rules;

        if ($this->editId) {
            $rules['kode'] = 'required|max:50|unique:jenis_pemeriksaan_radiologi,kode,' . $this->editId;
        }

        $this->validate($rules);

        JenisPemeriksaanRadiologi::updateOrCreate(
            ['id' => $this->editId],
            ['kode' => $this->kode, 'nama' => $this->nama]
        );

        Flux::modal('radiologiModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-pemeriksaan')->show();
    }

    public function delete()
    {
        JenisPemeriksaanRadiologi::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-pemeriksaan')->close();
    }

    public function resetForm()
    {
        $this->reset(['kode', 'nama', 'editId']);
    }
}
