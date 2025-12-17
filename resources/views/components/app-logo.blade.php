<div class="flex items-center gap-3 group">
    {{-- Logo Icon with Gradient Background --}}
    <div class="relative flex aspect-square size-9 sm:size-10 items-center justify-center rounded-xl 
                bg-gradient-to-br from-neutral-700 via-neutral-800 to-neutral-900 
                dark:from-neutral-600 dark:via-neutral-700 dark:to-neutral-800
                shadow-lg shadow-neutral-350/30 dark:shadow-neutral-350/20
                transition-all duration-300 group-hover:scale-105 group-hover:shadow-xl group-hover:shadow-neutral-350/40">
        {{-- Glow Effect --}}
        <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-white/20 to-transparent opacity-50"></div>
        
        {{-- Icon --}}
        <x-app-logo-icon class="relative size-6 sm:size-7 fill-current text-white drop-shadow-md" />
    </div>
    
    {{-- Text Content --}}
    <div class="flex flex-col flex-1 min-w-0">
        <flux:heading 
            size="lg" 
            variant="strong" 
            class="text-xl sm:text-1xl font-extrabold tracking-tight text-neutral-900 dark:text-neutral-100
                   transition-all duration-300 group-hover:text-neutral-700 dark:group-hover:text-neutral-300">
            SIMPUS
        </flux:heading>
        <span class="text-[12px] sm:text-xs font-medium text-neutral-400 dark:text-neutral-300 
                     tracking-wide -mt-0.5 truncate">
            Sistem Manajemen Puskesmas
        </span>
    </div>
</div>