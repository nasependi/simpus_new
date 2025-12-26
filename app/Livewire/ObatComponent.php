<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Obat;
use App\Models\DetailPembelianObatModel;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ObatImport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ObatComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $nama_obat, $golongan, $sediaan, $editId, $deleteId;
    public $importFile;
    public $search = '';
    public $sortField = 'nama_obat';
    public $sortDirection = 'asc';

    protected $rules = [
        'nama_obat' => 'required|max:100',
        'golongan' => 'nullable|max:50',
        'sediaan' => 'nullable|max:50', // Assuming 'sedian' is a nullable field
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
        $data = Obat::select('obat.*', DB::raw('SUM(detail_pembelian_obat.kuantitas) as stok_total'))
            ->leftJoin('detail_pembelian_obat', 'obat.id', '=', 'detail_pembelian_obat.obat_id')
            ->where('nama_obat', 'like', '%' . $this->search . '%')
            ->groupBy('obat.id', 'obat.nama_obat', 'obat.golongan', 'obat.sediaan')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.obat-component', compact('data'));
    }

    // Tambahkan method untuk mendapatkan stok obat
    public function getStokObat($obatId)
    {
        return DetailPembelianObatModel::where('obat_id', $obatId)
            ->where('kadaluarsa', '>', now())
            ->sum('kuantitas');
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('obatModal')->show();
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $this->editId = $obat->id;
        $this->nama_obat = $obat->nama_obat;
        $this->golongan = $obat->golongan;
        $this->sediaan = $obat->sediaan ?? ''; // Assuming 'sedian' is a nullable field

        Flux::modal('obatModal')->show();
    }

    public function save()
    {
        $this->validate();

        Obat::updateOrCreate(
            ['id' => $this->editId],
            ['nama_obat' => $this->nama_obat, 'golongan' => $this->golongan, 'sediaan' => $this->sediaan]
        );

        Flux::modal('obatModal')->close();
        Flux::toast(heading: 'Sukses', text: 'Data berhasil disimpan.', variant: 'success');
        $this->resetForm();
    }

    public function deleteConfirm($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-obat')->show();
    }

    public function delete()
    {
        Obat::findOrFail($this->deleteId)->delete();
        Flux::toast(heading: 'Terhapus', text: 'Data telah dihapus.', variant: 'success');
        Flux::modal('delete-obat')->close();
    }

    public function resetForm()
    {
        $this->reset(['nama_obat', 'golongan', 'sediaan']);
    }

    public function showImportModal()
    {
        $this->reset(['importFile']);
        Flux::modal('importModal')->show();
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:2048', // 2MB
        ], [
            'importFile.required' => 'File Excel wajib dipilih.',
            'importFile.file' => 'File yang diupload tidak valid.',
            'importFile.mimes' => 'File harus berformat Excel (.xlsx, .xls, .csv).',
            'importFile.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            Excel::import(new ObatImport, $this->importFile->getRealPath());
            
            Flux::modal('importModal')->close();
            Flux::toast(
                heading: 'Sukses', 
                text: 'Data obat berhasil diimport.', 
                variant: 'success'
            );
            
            $this->reset(['importFile']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessage = 'Error pada baris: ';
            foreach ($failures as $failure) {
                $errorMessage .= $failure->row() . ' (' . implode(', ', $failure->errors()) . '), ';
            }
            
            Flux::toast(
                heading: 'Validasi Gagal', 
                text: rtrim($errorMessage, ', '), 
                variant: 'danger'
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Error', 
                text: 'Gagal import: ' . $e->getMessage(), 
                variant: 'danger'
            );
        }
    }

    public function downloadTemplate()
    {
        try {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set header row
            $sheet->setCellValue('A1', 'nama_obat');
            $sheet->setCellValue('B1', 'golongan');
            $sheet->setCellValue('C1', 'sediaan');
            
            // Style header row
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'], // Emerald 600
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
            
            // Add sample data
            $sampleData = [
                ['Paracetamol 500mg', 'Analgesik', 'Tablet'],
                ['Amoxicillin 500mg', 'Antibiotik', 'Kapsul'],
                ['Vitamin C 1000mg', 'Vitamin', 'Tablet'],
                ['Ibuprofen 400mg', 'Anti-inflamasi', 'Tablet'],
                ['Omeprazole 20mg', 'Antasida', 'Kapsul'],
            ];
            
            $row = 2;
            foreach ($sampleData as $data) {
                $sheet->setCellValue('A' . $row, $data[0]);
                $sheet->setCellValue('B' . $row, $data[1]);
                $sheet->setCellValue('C' . $row, $data[2]);
                $row++;
            }
            
            // Auto-size columns
            foreach (range('A', 'C') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Create writer and download
            $writer = new Xlsx($spreadsheet);
            
            $fileName = 'template_import_obat.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);
            $writer->save($tempFile);
            
            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Error',
                text: 'Gagal generate template: ' . $e->getMessage(),
                variant: 'danger'
            );
            return;
        }
    }
}
