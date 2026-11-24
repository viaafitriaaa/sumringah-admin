
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\MenuController;
use App\Http\Controllers\Owner\MejaController;
use App\Http\Controllers\Owner\SettingController;
use App\Http\Controllers\Pegawai\MejaController as PegawaiMejaController;
use App\Http\Controllers\Pegawai\MenuController as PegawaiMenuController;

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect dashboard sesuai role
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }

    return redirect()->route('pegawai.dashboard');
})->middleware('auth');

// Dashboard Owner
Route::get('/owner/dashboard', function () {
    abort_unless(auth()->user()->role === 'owner', 403);

    $mejas = \App\Models\Meja::all();
    $setting = \App\Models\Setting::first();

    return view('owner.dashboard', compact('mejas', 'setting'));
})->middleware('auth')->name('owner.dashboard');

// Dashboard Pegawai
Route::get('/pegawai/dashboard', function () {
    abort_unless(auth()->user()->role === 'pegawai', 403);

    $mejas = \App\Models\Meja::orderBy('area')->orderBy('kode_meja')->get();
    $setting = \App\Models\Setting::first();

    return view('pegawai.dashboard', compact('mejas', 'setting'));
})->middleware('auth')->name('pegawai.dashboard');

// Fitur Owner
Route::middleware(['auth'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        // Menu & Stok
        Route::resource('menus', MenuController::class);

        // Owner hanya dapat melihat dan memantau meja
        Route::get('/mejas', [MejaController::class, 'index'])
            ->name('mejas.index');

        // Pengaturan Owner
        Route::get('/settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::post('/settings/profile', [SettingController::class, 'updateProfile'])
            ->name('settings.profile');

        Route::post('/settings/hours', [SettingController::class, 'updateHours'])
            ->name('settings.hours');

        Route::post('/settings/password', [SettingController::class, 'updatePassword'])
            ->name('settings.password');
    });

// Fitur Pegawai
Route::middleware(['auth', 'pegawai'])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {

        // Info Meja
        Route::resource('mejas', PegawaiMejaController::class);

        // Menu & Stok
        Route::get('/menus', [PegawaiMenuController::class, 'index'])
            ->name('menus.index');

        Route::patch('/menus/{menu}/stok', [PegawaiMenuController::class, 'updateStok'])
            ->name('menus.updateStok');
    });

// Cek konfigurasi upload
Route::get('/cek-php', function () {
    return [
        'ini_file' => php_ini_loaded_file(),
        'upload_max' => ini_get('upload_max_filesize'),
        'post_max' => ini_get('post_max_size'),
        'tmp_dir' => sys_get_temp_dir(),
        'tmp_writable' => is_writable(sys_get_temp_dir()),
    ];
});

require __DIR__.'/customer.php';
