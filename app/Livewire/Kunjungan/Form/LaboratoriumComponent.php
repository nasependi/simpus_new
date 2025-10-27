<?php

namespace App\Livewire\Kunjungan\Form;

use Flux\Flux;
use Livewire\Component;
use App\Models\Laboratorium;
use App\Models\PemeriksaanLab;
use Illuminate\Support\Carbon;

class LaboratoriumComponent extends Component
{
    public $kunjungan_id;
    public $form = [];
    public $selectedExams = [];
    public $currentExam = '';

    protected $rules = [
        'form.nomor_pemeriksaan' => 'required|string',
        'form.tanggal_permintaan' => 'required|date',
        'form.jam_permintaan' => 'required',
        'form.dokter_pengirim' => 'required|string',
        'form.prioritas_pemeriksaan' => 'required|in:0,1',
        'selectedExams' => 'required|array|min:1',
    ];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;
        $now = Carbon::now('Asia/Jakarta');

        // ✅ Default form lengkap sesuai field di model
        $this->form = [
            'nama_pemeriksaan' => '',
            'nomor_pemeriksaan' => '',
            'tanggal_permintaan' => $now->toDateString(),
            'jam_permintaan' => $now->format('H:i'),
            'dokter_pengirim' => '',
            'nomor_telepon_dokter' => '',
            'nama_fasilitas_pelayanan' => '',
            'unit_pengirim' => '',
            'prioritas_pemeriksaan' => 0, // 0 = NON CITO
            'diagnosis_masalah' => '',
            'catatan_permintaan' => '',
            'metode_pengiriman' => '',
            'asal_sumber_spesimen' => '',
            'lokasi_pengambilan_spesimen' => '',
            'jumlah_spesimen' => '',
            'volume_spesimen' => '',
            'metode_pengambilan_spesimen' => '',
            'tanggal_pengambilan_spesimen' => $now->toDateString(),
            'jam_pengambilan_spesimen' => $now->format('H:i'),
            'kondisi_spesimen' => '',
            'tanggal_fiksasi_spesimen' => $now->toDateString(),
            'jam_fiksasi_spesimen' => $now->format('H:i'),
            'cairan_fiksasi' => '',
            'volume_cairan_fiksasi' => '',
            'petugas_mengambil_spesimen' => '',
            'petugas_mengantarkan_spesimen' => '',
            'petugas_menerima_spesimen' => '',
            'petugas_menganalisis_spesimen' => '',
            'tanggal_pemeriksaan_spesimen' => $now->toDateString(),
            'jam_pemeriksaan_spesimen' => $now->format('H:i'),
            'nilai_hasil_pemeriksaan' => '',
            'nilai_moral' => '',
            'nilai_rujukan' => '',
            'nilai_kritis' => '',
            'interpretasi_hasil' => '',
            'dokter_validasi' => '',
            'dokter_interpretasi' => '',
            'tanggalpemeriksaan_keluar' => $now->toDateString(),
            'jam_pemeriksaan_keluar' => $now->format('H:i'),
            'tanggal_pemeriksaan_diterima' => $now->toDateString(),
            'jam_pemeriksaan_diterima' => $now->format('H:i'),
            'fasilitas_kesehatan_pemeriksaan' => '',
        ];

        // 🔍 Jika sudah ada data lab sebelumnya, isi ulang form dari database
        $lab = Laboratorium::where('kunjungan_id', $kunjungan_id)->first();
        if ($lab) {
            $this->form = array_merge($this->form, $lab->toArray());
            $this->selectedExams = array_filter(explode(', ', $lab->nama_pemeriksaan ?? ''));

            if (!empty($this->form['nomor_telepon_dokter'])) {
                $this->form['nomor_telepon_dokter_input'] =
                    ltrim(str_replace('+62', '', $this->form['nomor_telepon_dokter']), '0');
            }
        }
    }


    public function addExam()
    {
        if (empty($this->currentExam)) {
            Flux::toast('Peringatan', 'Silakan pilih pemeriksaan terlebih dahulu.', 'warning');
            return;
        }

        if (in_array($this->currentExam, $this->selectedExams)) {
            Flux::toast('Peringatan', 'Pemeriksaan ini sudah dipilih sebelumnya.', 'warning');
            $this->currentExam = '';
            return;
        }

        $this->selectedExams[] = $this->currentExam;
        $this->currentExam = '';

        Flux::toast('Berhasil', 'Pemeriksaan berhasil ditambahkan.', 'success');
    }


    public function removeExam($index)
    {
        unset($this->selectedExams[$index]);
        $this->selectedExams = array_values($this->selectedExams);

        Flux::toast('Berhasil', 'Pemeriksaan berhasil dihapus', 'success');
    }

    public function saveAll()
    {
        $this->validate();

        try {
            $this->form['kunjungan_id'] = $this->kunjungan_id;
            $this->form['nama_pemeriksaan'] = implode(', ', $this->selectedExams);

            // Ensure prioritas_pemeriksaan is set
            if (!isset($this->form['prioritas_pemeriksaan'])) {
                $this->form['prioritas_pemeriksaan'] = 0;
            }

            // Format nomor telepon
            if (!empty($this->form['nomor_telepon_dokter_input'])) {
                $nomor = preg_replace('/[^0-9]/', '', $this->form['nomor_telepon_dokter_input']);
                $this->form['nomor_telepon_dokter'] = '+62' . ltrim($nomor, '0');
            }
            unset($this->form['nomor_telepon_dokter_input']);

            Laboratorium::updateOrCreate(
                ['kunjungan_id' => $this->kunjungan_id],
                $this->form
            );

            Flux::toast(
                heading: 'Sukses',
                text: 'Data Laboratorium berhasil disimpan.',
                variant: 'success'
            );
        } catch (\Exception $e) {
            logger()->error('Error saving laboratorium:', [
                'message' => $e->getMessage(),
                'form' => $this->form
            ]);

            Flux::toast(
                heading: 'Error',
                text: 'Gagal menyimpan data laboratorium.' . $e->getMessage(),
                variant: 'error'
            );
        }
    }

    public function render()
    {
        return view('livewire.kunjungan.form.laboratorium-component', [
            'pemeriksaanLab' => PemeriksaanLab::orderBy('nama')->get(),
        ]);
    }
}
