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
use Flux\Flux;

class PasienUmumCrud extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';

    public $editId, $nama_lengkap, $no_rekamedis, $nik, $paspor, $ibu_kandung, $tempat_lahir;
    public $tanggal_lahir, $jk_id, $agama_id, $suku, $bahasa_dikuasai, $alamat_lengkap;
    public $rt, $rw, $kel_id, $kec_id, $kab_id, $kodepos_id, $prov_id;
    public $alamat_domisili, $domisili_rt, $domisili_rw, $domisili_kel, $domisili_kec, $domisili_kab, $domisili_kodepos, $domisili_prov, $domisili_negara;
    public $no_rumah, $no_hp, $pendidikan_id, $pekerjaan_id, $statusnikah_id;

    public $regencies = [];

    protected $rules = [
        'nama_lengkap' => 'required|max:100',
        // 'no_hp' => 'required|max:20',
        'jk_id' => 'required|exists:jenis_kelamin,id',
        'agama_id' => 'required|exists:agama,id',
        'pendidikan_id' => 'required|exists:pendidikan,id',
        'pekerjaan_id' => 'required|exists:pekerjaan,id',
        'statusnikah_id' => 'required|exists:status_pernikahan,id',
    ];

    public function updatedProvId($value)
    {
        $this->kab_id = null; // Reset kabupaten
        $this->regencies = Regency::where('province_id', $value)->pluck('name', 'id');
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field && $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->sortField = $field;
    }

    public function render()
    {
        $data = PasienUmum::with(['agama', 'jenisKelamin', 'pendidikan', 'pekerjaan', 'statusPernikahan', 'province'])
            ->where('nama_lengkap', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);


        // Ambil provinsi
        $province = Province::pluck('name', 'id');
        return view('livewire.pasien-umum', [
            'data' => $data,
            'province' => $province,
            'regencies' => $this->regencies,
            'jenis_kelamin' => JenisKelamin::get(),
            'agama' => Agama::get(),
            'pendidikan' => Pendidikan::get(),
            'pekerjaan' => Pekerjaan::get(),
            'status_pernikahan' => StatusPernikahan::get(),
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
        $this->no_rekamedis = $data->no_rekamedis;
        $this->nik = $data->nik;
        $this->paspor = $data->paspor;
        $this->ibu_kandung = $data->ibu_kandung;
        $this->tempat_lahir = $data->tempat_lahir;
        $this->tanggal_lahir = $data->tanggal_lahir;
        $this->jk_id = $data->jk_id;
        $this->agama_id = $data->agama_id;
        $this->suku = $data->suku;
        $this->bahasa_dikuasai = $data->bahasa_dikuasai;
        $this->alamat_lengkap = $data->alamat_lengkap;
        $this->rt = $data->rt;
        $this->rw = $data->rw;
        $this->kel_id = $data->kel_id;
        $this->kec_id = $data->kec_id;
        $this->kab_id = $data->kab_id;
        $this->kodepos_id = $data->kodepos_id;
        $this->prov_id = $data->prov_id;
        $this->alamat_domisili = $data->alamat_domisili;
        $this->domisili_rt = $data->domisili_rt;
        $this->domisili_rw = $data->domisili_rw;
        $this->domisili_kel = $data->domisili_kel;
        $this->domisili_kec = $data->domisili_kec;
        $this->domisili_kab = $data->domisili_kab;
        $this->domisili_kodepos = $data->domisili_kodepos;
        $this->domisili_prov = $data->domisili_prov;
        $this->domisili_negara = $data->domisili_negara;
        $this->no_rumah = $data->no_rumah;
        $this->no_hp = $data->no_hp;
        $this->pendidikan_id = $data->pendidikan_id;
        $this->pekerjaan_id = $data->pekerjaan_id;
        $this->statusnikah_id = $data->statusnikah_id;

        $this->kab_id = $data->kab_id;
        $this->updatedProvId($this->prov_id);

        Flux::modal('pasienModal')->show();
    }

    public function save()
    {
        $this->validate();

        PasienUmum::updateOrCreate(
            ['id' => $this->editId],
            [
                'nama_lengkap' => $this->nama_lengkap,
                'no_rekamedis' => $this->no_rekamedis,
                'nik' => $this->nik,
                'paspor' => $this->paspor,
                'ibu_kandung' => $this->ibu_kandung,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => "2003-08-09",
                'jk_id' => $this->jk_id,
                'agama_id' => $this->agama_id,
                'suku' => 'Suku',
                'bahasa_dikuasai' => 'Bahasa',
                'alamat_lengkap' => $this->alamat_lengkap,
                'rt' => 'RT',
                'rw' => 'RW',
                'kel_id' => '1101010001',
                'kec_id' => '1101010',
                'kab_id' => $this->kab_id,
                'kodepos_id' => '41161',
                'prov_id' => $this->prov_id,
                'alamat_domisili' => 'example',
                'domisili_rt' => 'example',
                'domisili_rw' => 'example',
                'domisili_kel' => '1101010001',
                'domisili_kec' => '1101010',
                'domisili_kab' => '1101',
                'domisili_kodepos' => '41161',
                'domisili_prov' => '11',
                'domisili_negara' => 'example',
                'no_rumah' => 'Example',
                'no_hp' => 'Example',
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
