@extends('layouts.app')

@section('title', 'Aktivasi & Setel Password Baru - Handal Sekolah')

@section('content')
    <div class="min-h-screen flex relative overflow-hidden bg-slate-50">

        {{-- BAGIAN KIRI: BRANDING & VISUAL --}}
        <div class="hidden lg:flex lg:w-1/2 relative z-10 overflow-hidden group bg-blue-900">
            <img src="{{ asset('img/SAMPUL FINAL XTRA COLOR 2.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay transition-transform duration-[20s] group-hover:scale-110"
                alt="Background Sekolah">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 opacity-90"></div>

            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;">
            </div>

            <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-b from-transparent to-blue-950/50"></div>

            <div class="relative z-20 w-full flex flex-col justify-between p-16 text-white h-full">

                <div class="flex items-center gap-3 opacity-90">
                    <div class="w-1 h-8 bg-blue-400 rounded-full"></div>
                    <span class="text-sm font-bold tracking-widest uppercase">Handal Security Access</span>
                </div>

                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-bold mb-4">
                        <i class="bi bi-shield-lock-fill"></i> Tautan Aktivasi Terverifikasi
                    </span>
                    <h2 class="text-4xl font-black mb-4 leading-tight drop-shadow-md">
                        Aktivasi Akun & <br>
                        <span class="text-blue-300">Setel Password Baru.</span>
                    </h2>
                    <div class="h-1.5 w-24 bg-blue-500 rounded-full mb-6"></div>
                    <p class="text-blue-100 text-base leading-relaxed max-w-md font-light">
                        Tautan ini diterbitkan secara resmi oleh BBLSDM Komdigi Makassar untuk memverifikasi dan menetapkan kata sandi baru akun sekolah Anda.
                    </p>
                </div>

                <div class="flex justify-between items-end border-t border-blue-500/30 pt-8">
                    <div class="text-xs text-blue-200">
                        &copy; 2026 BBLSDM Komdigi Makassar
                    </div>
                    <div class="flex gap-2">
                        <span class="w-8 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="w-2 h-1.5 rounded-full bg-blue-400/30"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: FORM RESET / AKTIVASI --}}
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 relative z-10 bg-white">

            <div class="lg:hidden absolute top-0 left-0 w-full h-2 bg-blue-600"></div>

            <div class="mx-auto w-full max-w-sm lg:w-[26rem]">

                <div class="mb-8 text-center lg:text-left">
                    <img class="h-14 w-auto mb-6 mx-auto lg:mx-0 lg:hidden drop-shadow-sm"
                        src="{{ asset('img/Handal 8.png') }}" alt="Handal Logo">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100 mb-3">
                        <i class="bi bi-key-fill"></i> Aktivasi Akun Sekolah
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Setel Password Baru</h2>
                    <p class="mt-2 text-sm text-slate-500 font-medium">
                        Masukkan kata sandi baru untuk mengaktifkan akses login sekolah Anda.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm text-sm">
                        <i class="bi bi-exclamation-triangle-fill text-lg mt-0.5"></i>
                        <div>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Alamat Email Terdaftar
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email', $email) }}" required readonly
                                class="w-full pl-10 pr-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-600 text-sm font-semibold cursor-not-allowed">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Email dikunci sesuai permohonan pendaftaran.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Password Baru
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <input type="password" name="password" required minlength="8"
                                placeholder="Minimal 8 karakter..."
                                class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <i class="bi bi-check2-circle"></i>
                            </span>
                            <input type="password" name="password_confirmation" required minlength="8"
                                placeholder="Ulangi password baru..."
                                class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Simpan Password & Masuk
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                        &larr; Kembali ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
