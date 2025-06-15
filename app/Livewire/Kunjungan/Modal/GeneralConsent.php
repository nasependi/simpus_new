<?php

namespace App\Livewire\Kunjungan\Modal;

use Flux\Flux;
use Throwable;
use Livewire\Component;
use App\Models\Kunjungan;
use App\Models\PasienUmum;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Volt\Compilers\Mount;
use App\Models\GeneralConsent as ModelsGeneralConsent;

class GeneralConsent extends Component
{
    use WithPagination;

    protected $listeners = [
        'open-modal-generalconsent' => 'openModal',
        'cetak-generalconsent' => 'cetakConsent',
    ];

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

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->jam = now()->setTimezone('Asia/Jakarta')->format('H:i');
    }
    public function render()
    {
        return view('livewire.kunjungan.modal.general-consent');
    }


    public function openModal($kunjungan_id = null)
    {

        if ($kunjungan_id != null) {
            $pasien = Kunjungan::with('pasien')->where('id', $kunjungan_id)->first();
            $this->nama_pasien = $pasien->pasien->nama_lengkap;
            $this->kunjungan_id = $kunjungan_id;
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
        try {
            $validated = $this->validate([
                'kunjungan_id' => 'required|exists:kunjungan,id',
                'tanggal' => 'required|date',
                'jam' => 'required',
                'persetujuan_pasien' => 'nullable|boolean',
                'informasi_ketentuan_pembayaran' => 'nullable|boolean',
                'informasi_hak_kewajiban' => 'nullable|boolean',
                'informasi_tata_tertib_rs' => 'nullable|boolean',
                'kebutuhan_penerjemah_bahasa' => 'nullable|boolean',
                'kebutuhan_rohaniawan' => 'nullable|boolean',
                'kerahasiaan_informasi' => 'nullable|boolean',
                'pemeriksaan_ke_pihak_penjamin' => 'nullable|boolean',
                'pemeriksaan_diakses_peserta_didik' => 'nullable|boolean',
                'anggota_keluarga_dapat_akses' => 'nullable|string',
                'akses_fasyankes_rujukan' => 'nullable|boolean',
                'penanggung_jawab' => 'required|string',
                'petugas_pemberi_penjelasan' => 'required|string',
            ]);

            ModelsGeneralConsent::updateOrCreate(
                ['id' => $this->editId],
                $validated
            );

            Flux::modal('consentModal')->close();
            Flux::toast('Berhasil', 'Data General Consent disimpan.', 'success');

            $this->resetForm();
        } catch (Throwable $th) {
            Flux::toast(
                heading: 'Gagal',
                text: 'Data General Consent gagal disimpan. ' . $th->getMessage(),
                variant: 'danger'
            );
        }
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
}
