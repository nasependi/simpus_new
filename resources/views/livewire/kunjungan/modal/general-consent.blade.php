<div>

    <flux:modal name="consentModal" class="space-y-4  w-full max-w-3xl">
        <flux:heading class="text-lg font-semibold">
            {{ $editId ? 'Edit' : 'Tambah' }} General Consent
        </flux:heading>

        <flux:input wire:model="nama_pasien" label="Pasien" />
        <div class="grid grid-cols-2 gap-2 items-center">
            <flux:input wire:model="tanggal" label="Tanggal" type="date" required />
            <flux:input wire:model="jam" label="Jam" type="time" required />
        </div>

        <div class="text-sm space-y-3">
            <!-- Judul -->
            <div class="grid grid-cols-[10px_1fr]">
                <div></div>
                <flux:switch wire:model="persetujuan_pasien" label="Persetujuan Pasien" />
            </div>

            <!-- a -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="informasi_ketentuan_pembayaran" label="Informasi Ketentuan Pembayaran" />
            </div>

            <!-- b -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="informasi_hak_kewajiban" label="Informasi tentang Hak dan Kewajiban Pasien" />
            </div>

            <!-- c -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="informasi_tata_tertib_rs" label="Informasi tentang Tata Tertib RS" />
            </div>

            <!-- d -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="kebutuhan_penerjemah_bahasa" label="Kebutuhan Penterjemah Bahasa" />
            </div>

            <!-- e -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="kebutuhan_rohaniawan" label="Kebutuhan Rohaniawan" />
            </div>

            <!-- f -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <flux:switch wire:model="kerahasiaan_informasi" label="Kerahasiaan Informasi" />
            </div>

            <!-- f.1 -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                    <div></div>
                    <flux:switch wire:model="pemeriksaan_ke_pihak_penjamin" label="Hasil Pemeriksaan Penunjang dapat Diberikan kepada Pihak Penjamin" />
                </div>
            </div>

            <!-- f.2 -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                    <div></div>
                    <flux:switch wire:model="pemeriksaan_diakses_peserta_didik" label="Hasil Pemeriksaan Penunjang dapat Diakses oleh Peserta Didik" />
                </div>
            </div>

            <!-- f.3 -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                    <div></div>
                    <flux:input wire:model="anggota_keluarga_dapat_akses" label="Anggota Keluarga Lain yang dapat Diberikan Informasi Data-data Pasien" />
                </div>
            </div>

            <!-- f.4 -->
            <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                <div></div>
                <div class="grid grid-cols-[20px_1fr] items-start gap-2">
                    <div></div>
                    <flux:switch wire:model="akses_fasyankes_rujukan" label="Fasyankes tertentu dalam rangka rujukan" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 justify-items-between">
            <div class="w-full">
                <flux:input wire:model="penanggung_jawab" label="Nama Penanggung Jawab" required class="mb-3" />
                
                <div x-data="signaturePad(@entangle('ttd_penanggung_jawab'))" class="w-full">
                    <label class="block text-sm font-medium mb-1">Tanda Tangan Penanggung Jawab <span class="text-red-500">*</span></label>
                    <div class="border-2 rounded" :class="value ? 'border-green-500' : 'border-gray-300'">
                        <canvas x-ref="signature_canvas" width="300" height="150" class="rounded shadow touch-none w-full"></canvas>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <button type="button" class="text-sm text-green-600 hover:underline font-medium" @click="sync()">
                            <span x-show="value">✓ Tersimpan</span>
                            <span x-show="!value">Simpan</span>
                        </button>
                        <button type="button" class="text-sm text-red-600 hover:underline" @click="clear()">Hapus</button>
                    </div>
                </div>
            </div>

            {{-- Petugas Pemberi Penjelasan --}}
            <div class="w-full">
                <flux:input wire:model="petugas_pemberi_penjelasan" label="Nama Petugas Pemberi Penjelasan" required class="mb-3" />
                
                <div x-data="signaturePad(@entangle('ttd_petugas'))" class="w-full">
                    <label class="block text-sm font-medium mb-1">Tanda Tangan Petugas Pemberi Penjelasan <span class="text-red-500">*</span></label>
                    <div class="border-2 rounded" :class="value ? 'border-green-500' : 'border-gray-300'">
                        <canvas x-ref="signature_canvas" width="300" height="150" class="rounded shadow touch-none w-full"></canvas>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <button type="button" class="text-sm text-green-600 hover:underline font-medium" @click="sync()">
                            <span x-show="value">✓ Tersimpan</span>
                            <span x-show="!value">Simpan</span>
                        </button>
                        <button type="button" class="text-sm text-red-600 hover:underline" @click="clear()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:button wire:click="save" onclick="syncConsentSignatures()" variant="primary">Simpan</flux:button>
        </div>
    </flux:modal>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        function syncConsentSignatures() {
            // Trigger sync for all signature pads in the consent modal
            document.querySelectorAll('[x-data^="signaturePad"]').forEach(el => {
                if (el.__x && el.__x.$data?.sync) {
                    el.__x.$data.sync();
                }
            });
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('signaturePad', (value) => ({
                signaturePadInstance: null,
                value: value,
                init() {
                    // Set canvas size explicitly
                    const canvas = this.$refs.signature_canvas;
                    const rect = canvas.getBoundingClientRect();
                    canvas.width = rect.width;
                    canvas.height = rect.height;
                    
                    this.signaturePadInstance = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(255, 255, 255)',
                        penColor: 'rgb(0, 0, 0)'
                    });

                    this.signaturePadInstance.addEventListener("endStroke", () => {
                        this.sync();
                    });

                    // Load existing signature if available
                    if (this.value) {
                        this.loadSignature();
                    }
                },
                loadSignature() {
                    const img = new Image();
                    img.onload = () => {
                        const ctx = this.$refs.signature_canvas.getContext("2d");
                        ctx.clearRect(0, 0, this.$refs.signature_canvas.width, this.$refs.signature_canvas.height);
                        ctx.drawImage(img, 0, 0, this.$refs.signature_canvas.width, this.$refs.signature_canvas.height);
                    };
                    img.src = this.value;
                },
                sync() {
                    if (!this.signaturePadInstance.isEmpty()) {
                        this.value = this.signaturePadInstance.toDataURL('image/png');
                    }
                },
                clear() {
                    this.signaturePadInstance.clear();
                    this.value = null;
                }
            }));
        });
    </script>

    {{-- Modal Konfirmasi Hapus --}}
    <flux:modal name="delete-consent" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus Data?</flux:heading>
                <flux:text>Data yang dihapus tidak dapat dikembalikan.</flux:text>
            </div>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="danger">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
