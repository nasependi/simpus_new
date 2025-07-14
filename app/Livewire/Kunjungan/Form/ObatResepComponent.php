<?php

namespace App\Livewire\Kunjungan\Form;

use Flux\Flux;
use App\Models\Obat;
use Livewire\Component;
use App\Models\ObatResep;
use App\Livewire\ObatComponent;
use Illuminate\Validation\Rule;

class ObatResepComponent extends Component
{
    public $kunjungan_id, $obatList, $sediaanList;
    public $state = [];

    protected $listeners = ['obat-resep' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;

        // Ambil ID obat yang sudah pernah dipakai pada kunjungan ini
        $usedObatIds = ObatResep::where('kunjungan_id', $kunjungan_id)->pluck('id_obat')->toArray();

        // Ambil daftar obat yang belum digunakan
        $this->obatList = Obat::whereNotIn('id', $usedObatIds)->get();

        // Ambil tb dan bb dari session
        $this->state['tb_pasien'] = session('tb_pasien');
        $this->state['bb_pasien'] = session('bb_pasien');
    }

    public function updatedState($key, $propertyName)
    {
        if ($propertyName === 'id_obat') {
            $obat = Obat::find($this->state['id_obat']);
            if ($obat) {
                $this->state['nama_obat'] = $obat->nama_obat;
                $this->state['sediaan'] = $obat->sediaan;
            }
        }
    }

    public function save()
    {
        $this->state['id_resep'] = $this->kunjungan_id;
        $this->state['dokter_penulis_resep'] = auth()->user()->name;
        $this->state['nomor_telepon_dokter'] = auth()->user()->phone ?? '-';
        $this->state['tanggal_penulisan_resep'] = now()->toDateString();
        $this->state['jam_penulisan_resep'] = now()->toTimeString();
        $this->state['ttd_dokter'] = auth()->user()->signature ?? '-';
        $this->state['status_resep'] = 'Aktif';
        $this->state['pengkajian_resep'] = 'Pengkajian awal';
        session()->put('tb_pasien', $this->state['tb_pasien']);
        session()->put('bb_pasien', $this->state['bb_pasien']);
        try {
            $this->validate([
                'state.tb_pasien' => 'required|string|max:255',
                'state.bb_pasien' => 'required|string|max:255',
                'state.id_resep' => 'required|max:255',
                'state.nama_obat' => 'required|string|max:255',
                'state.id_obat' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('obat_resep', 'id_obat')
                        ->where('kunjungan_id', $this->kunjungan_id)
                ],
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

            $this->resetForm();

            $this->obatList = Obat::whereNotIn('id', ObatResep::where('kunjungan_id', $this->kunjungan_id)->pluck('id_obat'))->get();

            Flux::toast(
                heading: 'Berhasil',
                text: 'Obat resep berhasil ditambahkan.',
                variant: 'success'
            );
        } catch (\Throwable $e) {
            Flux::toast(
                heading: 'Gagal',
                text: 'Terjadi kesalahan: ' . $e->getMessage(),
                variant: 'destructive'
            );
            logger()->error('Gagal menyimpan resep: ' . $e->getMessage());
        }
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

    public function resetForm()
    {
        $tb = $this->state['tb_pasien'] ?? null;
        $bb = $this->state['bb_pasien'] ?? null;

        $this->reset('state');

        $this->state['tb_pasien'] = $tb;
        $this->state['bb_pasien'] = $bb;
    }
}
