<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Setting;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    // Menampilkan daftar meja
    public function index()
    {
        $mejas = Meja::orderBy('kode_meja')->get();

        $totalMeja = $mejas->count();
        $mejaKosong = $mejas->where('status', 'Kosong')->count();
        $mejaTerisi = $mejas->where('status', 'Terisi')->count();
        $perluDibersihkan = $mejas->where('status', 'Perlu Dibersihkan')->count();

        $setting = Setting::first();

        return view('pegawai.mejas.index', compact(
            'mejas',
            'totalMeja',
            'mejaKosong',
            'mejaTerisi',
            'perluDibersihkan',
            'setting'
        ));
    }

    // Menampilkan form tambah meja
    public function create()
    {
        $setting = Setting::first();

        return view('pegawai.mejas.create', compact('setting'));
    }

    // Menyimpan meja baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_meja' => 'required|string|max:255|unique:mejas,kode_meja',
            'area' => 'required|string|max:100',
            'lantai' => 'required|integer|min:1',
            'jenis_area' => 'required|in:Indoor,Outdoor',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:Kosong,Terisi,Perlu Dibersihkan',
        ]);

        Meja::create($validated);

        return redirect()->route('pegawai.mejas.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    // Menampilkan form edit meja
    public function edit(Meja $meja)
    {
        $setting = Setting::first();

        return view('pegawai.mejas.edit', compact('meja', 'setting'));
    }

    // Memperbarui data meja
    public function update(Request $request, Meja $meja)
    {
        $validated = $request->validate([
            'kode_meja' => 'required|string|max:255|unique:mejas,kode_meja,' . $meja->id,
            'area' => 'required|string|max:100',
            'lantai' => 'required|integer|min:1',
            'jenis_area' => 'required|in:Indoor,Outdoor',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:Kosong,Terisi,Perlu Dibersihkan',
        ]);

        $meja->update($validated);

        return redirect()->route('pegawai.mejas.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    // Menghapus meja
    public function destroy(Meja $meja)
    {
        $meja->delete();

        return redirect()->route('pegawai.mejas.index')
            ->with('success', 'Meja berhasil dihapus.');
    }
}
