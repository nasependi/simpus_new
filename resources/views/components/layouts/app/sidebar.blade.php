<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
  @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
  <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <!-- Tombol sidebar untuk mobile -->
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <!-- Brand / Logo -->
    <a href="{{ route('dashboard') }}" class="max-lg:hidden flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
      <x-app-logo />
    </a>

    <!-- Navbar Menu Utama -->
    <flux:navbar class="-mb-px max-lg:hidden">
      <flux:navbar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
        Dashboard
      </flux:navbar.item>
      <flux:navbar.item icon="user-group" href="{{ route('pasien-umum') }}" :active="request()->routeIs('pasien-umum')">
        Pendaftaran
      </flux:navbar.item>
      <flux:navbar.item icon="users" href="{{ route('kunjungan') }}" :active="request()->routeIs('kunjungan')">
        Pelayanan
      </flux:navbar.item>
      <flux:navbar.item icon="beaker" href="{{ route('farmasi') }}" :active="request()->routeIs('farmasi')">
        Farmasi
      </flux:navbar.item>
      <flux:navbar.item icon="folder-plus" href="{{ route('poli') }}" :active="request()->routeIs('poli')">
        Poli
      </flux:navbar.item>
    </flux:navbar>

    <flux:spacer />

    <!-- Menu User -->

    @hasanyrole('admin|super-admin')
    <!-- Dropdown untuk kategori admin & super admin -->
    <flux:dropdown class="max-lg:hidden">
      <flux:navbar.item icon:trailing="chevron-down">Kategori</flux:navbar.item>
      <flux:navmenu>
        <flux:navmenu.item href="{{ route('jenis-kelamin') }}">Jenis Kelamin</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('agama') }}">Agama</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('pendidikan') }}">Pendidikan</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('pekerjaan') }}">Pekerjaan</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('status-pernikahan') }}">Status Pernikahan</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('cara') }}">Cara Pembayaran</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('jenis-pemeriksaan-radiologi') }}">Jenis Pemeriksaan Radiologi</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('pemeriksaan-lab') }}">Jenis Pemeriksaan Laboratorium</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('pemeriksaan-tindakan') }}">Jenis Pemeriksaan Tindakan</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('tingkat-kesadaran') }}">Tingkat Kesadaran</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('faskes') }}">Unit Kerja</flux:navmenu.item>
      </flux:navmenu>
    </flux:dropdown>
    @endhasanyrole

    @hasanyrole('apotek|super-admin')
    <flux:dropdown class="max-lg:hidden">
      <flux:navbar.item icon:trailing="chevron-down">Obat</flux:navbar.item>
      <flux:navmenu>
        <flux:navmenu.item href="{{ route('obat') }}">Obat</flux:navmenu.item>
        <flux:navmenu.item href="{{ route('pembelian-obat') }}">Pembelian Obat</flux:navmenu.item>
        <!-- <flux:navmenu.item href="{{ route('penjualan-obat') }}">Penjualan Obat</flux:navmenu.item> -->
      </flux:navmenu>
    </flux:dropdown>
    @endhasanyrole

    @role('super-admin')
    <flux:dropdown class="max-lg:hidden">
      <flux:navbar.item icon:trailing="chevron-down">User</flux:navbar.item>
      <flux:navmenu>
        <flux:navmenu.item :href="route('userscrud')" :current="request()->routeIs('userscrud.*')" wire:navigate>
          Users
        </flux:navmenu.item>
        <flux:navmenu.item :href="route('roles')" :current="request()->routeIs('roles.*')" wire:navigate>
          Role
        </flux:navmenu.item>
        <flux:navmenu.item :href="route('permissions')" :current="request()->routeIs('permissions.*')" wire:navigate>
          Permission
        </flux:navmenu.item>
      </flux:navmenu>
    </flux:dropdown>
    @endrole

    <flux:dropdown position="top" align="start">
      <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()" icon-trailing="chevrons-up-down" />
      <flux:menu class="w-[220px]">
        <flux:menu.radio.group>
          <div class="p-0 text-sm font-normal">
            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
              <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                  {{ auth()->user()->initials() }}
                </span>
              </span>
              <div class="grid flex-1 text-start text-sm leading-tight">
                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
              </div>
            </div>
          </div>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <flux:menu.radio.group>
          <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
            {{ __('Settings') }}
          </flux:menu.item>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
            {{ __('Log Out') }}
          </flux:menu.item>
        </form>
      </flux:menu>
    </flux:dropdown>
  </flux:header>


  <!-- Mobile User Menu -->
  <flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <flux:spacer />

    <flux:dropdown position="top" align="end">
      <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

      <flux:menu>
        <flux:menu.radio.group>
          <div class="p-0 text-sm font-normal">
            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
              <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                <span
                  class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                  {{ auth()->user()->initials() }}
                </span>
              </span>

              <div class="grid flex-1 text-start text-sm leading-tight">
                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
              </div>
            </div>
          </div>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <flux:menu.radio.group>
          <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
            {{ __('Settings') }}
          </flux:menu.item>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
            {{ __('Log Out') }}
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