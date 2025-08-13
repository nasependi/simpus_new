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

    // Detail pembelian
    public $obat_id, $kuantitas;
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

    public function addItem()
    {
        $this->validate([
            'obat_id'   => 'required',
            'kuantitas' => 'required|integer|min:1',
        ]);

        $obat = null;
        if (is_numeric($this->obat_id)) {
            $obat = Obat::find((int) $this->obat_id);
        }

        if (!$obat) {
            $obat = Obat::where('nama_obat', $this->obat_id)->first();
        }

        if (!$obat) {
            $this->addError('obat_id', 'Obat tidak ditemukan.');
            return;
        }

        $this->detailItems[] = [
            'obat_id'   => $obat->id,
            'nama_obat' => $obat->nama_obat,
            'kuantitas' => (int) $this->kuantitas,
        ];

        $this->reset(['obat_id', 'kuantitas']);
    }

    public function removeItem($index)
    {
        unset($this->detailItems[$index]);
        $this->detailItems = array_values($this->detailItems);
    }

    public function save()
    {
        $this->validate();

        // Hitung jumlah beli dari total kuantitas
        $jumlahBeli = array_sum(array_column($this->detailItems, 'kuantitas'));

        PembelianObatModel::updateOrCreate(
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

        Flux::modal('pembelianModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function edit($id)
    {
        // Ambil data pembelian header
        $m = PembelianObatModel::findOrFail($id);

        $this->editId            = $m->id;
        $this->no_faktur         = $m->no_faktur;
        $this->ppn               = $m->ppn ?? 0;
        $this->pph               = $m->pph ?? 0;
        $this->diskon            = $m->diskon ?? 0;
        $this->harga_beli_kotor  = $m->harga_beli_kotor;
        $this->harga_beli_bersih = $m->harga_beli_bersih;

        // Ambil detail dari tabel DetailPembelianObatModel
        $details = DetailPembelianObatModel::with('obat')
            ->where('pembelian_id', $m->id)
            ->get();

        $this->detailItems = $details->map(function ($d) {
            return [
                'obat_id'   => $d->obat_id,
                'nama_obat' => $d->obat->nama_obat ?? '',
                'kuantitas' => (int) $d->kuantitas,
            ];
        })->toArray();

        // Tampilkan modal edit
        Flux::modal('pembelianModal')->show();
    }


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
            'editId'
        ]);
    }
}
