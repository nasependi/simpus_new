<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\PemeriksaanFisik as ModelPemeriksaanFisik;
use App\Models\TingkatKesadaran;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PemeriksaanFisik extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $Kunjungan_id;
    public $tingkatKesadaranOptions = [];
    public $tingkat_kesadaran = '';
    public $gambar_anatomitubuh;

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
        'save-pemeriksaan-fisik' => 'save',
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

        return view('livewire.kunjungan.form.pemeriksaan-fisik', [
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

    public function save()
    {
        $this->form['kunjungan_id'] = $this->Kunjungan_id;
        $this->validate([
            'form.kunjungan_id' => 'required|exists:kunjungan,id',
            'form.tingkatkesadaran_id' => 'required|exists:tingkat_kesadaran,id',
            'gambar_anatomitubuh' => 'required|image|max:2048', // max 2MB
            'form.denyut_jantung' => 'required',
            // validasi lainnya bisa ditambahkan jika dibutuhkan
        ]);
        try {
            if ($this->gambar_anatomitubuh) {
                // Simpan file saat tombol Save diklik
                $path = $this->gambar_anatomitubuh->store('anatomi', 'public');
                $this->form['gambar_anatomitubuh'] = $path;
            }
            if ($this->editingId) {
                ModelPemeriksaanFisik::findOrFail($this->editingId)->update($this->form);
                Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
            } else {
                ModelPemeriksaanFisik::create($this->form);
                Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
            }
            $this->closeModal();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Error', text: 'Terjadi kesalahan: ' . $e->getMessage(), variant: 'danger');
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

    public function updatedTingkatKesadaran($value)
    {
        // Autocomplete logic jika diperlukan
        $this->tingkatKesadaranOptions = TingkatKesadaran::where('keterangan', 'like', '%' . $value . '%')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'keterangan' => $item->keterangan,
            ])->toArray();
    }

    public function selectTingkatKesadaran($id, $keterangan)

    {
        $this->form['tingkatkesadaran_id'] = $id;
        $this->tingkat_kesadaran = $keterangan;
        $this->tingkatKesadaranOptions = []; // clear opsi
    }
}
