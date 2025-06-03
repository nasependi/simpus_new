<?php

namespace App\Livewire;

use App\Models\GeneralConsent as ModelsGeneralConsent;
use App\Models\Kunjungan;
use Flux\Flux;
use Livewire\Component;
use App\Models\PasienUmum;
use Livewire\Attributes\On;
use Livewire\Volt\Compilers\Mount;
use Livewire\WithPagination;

class GeneralConsent extends Component
{
    use WithPagination;

    protected $listeners = ['open-modal-generalconsent' => 'openModal'];
    public $search = '';
    public $editId, $deleteId, $data, $nama_pasien;

    // Field GeneralConsent
    public $pasien_id, $tanggal, $jam;
    public $persetujuan_pasien = false, $informasi_ketentuan_pembayaran = false, $informasi_hak_kewajiban = false;
    public $informasi_tata_tertib_rs = false, $kebutuhan_penerjemah_bahasa = false, $kebutuhan_rohaniawan = false;
    public $kerahasiaan_informasi = false, $pemeriksaan_ke_pihak_penjamin = false, $pemeriksaan_diakses_peserta_didik = false;
    public $anggota_keluarga_dapat_akses, $akses_fasyankes_rujukan = false;
    public $penanggung_jawab, $petugas_pemberi_penjelasan;

    public $showModal = false;
    public $kunjungan_id;


    public function render()
    {


        return view('livewire.general-consent');
    }


    public function openModal($kunjungan_id = null)
    {

        if ($kunjungan_id != null) {
            $pasien = Kunjungan::with('pasien')->where('id', $kunjungan_id)->first();
            $this->nama_pasien = $pasien->pasien->nama_lengkap;
            $this->showModal = true;
            Flux::modal('consentModal')->show();
        } else {
            Flux::toast(heading: 'Gagal', text: 'Data Pasien Tidak Ditemukan.', variant: 'danger');
        }
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('consentModal')->show();
    }

    public function edit($id)
    {
        $data = ModelsGeneralConsent::findOrFail($id);
        $this->editId = $id;

        foreach ($data->toArray() as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        Flux::modal('consentModal')->show();
    }

    public function save()
    {
        $this->validate([
            'pasien_id' => 'required|exists:pasien_umum,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'penanggung_jawab' => 'required|string',
            'petugas_pemberi_penjelasan' => 'required|string',
        ]);

        ModelsGeneralConsent::updateOrCreate(
            ['id' => $this->editId],
            $this->getPublicVars()
        );

        Flux::modal('consentModal')->close();
        Flux::toast('Berhasil', 'Data General Consent disimpan.', 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-consent')->show();
    }

    public function delete()
    {
        ModelsGeneralConsent::findOrFail($this->deleteId)->delete();
        Flux::modal('delete-consent')->close();
        Flux::toast('Terhapus', 'Data berhasil dihapus.', 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'pasien_id',
            'tanggal',
            'jam',
            'persetujuan_pasien',
            'informasi_ketentuan_pembayaran',
            'informasi_hak_kewajiban',
            'informasi_tata_tertib_rs',
            'kebutuhan_penerjemah_bahasa',
            'kebutuhan_rohaniawan',
            'kerahasiaan_informasi',
            'pemeriksaan_ke_pihak_penjamin',
            'pemeriksaan_diakses_peserta_didik',
            'anggota_keluarga_dapat_akses',
            'akses_fasyankes_rujukan',
            'penanggung_jawab',
            'petugas_pemberi_penjelasan',
            'editId'
        ]);
    }

    protected function getPublicVars()
    {
        return collect(get_object_vars($this))
            ->filter(fn($_, $key) => in_array($key, (new GeneralConsent)->getFillable()))
            ->toArray();
    }
}
