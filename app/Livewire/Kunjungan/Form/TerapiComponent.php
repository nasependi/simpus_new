<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\Terapi;
use App\Models\ObatResep;
use Livewire\Component;
use Flux\Flux;

class TerapiComponent extends Component
{
    public $kunjungan_id;
    public $state = [];
    public $editId;

    protected $listeners = ['save-terapi' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;

        $data = Terapi::where('kunjungan_id', $kunjungan_id)->first();

        if ($data) {
            $this->editId = $data->id;
            $this->state = $data->only([
                'obat_id',
                'nama_tindakan',
                'petugas',
                'tanggal_pelaksanaan_tindakan',
                'jam_mulai_tindakan',
                'jam_selesai_tindakan',
                'alat_medis',
                'bmhp'
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'state.obat_id' => 'required|exists:obat_resep,id',
            'state.nama_tindakan' => 'required|string|max:255',
            'state.petugas' => 'required|string|max:255',
            'state.tanggal_pelaksanaan_tindakan' => 'required|date',
            'state.jam_mulai_tindakan' => 'required',
            'state.jam_selesai_tindakan' => 'required',
            'state.alat_medis' => 'required|string',
            'state.bmhp' => 'required|string',
        ]);

        Terapi::updateOrCreate(
            ['kunjungan_id' => $this->kunjungan_id],
            array_merge($this->state, ['kunjungan_id' => $this->kunjungan_id])
        );

        Flux::toast(heading: 'Berhasil', text: 'Data Terapi disimpan.', variant: 'success');
    }

    public function render()
    {
        $obatResepList = ObatResep::all();

        return view('livewire.kunjungan.form.terapi-component', compact('obatResepList'));
    }
}
