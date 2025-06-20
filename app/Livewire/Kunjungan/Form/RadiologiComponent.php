<?php

namespace App\Livewire\Kunjungan\Form;

use Flux\Flux;
use Livewire\Component;
use App\Models\Radiologi;
use Livewire\WithFileUploads;

class RadiologiComponent extends Component
{
    use WithFileUploads; // << Wajib untuk upload file

    public $kunjungan_id;
    public $state = [];
    public $editId;
    public $attachments = [];


    protected $listeners = ['save-radiologi' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;

        $data = Radiologi::where('kunjungan_id', $kunjungan_id)->first();

        if ($data) {
            $this->editId = $data->id;
            $this->state = $data->only([
                'nama_pemeriksaan',
                'jenis_pemeriksaan',
                'nomor_pemeriksaan',
                'tanggal_permintaan',
                'jam_permintaan',
                'dokter_pengirim',
                'nomor_telepon_dokter',
                'nama_fasilitas_radiologi',
                'unit_pengirim_radiologi',
                'prioritas_pemeriksaan_radiologi',
                'diagnosis_kerja',
                'catatan_permintaan',
                'metode_penyampaian_pemeriksaan',
                'status_alergi',
                'status_kehamilan',
                'tanggal_pemeriksaan',
                'jam_pemeriksaan',
                'jenis_bahan_kontras',
                'foto_hasil',
                'nama_dokter_pemeriksaan',
                'interpretasi_radiologi'
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'kunjungan_id' => 'required|exists:kunjungan,id',
            'state.nama_pemeriksaan' => 'required|string|max:255',
            'state.jenis_pemeriksaan' => 'required|string|max:255',
            'state.nomor_pemeriksaan' => 'required|string|max:255',
            'state.tanggal_permintaan' => 'required|date',
            'state.jam_permintaan' => 'required',
            'state.dokter_pengirim' => 'required|string|max:255',
            'state.nomor_telepon_dokter' => 'required|string',
            'state.nama_fasilitas_radiologi' => 'required|string',
            'state.unit_pengirim_radiologi' => 'required|string',
            'state.prioritas_pemeriksaan_radiologi' => 'required|string',
            'state.diagnosis_kerja' => 'required|string',
            'state.catatan_permintaan' => 'nullable|string',
            'state.metode_penyampaian_pemeriksaan' => 'required|string',
            'state.status_alergi' => 'required|boolean',
            'state.status_kehamilan' => 'required|string',
            'state.tanggal_pemeriksaan' => 'required|date',
            'state.jam_pemeriksaan' => 'required',
            'state.jenis_bahan_kontras' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'state.nama_dokter_pemeriksaan' => 'required|string',
            'state.interpretasi_radiologi' => 'nullable|string',
        ]);

        $fotoPaths = [];
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $file) {
                $path = $file->store('radiologi/foto-hasil', 'public');
                $fotoPaths[] = $path;
            }
        }

        \App\Models\Radiologi::updateOrCreate(
            ['kunjungan_id' => $this->kunjungan_id],
            array_merge($this->state, [
                'kunjungan_id' => $this->kunjungan_id,
                'foto_hasil' => !empty($fotoPaths)
                    ? json_encode($fotoPaths)
                    : ($this->state['foto_hasil'] ?? null),
            ])
        );
        $updateData = array_merge($this->state, [
            'kunjungan_id' => $this->kunjungan_id,
        ]);

        if (!empty($fotoPaths)) {
            $updateData['foto_hasil'] = json_encode($fotoPaths);
        }

        Radiologi::updateOrCreate(
            ['kunjungan_id' => $this->kunjungan_id],
            $updateData
        );

        Flux::toast(heading: 'Berhasil', text: 'Data Radiologi disimpan.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.kunjungan.form.radiologi-component');
    }
}
