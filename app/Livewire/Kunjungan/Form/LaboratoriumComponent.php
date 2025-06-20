<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\Laboratorium;
use Livewire\Component;
use Flux\Flux;

class LaboratoriumComponent extends Component
{
    public $kunjungan_id;
    public $form = [];

    protected $listeners = ['save-laboratorium' => 'save'];

    protected $rules = [
        'form.nama_pemeriksaan' => 'required|string',
        'form.nomor_pemeriksaan' => 'required|string',
        // Tambahkan validasi lainnya sesuai kebutuhan
    ];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;

        $lab = Laboratorium::where('kunjungan_id', $kunjungan_id)->first();
        if ($lab) {
            $this->form = $lab->toArray();
        }
    }

    public function saveAll()
    {
        $this->validate();

        $this->form['kunjungan_id'] = $this->kunjungan_id;

        Laboratorium::updateOrCreate(
            ['kunjungan_id' => $this->kunjungan_id],
            $this->form
        );

        Flux::toast(
            heading: 'Sukses',
            text: 'Data Laboratorium berhasil disimpan.',
            variant: 'success'
        );
    }

    public function render()
    {
        return view('livewire.kunjungan.form.laboratorium-component');
    }
}
