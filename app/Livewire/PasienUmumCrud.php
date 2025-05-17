<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Agama;
use App\Models\Regency;
use App\Models\Village;
use Livewire\Component;
use App\Models\District;
use App\Models\Province;
use App\Models\Pekerjaan;
use App\Models\PasienUmum;
use App\Models\Pendidikan;
use App\Models\JenisKelamin;
use Livewire\WithPagination;
use App\Models\StatusPernikahan;


class PasienUmumCrud extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';

    public $editId, $nama_lengkap, $no_rekamedis, $nik, $paspor, $ibu_kandung, $tempat_lahir;
    public $tanggal_lahir, $jk_id, $agama_id, $suku, $bahasa_dikuasai, $alamat_lengkap;
    public $rt, $rw, $kodepos_id;
    public $alamat_domisili, $domisili_rt, $domisili_rw, $domisili_kel, $domisili_kec, $domisili_kab, $domisili_kodepos, $domisili_prov, $domisili_negara;
    public $no_rumah, $no_hp, $pendidikan_id, $pekerjaan_id, $statusnikah_id;

    // public $regencies = [];
    public $search_provinsi = '';
    public $prov_id, $prov_name, $provinsiOptions = [];

    public $search_kabupaten = '';
    public $kab_id, $kab_name, $kabupatenOptions = [];

    public $search_kecamatan = '';
    public $kec_id, $kec_name, $kecamatanOptions = [];

    public $search_kelurahan, $kel_id, $kel_name, $kelurahanOptions = [];


    public $umur, $hitungan = 'tahun';

    public $halaman = 1;

    public function next()
    {
        if ($this->halaman < 3) {
            $this->halaman += 1;
        }
    }

    public function back()
    {
        if ($this->halaman > 1) {
            $this->halaman -= 1;
        }
    }

    public function updatedUmur()
    {
        $this->hitungTanggalLahir();
    }

    public function updatedHitungan()
    {
        $this->hitungTanggalLahir();
    }

    protected function hitungTanggalLahir()
    {
        if ($this->umur && $this->hitungan) {
            try {
                $now = now();

                switch ($this->hitungan) {
                    case 'hari':
                        $this->tanggal_lahir = $now->subDays($this->umur)->toDateString();
                        break;
                    case 'bulan':
                        $this->tanggal_lahir = $now->subMonths($this->umur)->toDateString();
                        break;
                    case 'tahun':
                        $this->tanggal_lahir = $now->subYears($this->umur)->toDateString();
                        break;
                }
            } catch (\Exception $e) {
                // Jika terjadi kesalahan, kosongkan tanggal
                $this->tanggal_lahir = null;
            }
        }
    }

    protected $rules = [
        'nama_lengkap' => 'required|max:100',
        'no_hp' => 'required|max:20',
        'jk_id' => 'required|exists:jenis_kelamin,id',
        'agama_id' => 'required|exists:agama,id',
        'pendidikan_id' => 'required|exists:pendidikan,id',
        'pekerjaan_id' => 'required|exists:pekerjaan,id',
        'statusnikah_id' => 'required|exists:status_pernikahan,id',
        'domisili_prov' => 'required|exists:provinces,id',
        'domisili_kab' => 'required|exists:regencies,id',
        'domisili_kec' => 'required|exists:districts,id',
        'domisili_kel' => 'required|exists:villages,id',
        'no_rekamedis' => 'nullable|string|max:50',
        'ibu_kandung' => 'nullable|string|max:255',
        'tempat_lahir' => 'nullable|string|max:100',
        'suku' => 'nullable|string|max:100',
        'bahasa_dikuasai' => 'nullable|string|max:100',
        'alamat_lengkap' => 'nullable|string',
        'no_rumah' => 'nullable|string|max:20',
    ];

    public function updatedSearchProvinsi()
    {
        $this->provinsiOptions = Province::where('name', 'like', '%' . $this->search_provinsi . '%')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['id' => $item->id, 'name' => $item->name])
            ->toArray();
    }

    public function selectProvinsi($id, $name)
    {
        $this->prov_id = $id;
        $this->prov_name = $name;
        $this->search_provinsi = $name;
        $this->provinsiOptions = [];

        // Reset dependent
        $this->kab_id = null;
        $this->search_kabupaten = '';
        $this->kabupatenOptions = [];
    }

    public function updatedSearchKabupaten()
    {
        if (!$this->prov_id) return;

        $this->kabupatenOptions = Regency::where('province_id', $this->prov_id)
            ->where('name', 'like', '%' . $this->search_kabupaten . '%')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['id' => $item->id, 'name' => $item->name])
            ->toArray();
    }

    public function selectKabupaten($id, $name)
    {
        $this->kab_id = $id;
        $this->kab_name = $name;
        $this->search_kabupaten = $name;
        $this->kabupatenOptions = [];

        // Reset dependent
        $this->kec_id = null;
        $this->search_kecamatan = '';
    }

    public function updatedSearchKecamatan()
    {
        if ($this->kab_id) {
            $this->kecamatanOptions = District::where('regency_id', $this->kab_id)
                ->where('name', 'like', '%' . $this->search_kecamatan . '%')
                ->limit(10)
                ->get()
                ->toArray();
        }
    }

    public function selectKecamatan($id, $name)
    {
        $this->kec_id = $id;
        $this->kec_name = $name;
        $this->search_kecamatan = $name;
        $this->kecamatanOptions = [];

        // Reset dependent
        $this->kel_id = null;
        $this->search_kelurahan = '';
    }

    public function updatedSearchKelurahan()
    {
        if ($this->kec_id) {
            $this->kelurahanOptions = Village::where('district_id', $this->kec_id)
                ->where('name', 'like', '%' . $this->search_kelurahan . '%')
                ->limit(10)
                ->get()
                ->toArray();
        }
    }

    public function selectKelurahan($id, $name)
    {
        $this->kel_id = $id;
        $this->kel_name = $name;
        $this->search_kelurahan = $name;
        $this->kelurahanOptions = [];
    }

    // DOMISLI

    public $sama_domisili = false;

    public $search_domisili_prov, $domisili_prov_id, $domisili_prov_nama, $domisiliProvinsiOptions = [];
    public $search_domisili_kab, $domisili_kab_id, $domisili_kab_nama, $domisiliKabupatenOptions = [];
    public $search_domisili_kec, $domisili_kec_id, $domisili_kec_nama, $domisiliKecamatanOptions = [];
    public $search_domisili_kel, $domisili_kel_id, $domisili_kel_nama, $domisiliKelurahanOptions = [];

    public function updatedSamaDomisili()
    {
        if ($this->sama_domisili) {
            $this->domisili_prov = $this->prov_id;
            $this->domisili_prov_nama = $this->prov_name;
            $this->search_domisili_prov = $this->prov_name;
            $this->domisili_kab = $this->kab_id;
            $this->domisili_kab_nama = $this->kab_name;
            $this->domisili_kec = $this->kec_id;
            $this->domisili_kec_nama = $this->kec_name;
            $this->domisili_kel = $this->kel_id;
            $this->domisili_kel_nama = $this->kel_name;
        } else {
            $this->reset([
                'domisili_prov',
                'domisili_prov_nama',
                'domisili_kab',
                'domisili_kab_nama',
                'domisili_kec',
                'domisili_kec_nama',
                'domisili_kel',
                'domisili_kel_nama',
                'search_domisili_prov',
                'search_domisili_kab',
                'search_domisili_kec',
                'search_domisili_kel'
            ]);
        }
    }

    public function updatedSearchDomisiliProv()
    {
        $this->domisiliProvinsiOptions = Province::where('name', 'like', '%' . $this->search_domisili_prov . '%')
            ->limit(10)->get(['id', 'name'])->toArray();
    }

    public function updatedSearchDomisiliKab()
    {
        if ($this->domisili_prov_id) {
            $this->domisiliKabupatenOptions = Regency::where('province_id', $this->domisili_prov_id)
                ->where('name', 'like', '%' . $this->search_domisili_kab . '%')
                ->limit(10)->get(['id', 'name'])->toArray();
        }
    }

    public function updatedSearchDomisiliKec()
    {
        if ($this->domisili_kab_id) {
            $this->domisiliKecamatanOptions = District::where('regency_id', $this->domisili_kab_id)
                ->where('name', 'like', '%' . $this->search_domisili_kec . '%')
                ->limit(10)->get(['id', 'name'])->toArray();
        }
    }

    public function updatedSearchDomisiliKel()
    {
        if ($this->domisili_kec_id) {
            $this->domisiliKelurahanOptions = Village::where('district_id', $this->domisili_kec_id)
                ->where('name', 'like', '%' . $this->search_domisili_kel . '%')
                ->limit(10)->get(['id', 'name'])->toArray();
        }
    }

    public function selectDomisiliProvinsi($id, $nama)
    {
        $this->domisili_prov_id = $id;
        $this->domisili_prov_nama = $nama;
        $this->search_domisili_prov = $nama;

        // Reset kabupaten ke bawah
        $this->reset([
            'domisili_kab_id',
            'domisili_kab_nama',
            'domisili_kec_id',
            'domisili_kec_nama',
            'domisili_kel_id',
            'domisili_kel_nama',
            'search_domisili_kab',
            'search_domisili_kec',
            'search_domisili_kel',
            'domisiliKabupatenOptions',
            'domisiliKecamatanOptions',
            'domisiliKelurahanOptions'
        ]);
    }

    public function selectDomisiliKabupaten($id, $nama)
    {
        $this->domisili_kab_id = $id;
        $this->domisili_kab_nama = $nama;
        $this->search_domisili_kab = $nama;

        // Reset kecamatan ke bawah
        $this->reset([
            'domisili_kec_id',
            'domisili_kec_nama',
            'domisili_kel_id',
            'domisili_kel_nama',
            'search_domisili_kec',
            'search_domisili_kel',
            'domisiliKecamatanOptions',
            'domisiliKelurahanOptions'
        ]);
    }

    public function selectDomisiliKecamatan($id, $nama)
    {
        $this->domisili_kec_id = $id;
        $this->domisili_kec_nama = $nama;
        $this->search_domisili_kec = $nama;

        // Reset kelurahan
        $this->reset([
            'domisili_kel_id',
            'domisili_kel_nama',
            'search_domisili_kel',
            'domisiliKelurahanOptions'
        ]);
    }

    public function selectDomisiliKelurahan($id, $nama)
    {
        $this->domisili_kel_id = $id;
        $this->domisili_kel_nama = $nama;
        $this->search_domisili_kel = $nama;
    }

    // END DOMISLI

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
        // $province = Province::pluck('name', 'id');
        return view('livewire.pasien-umum', [
            'data' => $data,
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
        $this->prov_id = $data->prov_id;
        $prov = Province::findOrFail($data->prov_id);
        $this->selectProvinsi($this->prov_id, $prov->name);
        $this->kab_id = $data->kab_id;
        $kab = Regency::findOrFail($data->kab_id);
        $this->selectKabupaten($this->kab_id, $kab->name);
        $this->kec_id = $data->kec_id;
        $kec = District::findOrFail($data->kec_id);
        $this->selectKecamatan($this->kec_id, $kec->name);
        $this->kel_id = $data->kel_id;
        $kel = Village::findOrFail($data->kel_id);
        $this->selectKelurahan($this->kel_id, $kel->name);
        $this->kodepos_id = $data->kodepos_id;
        $this->alamat_domisili = $data->alamat_domisili;
        $this->domisili_rt = $data->domisili_rt;
        $this->domisili_rw = $data->domisili_rw;
        $this->domisili_prov = $data->domisili_prov;
        $this->domisili_kab = $data->domisili_kab;
        $this->domisili_kec = $data->domisili_kec;
        $this->domisili_kel = $data->domisili_kel;
        $this->domisili_kodepos = $data->domisili_kodepos;
        $this->domisili_negara = $data->domisili_negara;
        $this->no_rumah = $data->no_rumah;
        $this->no_hp = $data->no_hp;
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
                'no_rekamedis' => $this->no_rekamedis,
                'nik' => $this->nik,
                'paspor' => $this->paspor,
                'ibu_kandung' => $this->ibu_kandung,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jk_id' => $this->jk_id,
                'agama_id' => $this->agama_id,
                'suku' => $this->suku,
                'bahasa_dikuasai' => $this->bahasa_dikuasai,
                'alamat_lengkap' => $this->alamat_lengkap,
                'rt' => $this->rt,
                'rw' => $this->rw,
                'kel_id' => $this->kel_id,
                'kec_id' => $this->kec_id,
                'kab_id' => $this->kab_id,
                'kodepos_id' => $this->kodepos_id,
                'prov_id' => $this->prov_id,
                'alamat_domisili' => $this->alamat_domisili,
                'domisili_rt' => $this->domisili_rt,
                'domisili_rw' => $this->domisili_rw,
                'domisili_kel' => $this->domisili_kel,
                'domisili_kec' => $this->domisili_kec,
                'domisili_kab' => $this->domisili_kab,
                'domisili_prov' => $this->domisili_prov,
                'domisili_kodepos' => $this->domisili_kodepos,
                'domisili_negara' => $this->domisili_negara,
                'no_rumah' => $this->no_rumah,
                'no_hp' => $this->no_hp,
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
