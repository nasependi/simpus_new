<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\DetailPembelianObatModel;
use Flux\Flux;
use App\Models\Obat;
use Livewire\Component;
use App\Models\ObatResep;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ObatResepComponent extends Component
{
    public $kunjungan_id;
    public $obatList;
    public $sediaanList, $stok_obat, $sediaan;
    public $state = [];

    protected $listeners = ['obat-resep' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;

        // Ambil ID obat yang sudah dipakai pada kunjungan ini
        $usedObatIds = ObatResep::where('kunjungan_id', $kunjungan_id)
            ->pluck('id_obat')
            ->toArray();

        // Ambil list obat dengan eager loading dan perhitungan stok
        $this->obatList = Obat::with(['detailPembelian' => function ($query) {
            $query->where('kadaluarsa', '>', now())
                ->select('obat_id', DB::raw('SUM(kuantitas) as total_stok'))
                ->groupBy('obat_id');
        }])
            ->whereNotIn('id', $usedObatIds)
            ->get()
            ->map(function ($obat) {
                $obat->stok_total = $obat->detailPembelian->sum('total_stok') ?? 0;
                return $obat;
            });

        // Ambil tb dan bb dari session
        $this->state['tb_pasien'] = session('tb_pasien');
        $this->state['bb_pasien'] = session('bb_pasien');
    }

    public function updatedStateJumlahObat()
    {
        if (empty($this->state['id_obat'])) {
            return;
        }


        if ($this->state['jumlah_obat'] > $this->stok_obat) {
            $this->state['jumlah_obat'] = $this->stok_obat;

            Flux::toast(
                heading: 'Peringatan',
                text: "Jumlah obat melebihi stok tersedia. Stok maksimal: {$this->stok_obat}",
                variant: 'warning'
            );
        }
    }

    public function updatedState($key, $propertyName)
    {
        if ($propertyName === 'id_obat') {
            $obat = Obat::find($this->state['id_obat']);
            if ($obat) {
                $this->state['nama_obat'] = $obat->nama_obat;
                $this->state['sediaan']  = $obat->sediaan;
                $pembelian = DetailPembelianObatModel::where('obat_id', $obat->id)
                    ->where('kadaluarsa', '>', now())
                    ->sum('kuantitas');
                $pengeluaran = ObatResep::where('id_obat', $obat->id)
                    ->sum('jumlah_obat');
                $this->stok_obat = $pembelian - $pengeluaran;
            }
            // dd($pembelian, $pengeluaran);
        }
    }

    public function save()
    {

        if ($this->state['jumlah_obat'] > $this->stok_obat) {
            Flux::toast(
                heading: 'Error',
                text: "Jumlah obat melebihi stok tersedia. Stok maksimal: {$this->stok_obat}",
                variant: 'error'
            );
            return;
        }

        // Tambahkan data resep
        $this->state['id_resep']              = $this->kunjungan_id;
        $this->state['dokter_penulis_resep']  = auth()->user()->name;
        $this->state['nomor_telepon_dokter']  = auth()->user()->phone ?? '-';
        $this->state['tanggal_penulisan_resep'] = now()->toDateString();
        $this->state['jam_penulisan_resep']   = now()->toTimeString();
        $this->state['ttd_dokter']            = auth()->user()->signature ?? '-';
        $this->state['status_resep']          = $this->state['status_resep'] ?? 'Pending';
        $this->state['pengkajian_resep']      = 'Pengkajian awal';

        // Simpan tb & bb ke session
        session()->put('tb_pasien', $this->state['tb_pasien']);
        session()->put('bb_pasien', $this->state['bb_pasien']);

        try {
            $this->validate([
                'state.tb_pasien'            => 'required|string|max:255',
                'state.bb_pasien'            => 'required|string|max:255',
                'state.id_resep'             => 'required|max:255',
                'state.nama_obat'            => 'required|string|max:255',
                'state.id_obat'              => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('obat_resep', 'id_obat')
                        ->where('kunjungan_id', $this->kunjungan_id)
                ],
                'state.sediaan'              => 'required|string|max:255',
                'state.jumlah_obat'          => 'required|integer|min:1',
                'state.metode_pemberian'     => 'required|string',
                'state.dosis_diberikan'      => 'required|string',
                'state.unit'                 => 'required|string',
                'state.frekuensi'            => 'required|string',
                'state.aturan_tambahan'      => 'nullable|string',
                'state.catatan_resep'        => 'nullable|string',
                'state.dokter_penulis_resep' => 'required|string',
                'state.nomor_telepon_dokter' => 'required|string',
                'state.tanggal_penulisan_resep' => 'required|date',
                'state.jam_penulisan_resep'  => 'required',
                'state.ttd_dokter'           => 'required|string',
                'state.status_resep'         => 'required|string',
                'state.pengkajian_resep'     => 'required|string',
            ]);

            ObatResep::create(array_merge(
                $this->state,
                ['kunjungan_id' => $this->kunjungan_id]
            ));

            $this->resetForm();

            // Refresh list obat setelah simpan
            $this->listObat();

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

    private function listObat()
    {
        return $this->obatList = Obat::whereNotIn(
            'id',
            ObatResep::where('kunjungan_id', $this->kunjungan_id)->pluck('id_obat')
        )->get();
    }

    public function delete($id)
    {
        ObatResep::findOrFail($id)->delete();

        $this->listObat();
        Flux::toast(
            heading: 'Dihapus',
            text: 'Obat resep berhasil dihapus.',
            variant: 'success'
        );
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
        $this->reset('stok_obat');

        $this->state['tb_pasien'] = $tb;
        $this->state['bb_pasien'] = $bb;
    }
}
