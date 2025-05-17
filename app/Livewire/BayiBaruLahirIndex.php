<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\JenisKelamin;
use Livewire\WithPagination;
use App\Models\BayiBaruLahir;

class BayiBaruLahirIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nama_bayi';
    public $sortDirection = 'asc';

    public $editId;
    public $nama_bayi, $nik_ibuk, $no_rekamedis, $tempat_lahir, $tanggal_lahir, $jam_lahir, $jk_id;
    public $listJenisKelamin = [];

    protected $rules = [
        'nama_bayi' => 'required|string|max:100',
        'nik_ibuk' => 'required|string|max:16',
        'no_rekamedis' => 'required|string|max:50',
        'tempat_lahir' => 'required|string|max:100',
        'tanggal_lahir' => 'required|date',
        'jam_lahir' => 'required',
        'jk_id' => 'required|exists:jenis_kelamin,id',
    ];

    public function mount()
    {
        $this->listJenisKelamin = JenisKelamin::all();
    }

    public function render()
    {
        $data = BayiBaruLahir::with('jenisKelamin')
            ->where('nama_bayi', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.bayi-baru-lahir', [
            'data' => $data,
            'jenis_kelamin' => JenisKelamin::get(),
        ]);
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
        Flux::modal('bayiModal')->show();
    }

    public function edit($id)
    {
        $this->resetForm();

        $data = BayiBaruLahir::findOrFail($id);
        $this->editId = $data->id;
        $this->nama_bayi = $data->nama_bayi;
        $this->nik_ibuk = $data->nik_ibuk;
        $this->no_rekamedis = $data->no_rekamedis;
        $this->tempat_lahir = $data->tempat_lahir;
        $this->tanggal_lahir = $data->tanggal_lahir;
        $this->jam_lahir = $data->jam_lahir;
        $this->jk_id = $data->jk_id;

        Flux::modal('bayiModal')->show();
    }

    public function save()
    {
        $this->validate();

        try {
            BayiBaruLahir::updateOrCreate(
                ['id' => $this->editId],
                [
                    'nama_bayi' => $this->nama_bayi,
                    'nik_ibuk' => $this->nik_ibuk,
                    'no_rekamedis' => $this->no_rekamedis,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'jam_lahir' => $this->jam_lahir,
                    'jk_id' => $this->jk_id,
                ]
            );

            Flux::modal('bayiModal')->close();
            Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
            $this->resetForm();
        } catch (\Throwable $e) {
            Flux::toast(heading: 'Danger', text: 'Data gagal disimpan.', variant: 'danger');
        }
    }

    public function deleteConfirm($id)
    {
        $this->editId = $id;
        Flux::modal('delete-bayi')->show();
    }

    public function delete()
    {
        try {
            BayiBaruLahir::findOrFail($this->editId)->delete();
            Flux::modal('delete-agama')->close();
            Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        } catch (\Throwable $e) {
            Flux::toast(heading: 'Gagal', text: 'Data gagal dihapus.', variant: 'danger');
        }
    }

    private function resetForm()
    {
        $this->reset(['editId', 'nama_bayi', 'nik_ibuk', 'no_rekamedis', 'tempat_lahir', 'tanggal_lahir', 'jam_lahir', 'jk_id']);
        $this->resetValidation();
    }
}
