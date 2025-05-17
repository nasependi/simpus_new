<?php

use App\Livewire\Agama;
use Livewire\Volt\Volt;
use App\Livewire\Pekerjaan;
use App\Livewire\Pendidikan;
use App\Livewire\JenisKelamin;
use App\Livewire\Auth\RoleCrud;
use App\Livewire\Auth\UserCrud;
use App\Livewire\PasienUmumCrud;
use app\livewire\RegisterPasien;
use App\Models\StatusPernikahan;
use App\Livewire\Auth\PermissionCrud;
use App\Livewire\BayiBaruLahirIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\StatusPernikahan as LivewireStatusPernikahan;
use App\Models\BayiBaruLahir;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/pasien-umum', PasienUmumCrud::class)->name('pasien-umum');
    Route::get('/bayi-baru-lahir', BayiBaruLahirIndex::class)->name('bayi-baru-lahir');

    Route::redirect('settings', 'settings/profile');
    Route::get('/permissions', PermissionCrud::class)->name('permissions');
    Route::get('/roles', RoleCrud::class)->name('roles');
    Route::get('/userscrud', UserCrud::class)->name('userscrud');

    Route::get('/jenis-kelamin', JenisKelamin::class)->name('jenis-kelamin');
    Route::get('/agama', Agama::class)->name('agama');
    Route::get('/pendidikan', Pendidikan::class)->name('pendidikan');
    Route::get('/pekerjaan', Pekerjaan::class)->name('pekerjaan');
    Route::get('/status-pernikahan', LivewireStatusPernikahan::class)->name('status-pernikahan');


    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
