<?php

namespace App\Livewire\Kunjungan\Form;

use Flux\Flux;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use App\Models\PersetujuanTindakan as PersetujuanTindakanModel;

class PersetujuanTindakan extends Component
{
    public $kunjungan_id;
    public $state = [
        'ttd_dokter' => null,
        'ttd_pasien_keluarga' => null,
        'saksi1' => null,
        'saksi2' => null,
    ];
    public $editId;

    protected $listeners = ['syncSignaturesDone' => 'save'];


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
        } else {
            // Set nilai default saat tidak ada data
            $this->state['tanggal_tindakan'] = Carbon::now()->format('Y-m-d');
            $this->state['jam_tindakan'] = Carbon::now()->format('H:i');
        }
    }


    public function save()
    {

        // dd($this->state);
        try {
            if ($this->state['ttd_dokter']) {
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->state['ttd_dokter']));
                Storage::disk('public')->put('signatures/ttd_dokter.png', $image);
            }

            if ($this->state['ttd_pasien_keluarga']) {
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->state['ttd_pasien_keluarga']));
                Storage::disk('public')->put('signatures/ttd_pasien_keluarga.png', $image);
            }

            if ($this->state['saksi1']) {
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->state['saksi1']));
                Storage::disk('public')->put('signatures/saksi1.png', $image);
            }

            if ($this->state['saksi2']) {
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->state['saksi2']));
                Storage::disk('public')->put('signatures/saksi2.png', $image);
            }

            $this->validate([
                'kunjungan_id' => 'required|exists:kunjungan,id',
                'state.nama_dokter' => 'required|string|max:255',
                'state.nama_petugas_mendampingi' => 'required|string|max:255',
                'state.nama_keluarga_pasien' => 'nullable|string|max:255',
                'state.tindakan_dilakukan' => 'required|string|max:255',
                'state.konsekuensi_tindakan' => 'required|string|max:255',
                'state.tanggal_tindakan' => 'required|date',
                'state.jam_tindakan' => 'required',
                'state.ttd_dokter' => 'required|string',
                'state.ttd_pasien_keluarga' => 'required|string',
                'state.saksi1' => 'required|string',
                'state.saksi2' => 'required|string',
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

            // Flux::toast(heading: 'Berhasil', text: 'Data Persetujuan Tindakan disimpan.', variant: 'success');
        } catch (\Throwable $th) {
            Flux::toast(heading: 'Gagal', text: 'Data Persetujuan Tindakan gagal disimpan: ' . $th->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.kunjungan.form.persetujuan-tindakan');
    }
}
