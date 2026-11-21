<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Menampilkan daftar menu
    public function index()
    {
        $menus = Menu::latest()->get();
        $setting = Setting::first();

        return view('owner.menus.index', compact('menus', 'setting'));
    }

    // Menampilkan form tambah menu
    public function create()
    {
        $setting = Setting::first();

        return view('owner.menus.create', compact('setting'));
    }

    // Menyimpan menu baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:5120',
            'status' => 'required|in:tersedia,habis',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('menus', 'public');
        }

        Menu::create($validated);

        return redirect()->route('owner.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    // Menampilkan form edit menu
    public function edit(Menu $menu)
    {
        $setting = Setting::first();

        return view('owner.menus.edit', compact('menu', 'setting'));
    }

    // Memperbarui menu
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:5120',
            'status' => 'required|in:tersedia,habis',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('owner.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    // Menghapus menu
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('owner.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
