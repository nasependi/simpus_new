<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Livewire\PasienUmum;
use Livewire\WithPagination;

class RegisterPasien extends Component
{

    use WithPagination;

    public $nama_lengkap, $no_rekamedis, $nik, $paspor, $ibu_kandung, $tempat_lahir,
        $tanggal_lahir, $jk_id, $agama_id, $suku, $bahasa_dikuasai, $alamat_lengkap,
        $rt_id, $rw_id, $kel_id, $kec_id, $kab_id, $kodepos_id, $prov_id,
        $alamat_domisili, $domisili_rt, $domisili_rw, $domisili_kel, $domisili_kec,
        $domisili_kab, $domisili_kodepos, $domisili_prov, $domisili_negara, $no_rumah,
        $no_hp, $pendidikan_id, $pekerjaan_id, $statusnikah_id, $editId, $deleteId;

    public $search = '';
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';

    protected $rules = [
        'nama_lengkap' => 'required|max:100',
        'no_rekamedis' => 'required|max:50',
        'nik' => 'required|digits:16',
        'ibu_kandung' => 'required|max:50',
        'tempat_lahir' => 'required|max:30',
        'tanggal_lahir' => 'required|date',
        'jk_id' => 'required',
        'agama_id' => 'required',
        'alamat_lengkap' => 'required',
        'rt_id' => 'required',
        'rw_id' => 'required',
        'kel_id' => 'required',
        'kec_id' => 'required',
        'kab_id' => 'required',
        'kodepos_id' => 'required',
        'prov_id' => 'required',
        'alamat_domisili' => 'required',
        'no_hp' => 'required|max:20',
        'pendidikan_id' => 'required',
        'pekerjaan_id' => 'required',
        'statusnikah_id' => 'required',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $data = PasienUmum::where('nama_lengkap', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        return view('livewire.register-pasien', compact('data'));
    }

    public function create()
    {

        $this->resetForm();
        Flux::modal('pasienUmumModal')->show();
    }

    public function modalShow()
    {
        Flux::modal('edit-profile')->show();
    }

    public function edit($id)
    {
        $pasien = PasienUmum::findOrFail($id);
        $this->editId = $pasien->id;
        $this->nama_lengkap = $pasien->nama_lengkap;
        $this->no_rekamedis = $pasien->no_rekamedis;
        $this->nik = $pasien->nik;
        $this->ibu_kandung = $pasien->ibu_kandung;
        $this->tempat_lahir = $pasien->tempat_lahir;
        $this->tanggal_lahir = $pasien->tanggal_lahir;
        $this->jk_id = $pasien->jk_id;
        $this->agama_id = $pasien->agama_id;
        $this->alamat_lengkap = $pasien->alamat_lengkap;
        $this->no_hp = $pasien->no_hp;
        $this->pendidikan_id = $pasien->pendidikan_id;
        $this->pekerjaan_id = $pasien->pekerjaan_id;
        $this->statusnikah_id = $pasien->statusnikah_id;

        Flux::modal('pasienUmumModal')->show();
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
                'ibu_kandung' => $this->ibu_kandung,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jk_id' => $this->jk_id,
                'agama_id' => $this->agama_id,
                'alamat_lengkap' => $this->alamat_lengkap,
                'no_hp' => $this->no_hp,
                'pendidikan_id' => $this->pendidikan_id,
                'pekerjaan_id' => $this->pekerjaan_id,
                'statusnikah_id' => $this->statusnikah_id,
            ]
        );

        Flux::modal('pasienUmumModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-pasienUmum')->show();
    }

    public function delete()
    {
        PasienUmum::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-pasienUmum')->close();
    }

    public function resetForm()
    {
        $this->reset([
            'nama_lengkap',
            'no_rekamedis',
            'nik',
            'paspor',
            'ibu_kandung',
            'tempat_lahir',
            'tanggal_lahir',
            'jk_id',
            'agama_id',
            'suku',
            'bahasa_dikuasai',
            'alamat_lengkap',
            'rt_id',
            'rw_id',
            'kel_id',
            'kec_id',
            'kab_id',
            'kodepos_id',
            'prov_id',
            'alamat_domisili',
            'domisili_rt',
            'domisili_rw',
            'domisili_kel',
            'domisili_kec',
            'domisili_kab',
            'domisili_kodepos',
            'domisili_prov',
            'domisili_negara',
            'no_rumah',
            'no_hp',
            'pendidikan_id',
            'pekerjaan_id',
            'statusnikah_id',
            'editId',
            'deleteId'
        ]);
    }
}
