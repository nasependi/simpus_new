<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\PemeriksaanFisik as ModelPemeriksaanFisik;
use App\Models\TingkatKesadaran;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;

class PemeriksaanFisik extends Component
{
    use WithFileUploads;

    public $kunjungan_id;
    public $tingkat_kesadaran = '';
    public $tingkatKesadaranOptions = [];
    public $gambar_anatomitubuh;
    public $editingId = null;

    public $form = [
        'kunjungan_id' => '',
        'tingkatkesadaran_id' => '',
        'gambar_anatomitubuh' => '',
        'denyut_jantung' => '',
        'pernapasan' => '',
        'sistole' => '',
        'diastole' => '',
        'suhu_tubuh' => '',
        // Semua field pemeriksaan fisik
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

    protected $listeners = ['save-pemeriksaan-fisik' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;
        $this->form['kunjungan_id'] = $kunjungan_id;

        // Cek data sebelumnya
        $existing = ModelPemeriksaanFisik::where('kunjungan_id', $kunjungan_id)->first();
        if ($existing) {
            $this->editingId = $existing->id;
            $this->form = array_merge($this->form, $existing->toArray());
            $this->tingkat_kesadaran = optional($existing->tingkatKesadaran)->keterangan;
        }
    }

    public function render()
    {
        return view('livewire.kunjungan.form.pemeriksaan-fisik', [
            'daftarKesadaran' => TingkatKesadaran::get(),
        ]);
    }

    public function save()
    {
        $this->validate([
            'form.kunjungan_id' => 'required|exists:kunjungan,id',
            'form.tingkatkesadaran_id' => 'required|exists:tingkat_kesadaran,id',
            'gambar_anatomitubuh' => $this->editingId ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'form.denyut_jantung' => 'required|numeric',
        ]);

        try {
            // Upload gambar jika ada
            if ($this->gambar_anatomitubuh) {
                $path = $this->gambar_anatomitubuh->store('anatomi', 'public');
                $this->form['gambar_anatomitubuh'] = $path;
            }

            ModelPemeriksaanFisik::updateOrCreate(
                ['kunjungan_id' => $this->form['kunjungan_id']],
                $this->form
            );

            Flux::toast('Sukses', 'Data berhasil disimpan.', 'success');
        } catch (\Exception $e) {
            Flux::toast('Error', 'Terjadi kesalahan: ' . $e->getMessage(), 'danger');
        }
    }

    public function updatedTingkatKesadaran($value)
    {
        $this->tingkatKesadaranOptions = TingkatKesadaran::where('keterangan', 'like', "%{$value}%")
            ->limit(10)
            ->get(['id', 'keterangan'])
            ->toArray();
    }

    public function selectTingkatKesadaran($id, $keterangan)
    {
        $this->form['tingkatkesadaran_id'] = $id;
        $this->tingkat_kesadaran = $keterangan;
        $this->tingkatKesadaranOptions = [];
    }
}
