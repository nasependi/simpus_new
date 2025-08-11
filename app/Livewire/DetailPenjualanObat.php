<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Obat;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PenjualanObatModel;
use App\Models\DetailPenjualanObatModel;

class DetailPenjualanObat extends Component
{
    use WithPagination;

    public $penjualan_id, $obat_id, $kuantitas, $harga_beli, $jumlah, $kadaluarsa;
    public $editId, $deleteId;
    public $search = '';
    public $sortField = 'kadaluarsa';
    public $sortDirection = 'asc';

    protected $rules = [
        'penjualan_id' => 'required|exists:penjualan_obat,id',
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
        $data = DetailPenjualanObatModel::with(['penjualan', 'obat'])
            ->whereHas('penjualan', function ($q) {
                $q->where('no_faktur', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $penjualanList = PenjualanObatModel::pluck('no_faktur', 'id');
        $obatList = Obat::pluck('nama_obat', 'id');

        return view('livewire.detail-penjualan-obat', compact('data', 'penjualanList', 'obatList'));
    }


    public function create()
    {
        $this->resetForm();
        // Assuming you have a modal to show for creating a new entry
        Flux::modal('detailPenjualanModal')->show();
    }

    public function edit($id)
    {
        $item = DetailPenjualanObatModel::findOrFail($id);
        $this->editId     = $item->id;
        $this->penjualan_id = $item->penjualan_id;
        $this->obat_id    = $item->obat_id;
        $this->kuantitas  = $item->kuantitas;
        $this->harga_beli = $item->harga_beli;
        $this->jumlah     = $item->jumlah;
        $this->kadaluarsa = $item->kadaluarsa;

        Flux::modal('detailPenjualanModal')->show();
    }

    public function save()
    {
        $this->validate();

        DetailPenjualanObatModel::updateOrCreate(
            ['id' => $this->editId],
            [
                'penjualan_id' => $this->penjualan_id,
                'obat_id'      => $this->obat_id,
                'kuantitas'    => $this->kuantitas,
                'harga_beli'   => $this->harga_beli,
                'jumlah'       => $this->jumlah,
                'kadaluarsa'   => $this->kadaluarsa
            ]
        );

        Flux::modal('detailPenjualanModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Detail penjualan berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function delete($id)
    {
        $this->deleteId = $id;
        Flux::modal('confirmDeleteModal')->show();
    }

    public function confirmDelete()
    {
        DetailPenjualanObatModel::destroy($this->deleteId);
        $this->reset(['deleteId']);
        Flux::modal('confirmDeleteModal')->hide();
        session()->flash('message', 'Detail penjualan obat deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['penjualan_id', 'obat_id', 'kuantitas', 'harga_beli', 'jumlah', 'kadaluarsa', 'editId', 'deleteId']);
    }
}
