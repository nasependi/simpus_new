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
use App\Livewire\FaskesComponent;
use App\Livewire\FarmasiComponent;
use App\Livewire\BayiBaruLahirIndex;
use App\Livewire\Auth\PermissionCrud;
use App\Livewire\Kunjungan\Kunjungan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\TiketController;
use App\Livewire\HasilLabComponent;
use App\Livewire\HasilRadiologiComponent;
use App\Livewire\PemeriksaanLabComponent;
use App\Livewire\PemeriksaanTindakanComponent;
use App\Livewire\JenisPemeriksaanRadiologiComponent;
use App\Livewire\StatusPernikahan as LivewireStatusPernikahan;
use App\Livewire\TingkatKesadaran as LivewireTingkatKesadaran;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    //Menu for management
    Route::get('/pasien-umum', PasienUmumCrud::class)->name('pasien-umum');
    Route::get('/bayi-baru-lahir', BayiBaruLahirIndex::class)->name('bayi-baru-lahir');
    Route::get('/kunjungan', Kunjungan::class)->name('kunjungan');
    Route::get('/farmasi', FarmasiComponent::class)->name('farmasi');
    Route::get('/hasil-lab', HasilLabComponent::class)->name('hasil-lab');
    Route::get('/hasil-radiologi', HasilRadiologiComponent::class)->name('hasil-radiologi');


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
    Route::get('/pemeriksaan-lab', PemeriksaanLabComponent::class)->name('pemeriksaan-lab');
    Route::get('/pemeriksaan-tindakan', PemeriksaanTindakanComponent::class)->name('pemeriksaan-tindakan');
    Route::get('/tingkat-kesadaran', LivewireTingkatKesadaran::class)->name('tingkat-kesadaran');
    Route::get('/faskes', FaskesComponent::class)->name('faskes');

    Route::get('/farmasi/resep/{id}/print', [ResepController::class, 'print'])->name('resep.print');
    Route::get('/farmasi/tiket/{id}/print', [TiketController::class, 'print'])->name('tiket.print');



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
