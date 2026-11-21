<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // Menampilkan halaman pengaturan
    public function index()
{
    $setting = Setting::firstOrCreate(
        [],
        ['nama_usaha' => 'Warkop Sumringah']
    );

    return view('owner.settings.index', compact('setting'));
}
    // Menyimpan profil usaha
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'instagram' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $setting = Setting::firstOrCreate(
            [],
            ['nama_usaha' => 'Warkop Sumringah']
        );

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }

            $validated['logo'] = $request->file('logo')
                ->store('logos', 'public');
        }

        $setting->update(collect($validated)->except('logo')->all());

        if (isset($validated['logo'])) {
            $setting->update(['logo' => $validated['logo']]);
        }

        return back()->with('success', 'Profil usaha berhasil diperbarui.');
    }

    // Menyimpan jam operasional
    public function updateHours(Request $request)
    {
        $validated = $request->validate([
            'jam_operasional' => 'required|array',
        ]);

        $setting = Setting::firstOrCreate(
            [],
            ['nama_usaha' => 'Warkop Sumringah']
        );

        $setting->update([
            'jam_operasional' => $validated['jam_operasional'],
        ]);

        return back()->with('success', 'Jam operasional berhasil diperbarui.');
    }

    // Mengganti password Owner
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->password = Hash::make($request->password_baru);
        

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
