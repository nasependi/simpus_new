<?php

namespace App\Livewire\Pemeriksaan;

use Exception;
use Flux\Flux;
use Livewire\Component;
use App\Models\PersetujuanTindakan;

class FormPersetujuanTindakan extends Component
{
    public $state = [];
    public $id, $testing;

    public $kunjungan_id;

    public function mount()
    {
        logger('Nilai kunjungan_id dari mount:', ['kunjungan_id' => $this->kunjungan_id]);

        $this->id = $this->kunjungan_id;
        $this->state['kunjungan_id'] = $this->kunjungan_id;
    }


    public function save()
    {
        dd($this->testing);

        try {

            $this->validate([
                'state.kunjungan_id' => 'required',
                'state.nama_dokter' => 'required',
                'state.persetujuan_penolakan' => 'required',
            ]);

            if (isset($this->state['id'])) {
                PersetujuanTindakan::findOrFail($this->state['id'])->update($this->state);
                Flux::toast(heading: 'Sukses', text: 'Data berhasil diperbaharui.', variant: 'success');
            } else {
                PersetujuanTindakan::create($this->state);
                Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
            }

            return redirect()->route('persetujuan-tindakan.index');
        } catch (Exception $e) {
            Flux::toast(heading: 'Gagal', text: 'Data tidak berhasil disimpan. ' . $e->getMessage(), variant: 'danger');
        }
    }


    public function render()
    {
        return view('livewire.pemeriksaan.form-persetujuan-tindakan');
    }
}
