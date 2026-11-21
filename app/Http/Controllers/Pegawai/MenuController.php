<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('nama_menu')->get();
        $setting = Setting::first();

        return view('pegawai.menus.index', compact('menus', 'setting'));
    }

    public function updateStok(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,habis',
        ]);

        $menu->update($validated);

        return redirect()
            ->route('pegawai.menus.index')
            ->with('success', 'Stok menu berhasil diperbarui.');
    }
}
