<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">

        <flux:select wire:model="status_psikologis" placeholder="Choose industry...">
            <flux:select.option>Photography</flux:select.option>
            <flux:select.option>Design services</flux:select.option>
            <flux:select.option>Web development</flux:select.option>
        </flux:select>
        <flux:input wire:model="sosial_ekonomi" label="Nama Obat" required />
        <flux:input wire:model="spiritual" label="Dosis" required />

        <div class="flex justify-end gap-2 mt-3">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:button wire:click="save" variant="primary">Simpan</flux:button>
        </div>
    </flux:card>
</div>
