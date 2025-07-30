<?php
// app/Livewire/Kunjungan/Form/DiagnosisComponent.php

namespace App\Livewire\Kunjungan\Form;

use App\Models\Diagnosis;
use App\Models\MasterIcd;

use Livewire\Component;
use Flux\Flux;

class DiagnosisComponent extends Component
{
    public $kunjungan_id;
    public $diagnosis_awal = '';
    public $diagnosis_primer = '';
    public $diagnosis_sekunder = '';
    public $diagnosisList = [];
    public $display_awal = '';
    public $display_primer = '';
    public $display_sekunder = '';


    public $searchAwal = '', $searchPrimer = '', $searchSekunder = '';
    public $suggestionsAwal = [], $suggestionsPrimer = [], $suggestionsSekunder = [];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;
        $this->fetchDiagnosis();
    }

    public function fetchDiagnosis()
    {
        $diagnosisRaw = Diagnosis::where('kunjungan_id', $this->kunjungan_id)->latest()->get();

        $this->diagnosisList = $diagnosisRaw->map(function ($item) {
            $icdAwal = MasterIcd::where('code', $item->diagnosis_awal)->first();
            $icdPrimer = MasterIcd::where('code', $item->diagnosis_primer)->first();
            $icdSekunder = MasterIcd::where('code', $item->diagnosis_sekunder)->first();

            return [
                'id' => $item->id,
                'diagnosis_awal' => $item->diagnosis_awal . ' - ' . ($icdAwal->name_id ?? '-'),
                'diagnosis_primer' => $item->diagnosis_primer . ' - ' . ($icdPrimer->name_id ?? '-'),
                'diagnosis_sekunder' => $item->diagnosis_sekunder
                    ? $item->diagnosis_sekunder . ' - ' . ($icdSekunder->name_id ?? '-')
                    : '-',
            ];
        })->toArray();
    }


    public function updatedDisplayAwal($value)
    {
        $this->searchAwal = $value;

        $this->suggestionsAwal = MasterIcd::where('name_en', 'like', '%' . $value . '%')
            ->orWhere('name_id', 'like', '%' . $value . '%')
            ->orWhere('code', 'like', '%' . $value . '%')
            ->limit(10)
            ->get()
            ->map(function ($row) use ($value) {
                $displayName = stripos($row->name_id, $value) !== false ? $row->name_id : $row->name_en;
                return [
                    'id' => $row->id,
                    'label' => "{$row->code} - {$displayName}",
                    'value' => "{$row->code} - {$displayName}",
                ];
            })
            ->toArray();
    }

    public function updatedDisplayPrimer($value)
    {
        $this->searchPrimer = $value;

        $this->suggestionsPrimer = MasterIcd::where('name_en', 'like', '%' . $value . '%')
            ->orWhere('name_id', 'like', '%' . $value . '%')
            ->orWhere('code', 'like', '%' . $value . '%')
            ->limit(10)
            ->get()
            ->map(function ($row) use ($value) {
                $displayName = stripos($row->name_id, $value) !== false ? $row->name_id : $row->name_en;
                return [
                    'id' => $row->id,
                    'label' => "{$row->code} - {$displayName}",
                    'value' => "{$row->code} - {$displayName}",
                ];
            })
            ->toArray();
    }

    public function updatedDisplaySekunder($value)
    {
        $this->searchSekunder = $value;

        $this->suggestionsSekunder = MasterIcd::where('name_en', 'like', '%' . $value . '%')
            ->orWhere('name_id', 'like', '%' . $value . '%')
            ->orWhere('code', 'like', '%' . $value . '%')
            ->limit(10)
            ->get()
            ->map(function ($row) use ($value) {
                $displayName = stripos($row->name_id, $value) !== false ? $row->name_id : $row->name_en;
                return [
                    'id' => $row->id,
                    'label' => "{$row->code} - {$displayName}",
                    'value' => "{$row->code} - {$displayName}",
                ];
            })
            ->toArray();
    }

    public function selectAwal($label)
    {
        $code = explode(' - ', $label)[0] ?? $label;
        $this->diagnosis_awal = $code;
        $this->display_awal = $label;
        $this->suggestionsAwal = [];
    }


    public function selectPrimer($label)
    {
        $code = explode(' - ', $label)[0] ?? $label;
        $this->diagnosis_primer = $code;
        $this->display_primer = $label;
        $this->suggestionsPrimer = [];
    }

    public function selectSekunder($label)
    {
        $code = explode(' - ', $label)[0] ?? $label;
        $this->diagnosis_sekunder = $code;
        $this->display_sekunder = $label;
        $this->suggestionsSekunder = [];
    }


    public function save()
    {
        $this->validate([
            'diagnosis_awal' => 'required|string',
            'diagnosis_primer' => 'required|string',
        ]);

        Diagnosis::create([
            'kunjungan_id' => $this->kunjungan_id,
            'diagnosis_awal' => $this->diagnosis_awal,
            'diagnosis_primer' => $this->diagnosis_primer,
            'diagnosis_sekunder' => $this->diagnosis_sekunder,
        ]);

        $this->reset([
            'diagnosis_awal',
            'diagnosis_primer',
            'diagnosis_sekunder',
            'display_awal',
            'display_primer',
            'display_sekunder'
        ]);
        $this->fetchDiagnosis();

        Flux::toast(heading: 'Sukses', text: 'Diagnosis berhasil ditambahkan.', variant: 'success');
    }

    public function delete($id)
    {
        Diagnosis::findOrFail($id)->delete();
        $this->fetchDiagnosis();

        Flux::toast(heading: 'Dihapus', text: 'Diagnosis berhasil dihapus.', variant: 'destructive');
    }

    public function render()
    {
        return view('livewire.kunjungan.form.diagnosis-component');
    }
}
