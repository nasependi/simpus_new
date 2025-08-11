<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PenjualanObatModel;

class PenjualanObat extends Component
{
    use WithPagination;

    public $no_faktur, $jumlah_beli, $ppn, $pph, $diskon, $harga_beli_kotor, $harga_beli_bersih;
    public $editId, $deleteId;
    public $search = '';
    public $sortField = 'no_faktur';
    public $sortDirection = 'asc';

    protected $rules = [
        'no_faktur'         => 'required|max:50',
        'jumlah_beli'       => 'required|integer|min:1',
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
        $data = PenjualanObatModel::where('no_faktur', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.penjualan-obat', compact('data'));
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('penjualanModal')->show();
    }

    public function edit($id)
    {
        $item = PenjualanObatModel::findOrFail($id);
        $this->editId = $item->id;
        $this->no_faktur         = $item->no_faktur;
        $this->jumlah_beli       = $item->jumlah_beli;
        $this->ppn               = $item->ppn;
        $this->pph               = $item->pph;
        $this->diskon            = $item->diskon;
        $this->harga_beli_kotor  = $item->harga_beli_kotor;
        $this->harga_beli_bersih = $item->harga_beli_bersih;

        Flux::modal('penjualanModal')->show();
    }

    public function save()
    {
        $this->validate();

        PenjualanObatModel::updateOrCreate(
            ['id' => $this->editId],
            [
                'no_faktur'         => $this->no_faktur,
                'jumlah_beli'       => $this->jumlah_beli,
                'ppn'               => $this->ppn,
                'pph'               => $this->pph,
                'diskon'            => $this->diskon,
                'harga_beli_kotor'  => $this->harga_beli_kotor,
                'harga_beli_bersih' => $this->harga_beli_bersih,
            ]
        );

        Flux::toast(heading: 'Berhasil', text: 'Data telah disimpan.', variant: 'success');
        Flux::modal('penjualanModal')->close();
        $this->resetForm();
    }

    public function delete($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-penjualan')->show();
    }

    public function deleteConfirm()
    {
        PenjualanObatModel::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-penjualan')->close();
    }

    public function resetForm()
    {
        $this->reset(['no_faktur', 'jumlah_beli', 'ppn', 'pph', 'diskon', 'harga_beli_kotor', 'harga_beli_bersih', 'editId']);
    }
}
