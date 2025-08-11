<?php

use App\Livewire\Poli;
use App\Livewire\Agama;
use Livewire\Volt\Volt;
use App\Livewire\Pekerjaan;
use App\Livewire\Pendidikan;
use App\Livewire\JenisKelamin;
use App\Livewire\Auth\RoleCrud;
use App\Livewire\Auth\UserCrud;
use App\Livewire\ObatComponent;
use App\Livewire\CaraPembayaran;
use App\Livewire\PasienUmumCrud;
use App\Livewire\BayiBaruLahirIndex;
use App\Livewire\Auth\PermissionCrud;
use App\Livewire\Kunjungan\Kunjungan;
use Illuminate\Support\Facades\Route;
use App\Livewire\JenisPemeriksaanRadiologiComponent;
use App\Livewire\StatusPernikahan as LivewireStatusPernikahan;
use App\Livewire\TingkatKesadaran as LivewireTingkatKesadaran;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    //Menu for management
    Route::get('/pasien-umum', PasienUmumCrud::class)->name('pasien-umum');
    Route::get('/bayi-baru-lahir', BayiBaruLahirIndex::class)->name('bayi-baru-lahir');
    Route::get('/kunjungan', Kunjungan::class)->name('kunjungan');

    //User management
    Route::redirect('settings', 'settings/profile');
    Route::get('/permissions', PermissionCrud::class)->name('permissions');
    Route::get('/roles', RoleCrud::class)->name('roles');
    Route::get('/userscrud', UserCrud::class)->name('userscrud');

    //Categories for management
    Route::get('/jenis-kelamin', JenisKelamin::class)->name('jenis-kelamin');
    Route::get('/agama', Agama::class)->name('agama');
    Route::get('/pendidikan', Pendidikan::class)->name('pendidikan');
    Route::get('/pekerjaan', Pekerjaan::class)->name('pekerjaan');
    Route::get('/status-pernikahan', LivewireStatusPernikahan::class)->name('status-pernikahan');
    Route::get('/poli', Poli::class)->name('poli');
    Route::get('/cara-pembayaran', CaraPembayaran::class)->name('cara');
    Route::get('/jenis-pemeriksaan-radiologi', JenisPemeriksaanRadiologiComponent::class)->name('jenis-pemeriksaan-radiologi');
    Route::get('/tingkat-kesadaran', LivewireTingkatKesadaran::class)->name('tingkat-kesadaran');

    //Categories for drug management
    Route::get('/obat', ObatComponent::class)->name('obat');
    Route::get('/pembelian-obat', \App\Livewire\PembelianObat::class)->name('pembelian-obat');
    Route::get('/penjualan-obat', \App\Livewire\PenjualanObat::class)->name('penjualan-obat');
    Route::get('/detail-pembelianobat', \App\Livewire\DetailPembelianObat::class)->name('detail-pembelianobat');
    Route::get('/detail-penjualanobat', \App\Livewire\DetailPenjualanObat::class)->name('detail-penjualanobat');

    //Settings
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
