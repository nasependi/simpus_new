<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Obat;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PembelianObatModel;
use App\Models\DetailPembelianObatModel;

class PembelianObat extends Component
{
    use WithPagination;

    public $no_faktur, $ppn = 0, $pph = 0, $diskon = 0, $harga_beli_kotor, $harga_beli_bersih;
    public $editId, $deleteId;

    // Detail pembelian (form item)
    public $obat_id, $kuantitas, $harga_beli, $jumlah, $kadaluarsa;
    public $detailItems = [];

    public $search = '';
    public $sortField = 'no_faktur';
    public $sortDirection = 'asc';

    protected $rules = [
        'no_faktur'         => 'required|max:50',
        'ppn'               => 'nullable|numeric|min:0',
        'pph'               => 'nullable|numeric|min:0',
        'diskon'            => 'nullable|numeric|min:0',
        'harga_beli_kotor'  => 'required|numeric|min:0',
        'harga_beli_bersih' => 'required|numeric|min:0',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $data = PembelianObatModel::where('no_faktur', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $obatList = Obat::orderBy('nama_obat')->get();

        return view('livewire.pembelian-obat', compact('data', 'obatList'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('pembelianModal')->show();
    }

    // === FORM DETAIL ITEM ===

    public function updatedKuantitas()
    {
        $this->hitungJumlah();
    }

    public function updatedHargaBeli()
    {
        $this->hitungJumlah();
    }

    private function hitungJumlah()
    {
        $this->jumlah = (int) $this->kuantitas * (int) $this->harga_beli;
    }

    public function addItem()
    {
        $this->validate([
            'obat_id'    => 'required',
            'kuantitas'  => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'kadaluarsa' => 'required|date',
        ]);

        $obat = is_numeric($this->obat_id)
            ? Obat::find((int) $this->obat_id)
            : Obat::where('nama_obat', $this->obat_id)->first();

        if (!$obat) {
            $this->addError('obat_id', 'Obat tidak ditemukan.');
            return;
        }

        // pastikan jumlah sudah dihitung
        $this->hitungJumlah();

        $this->detailItems[] = [
            'obat_id'    => $obat->id,
            'nama_obat'  => $obat->nama_obat,
            'kuantitas'  => (int) $this->kuantitas,
            'harga_beli' => (float) $this->harga_beli,
            'jumlah'     => (float) $this->jumlah,
            'kadaluarsa' => $this->kadaluarsa,
        ];

        $this->hitungTotal();

        // reset form input item
        $this->reset(['obat_id', 'kuantitas', 'harga_beli', 'jumlah', 'kadaluarsa']);
    }

    public function removeItem($index)
    {
        unset($this->detailItems[$index]);
        $this->hitungTotal();
        $this->detailItems = array_values($this->detailItems);
    }

    private function hitungTotal()
    {
        // harga kotor = total semua jumlah item
        $this->harga_beli_kotor = array_sum(
            array_map('floatval', array_column($this->detailItems, 'jumlah'))
        );

        // pastikan semua angka dikonversi ke float
        $ppnPersen = floatval($this->ppn ?? 0);
        $pphPersen = floatval($this->pph ?? 0);
        $diskon    = floatval($this->diskon ?? 0);
        $kotor     = floatval($this->harga_beli_kotor ?? 0);

        // hitung ppn & pph dalam bentuk nilai
        $ppn = $kotor * ($ppnPersen / 100);
        $pph = $kotor * ($pphPersen / 100);

        // harga bersih = harga kotor + ppn + pph - diskon
        $this->harga_beli_bersih = $kotor + $ppn + $pph - $diskon;
    }



    public function updatedPpn()
    {
        $this->hitungTotal();
    }

    public function updatedPph()
    {
        $this->hitungTotal();
    }

    public function updatedDiskon()
    {
        $this->hitungTotal();
    }

    // === SIMPAN ===

    public function save()
    {
        $this->validate();

        // Hitung jumlah beli dari total kuantitas
        $jumlahBeli = array_sum(array_column($this->detailItems, 'kuantitas'));

        // Simpan header pembelian
        $pembelian = PembelianObatModel::updateOrCreate(
            ['id' => $this->editId],
            [
                'no_faktur'         => $this->no_faktur,
                'jumlah_beli'       => $jumlahBeli,
                'ppn'               => $this->ppn ?? 0,
                'pph'               => $this->pph ?? 0,
                'diskon'            => $this->diskon ?? 0,
                'harga_beli_kotor'  => $this->harga_beli_kotor,
                'harga_beli_bersih' => $this->harga_beli_bersih,
            ]
        );

        // Hapus detail lama (kalau edit)
        DetailPembelianObatModel::where('pembelian_id', $pembelian->id)->delete();

        // Simpan detail baru
        foreach ($this->detailItems as $item) {
            DetailPembelianObatModel::create([
                'pembelian_id' => $pembelian->id,
                'obat_id'      => $item['obat_id'],
                'kuantitas'    => $item['kuantitas'],
                'harga_beli'   => $item['harga_beli'],
                'jumlah'       => $item['jumlah'],
                'kadaluarsa'   => $item['kadaluarsa'],
            ]);
        }

        Flux::modal('pembelianModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    // === EDIT ===

    public function edit($id)
    {
        $m = PembelianObatModel::findOrFail($id);

        $this->editId            = $m->id;
        $this->no_faktur         = $m->no_faktur;
        $this->ppn               = $m->ppn ?? 0;
        $this->pph               = $m->pph ?? 0;
        $this->diskon            = $m->diskon ?? 0;
        $this->harga_beli_kotor  = $m->harga_beli_kotor;
        $this->harga_beli_bersih = $m->harga_beli_bersih;

        $details = DetailPembelianObatModel::with('obat')
            ->where('pembelian_id', $m->id)
            ->get();

        $this->detailItems = $details->map(function ($d) {
            return [
                'obat_id'    => $d->obat_id,
                'nama_obat'  => $d->obat->nama_obat ?? '',
                'kuantitas'  => (int) $d->kuantitas,
                'harga_beli' => (float) $d->harga_beli,
                'jumlah'     => (float) $d->jumlah,
                'kadaluarsa' => $d->kadaluarsa,
            ];
        })->toArray();

        Flux::modal('pembelianModal')->show();
    }

    // === DELETE ===

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-pembelian')->show();
    }

    public function delete()
    {
        PembelianObatModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-pembelian')->close();
    }

    public function resetForm()
    {
        $this->reset([
            'no_faktur',
            'ppn',
            'pph',
            'diskon',
            'harga_beli_kotor',
            'harga_beli_bersih',
            'detailItems',
            'editId',
            'obat_id',
            'kuantitas',
            'harga_beli',
            'jumlah',
            'kadaluarsa',
        ]);
    }

    // === DETAIL VIEW ===

    public $detailPembelian = [];
    public $pembelian = [];

    public function showDetail($pembelianId)
    {
        // Load data pembelian header
        $pembelianModel = PembelianObatModel::findOrFail($pembelianId);
        $this->pembelian = [
            'no_faktur'         => $pembelianModel->no_faktur,
            'tanggal'           => $pembelianModel->created_at,
            'harga_beli_kotor'  => $pembelianModel->harga_beli_kotor,
            'harga_beli_bersih' => $pembelianModel->harga_beli_bersih,
            'ppn'               => $pembelianModel->ppn ?? 0,
            'pph'               => $pembelianModel->pph ?? 0,
            'diskon'            => $pembelianModel->diskon ?? 0,
        ];

        // Load detail items (JANGAN format di sini, biar di view)
        $this->detailPembelian = DetailPembelianObatModel::with('obat')
            ->where('pembelian_id', $pembelianId)
            ->get()
            ->map(function ($d) {
                return [
                    'nama_obat'  => $d->obat->nama_obat ?? '-',
                    'kuantitas'  => $d->kuantitas,
                    'harga_beli' => $d->harga_beli,
                    'jumlah'     => $d->jumlah,
                    'kadaluarsa' => $d->kadaluarsa,
                ];
            })
            ->toArray();

        Flux::modal('detailPembelianModal')->show();
    }

    public function updatedProperty($propertyName)
    {
        dd("Updated property: {$propertyName} with value: {$this->{$propertyName}}");
    }
}
