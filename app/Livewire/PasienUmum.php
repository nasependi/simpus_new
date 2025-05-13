<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PasienUmum as PasienModel;
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
use Illuminate\Support\Facades\DB;

class PasienUmum extends Component
{
    use WithPagination;

    public $editId, $deleteId;
    public $search = '';
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';

    public $nama_lengkap, $no_rekamedis, $nik, $paspor, $ibu_kandung, $tempat_lahir;
    public $tanggal_lahir, $jk_id, $agama_id, $suku, $bahasa_dikuasai, $alamat_lengkap;
    public $rt, $rw, $kel_id, $kec_id, $kab_id, $kodepos_id, $prov_id;
    public $alamat_domisili, $domisili_rt, $domisili_rw, $domisili_kel, $domisili_kec, $domisili_kab, $domisili_kodepos, $domisili_prov, $domisili_negara;
    public $no_rumah, $no_hp, $pendidikan_id, $pekerjaan_id, $statusnikah_id;


    protected $rules = [
        'nama_lengkap' => 'required|string|max:100',
        'no_rekamedis' => 'required|max:50',
        'nik' => 'required|max:16',
        'ibu_kandung' => 'required|max:50',
        'tempat_lahir' => 'required|max:30',
        'tanggal_lahir' => 'required|date',
        'jk_id' => 'required',
        'agama_id' => 'required',
        'alamat_lengkap' => 'required',
        'rt' => 'required',
        'rw' => 'required',
        'kel_id' => 'required',
        'kec_id' => 'required',
        'kab_id' => 'required',
        'prov_id' => 'required',
        'alamat_domisili' => 'required',
        'domisili_rt' => 'required',
        'domisili_rw' => 'required',
        'domisili_kel' => 'required',
        'domisili_kec' => 'required',
        'domisili_kab' => 'required',
        'domisili_prov' => 'required',
        'domisili_negara' => 'required',
        'no_hp' => 'required|max:20',
        'pendidikan_id' => 'required',
        'pekerjaan_id' => 'required',
        'statusnikah_id' => 'required',
    ];

    public function render()
    {
        $data = PasienModel::with(['agama', 'jenisKelamin'])
            ->where('nama_lengkap', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        dd(JenisKelamin::all());

        return view('livewire.pasien', [
            'jks' => JenisKelamin::all(),
            'agamas' => Agama::all(),
            'pendidikans' => Pendidikan::all(),
            'pekerjaans' => Pekerjaan::all(),
            'statusNikahs' => StatusPernikahan::all(),
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
        Flux::modal('pasienModal')->show();
    }

    public function edit($id)
    {
        $item = PasienModel::findOrFail($id);
        $this->editId = $item->id;

        foreach ($item->getAttributes() as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }

        Flux::modal('pasienModal')->show();
    }

    public function save()
    {
        $this->validate();
        dd('Tombol simpan dipanggil');

        try {
            PasienModel::updateOrCreate(
                ['id' => $this->editId],
                $this->only(array_keys($this->rules))
            );

            Flux::modal('pasienModal')->close();
            Flux::toast('Sukses', 'Data pasien berhasil disimpan.', 'success');
            $this->resetForm();
        } catch (\Throwable $th) {
            Flux::toast('Gagal', 'Terjadi kesalahan saat menyimpan data: ' . $th->getMessage(), 'destructive');
        }
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-pasien')->show();
    }

    public function delete()
    {
        try {
            PasienModel::findOrFail($this->deleteId)->delete();
            Flux::toast('Terhapus', 'Data pasien telah dihapus.', 'success');
            Flux::modal('delete-pasien')->close();
        } catch (\Throwable $th) {
            Flux::toast('Gagal', 'Gagal menghapus data: ' . $th->getMessage(), 'destructive');
        }
    }

    public function resetForm()
    {
        $this->reset(array_keys($this->rules));
        $this->reset(['paspor', 'suku', 'bahasa_dikuasai', 'kodepos_id', 'domisili_kodepos', 'no_rumah', 'editId']);
    }
}
