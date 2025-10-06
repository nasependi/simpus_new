<?php

namespace App\Livewire\Kunjungan\Form;

use Flux\Flux;
use Livewire\Component;
use App\Models\Laboratorium;
use App\Models\PemeriksaanLab;

class LaboratoriumComponent extends Component
{
    public $kunjungan_id;
    public $form = [];

    protected $listeners = ['save-laboratorium' => 'save'];

    protected $rules = [
        'form.nama_pemeriksaan' => 'required|string',
        'form.nomor_pemeriksaan' => 'required|string',
        'form.tanggal_permintaan' => 'required|date',
        'form.jam_permintaan' => 'required',
        'form.dokter_pengirim' => 'required|string',
    ];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;

        $lab = Laboratorium::where('kunjungan_id', $kunjungan_id)->first();
        if ($lab) {
            $this->form = $lab->toArray();

            if (!empty($this->form['nomor_telepon_dokter'])) {
                // Buat input hanya angka tanpa +62
                $this->form['nomor_telepon_dokter_input'] = ltrim(str_replace('+62', '', $this->form['nomor_telepon_dokter']), '0');
            }
        }
    }

    public function saveAll()
    {
        $this->validate();

        $this->form['kunjungan_id'] = $this->kunjungan_id;

        // Handle nomor telepon dokter pengirim
        if (!empty($this->form['nomor_telepon_dokter_input'])) {
            $nomor = preg_replace('/[^0-9]/', '', $this->form['nomor_telepon_dokter_input']); // pastikan hanya angka
            $this->form['nomor_telepon_dokter'] = '+62' . ltrim($nomor, '0');
        }

        unset($this->form['nomor_telepon_dokter_input']); // pastikan tidak ikut disimpan ke DB

        Laboratorium::updateOrCreate(
            ['kunjungan_id' => $this->kunjungan_id],
            $this->form
        );

        Flux::toast(
            heading: 'Sukses',
            text: 'Data Laboratorium berhasil disimpan.',
            variant: 'success'
        );
    }

    public function render()
    {
        // Ambil semua data dari tabel pemeriksaan_lab
        $pemeriksaanLab = PemeriksaanLab::orderBy('nama')->get();

        return view('livewire.kunjungan.form.laboratorium-component', [
            'pemeriksaanLab' => $pemeriksaanLab,
        ]);
    }
}
