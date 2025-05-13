<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PasienUmum;
use App\Models\Agama;
use App\Models\JenisKelamin;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\StatusPernikahan;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Flux\Flux;

class PasienUmumCrud extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';

    public $editId, $nama_lengkap, $no_hp, $jk_id, $agama_id, $pendidikan_id, $pekerjaan_id, $statusnikah_id;

    protected $rules = [
        'nama_lengkap' => 'required|max:100',
        'no_hp' => 'required|max:20',
        'jk_id' => 'required|exists:jenis_kelamin,id',
        'agama_id' => 'required|exists:agama,id',
        'pendidikan_id' => 'required|exists:pendidikan,id',
        'pekerjaan_id' => 'required|exists:pekerjaan,id',
        'statusnikah_id' => 'required|exists:status_pernikahan,id',
    ];

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field && $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->sortField = $field;
    }

    public function render()
    {
        $data = PasienUmum::with(['agama', 'jenisKelamin', 'pendidikan', 'pekerjaan', 'statusPernikahan'])
            ->where('nama_lengkap', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.pasien-umum', [
            'data' => $data,
            'jenis_kelamin' => JenisKelamin::all(),
            'agama' => Agama::all(),
            'pendidikan' => Pendidikan::all(),
            'pekerjaan' => Pekerjaan::all(),
            'status_pernikahan' => StatusPernikahan::all(),
        ]);
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('pasienModal')->show();
    }

    public function edit($id)
    {
        $data = PasienUmum::findOrFail($id);
        $this->editId = $data->id;
        $this->nama_lengkap = $data->nama_lengkap;
        $this->no_hp = $data->no_hp;
        $this->jk_id = $data->jk_id;
        $this->agama_id = $data->agama_id;
        $this->pendidikan_id = $data->pendidikan_id;
        $this->pekerjaan_id = $data->pekerjaan_id;
        $this->statusnikah_id = $data->statusnikah_id;

        Flux::modal('pasienModal')->show();
    }

    public function save()
    {
        $this->validate();

        PasienUmum::updateOrCreate(
            ['id' => $this->editId],
            [
                'nama_lengkap' => $this->nama_lengkap,
                'no_hp' => $this->no_hp,
                'jk_id' => $this->jk_id,
                'agama_id' => $this->agama_id,
                'pendidikan_id' => $this->pendidikan_id,
                'pekerjaan_id' => $this->pekerjaan_id,
                'statusnikah_id' => $this->statusnikah_id,
            ]
        );

        Flux::modal('pasienModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->editId = $id;
        Flux::modal('delete-pasien')->show();
    }

    public function delete()
    {
        PasienUmum::findOrFail($this->editId)->delete();
        Flux::toast(heading: 'Dihapus', text: 'Data pasien dihapus.', variant: 'success');
        Flux::modal('delete-pasien')->close();
    }

    public function resetForm()
    {
        $this->reset(['editId', 'nama_lengkap', 'no_hp', 'jk_id', 'agama_id', 'pendidikan_id', 'pekerjaan_id', 'statusnikah_id']);
    }
}
