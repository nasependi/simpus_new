<div class="p-6">
    <flux:card class="shadow-lg rounded-lg">
        <flux:select wire:model="form.status_psikologis" placeholder="Status psikologis...">
            <flux:select.option>1 . Tidak ada kelainan</flux:select.option>
            <flux:select.option>2. Cemas</flux:select.option>
            <flux:select.option>3. Takut</flux:select.option>
            <flux:select.option>4. Marah</flux:select.option>
            <flux:select.option>5. Sedih</flux:select.option>
        </flux:select>

        <flux:input wire:model="form.sosial_ekonomi" label="Sosial Ekonomi" required />
        <flux:input wire:model="form.spiritual" label="Spiritual" required />

        <div class="flex justify-end gap-2 mt-3">
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:button wire:click="save" variant="primary">Simpan</flux:button>
        </div>
    </flux:card>
</div>
