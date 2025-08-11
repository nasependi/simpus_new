<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DetailPembelianObatModel;
use App\Models\PembelianObat;
use App\Models\Obat;
use App\Models\PembelianObatModel;
use Flux\Flux;

class DetailPembelianObat extends Component
{
    use WithPagination;

    public $pembelian_id, $obat_id, $kuantitas, $harga_beli, $jumlah, $kadaluarsa;
    public $editId, $deleteId;
    public $search = '';
    public $sortField = 'kadaluarsa';
    public $sortDirection = 'asc';

    protected $rules = [
        'pembelian_id' => 'required|exists:pembelian_obat,id',
        'obat_id'      => 'required|exists:obat,id',
        'kuantitas'    => 'required|integer|min:1',
        'harga_beli'   => 'required|numeric|min:0',
        'jumlah'       => 'required|numeric|min:0',
        'kadaluarsa'   => 'required|date'
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
        $data = DetailPembelianObatModel::with(['pembelian', 'obat'])
            ->whereHas('pembelian', function ($q) {
                $q->where('no_faktur', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $pembelianList = PembelianObatModel::pluck('no_faktur', 'id');
        $obatList = Obat::pluck('nama_obat', 'id');

        return view('livewire.detail-pembelian-obat', compact('data', 'pembelianList', 'obatList'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('detailPembelianModal')->show();
    }

    public function edit($id)
    {
        $item = DetailPembelianObatModel::findOrFail($id);
        $this->editId     = $item->id;
        $this->pembelian_id = $item->pembelian_id;
        $this->obat_id    = $item->obat_id;
        $this->kuantitas  = $item->kuantitas;
        $this->harga_beli = $item->harga_beli;
        $this->jumlah     = $item->jumlah;
        $this->kadaluarsa = $item->kadaluarsa;

        Flux::modal('detailPembelianModal')->show();
    }

    public function save()
    {
        $this->validate();

        DetailPembelianObatModel::updateOrCreate(
            ['id' => $this->editId],
            [
                'pembelian_id' => $this->pembelian_id,
                'obat_id'      => $this->obat_id,
                'kuantitas'    => $this->kuantitas,
                'harga_beli'   => $this->harga_beli,
                'jumlah'       => $this->jumlah,
                'kadaluarsa'   => $this->kadaluarsa
            ]
        );

        Flux::modal('detailPembelianModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Detail pembelian berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-detail-pembelian')->show();
    }

    public function delete()
    {
        DetailPembelianObatModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-detail-pembelian')->close();
    }

    public function resetForm()
    {
        $this->reset([
            'pembelian_id',
            'obat_id',
            'kuantitas',
            'harga_beli',
            'jumlah',
            'kadaluarsa',
            'editId'
        ]);
    }
}
