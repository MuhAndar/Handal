<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $school = $user->school; 

        return view('school.profile', compact('user', 'school'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $school = $user->school;

        $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'jenjang' => 'required|string|max:50', 
            'address' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto yang didukung: JPG, JPEG, PNG, WEBP.',
            'photo.max'   => 'Ukuran foto maksimal 2MB.',
        ]);

        $user->update([
            'email' => $request->email
        ]);

        $school->update([
            'jenjang' => $request->jenjang,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('school_photos', 'public');
            $school->pushPhotoToHistory($path);
        }

        return back()->with('success', 'Profil dan foto sekolah berhasil diperbarui.');
    }

    /**
     * Ganti foto aktif dari daftar 5 riwayat foto terakhir.
     */
    public function switchPhoto(Request $request)
    {
        $request->validate([
            'photo_path' => 'required|string'
        ]);

        $school = Auth::user()->school;
        if ($school && $school->setActivePhoto($request->photo_path)) {
            return back()->with('success', 'Foto profil sekolah aktif berhasil diubah.');
        }

        return back()->with('error', 'Foto tidak ditemukan dalam riwayat.');
    }

    /**
     * Hapus foto tertentu dari riwayat sekolah.
     */
    public function deletePhoto(Request $request)
    {
        $request->validate([
            'photo_path' => 'required|string'
        ]);

        $school = Auth::user()->school;
        if ($school) {
            $school->removePhotoFromHistory($request->photo_path);
            return back()->with('success', 'Foto berhasil dihapus dari riwayat.');
        }

        return back()->with('error', 'Gagal memproses penghapusan foto.');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed', 
        ]);
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }
        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
