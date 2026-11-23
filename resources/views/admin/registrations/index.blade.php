@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8">
        
        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Penerbitan Akun Sekolah</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola verifikasi final, pengiriman akun via email, dan tautan cadangan aktivasi (anti-spam).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold">
                    <i class="bi bi-envelope-check-fill text-blue-600"></i> SMTP: {{ config('mail.mailers.smtp.host') }}:{{ config('mail.mailers.smtp.port') }}
                </span>
            </div>
        </div>

        {{-- ALERT CARD 1: AKUN BARU SAJA DITERBITKAN (LENGKAP DENGAN BACKUP LINK & WHATSAPP) --}}
        @if(session('issued_account'))
            @php $acc = session('issued_account'); @endphp
            <div class="rounded-2xl border p-6 shadow-xl transition-all {{ $acc['email_status'] === 'success' ? 'bg-gradient-to-br from-emerald-50 via-white to-blue-50 border-emerald-200' : 'bg-gradient-to-br from-amber-50 via-white to-orange-50 border-amber-300' }}">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4 pb-4 border-b border-slate-200/80">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            @if($acc['email_status'] === 'success')
                                <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 flex items-center gap-1.5">
                                    <i class="bi bi-check-circle-fill text-emerald-600"></i> EMAIL TERKIRIM
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 flex items-center gap-1.5">
                                    <i class="bi bi-exclamation-triangle-fill text-amber-600"></i> EMAIL TERTUNDA / GAGAL
                                </span>
                            @endif
                            <h3 class="text-lg font-black text-slate-800">
                                Akun Sekolah: {{ $acc['school_name'] }}
                            </h3>
                        </div>
                        <p class="text-xs text-slate-600">
                            @if($acc['email_status'] === 'success')
                                Email kredensial telah otomatis dikirimkan ke <strong>{{ $acc['email'] }}</strong>.
                            @else
                                Pengiriman email otomatis mengalami kendala. Anda dapat membagikan kredensial langsung melalui tautan cadangan di bawah.
                            @endif
                        </p>
                    </div>

                    <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm text-xs font-mono">
                        <span class="text-slate-400">Password Sementara:</span>
                        <span class="font-bold text-slate-800 text-sm ml-1 select-all" id="tempPass">{{ $acc['password'] }}</span>
                    </div>
                </div>

                {{-- TAUTAN CADANGAN (ANTI-SPAM) --}}
                <div class="bg-white/80 backdrop-blur rounded-xl p-4 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <i class="bi bi-shield-check text-blue-600"></i> Tautan Cadangan Aktivasi / Reset Password (Bypass Email)
                        </label>
                        <span class="text-[11px] text-slate-400">Berlaku untuk 60 menit</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <input type="text" readonly id="issuedResetUrl" value="{{ $acc['reset_url'] }}"
                               class="flex-1 bg-slate-50 border border-slate-200 text-slate-700 text-xs font-mono rounded-lg px-3 py-2 select-all focus:outline-none focus:ring-2 focus:ring-blue-500">
                        
                        <button type="button" onclick="copyIssuedLink()"
                                class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-1.5">
                            <i class="bi bi-clipboard-check"></i> <span id="copyLinkText">Salin Tautan</span>
                        </button>

                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $acc['contact_number']);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                            $waMessage = "Halo " . $acc['school_name'] . ",\n\nAkun Anda di Handal BBLSDM Komdigi telah diterbitkan.\n- Email: " . $acc['email'] . "\n- Password Sementara: " . $acc['password'] . "\n\nJika email notifikasi tidak masuk ke inbox (misal masuk ke folder Spam), Anda dapat langsung mengaktifkan akun dan mengatur password baru melalui tautan resmi ini:\n" . $acc['reset_url'] . "\n\nTerima kasih,\nTim Handal Komdigi";
                            $waLink = "https://wa.me/" . $cleanPhone . "?text=" . urlencode($waMessage);
                        @endphp

                        @if(!empty($acc['contact_number']))
                            <a href="{{ $waLink }}" target="_blank"
                               class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-1.5">
                                <i class="bi bi-whatsapp"></i> Kirim WhatsApp PIC
                            </a>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-500">
                        <i class="bi bi-info-circle mr-1"></i> Tautan unik ini mengizinkan operator sekolah untuk langsung membuat kata sandi baru secara aman tanpa harus bergantung pada penerimaan email.
                    </p>
                </div>
            </div>
        @endif

        {{-- ALERT CARD 2: BACKUP RESET LINK GENERATED RECENTLY --}}
        @if(session('backup_link'))
            @php $b = session('backup_link'); @endphp
            <div class="rounded-2xl border border-blue-200 bg-blue-50/70 p-5 shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs">
                            <i class="bi bi-key-fill"></i>
                        </span>
                        <h4 class="font-bold text-slate-800 text-sm">
                            Tautan Baru Diterbitkan: {{ $b['school_name'] }} ({{ $b['email'] }})
                        </h4>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" readonly id="backupResetUrl" value="{{ $b['reset_url'] }}"
                           class="flex-1 bg-white border border-blue-200 text-slate-700 text-xs font-mono rounded-lg px-3 py-2 select-all focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('backupResetUrl').value); window.handalAlert({ title: 'Tautan Tersalin!', message: 'Tautan reset password unik telah disalin ke clipboard.', type: 'success' });"
                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-colors">
                        <i class="bi bi-clipboard"></i> Salin Tautan
                    </button>
                </div>
            </div>
        @endif

        {{-- TABEL 1: MENUNGGU PERSETUJUAN ADMIN (STATUS: VERIFIED) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Permohonan Menunggu Penerbitan Akun</h3>
                    <p class="text-xs text-slate-500">Berkas pendaftaran telah diverifikasi oleh Validator dan siap dibuatkan akun.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-700">
                    {{ $registrations->count() }} Antrean
                </span>
            </div>

            <table class="w-full text-left">
                <thead class="bg-slate-100/70 text-slate-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 font-bold">Nama Sekolah & Jenjang</th>
                        <th class="px-6 py-3.5 font-bold">Email & Kontak PIC</th>
                        <th class="px-6 py-3.5 font-bold">NPSN</th>
                        <th class="px-6 py-3.5 font-bold text-right">Aksi Penerbitan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $reg->school_name }}</div>
                                <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                    <span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 font-semibold">{{ $reg->jenjang }}</span>
                                    <span>{{ $reg->city }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-mono text-xs text-slate-700 font-medium">{{ $reg->email }}</div>
                                <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                    <i class="bi bi-telephone text-slate-400"></i> {{ $reg->contact_number ?? '-' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                {{ $reg->npsn }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.registrations.create', $reg->id) }}" method="POST"
                                          onsubmit="event.preventDefault(); window.handalConfirmForm(this, 'Terbitkan akun untuk sekolah {{ $reg->school_name }}? Sistem akan membuat akun, mengirim email kredensial resmi, dan menyiapkan tautan cadangan aktivasi mandiri.', 'Penerbitan Akun Sekolah', 'info');">
                                        @csrf
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md shadow-blue-500/15 transition-all flex items-center gap-1.5">
                                            <i class="bi bi-key-fill"></i> Terbitkan Akun
                                        </button>
                                    </form>

                                    <button
                                        data-id="{{ $reg->id }}"
                                        data-name="{{ $reg->school_name }}"
                                        onclick="openAdminRejectModal(this)"
                                        class="bg-orange-50 text-orange-600 hover:bg-orange-100 px-3.5 py-2 rounded-xl font-bold text-xs border border-orange-200 transition-all flex items-center gap-1">
                                        <i class="bi bi-arrow-counterclockwise"></i> Kembalikan
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                <i class="bi bi-inbox text-3xl mb-2 block text-slate-300"></i>
                                Tidak ada data pendaftaran yang sedang menunggu persetujuan admin saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TABEL 2: RIWAYAT AKUN TELAH DITERBITKAN (STATUS: APPROVED) & DUKUNGAN RESET LINK --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Riwayat Akun Telah Diterbitkan (Terverifikasi)</h3>
                    <p class="text-xs text-slate-500">Jika sekolah melaporkan email tidak masuk / terindikasi spam, Anda dapat membuat ulang tautan aktivasi mandiri di sini.</p>
                </div>
            </div>

            <table class="w-full text-left">
                <thead class="bg-slate-100/70 text-slate-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 font-bold">Sekolah</th>
                        <th class="px-6 py-3.5 font-bold">Email Akun</th>
                        <th class="px-6 py-3.5 font-bold">Status</th>
                        <th class="px-6 py-3.5 font-bold text-right">Cadangan Tautan (Anti-Spam)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($approvedRegistrations as $appReg)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $appReg->school_name }}</div>
                                <div class="text-xs text-slate-400">NPSN: {{ $appReg->npsn }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-700">
                                {{ $appReg->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    <i class="bi bi-check2-circle"></i> Akun Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.registrations.reset_link', $appReg->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-xs font-bold transition-colors">
                                        <i class="bi bi-link-45deg"></i> Buat Tautan Reset Baru
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-slate-400 text-xs">
                                Belum ada riwayat akun yang diterbitkan dari modul registrasi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- MODAL KEMBALIKAN KE VALIDATOR --}}
    <div id="adminRejectModal"
        class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-slate-900 mb-2">Kembalikan ke Validator?</h3>

            <p class="text-slate-500 text-sm mb-4">
                Berikan catatan untuk validator mengenai data
                <span id="modalSchoolName" class="font-bold"></span>
            </p>

            <form id="rejectForm" method="POST">
                @csrf

                <textarea
                    name="admin_note"
                    rows="3"
                    required
                    class="w-full border-slate-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 mb-4 p-3 text-sm"
                    placeholder="Contoh: Nama sekolah typo, mohon cek ulang berkas PDF..."></textarea>

                <div class="flex gap-2">
                    <button
                        type="button"
                        onclick="closeAdminRejectModal()"
                        class="flex-grow py-2.5 text-slate-500 font-bold hover:bg-slate-50 rounded-lg">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="flex-grow py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-bold shadow-md">
                        Kirim ke Validator
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAdminRejectModal(button) {
            const id = button.dataset.id;
            const name = button.dataset.name;

            document.getElementById('modalSchoolName').innerText = name;

            let url = "{{ route('admin.registrations.reject', ':id') }}";
            document.getElementById('rejectForm').action = url.replace(':id', id);

            document.getElementById('adminRejectModal').classList.remove('hidden');
        }

        function closeAdminRejectModal() {
            document.getElementById('adminRejectModal').classList.add('hidden');
        }

        function copyIssuedLink() {
            const input = document.getElementById('issuedResetUrl');
            if (input) {
                input.select();
                navigator.clipboard.writeText(input.value);
                const txt = document.getElementById('copyLinkText');
                if (txt) {
                    txt.innerText = 'Tersalin!';
                    setTimeout(() => { txt.innerText = 'Salin Tautan'; }, 2000);
                }
            }
        }
    </script>
@endsection
