<div class="space-y-3">
    <flux:select wire:model="form.status_psikologis" placeholder="Status psikologis...">
        <flux:select.option>1 . Tidak ada kelainan</flux:select.option>
        <flux:select.option>2. Cemas</flux:select.option>
        <flux:select.option>3. Takut</flux:select.option>
        <flux:select.option>4. Marah</flux:select.option>
        <flux:select.option>5. Sedih</flux:select.option>
    </flux:select>
    <div class="grid grid-cols-2 gap-2">
        <flux:input wire:model="form.sosial_ekonomi" label="Sosial Ekonomi" required />
        <flux:input wire:model="form.spiritual" label="Spiritual" required />
    </div>
</div>
