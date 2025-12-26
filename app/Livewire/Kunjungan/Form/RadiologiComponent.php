<?php

namespace App\Livewire\Kunjungan\Form;

use App\Models\Radiologi;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\JenisPemeriksaanRadiologi;
use Flux\Flux;

class RadiologiComponent extends Component
{
    use WithFileUploads;

    public $kunjungan_id;
    public $state = [];

    public $selectedExams = [];
    public $currentExam = '';
    public $editId;
    public $attachments = [];
    public $listJenisPemeriksaanRadiologi = [];

    protected $listeners = ['save-radiologi' => 'save'];

    public function mount($kunjungan_id)
    {
        $this->kunjungan_id = $kunjungan_id;
        $this->refreshExamList();
        $this->listJenisPemeriksaanRadiologi = JenisPemeriksaanRadiologi::orderBy('created_at')->get();

        $data = Radiologi::where('kunjungan_id', $kunjungan_id)->first();

        if ($data) {
            $this->state = $data->toArray();
            $this->selectedExams = array_filter(explode(', ', $data->jenis_pemeriksaan));
        }

        // jika ada data (edit)
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
                'interpretasi_radiologi',
            ]);

            // Hilangkan +62 jika ada
            if (isset($this->state['nomor_telepon_dokter'])) {
                $this->state['nomor_telepon_dokter'] = preg_replace('/^\+62/', '', $this->state['nomor_telepon_dokter']);
            }
        } else {
            // jika form baru, isi default waktu sekarang
            $this->state['tanggal_permintaan'] = now()->toDateString();
            $this->state['jam_permintaan'] = now()->format('H:i');
            $this->state['tanggal_pemeriksaan'] = now()->toDateString();
            $this->state['jam_pemeriksaan'] = now()->format('H:i');
        }
    }

    public function refreshExamList()
    {
        // Get all jenis pemeriksaan except those already selected
        $this->listJenisPemeriksaanRadiologi = JenisPemeriksaanRadiologi::whereNotIn('kode', $this->selectedExams)
            ->orderBy('nama')
            ->get();
    }

    public function addExam()
    {
        if (empty($this->currentExam)) {
            return;
        }

        if (in_array($this->currentExam, $this->selectedExams)) {
            Flux::toast(
                heading: 'Peringatan',
                text: 'Pemeriksaan ini sudah dipilih',
                variant: 'warning'
            );
            return;
        }

        $this->selectedExams[] = $this->currentExam;
        $this->currentExam = '';
        $this->refreshExamList();

        Flux::toast(
            heading: 'Berhasil',
            text: 'Pemeriksaan berhasil ditambahkan',
            variant: 'success'
        );
    }

    public function removeExam($index)
    {
        $removedExam = $this->selectedExams[$index];
        unset($this->selectedExams[$index]);
        $this->selectedExams = array_values($this->selectedExams);
        $this->refreshExamList();

        Flux::toast(
            heading: 'Berhasil',
            text: 'Pemeriksaan berhasil dihapus',
            variant: 'success'
        );
    }

    public function updatedStateJenisPemeriksaan($value)
    {
        if ($this->editId) return;

        $jenis = JenisPemeriksaanRadiologi::where('kode', $value)->first();

        if ($jenis) {
            $count = Radiologi::where('jenis_pemeriksaan', $value)->count() + 1;
            $kodeBaru = $jenis->kode . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            $this->state['nomor_pemeriksaan'] = $kodeBaru;
        }
    }

    public function save()
    {
        try {
            // Skip if no exams selected
            if (empty($this->selectedExams)) {
                Flux::toast(
                    heading: 'Peringatan',
                    text: 'Pilih minimal satu jenis pemeriksaan',
                    variant: 'warning'
                );
                return false;
            }

            // Gabungkan selected exams jadi string
            $this->state['jenis_pemeriksaan'] = implode(', ', $this->selectedExams);

            $this->validate([
                'kunjungan_id' => 'required|exists:kunjungan,id',
                'state.nama_pemeriksaan' => 'required|string|max:255',
                'state.jenis_pemeriksaan' => 'required|string|max:255',
                'state.nomor_pemeriksaan' => 'required|string|max:255',
                'state.tanggal_permintaan' => 'required|date',
                'state.jam_permintaan' => 'required',
                'state.dokter_pengirim' => 'required|string|max:255',
                'state.nomor_telepon_dokter' => 'required|regex:/^[0-9]{9,15}$/',
                'state.nama_fasilitas_radiologi' => 'required|string',
                'state.unit_pengirim_radiologi' => 'required|string',
                'state.prioritas_pemeriksaan_radiologi' => 'required|string',
                'state.diagnosis_kerja' => 'required|string',
                'state.catatan_permintaan' => 'required|string',
                'state.metode_penyampaian_pemeriksaan' => 'required|string',
                'state.status_alergi' => 'required',
                'state.status_kehamilan' => 'required|string',
                'state.tanggal_pemeriksaan' => 'required|date',
                'state.jam_pemeriksaan' => 'required',
                'state.jenis_bahan_kontras' => 'required|string',
                'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'state.nama_dokter_pemeriksaan' => 'required|string',
                'state.interpretasi_radiologi' => 'required|string',
            ]);

            $fotoPaths = [];
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $fotoPaths[] = $file->store('radiologi/foto-hasil', 'public');
                }
            }

            $updateData = array_merge($this->state, [
                'kunjungan_id' => $this->kunjungan_id,
                'nomor_telepon_dokter' => '+62' . ltrim($this->state['nomor_telepon_dokter'], '0'),
            ]);

            if (!empty($fotoPaths)) {
                $updateData['foto_hasil'] = json_encode($fotoPaths);
            } elseif (isset($this->state['foto_hasil'])) {
                $updateData['foto_hasil'] = $this->state['foto_hasil'];
            }

            $radiologi = Radiologi::updateOrCreate(
                ['kunjungan_id' => $this->kunjungan_id],
                $updateData
            );

            // Reload data setelah save
            $this->editId = $radiologi->id;
            $this->state = $radiologi->fresh()->toArray();
            $this->selectedExams = array_filter(explode(', ', $radiologi->jenis_pemeriksaan));
            $this->refreshExamList();

            // Clear attachments after successful upload
            $this->attachments = [];

            Flux::toast(
                heading: 'Berhasil',
                text: 'Data Radiologi berhasil disimpan.',
                variant: 'success'
            );

            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors will be shown automatically
            return false;
        } catch (\Exception $e) {
            logger()->error('Radiologi save error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Flux::toast(
                heading: 'Error',
                text: 'Gagal menyimpan data: ' . $e->getMessage(),
                variant: 'danger'
            );

            return false;
        }
    }

    public function render()
    {
        return view('livewire.kunjungan.form.radiologi-component');
    }
}
