<?php

namespace App\Imports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ObatImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Obat([
            'nama_obat' => $row['nama_obat'],
            'golongan'  => $row['golongan'] ?? null,
            'sediaan'   => $row['sediaan'] ?? null,
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'nama_obat' => 'required|max:100',
            'golongan' => 'nullable|max:50',
            'sediaan' => 'nullable|max:50',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'nama_obat.max' => 'Nama obat maksimal 100 karakter.',
            'golongan.max' => 'Golongan maksimal 50 karakter.',
            'sediaan.max' => 'Sediaan maksimal 50 karakter.',
        ];
    }
}
