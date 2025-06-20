<?php

// app/Livewire/Kunjungan/Form/DiagnosisComponent.php

namespace App\Livewire\Kunjungan\Form;

use App\Models\Diagnosis;
use Livewire\Component;
use Flux\Flux;

class DiagnosisComponent extends Component
{
    public $kunjungan_id;
    public $diagnosis_awal, $diagnosis_primer, $diagnosis_sekunder;
    public $diagnosisList = [];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;
        $this->fetchDiagnosis();
    }

    public function fetchDiagnosis()
    {
        $this->diagnosisList = Diagnosis::where('kunjungan_id', $this->kunjungan_id)->latest()->get()->toArray();
    }

    public function save()
    {
        $this->validate([
            'diagnosis_awal' => 'required|string',
            'diagnosis_primer' => 'required|string',
            'diagnosis_sekunder' => 'nullable|string',
        ]);

        Diagnosis::create([
            'kunjungan_id' => $this->kunjungan_id,
            'diagnosis_awal' => $this->diagnosis_awal,
            'diagnosis_primer' => $this->diagnosis_primer,
            'diagnosis_sekunder' => $this->diagnosis_sekunder,
        ]);

        $this->reset(['diagnosis_awal', 'diagnosis_primer', 'diagnosis_sekunder']);
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
