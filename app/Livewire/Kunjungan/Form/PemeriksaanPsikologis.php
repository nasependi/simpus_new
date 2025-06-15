<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\PemeriksaanPsikologis as PemeriksaanPsikologisModel;
use Livewire\Component;

use Flux\Flux;

class PemeriksaanPsikologis extends Component
{
    public $kunjungan_id;
    public $editingId;
    protected $listeners = ['save-psikologis' => 'save'];

    public $form = [
        'kunjungan_id' => '',
        'status_psikologis' => '',
        'sosial_ekonomi' => '',
        'spiritual' => '',
    ];

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
                Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
            } else {
                PemeriksaanPsikologisModel::create($this->form);
                Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
            }
            $this->closeModal();
        } catch (\Exception $e) {
            Flux::toast(heading: 'Error', text: 'Terjadi kesalahan: ' . $e->getMessage(), variant: 'danger');
        }
    }
}
