<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
  @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
  <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <!-- Brand / Logo -->
    <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
      <x-app-logo />
    </a>

    <!-- Menu Utama -->
    <flux:navlist variant="outline">
      <flux:navlist.group :heading="__('')" class="grid">
        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
          Dashboard
        </flux:navlist.item>

        <flux:navlist.item icon="user-group" :href="route('pasien-umum')" :current="request()->routeIs('pasien-umum')" wire:navigate>
          Pendaftaran
        </flux:navlist.item>

        <flux:navlist.item icon="users" :href="route('kunjungan')" :current="request()->routeIs('kunjungan')" wire:navigate>
          Pelayanan
        </flux:navlist.item>

        <flux:navlist.item icon="beaker" :href="route('farmasi')" :current="request()->routeIs('farmasi')" wire:navigate>
          Farmasi
        </flux:navlist.item>

        <flux:navlist.item icon="beaker" :href="route('hasil-lab')" :current="request()->routeIs('hasil-lab')" wire:navigate>
          Laboratorium
        </flux:navlist.item>

        <flux:navlist.item icon="beaker" :href="route('hasil-radiologi')" :current="request()->routeIs('hasil-radiologi')" wire:navigate>
          Radiologi
        </flux:navlist.item>

        <flux:navlist.item icon="folder-plus" :href="route('poli')" :current="request()->routeIs('poli')" wire:navigate>
          Poli
        </flux:navlist.item>
      </flux:navlist.group>
    </flux:navlist>

    <flux:spacer />

    <!-- Dropdown Kategori (Admin & Super Admin) -->
    <flux:navlist variant="outline">
      @hasanyrole('admin|super-admin')
      <flux:navlist.group expandable heading="Kategori" class="lg:grid">
        <flux:navlist.item :href="route('jenis-kelamin')" wire:navigate>Jenis Kelamin</flux:navlist.item>
        <flux:navlist.item :href="route('agama')" wire:navigate>Agama</flux:navlist.item>
        <flux:navlist.item :href="route('pendidikan')" wire:navigate>Pendidikan</flux:navlist.item>
        <flux:navlist.item :href="route('pekerjaan')" wire:navigate>Pekerjaan</flux:navlist.item>
        <flux:navlist.item :href="route('status-pernikahan')" wire:navigate>Status Pernikahan</flux:navlist.item>
        <flux:navlist.item :href="route('cara')" wire:navigate>Cara Pembayaran</flux:navlist.item>
        <flux:navlist.item :href="route('jenis-pemeriksaan-radiologi')" wire:navigate>Pemeriksaan Radiologi</flux:navlist.item>
        <flux:navlist.item :href="route('pemeriksaan-lab')" wire:navigate>Pemeriksaan Laboratorium</flux:navlist.item>
        <flux:navlist.item :href="route('pemeriksaan-tindakan')" wire:navigate>Pemeriksaan Tindakan</flux:navlist.item>
        <flux:navlist.item :href="route('tingkat-kesadaran')" wire:navigate>Tingkat Kesadaran</flux:navlist.item>
        <flux:navlist.item :href="route('faskes')" wire:navigate>Unit Kerja</flux:navlist.item>
      </flux:navlist.group>
      @endhasanyrole
      <!-- Dropdown Obat (Apotek & Super Admin) -->
      @hasanyrole('apotek|super-admin')
      <flux:navlist.group expandable heading="Obat" class="lg:grid">
        <flux:navlist.item :href="route('obat')" wire:navigate>Obat</flux:navlist.item>
        <flux:navlist.item :href="route('pembelian-obat')" wire:navigate>Pembelian Obat</flux:navlist.item>
        {{-- <flux:navlist.item :href="route('penjualan-obat')" wire:navigate>Penjualan Obat</flux:navlist.item> --}}
      </flux:navlist.group>
      @endhasanyrole
      <!-- Dropdown User (Super Admin Only) -->
      @role('super-admin')
      <flux:navlist.group expandable heading="Manajemen Akses" class="lg:grid">
        <flux:navlist.item :href="route('userscrud')" :current="request()->routeIs('userscrud.*')" wire:navigate>Users</flux:navlist.item>
        <flux:navlist.item :href="route('roles')" :current="request()->routeIs('roles.*')" wire:navigate>Role</flux:navlist.item>
        <flux:navlist.item :href="route('permissions')" :current="request()->routeIs('permissions.*')" wire:navigate>Permission</flux:navlist.item>
      </flux:navlist.group>
      @endrole
    </flux:navlist>

    <!-- Profile Dropdown -->
    <flux:dropdown position="bottom" align="start">
      <flux:profile
        :name="auth()->user()->name"
        :initials="auth()->user()->initials()"
        icon-trailing="chevrons-up-down" />
      <flux:menu class="w-[220px]">
        <flux:menu.radio.group>
          <div class="p-0 text-sm font-normal">
            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
              <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                  {{ auth()->user()->initials() }}
                </span>
              </span>
              <div class="grid flex-1 text-left text-sm leading-tight">
                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
              </div>
            </div>
          </div>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <flux:menu.radio.group>
          <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>Settings</flux:menu.item>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
            Log Out
          </flux:menu.item>
        </form>
      </flux:menu>
    </flux:dropdown>
  </flux:sidebar>

  <!-- Mobile User Menu -->
  <flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
    <flux:spacer />

    <flux:dropdown position="top" align="end">
      <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
      <flux:menu>
        <flux:menu.radio.group>
          <div class="p-0 text-sm font-normal">
            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
              <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                  {{ auth()->user()->initials() }}
                </span>
              </span>
              <div class="grid flex-1 text-left text-sm leading-tight">
                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
              </div>
            </div>
          </div>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <flux:menu.radio.group>
          <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>Settings</flux:menu.item>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
            Log Out
          </flux:menu.item>
        </form>
      </flux:menu>
    </flux:dropdown>
  </flux:header>


  {{ $slot }}
  <flux:toast />
  @fluxScripts
  <!-- <script src="{{ asset('storage/js/sign-pad.min.js') }}"></script> -->

</body>

</html>