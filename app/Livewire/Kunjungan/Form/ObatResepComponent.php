<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\ObatResep;
use Livewire\Component;
use Flux\Flux;

class ObatResepComponent extends Component
{
    public $kunjungan_id;
    public $state = [];

    protected $listeners = ['obat-resep' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;
    }

    public function save()
    {
        $this->validate([
            'state.tb_pasien' => 'required|string|max:255',
            'state.bb_pasien' => 'required|string|max:255',
            'state.id_resep' => 'required|string|max:255',
            'state.nama_obat' => 'required|string|max:255',
            'state.id_obat' => 'required|string|max:255',
            'state.sediaan' => 'required|string|max:255',
            'state.jumlah_obat' => 'required|integer|min:1',
            'state.metode_pemberian' => 'required|string',
            'state.dosis_diberikan' => 'required|string',
            'state.unit' => 'required|string',
            'state.frekuensi' => 'required|string',
            'state.aturan_tambahan' => 'nullable|string',
            'state.catatan_resep' => 'nullable|string',
            'state.dokter_penulis_resep' => 'required|string',
            'state.nomor_telepon_dokter' => 'required|string',
            'state.tanggal_penulisan_resep' => 'required|date',
            'state.jam_penulisan_resep' => 'required',
            'state.ttd_dokter' => 'required|string',
            'state.status_resep' => 'required|string',
            'state.pengkajian_resep' => 'required|string',
        ]);

        ObatResep::create(array_merge(
            $this->state,
            ['kunjungan_id' => $this->kunjungan_id]
        ));

        $this->reset('state');
        Flux::toast(heading: 'Berhasil', text: 'Obat resep berhasil ditambahkan.', variant: 'success');
    }

    public function delete($id)
    {
        ObatResep::findOrFail($id)->delete();
        Flux::toast(heading: 'Dihapus', text: 'Obat resep berhasil dihapus.', variant: 'success');
    }

    public function render()
    {
        $resep = ObatResep::where('kunjungan_id', $this->kunjungan_id)->get();

        return view('livewire.kunjungan.form.obat-resep-component', compact('resep'));
    }
}
