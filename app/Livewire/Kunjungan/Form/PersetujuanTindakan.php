<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\PersetujuanTindakan as PersetujuanTindakanModel;
use Livewire\Component;
use Flux\Flux;

class PersetujuanTindakan extends Component
{
    public $kunjungan_id;
    public $state = [];
    public $editId;

    protected $listeners = ['save-persetujuan-tindakan' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;

        $data = PersetujuanTindakanModel::where('kunjungan_id', $kunjungan_id)->first();

        if ($data) {
            $this->editId = $data->id;
            $this->state = [
                'nama_dokter' => $data->nama_dokter,
                'nama_petugas_mendampingi' => $data->nama_petugas_mendampingi,
                'nama_keluarga_pasien' => $data->nama_keluarga_pasien,
                'tindakan_dilakukan' => $data->tindakan_dilakukan,
                'konsekuensi_tindakan' => $data->konsekuensi_tindakan,
                'tanggal_tindakan' => $data->tanggal_tindakan,
                'jam_tindakan' => $data->jam_tindakan,
                'ttd_dokter' => $data->ttd_dokter,
                'ttd_pasien_keluarga' => $data->ttd_pasien_keluarga,
                'saksi1' => $data->saksi1,
                'saksi2' => $data->saksi2,
                'persetujuan_penolakan' => $data->persetujuan_penolakan,
            ];
        }
    }

    public function save()
    {
        $this->validate([
            'kunjungan_id' => 'required|exists:kunjungan,id',
            'state.nama_dokter' => 'required|string|max:255',
            'state.nama_petugas_mendampingi' => 'required|string|max:255',
            'state.nama_keluarga_pasien' => 'nullable|string|max:255',
            'state.tindakan_dilakukan' => 'required|string|max:255',
            'state.konsekuensi_tindakan' => 'required|string|max:255',
            'state.tanggal_tindakan' => 'required|date',
            'state.jam_tindakan' => 'required',
            'state.ttd_dokter' => 'required|string|max:255',
            'state.ttd_pasien_keluarga' => 'required|string|max:255',
            'state.saksi1' => 'required|string|max:255',
            'state.saksi2' => 'required|string|max:255',
            'state.persetujuan_penolakan' => 'required|in:0,1',
        ]);

        PersetujuanTindakanModel::updateOrCreate(
            ['kunjungan_id' => $this->kunjungan_id],
            [
                'nama_dokter' => $this->state['nama_dokter'],
                'nama_petugas_mendampingi' => $this->state['nama_petugas_mendampingi'],
                'nama_keluarga_pasien' => $this->state['nama_keluarga_pasien'],
                'tindakan_dilakukan' => $this->state['tindakan_dilakukan'],
                'konsekuensi_tindakan' => $this->state['konsekuensi_tindakan'],
                'tanggal_tindakan' => $this->state['tanggal_tindakan'],
                'jam_tindakan' => $this->state['jam_tindakan'],
                'ttd_dokter' => $this->state['ttd_dokter'],
                'ttd_pasien_keluarga' => $this->state['ttd_pasien_keluarga'],
                'saksi1' => $this->state['saksi1'],
                'saksi2' => $this->state['saksi2'],
                'persetujuan_penolakan' => $this->state['persetujuan_penolakan'],
            ]
        );

        Flux::toast(heading: 'Berhasil', text: 'Data Persetujuan Tindakan disimpan.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.kunjungan.form.persetujuan-tindakan');
    }
}
