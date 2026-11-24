@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- HEADER --}}
        <div class="mb-8 flex items-center gap-4">
            @if(!empty($school->photo))
                <img src="{{ $school->photo_url }}" alt="{{ $school->name }}"
                     class="w-14 h-14 rounded-2xl object-cover border-2 border-slate-200 shadow-sm bg-white flex-shrink-0">
            @endif
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Halo, {{ Auth::user()->name }}</h1>
                <p class="text-slate-500 font-medium">Selamat datang di panel evaluasi keamanan digital sekolah.</p>
            </div>
        </div>

        {{-- LOGIKA TEMA --}}
        @php
            $rankLabel = strtolower($currentSurvey->rank_label ?? '');
            $status = $surveyStatus ?? 'draft';
            $isVerified = in_array($status, ['verified', 'approved']);

            $shineEffect =
                '<div class="absolute top-0 -left-[100%] w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent skew-x-[25deg] transition-all duration-1000 group-hover/shine:left-[200%]"></div>';

            $theme = [
                'bg_gradient' => 'from-blue-600 to-indigo-700',
                'card_border' => 'border-white/10',
                'text_accent' => 'text-blue-100',
                'icon_bg' => 'bg-blue-800/30',
                'anim_class' => '',
                'decor_element' => '',
            ];

            if ($isVerified) {
                if (str_contains($rankLabel, 'unggul') || str_contains($rankLabel, 'diamond')) {
                    $theme = [
                        'bg_gradient' => 'from-indigo-600 via-purple-600 to-fuchsia-700',
                        'card_border' => 'border-indigo-400/50',
                        'text_accent' => 'text-indigo-100',
                        'icon_bg' => 'bg-indigo-900/30',
                        'anim_class' =>
                            'hover:shadow-[0_10px_40px_rgba(168,85,247,0.6)] relative overflow-hidden group/shine hover:-translate-y-1 transition-all duration-500',
                        'decor_element' =>
                            $shineEffect .
                            '<div class="absolute top-2 right-4 text-white/70 animate-[ping_2s_infinite]"><i class="bi bi-star-fill text-[8px]"></i></div>',
                    ];
                } elseif (str_contains($rankLabel, 'maju') || str_contains($rankLabel, 'platinum')) {
                    $theme = [
                        'bg_gradient' => 'from-slate-500 via-cyan-600 to-blue-700',
                        'card_border' => 'border-cyan-400/50',
                        'text_accent' => 'text-cyan-100',
                        'icon_bg' => 'bg-cyan-800/30',
                        'anim_class' =>
                            'hover:shadow-[0_10px_40px_rgba(34,211,238,0.6)] relative overflow-hidden group/shine hover:-translate-y-1 transition-all duration-500',
                        'decor_element' => $shineEffect,
                    ];
                } elseif (str_contains($rankLabel, 'berkembang') || str_contains($rankLabel, 'gold')) {
                    $theme = [
                        'bg_gradient' => 'from-yellow-600 via-amber-500 to-orange-600',
                        'card_border' => 'border-yellow-400/50',
                        'text_accent' => 'text-yellow-100',
                        'icon_bg' => 'bg-yellow-900/30',
                        'anim_class' =>
                            'hover:shadow-[0_10px_40px_rgba(245,158,11,0.6)] relative overflow-hidden group/shine hover:-translate-y-1 transition-all duration-500',
                        'decor_element' => $shineEffect,
                    ];
                } elseif (str_contains($rankLabel, 'pemula') || str_contains($rankLabel, 'silver')) {
                    $theme = [
                        'bg_gradient' => 'from-gray-500 via-slate-500 to-zinc-600',
                        'card_border' => 'border-gray-400/50',
                        'text_accent' => 'text-gray-200',
                        'icon_bg' => 'bg-gray-800/30',
                        'anim_class' =>
                            'hover:shadow-[0_10px_40px_rgba(226,232,240,0.5)] relative overflow-hidden group/shine hover:-translate-y-1 transition-all duration-500',
                        'decor_element' => $shineEffect,
                    ];
                } elseif (str_contains($rankLabel, 'gagal')) {
                    $theme = [
                        'bg_gradient' => 'from-gray-900 via-slate-900 to-black',
                        'card_border' => 'border-gray-700',
                        'text_accent' => 'text-gray-400',
                        'icon_bg' => 'bg-gray-800',
                        'anim_class' => '',
                        'decor_element' => '',
                    ];
                } else {
                    $theme = [
                        'bg_gradient' => 'from-emerald-600 to-teal-700',
                        'card_border' => 'border-emerald-500/30',
                        'text_accent' => 'text-emerald-100',
                        'icon_bg' => 'bg-emerald-800/30',
                        'anim_class' => '',
                        'decor_element' => '',
                    ];
                }
            }
        @endphp

        {{-- BANNER STATUS --}}
        <div
            class="bg-gradient-to-r {{ $theme['bg_gradient'] }} rounded-3xl p-8 md:p-12 text-white shadow-xl mb-10 relative overflow-hidden group transition-all duration-500">
            <div
                class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full group-hover:scale-110 transition-transform duration-700 ease-out">
            </div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>

            <div class="relative z-10 text-center">
                @if ($isVerified)
                    <h2 class="text-3xl font-extrabold mb-3">Hasil Asesmen</h2>
                    <p class="{{ $theme['text_accent'] }} text-lg mb-6 max-w-2xl mx-auto">
                        Selamat! Berikut adalah predikat keamanan digital sekolah Anda berdasarkan verifikasi validator.
                    </p>

                    <div class="mb-6 flex justify-center">
                        <div
                            class="inline-flex flex-col items-center justify-center p-6 bg-white/10 backdrop-blur-md rounded-3xl border {{ $theme['card_border'] }} shadow-lg text-white relative min-w-[300px] cursor-default {{ $theme['anim_class'] }}">
                            {!! $theme['decor_element'] !!}

                            <div
                                class="absolute top-0 right-0 bg-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl shadow-sm uppercase tracking-wider backdrop-blur-sm z-10">
                                <i class="bi bi-patch-check-fill mr-1"></i> Verified
                            </div>

                            <div
                                class="w-20 h-20 flex items-center justify-center rounded-full mb-3 {{ $theme['icon_bg'] }} border border-white/20 shadow-inner z-10 relative">
                                @if (str_contains($rankLabel, 'gagal'))
                                    <i class="bi bi-x-circle text-4xl text-gray-500"></i>
                                @else
                                    <i
                                        class="bi {{ $currentSurvey->rank_icon ?? 'bi-shield' }} text-4xl drop-shadow-md"></i>
                                @endif
                            </div>

                            <h3 class="text-3xl font-black mb-1 uppercase tracking-tight drop-shadow-md z-10 relative">
                                {{ $currentSurvey->rank_label ?? '-' }}</h3>

                            <p class="text-white/80 text-xs font-bold uppercase tracking-widest mb-3 z-10 relative">Skor
                                Akhir</p>
                            <span
                                class="text-5xl font-black text-white drop-shadow-lg z-10 relative">{{ number_format($currentSurvey->total_score, 1) }}</span>

                            <div class="mt-6 w-full z-10 relative">
                                <a href="{{ route('school.survey.result', $currentSurvey->id) }}"
                                    class="block w-full py-2.5 rounded-xl bg-white text-slate-800 font-bold text-sm hover:bg-slate-100 transition-colors shadow-sm">
                                    Lihat Rincian
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif ($surveyStatus === 'submitted')
                    <div class="flex flex-col items-center py-4">
                        <div
                            class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-6 backdrop-blur-sm shadow-sm">
                            <i class="bi bi-hourglass-split text-4xl text-white"></i>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold mb-3">Menunggu Verifikasi</h2>
                        <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto leading-relaxed">
                            Asesmen Anda sedang ditinjau oleh tim validator. Skor resmi dan sertifikat akan diterbitkan setelah proses verifikasi selesai.
                        </p>
                        <div
                            class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-6 py-3 rounded-2xl text-sm font-medium shadow-sm cursor-default">
                            <span>Status:</span>
                            <span class="font-bold text-yellow-300 tracking-wide">Dalam Antrean Validator</span>
                        </div>
                    </div>
                @elseif($surveyStatus === 'rejected')
                    <div class="flex flex-col items-center py-4">
                        <div
                            class="w-20 h-20 bg-amber-500/25 border-2 border-amber-300/40 rounded-full flex items-center justify-center mb-5 backdrop-blur-md shadow-lg shadow-amber-900/20 animate-pulse">
                            <i class="bi bi-exclamation-triangle-fill text-4xl text-amber-300"></i>
                        </div>
                        <div class="inline-flex items-center gap-2 bg-amber-400/20 text-amber-200 border border-amber-300/30 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                            <i class="bi bi-arrow-counterclockwise"></i> Perlu Revisi / Perbaikan
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black mb-3 text-white">Asesmen Memerlukan Revisi</h2>
                        <p class="text-blue-100 text-base md:text-lg mb-8 max-w-xl mx-auto leading-relaxed">
                            Tim validator telah meninjau isian asesmen mandiri sekolah Anda dan memberikan catatan koreksi. Silakan periksa dan perbaiki jawaban pada indikator yang ditandai.
                        </p>
                        <a href="{{ route('school.survey.start') }}"
                            class="inline-flex items-center bg-amber-400 hover:bg-amber-300 text-slate-900 px-8 py-3.5 rounded-full font-black text-base shadow-xl shadow-amber-500/25 hover:-translate-y-1 transition-all">
                            <i class="bi bi-pencil-square mr-2 text-lg"></i> Perbaiki Asesmen Sekarang
                        </a>
                    </div>
                @elseif($surveyStatus === 'draft')
                    <h2 class="text-3xl font-extrabold mb-4">Lanjutkan Asesmen?</h2>
                    <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto">
                        Anda memiliki pengisian yang belum selesai. Segera lengkapi data untuk mengetahui status keamanan
                        sekolah.
                    </p>
                    <a href="{{ route('school.survey.start') }}"
                        class="inline-flex items-center bg-yellow-400 text-yellow-900 hover:bg-yellow-300 px-8 py-3.5 rounded-full font-bold text-lg shadow-lg hover:-translate-y-1 transition-all">
                        <i class="bi bi-play-circle-fill mr-2"></i> Lanjutkan Pengisian
                    </a>
                @else
                    <h2 class="text-3xl font-extrabold mb-4">Siap Mengukur Keamanan?</h2>
                    <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto">
                        Isi instrumen survei terbaru untuk mengetahui indeks keamanan sekolah Anda dan dapatkan rekomendasi
                        perbaikan.
                    </p>
                    <a href="{{ route('school.survey.start') }}"
                        class="inline-flex items-center bg-white text-blue-700 hover:bg-blue-50 px-8 py-3.5 rounded-full font-bold text-lg shadow-lg hover:-translate-y-1 transition-all">
                        <i class="bi bi-rocket-takeoff-fill mr-3"></i> Mulai Survei Baru
                    </a>
                @endif

            </div>
        </div>

        @if ($surveyStatus === 'verified' && $school->student_respondents_count < 10)
            <div class="mb-8 p-5 rounded-3xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/90 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-sm shadow-amber-500/20">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-amber-950 flex items-center gap-2">
                            Asesmen Mandiri Terverifikasi (Skor: {{ number_format($school->self_assessment_score, 1) }}) — Menunggu Kuorum Survei Siswa
                        </h4>
                        <p class="text-xs text-amber-900/90 mt-1 leading-relaxed">
                            Nilai Asesmen Mandiri sekolah Anda (bobot 70%) telah disahkan oleh tim validator. Sesuai metodologi evaluasi HANDAL, <strong>Skor Komposit Akhir dan Peringkat Publik resmi baru akan diterbitkan setelah survei siswa mencapai minimal 10 responden</strong> (Saat ini: <strong>{{ $school->student_respondents_count }}/10 Responden</strong>). Silakan bagikan tautan atau cetak QR Code di bawah.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- KARTU GENERATOR SURVEI SISWA (BOBOT 30%) --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 mb-10 overflow-hidden"
             x-data="{ copied: false, showQr: false }">
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="px-3 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider">
                                Instrumen Survei Siswa (30%)
                            </span>
                            @if ($school->is_student_survey_open)
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Dibuka
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 text-xs font-semibold">
                                    <i class="bi bi-lock-fill mr-1"></i> Ditutup
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Tautan & QR Code Survei Siswa</h3>
                        <p class="text-slate-500 text-sm mt-0.5">
                            Bagikan tautan atau tampilkan QR Code kuesioner skala Likert untuk diisi oleh siswa sekolah Anda.
                        </p>
                    </div>
                </div>

                {{-- TOMBOL KONTROL STATUS --}}
                <div class="flex items-center gap-3">
                    <form action="{{ route('school.student.survey.toggle') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 shadow-sm
                            {{ $school->is_student_survey_open
                                ? 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100'
                                : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                            <i class="bi {{ $school->is_student_survey_open ? 'bi-pause-circle' : 'bi-play-circle-fill' }}"></i>
                            {{ $school->is_student_survey_open ? 'Tutup Akses Survei' : 'Buka Akses Survei' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- STATISTIK PARTISIPASI SISWA --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-6 border-b border-slate-100">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Partisipasi Siswa</span>
                    <span class="text-2xl font-black text-slate-800">{{ $school->student_respondents_count }}</span>
                    <span class="text-xs text-slate-500 block mt-0.5">Minimal 10 Siswa</span>
                </div>

                <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                    <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider block mb-1">Skor Survei Siswa (30%)</span>
                    <span class="text-2xl font-black text-indigo-600">{{ number_format($school->student_survey_score, 1) }}</span>
                    <span class="text-xs text-indigo-500 block mt-0.5">Skala 0 - 100</span>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100">
                    <span class="text-xs font-bold text-blue-400 uppercase tracking-wider block mb-1">Asesmen Mandiri (70%)</span>
                    @if ($surveyStatus === 'verified')
                        <span class="text-2xl font-black text-blue-600">{{ number_format($school->self_assessment_score, 1) }}</span>
                        <span class="text-xs text-blue-600 font-bold block mt-0.5"><i class="bi bi-check-circle-fill"></i> Terverifikasi Validator</span>
                    @elseif ($surveyStatus === 'submitted')
                        <span class="text-lg font-black text-amber-600">Menunggu</span>
                        <span class="text-xs text-amber-600 block mt-0.5">Verifikasi Validator</span>
                    @elseif ($surveyStatus === 'rejected')
                        <span class="text-lg font-black text-rose-600">Perlu Revisi</span>
                        <span class="text-xs text-rose-500 block mt-0.5">Lihat Catatan Validator</span>
                    @else
                        <span class="text-2xl font-black text-slate-400">-</span>
                        <span class="text-xs text-slate-400 block mt-0.5">Bobot 70%</span>
                    @endif
                </div>

                <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-500 uppercase tracking-wider block mb-1">Skor Komposit Akhir</span>
                    @php
                        $isAssessmentVerified = ($surveyStatus === 'verified');
                        $isSurveyFulfilled = ($school->student_respondents_count >= 10);
                    @endphp

                    @if ($isAssessmentVerified && $isSurveyFulfilled)
                        <span class="text-2xl font-black text-emerald-700">{{ number_format($school->current_score, 1) }}</span>
                        <span class="text-xs text-emerald-600 font-bold block mt-0.5"><i class="bi bi-shield-check"></i> Indeks HANDAL Resmi</span>
                    @elseif ($isAssessmentVerified && !$isSurveyFulfilled)
                        <span class="text-lg font-black text-amber-600">Menunggu</span>
                        <span class="text-xs text-amber-600 block mt-0.5">Menunggu Hasil Survei Siswa</span>
                    @elseif (!$isAssessmentVerified && $isSurveyFulfilled)
                        <span class="text-lg font-black text-amber-600">Menunggu</span>
                        <span class="text-xs text-amber-600 block mt-0.5">Menunggu Hasil Asesmen Mandiri</span>
                    @else
                        <span class="text-2xl font-black text-slate-400">-</span>
                        <span class="text-xs text-slate-400 block mt-0.5">Menunggu Asesmen & Survei</span>
                    @endif
                </div>
            </div>

            {{-- BOX LINK & QR CODE GENERATOR --}}
            <div class="pt-6 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                        Tautan Publik Kuesioner Siswa:
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly
                            id="surveyUrlInput"
                            value="{{ route('student.survey.show', $school->survey_code) }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono text-slate-700 selection:bg-indigo-500 selection:text-white">
                        
                        <button type="button"
                            @click="navigator.clipboard.writeText('{{ route('student.survey.show', $school->survey_code) }}'); copied = true; setTimeout(() => copied = false, 2500)"
                            class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-sm flex-shrink-0">
                            <i class="bi" :class="copied ? 'bi-check2' : 'bi-clipboard'"></i>
                            <span x-text="copied ? 'Tersalin!' : 'Salin Tautan'"></span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3 pt-1 flex-wrap">
                        <button type="button" @click="showQr = !showQr"
                            class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 transition-colors">
                            <i class="bi bi-qr-code"></i>
                            <span x-text="showQr ? 'Sembunyikan QR Code' : 'Tampilkan / Cetak QR Code'"></span>
                        </button>

                        <span class="text-slate-300">&bull;</span>
                        <a href="{{ route('student.survey.show', $school->survey_code) }}" target="_blank"
                            class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                            <i class="bi bi-box-arrow-up-right"></i> Buka Kuesioner di Tab Baru
                        </a>
                    </div>
                </div>

                {{-- QR CODE DISPLAY --}}
                <div class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-100"
                     x-show="showQr" x-transition>
                    <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-200 mb-2.5">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('student.survey.show', $school->survey_code)) }}"
                             alt="QR Code Survei Siswa" class="w-36 h-36 object-contain">
                    </div>
                    
                    <div class="flex items-center gap-2 mb-2">
                        <button type="button"
                            onclick="downloadQrCode('https://api.qrserver.com/v1/create-qr-code/?size=800x800&data={{ urlencode(route('student.survey.show', $school->survey_code)) }}', 'qrcode-survei-{{ $school->npsn ?? $school->survey_code }}.png')"
                            class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all hover:-translate-y-0.5">
                            <i class="bi bi-download"></i> Unduh Gambar QR (.PNG)
                        </button>

                        <button type="button"
                            onclick="printQrCode('https://api.qrserver.com/v1/create-qr-code/?size=600x600&data={{ urlencode(route('student.survey.show', $school->survey_code)) }}', '{{ addslashes($school->name) }}', '{{ route('student.survey.show', $school->survey_code) }}')"
                            class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200 flex items-center gap-1.5 shadow-xs transition-all">
                            <i class="bi bi-printer"></i> Cetak
                        </button>
                    </div>

                    <span class="text-[11px] font-bold text-slate-600 text-center">Scan untuk Mengisi Kuesioner Siswa</span>
                    <span class="text-[10px] text-slate-400 text-center">Cocok diunduh untuk dibagikan via grup WhatsApp atau dicetak di mading</span>
                </div>
            </div>

        </div>

        {{-- TABEL RIWAYAT EVALUASI --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            <div
                class="px-8 py-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="bi bi-clock-history text-blue-600"></i> Riwayat Evaluasi
                    </h3>
                    <p class="text-slate-500 text-sm mt-1">Rekam jejak asesmen keamanan digital sekolah.</p>
                </div>
                <div class="bg-slate-50 px-4 py-2 rounded-xl text-xs font-bold text-slate-500 border border-slate-200">
                    Total: <span class="text-slate-800">{{ $historySurveys->count() }}</span> Data
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-8 py-5">Periode & ID</th>
                            <th class="px-6 py-5 text-center">Skor Akhir</th>
                            <th class="px-6 py-5 text-center">Predikat</th>
                            <th class="px-6 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($historySurveys as $history)
                            <tr class="group hover:bg-slate-50/60 transition-colors duration-200">
                                <td class="px-8 py-5 align-middle">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-white border border-slate-200 text-slate-700 flex flex-col items-center justify-center shadow-sm group-hover:border-blue-200 group-hover:text-blue-600 transition-colors">
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-wide text-slate-400">THN</span>
                                            <span class="font-black text-sm">{{ $history->year }}</span>
                                        </div>
                                        <div>
                                            <p
                                                class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">
                                                Evaluasi Tahunan</p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span
                                                    class="text-[11px] font-mono text-slate-400 bg-slate-100 px-1.5 rounded border border-slate-200">#{{ $history->id }}</span>
                                                <span
                                                    class="text-[11px] text-slate-400">{{ $history->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-middle text-center">
                                    @if (in_array($history->status, ['verified', 'approved']))
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="text-xl font-black text-slate-800 tracking-tight">{{ number_format($history->total_score, 1) }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">Poin</span>
                                        </div>
                                    @else
                                        <span class="text-slate-300 font-bold text-xl select-none">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-middle text-center">
                                    @if (in_array($history->status, ['verified', 'approved']))
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-[11px] font-bold border shadow-sm {{ $history->rank_color ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                            @if ($history->rank_icon)
                                                <i class="bi {{ $history->rank_icon }} mr-1.5"></i>
                                            @endif
                                            {{ $history->rank_label ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">Menunggu hasil</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-middle text-center">
                                    @php
                                        $statusConfig = [
                                            'draft' => [
                                                'bg' => 'bg-slate-100',
                                                'text' => 'text-slate-600',
                                                'border' => 'border-slate-200',
                                                'icon' => 'bi-pencil',
                                                'label' => 'Draft',
                                            ],
                                            'submitted' => [
                                                'bg' => 'bg-blue-50',
                                                'text' => 'text-blue-600',
                                                'border' => 'border-blue-200',
                                                'icon' => 'bi-send-fill',
                                                'label' => 'Diajukan',
                                            ],
                                            'verified' => [
                                                'bg' => 'bg-emerald-50',
                                                'text' => 'text-emerald-600',
                                                'border' => 'border-emerald-200',
                                                'icon' => 'bi-patch-check-fill',
                                                'label' => 'Terverifikasi',
                                            ],
                                            'approved' => [
                                                'bg' => 'bg-emerald-50',
                                                'text' => 'text-emerald-600',
                                                'border' => 'border-emerald-200',
                                                'icon' => 'bi-check-circle-fill',
                                                'label' => 'Selesai',
                                            ],
                                            'rejected' => [
                                                'bg' => 'bg-red-50',
                                                'text' => 'text-red-600',
                                                'border' => 'border-red-200',
                                                'icon' => 'bi-x-circle-fill',
                                                'label' => 'Ditolak',
                                            ],
                                        ];
                                        $config = $statusConfig[$history->status] ?? [
                                            'bg' => 'bg-gray-50',
                                            'text' => 'text-gray-500',
                                            'border' => 'border-gray-200',
                                            'icon' => 'bi-question-circle',
                                            'label' => ucfirst($history->status),
                                        ];
                                    @endphp
                                    <div
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                                        <i class="bi {{ $config['icon'] }}"></i> {{ $config['label'] }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 align-middle text-right">
                                    @if (in_array($history->status, ['submitted', 'verified', 'approved']))
                                        <a href="{{ route('school.survey.result', $history->id) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-500 text-xs font-bold hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm group-hover:shadow-md gap-2">
                                            <i class="bi bi-eye-fill"></i> Detail
                                        </a>
                                    @elseif($history->status == 'draft' && $history->year == date('Y'))
                                        <a href="{{ route('school.survey.start') }}"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-amber-400 text-amber-900 text-xs font-bold hover:bg-amber-300 hover:shadow-md transition-all shadow-sm gap-2">
                                            <i class="bi bi-play-fill text-sm"></i> Lanjutkan
                                        </a>
                                    @else
                                        <span class="text-slate-300 text-xl select-none">•</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="bi bi-clipboard-x text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-900 font-bold text-sm">Belum ada riwayat</h3>
                                        <p class="text-slate-500 text-xs mt-1">Sekolah ini belum pernah melakukan evaluasi
                                            keamanan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($historySurveys->count() > 0)
                <div
                    class="bg-slate-50/50 px-8 py-4 border-t border-slate-200 flex items-center justify-center md:justify-end">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                        Handal School Assessment System
                    </p>
                </div>
            @endif
        </div>

    </div>

    <script>
        function downloadQrCode(url, filename) {
            fetch(url)
                .then(res => res.blob())
                .then(blob => {
                    const blobUrl = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(blobUrl);
                    document.body.removeChild(a);
                })
                .catch(() => {
                    window.open(url, '_blank');
                });
        }

        function printQrCode(qrUrl, schoolName, surveyUrl) {
            const printWindow = window.open('', '', 'width=700,height=800');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Cetak QR Code Survei - ${schoolName}</title>
                        <style>
                            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; padding: 40px; color: #1e293b; background: #fff; }
                            .card { border: 2px solid #e2e8f0; border-radius: 24px; padding: 36px; max-width: 460px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
                            h2 { margin: 0 0 6px 0; color: #1e3a8a; font-size: 22px; font-weight: 800; }
                            .badge { display: inline-block; background: #eff6ff; color: #2563eb; padding: 5px 14px; border-radius: 999px; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 18px; border: 1px solid #bfdbfe; }
                            img { width: 250px; height: 250px; margin: 15px auto; display: block; }
                            .instruction { font-size: 13px; color: #475569; margin-top: 18px; line-height: 1.6; font-weight: 500; }
                            .url { font-family: monospace; font-size: 12px; background: #f8fafc; padding: 10px 14px; border-radius: 10px; border: 1px solid #e2e8f0; margin-top: 15px; word-break: break-all; color: #334155; font-weight: 600; }
                        </style>
                    </head>
                    <body>
                        <div class="card">
                            <div class="badge">PLATFORM HANDAL BBLSDM KOMDIGI</div>
                            <h2>${schoolName}</h2>
                            <p style="font-size: 13px; color: #64748b; margin: 0 0 10px 0;">Kuesioner Partisipasi Siswa</p>
                            <img src="${qrUrl}" alt="QR Code Survei Siswa" />
                            <p class="instruction">Buka kamera smartphone Anda dan pindai (scan) QR Code di atas untuk mengisi survei kesiapan & keamanan digital sekolah.</p>
                            <div class="url">${surveyUrl}</div>
                        </div>
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(() => window.close(), 500);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
@endsection
