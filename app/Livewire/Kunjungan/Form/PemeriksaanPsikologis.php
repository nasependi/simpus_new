<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\PemeriksaanPsikologis as PemeriksaanPsikologisModel;
use Livewire\Component;
use Flux\Flux;

class PemeriksaanPsikologis extends Component
{
    public $kunjungan_id;
    public $editingId, $modal = false;

    public $form = [
        'kunjungan_id' => '',
        'status_psikologis' => '',
        'sosial_ekonomi' => '',
        'spiritual' => '',
    ];

    protected $listeners = ['save-psikologis' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;
        $this->form['kunjungan_id'] = $kunjungan_id;

        // Cek apakah sudah ada data untuk kunjungan ini
        $existing = PemeriksaanPsikologisModel::where('kunjungan_id', $kunjungan_id)->first();
        if ($existing) {
            $this->editingId = $existing->id;
            $this->form['status_psikologis'] = $existing->status_psikologis;
            $this->form['sosial_ekonomi'] = $existing->sosial_ekonomi;
            $this->form['spiritual'] = $existing->spiritual;
        }
    }

    public function render()
    {
        return view('livewire.kunjungan.form.pemeriksaan-psikologis');
    }

    public function closeModal()
    {
        $this->modal = false;
    }

    public function save()
    {
        $this->form['kunjungan_id'] = $this->kunjungan_id;

        $this->validate([
            'form.status_psikologis' => 'required|string|max:255',
            'form.sosial_ekonomi' => 'required|string|max:255',
            'form.spiritual' => 'required|string|max:255',
        ]);

        try {
            if ($this->editingId) {
                PemeriksaanPsikologisModel::findOrFail($this->editingId)->update($this->form);
            } else {
                $created = PemeriksaanPsikologisModel::create($this->form);
                $this->editingId = $created->id;
            }

            Flux::toast(heading: 'Sukses', text: 'Data Pemeriksaan Psikologis berhasil disimpan.', variant: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Error', text: 'Terjadi kesalahan: ' . $e->getMessage(), variant: 'danger');
        }
    }

    public function resetForm()
    {
        $this->editingId = null;

        $existing = PemeriksaanPsikologisModel::where('kunjungan_id', $this->kunjungan_id)->first();

        $this->form = [
            'kunjungan_id' => $this->kunjungan_id,
            'status_psikologis' => $existing->status_psikologis ?? '',
            'sosial_ekonomi' => $existing->sosial_ekonomi ?? '',
            'spiritual' => $existing->spiritual ?? '',
        ];
    }
}
