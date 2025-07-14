<div class="space-y-3">
    <flux:input wire:model="nama_obat" label="Nama Obat" required />
    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="dosis" label="Dosis" required />
        <flux:input wire:model="waktu_penggunaan" label="Waktu Penggunaan" required />
    </div>
    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="rencana_rawat" label="Rencana Rawat" required />
        <flux:input wire:model="intruksi_medik" label="Instruksi Medik" required />
    </div>

</div>