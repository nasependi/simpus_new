<?php

namespace App\Livewire;

use App\Models\PemeriksaanFisik as ModelPemeriksaanFisik;
use App\Models\TingkatKesadaran;
use Livewire\Component;
use Livewire\WithPagination;

class PemeriksaanFisik extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $Kunjungan_id;

    public $form = [
        'kunjungan_id' => '',
        'tingkatkesadaran_id' => '',
        'gambar_anatomitubuh' => '',
        'denyut_jantung' => '',
        'pernapasan' => '',
        'sistole' => '',
        'diastole' => '',
        'suhu_tubuh' => '',
        'kepala' => '',
        'mata' => '',
        'telinga' => '',
        'hidung' => '',
        'rambut' => '',
        'bibir' => '',
        'gigi_geligi' => '',
        'lidah' => '',
        'langit_langit' => '',
        'leher' => '',
        'tenggorokan' => '',
        'tonsil' => '',
        'dada' => '',
        'payudara' => '',
        'punggung' => '',
        'perut' => '',
        'genital' => '',
        'anus' => '',
        'lengan_atas' => '',
        'lengan_bawah' => '',
        'kuku_tangan' => '',
        'persendian_tangan' => '',
        'tungkai_atas' => '',
        'tungkai_bawah' => '',
        'jari_kaki' => '',
        'kuku_kaki' => '',
        'persendian_kaki' => '',
    ];

    public $editingId = null;
    public $modal = false;
    public $autocomplete = [];

    protected $listeners = [
        'open-modal-generalconsent' => 'openModal',
        'cetak-generalconsent' => 'cetakConsent',
    ];

    public function Mount($kunjungan_id)
    {
        $this->Kunjungan_id = $kunjungan_id;
    }

    public function render()
    {
        $daftarkesadaran = TingkatKesadaran::get();
        $data = ModelPemeriksaanFisik::with('kunjungan', 'tingkatKesadaran')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.pemeriksaan-fisik', [
            'data' => $data,
            'daftarKesadaran' => $daftarkesadaran,
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
    }

    public function resetForm()
    {
        $this->form = array_fill_keys(array_keys($this->form), '');
        $this->editingId = null;
    }

    public function store()
    {
        $this->form['kunjungan_id'] = $this->Kunjungan_id;
        $this->validate([
            'form.kunjungan_id' => 'required|exists:kunjungan,id',
            'form.tingkatkesadaran_id' => 'required|exists:tingkat_kesadaran,id',
            'form.gambar_anatomitubuh' => 'required',
            'form.denyut_jantung' => 'required',
            // validasi lainnya bisa ditambahkan jika dibutuhkan
        ]);

        try {
            if ($this->editingId) {
                ModelPemeriksaanFisik::findOrFail($this->editingId)->update($this->form);
                session()->flash('success', 'Data berhasil diperbarui.');
            } else {
                ModelPemeriksaanFisik::create($this->form);
                session()->flash('success', 'Data berhasil disimpan.');
            }
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = ModelPemeriksaanFisik::findOrFail($id);
        $this->form = $data->toArray();
        $this->editingId = $id;
        $this->modal = true;
    }

    public function delete($id)
    {
        try {
            ModelPemeriksaanFisik::findOrFail($id)->delete();
            session()->flash('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updatedFormTingkatkesadaranId($value)
    {
        // Autocomplete logic jika diperlukan
    }

    public function searchKesadaran($query)
    {
        $this->autocomplete = TingkatKesadaran::where('keterangan', 'like', "%$query%")
            ->limit(5)
            ->get()
            ->toArray();
    }
}
