<?php

namespace App\Http\Livewire;

use App\Models\PersetujuanTindakan;
use App\Models\Kunjungan;
use Flux\Flux;
use Livewire\Component;

class PersetujuanTindakanForm extends Component
{
    public $state = [];
    public $listKunjungan;

    public function mount($id = null)
    {
        $this->listKunjungan = Kunjungan::all();

        if ($id) {
            $tindakan = PersetujuanTindakan::findOrFail($id);
            $this->state = $tindakan->toArray();
        }
    }

    public function test()
    {
        Flux::toast(
            heading: 'Changes saved.',
            text: 'You can always update this in your settings.',
        );
    }

    public function save()
    {
        $this->validate([
            'state.kunjungan_id' => 'required',
            'state.nama_dokter' => 'required',
            'state.persetujuan_penolakan' => 'required',
        ]);

        try {
            if (isset($this->state['id'])) {
                PersetujuanTindakan::findOrFail($this->state['id'])->update($this->state);
                session()->flash('success', 'Data berhasil diperbarui.');
            } else {
                PersetujuanTindakan::create($this->state);
                session()->flash('success', 'Data berhasil disimpan.');
            }

            return redirect()->route('persetujuan-tindakan.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.persetujuan-tindakan-form');
    }
}
