<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request; 
use App\Mail\AccountApproved;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::where('status', 'verified')->latest()->get();
        $pendingRegistrations = Registration::where('status', 'pending')->latest()->get();
        $approvedRegistrations = Registration::where('status', 'approved')->latest()->take(10)->get();
        return view('admin.registrations.index', compact('registrations', 'pendingRegistrations', 'approvedRegistrations'));
    }

    /**
     * Verifikasi pendaftaran langsung oleh Admin (Bypass antrean validator jika mendesak).
     */
    public function verifyDirectly($id)
    {
        $reg = Registration::findOrFail($id);
        $reg->update(['status' => 'verified']);

        return back()->with('success', "Pendaftaran sekolah {$reg->school_name} berhasil diverifikasi langsung oleh Admin dan kini siap diterbitkan akunnya.");
    }

    public function createAccount($id)
    {
        $reg = Registration::findOrFail($id);

        if ($reg->status !== 'verified') {
            return back()->with('error', 'Data status tidak valid.');
        }

        $password = Str::random(10);

        // Cari atau buat User
        $user = User::where('email', $reg->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $reg->school_name,
                'email' => $reg->email,
                'password' => Hash::make($password),
                'role' => 'school',
                'status' => 'active',
            ]);
        } else {
            $user->update([
                'name' => $reg->school_name,
                'password' => Hash::make($password),
                'role' => 'school',
                'status' => 'active',
            ]);
        }

        // Cari atau buat School
        $school = School::where('user_id', $user->id)->orWhere('npsn', $reg->npsn)->first();
        if (!$school) {
            School::create([
                'user_id' => $user->id,
                'name' => $reg->school_name,
                'address' => $reg->address,
                'jenjang' => $reg->jenjang,
                'npsn' => $reg->npsn,
                'survey_code' => Str::slug($reg->school_name, ''),
            ]);
        } else {
            $school->update([
                'user_id' => $user->id,
                'name' => $reg->school_name,
                'address' => $reg->address,
                'jenjang' => $reg->jenjang,
                'npsn' => $reg->npsn,
            ]);
        }

        $reg->update(['status' => 'approved']);

        // Generate Tautan Unik Cadangan (Anti-Spam / Bypass Email)
        $resetToken = Password::broker()->createToken($user);
        $resetUrl = route('password.reset', ['token' => $resetToken, 'email' => $user->email]);

        // Coba kirim email via SMTP
        $emailSent = false;
        $emailError = null;
        try {
            Mail::to($user->email)->send(new AccountApproved($user, $password));
            $emailSent = true;
        } catch (\Throwable $e) {
            $emailSent = false;
            $emailError = $e->getMessage();
        }

        // Simpan flash session detail akun dan tautan cadangan untuk Admin
        session()->flash('issued_account', [
            'school_name' => $reg->school_name,
            'email' => $user->email,
            'password' => $password,
            'contact_number' => $reg->contact_number,
            'reset_url' => $resetUrl,
            'email_status' => $emailSent ? 'success' : 'failed',
            'email_error' => $emailError,
        ]);

        if ($emailSent) {
            return back()->with('success', 'Akun sekolah ' . $reg->school_name . ' berhasil diterbitkan dan email notifikasi telah terkirim!');
        } else {
            return back()->with('warning', 'Akun sekolah ' . $reg->school_name . ' berhasil dibuat, tetapi email otomatis mengalami kendala. Gunakan tautan cadangan di bawah untuk mengirim kredensial ke narahubung sekolah.');
        }
    }

    public function generateResetLink($id)
    {
        $reg = Registration::findOrFail($id);
        $user = User::where('email', $reg->email)->first();

        if (!$user) {
            return back()->with('error', 'Akun user untuk sekolah ' . $reg->school_name . ' tidak ditemukan.');
        }

        $resetToken = Password::broker()->createToken($user);
        $resetUrl = route('password.reset', ['token' => $resetToken, 'email' => $user->email]);

        session()->flash('backup_link', [
            'school_name' => $reg->school_name,
            'email' => $user->email,
            'contact_number' => $reg->contact_number,
            'reset_url' => $resetUrl,
        ]);

        return back()->with('success', 'Tautan aktivasi / reset password baru berhasil dibuat untuk ' . $reg->school_name . '!');
    }

    public function rejectToValidator(Request $request, $id)
    {
        $request->validate(['admin_note' => 'required|string']);

        $reg = Registration::findOrFail($id);

        // Simpan catatan admin 
        $reg->update([
            'status' => 'pending',
            'admin_notes' => 'Catatan Admin: ' . $request->admin_note . ' (Mohon dicek ulang)'
        ]);

        return back()->with('success', 'Berkas dikembalikan ke Validator untuk pengecekan ulang.');
    }
}
