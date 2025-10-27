<?php

namespace App\Livewire\Kunjungan\Form;

use App\Livewire\ObatComponent;
use Flux\Flux;
use App\Models\Obat;
use App\Models\ObatResep;
use App\Models\Terapi;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\PemeriksaanTindakan;

class TerapiComponent extends Component
{
    public $kunjungan_id;
    public $state = [];
    public $editId;
    public $selectedTindakan = [];
    public $currentTindakan = '';
    public $pemeriksaanTindakanList = [];

    protected $listeners = ['save-terapi' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;
        $this->refreshTindakanList();

        $this->state['tanggal_pelaksanaan_tindakan'] = now()->toDateString();
        $this->state['jam_mulai_tindakan'] = now()->format('H:i');
        $this->state['jam_selesai_tindakan'] = now()->addMinutes(45)->format('H:i');

        $data = Terapi::where('kunjungan_id', $kunjungan_id)->first();
        if ($data) {
            $this->editId = $data->id;
            $this->state = $data->only([
                'obat_id',
                'petugas',
                'tanggal_pelaksanaan_tindakan',
                'jam_mulai_tindakan',
                'jam_selesai_tindakan',
                'alat_medis',
                'bmhp'
            ]);
            $this->selectedTindakan = array_filter(explode(', ', $data->nama_tindakan));
        }
    }

    public function refreshTindakanList()
    {
        $this->pemeriksaanTindakanList = PemeriksaanTindakan::whereNotIn('nama', $this->selectedTindakan)
            ->orderBy('nama')
            ->get();
    }

    public function addTindakan()
    {
        if (empty($this->currentTindakan)) {
            Flux::toast('Peringatan', 'Silakan pilih tindakan terlebih dahulu.', 'warning');
            return;
        }

        if (in_array($this->currentTindakan, $this->selectedTindakan)) {
            Flux::toast('Peringatan', 'Tindakan ini sudah dipilih sebelumnya.', 'warning');
            return;
        }

        $this->selectedTindakan[] = $this->currentTindakan;
        $this->currentTindakan = '';
        $this->refreshTindakanList();

        Flux::toast('Berhasil', 'Tindakan berhasil ditambahkan.', 'success');
    }

    public function removeTindakan($index)
    {
        unset($this->selectedTindakan[$index]);
        $this->selectedTindakan = array_values($this->selectedTindakan);
        $this->refreshTindakanList();

        Flux::toast('Berhasil', 'Tindakan berhasil dihapus.', 'success');
    }

    public function save()
    {
        try {
            // Skip if no tindakan selected
            if (empty($this->selectedTindakan)) {
                Flux::toast(
                    heading: 'Peringatan',
                    text: 'Pilih minimal satu tindakan',
                    variant: 'warning'
                );
                return;
            }

            // Gabungkan tindakan yang dipilih jadi satu string
            $this->state['nama_tindakan'] = implode(', ', $this->selectedTindakan);
            $this->state['kunjungan_id'] = $this->kunjungan_id;

            // Validasi
            $this->validate([
                'state.obat_id' => 'required|exists:obat_resep,id',
                'state.nama_tindakan' => 'required|string|max:500',
                'state.petugas' => 'required|string|max:255',
                'state.tanggal_pelaksanaan_tindakan' => 'required|date',
                'state.jam_mulai_tindakan' => 'required',
                'state.jam_selesai_tindakan' => 'required',
                'state.alat_medis' => 'required|string',
                'state.bmhp' => 'required|string',
            ]);

            // Remove the dd() statement that was here

            // Simpan / Update
            Terapi::updateOrCreate(
                ['kunjungan_id' => $this->kunjungan_id],
                $this->state
            );

            return true; // Return true for successful save
        } catch (\Exception $e) {
            logger()->error('Terapi save error', [
                'message' => $e->getMessage(),
                'state' => $this->state
            ]);

            Flux::toast(
                heading: 'Error',
                text: 'Gagal menyimpan data terapi' . $e->getMessage(),
                variant: 'error'
            );

            return false; // Return false for failed save
        }
    }
    public function render()
    {
        $obatResepList = \App\Models\ObatResep::select('obat_resep.*')
            ->get();

        return view('livewire.kunjungan.form.terapi-component', [
            'obatResepList' => $obatResepList,
            'pemeriksaanTindakanList' => $this->pemeriksaanTindakanList,
        ]);
    }
}
