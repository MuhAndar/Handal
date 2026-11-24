<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Literasi & Keamanan Digital Siswa - {{ $school->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="min-h-full flex flex-col justify-between text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    {{-- HEADER BRAND --}}
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40">
        <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ asset('img/Handal 8.png') }}" alt="Logo Handal" class="h-8 w-auto">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest pl-2 border-l border-slate-200">Survei Siswa</span>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-200">
                <i class="bi bi-shield-check mr-1"></i> Anonim & Aman
            </span>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="max-w-2xl mx-auto px-4 py-6 sm:py-10 flex-grow w-full">

        {{-- KARTU INFO SEKOLAH --}}
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-blue-500/10 mb-8 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-10 text-[10rem] pointer-events-none">
                <i class="bi bi-chat-heart-fill"></i>
            </div>
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-bold text-blue-100 mb-3">
                    <i class="bi bi-mortarboard-fill"></i> {{ $school->jenjang }} &bull; NPSN: {{ $school->npsn }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">{{ $school->name }}</h1>
                <p class="text-blue-100 text-sm leading-relaxed font-light">
                    Bantu sekolahmu mengukur kesiapan dan keamanan ruang digital. Partisipasi dan jawaban jujurmu sangat berharga dan tidak mempengaruhi nilai pelajaranmu.
                </p>
            </div>
        </div>

        {{-- NOTIFIKASI SUDAH MENGISI / RESTRIKSI DEVICE --}}
        <div id="alreadySubmittedNotice" class="{{ empty($hasSubmitted) ? 'hidden' : '' }} mb-6 p-5 rounded-3xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-sm shadow-amber-500/20">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <h4 class="font-black text-sm text-amber-950">Anda Sudah Pernah Mengisi Survei Ini</h4>
                <p class="text-xs text-amber-900/90 mt-1 leading-relaxed">
                    Perangkat Anda telah tercatat mengirimkan respon survei untuk <strong>{{ $school->name }}</strong>. Untuk menjaga objektivitas dan keabsahan data, setiap perangkat siswa hanya dapat mengirimkan 1 kali tanggapan. Terima kasih banyak atas partisipasi Anda!
                </p>
            </div>
        </div>

        @if (session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center gap-2">
                <i class="bi bi-exclamation-circle-fill text-rose-500 text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- NOTIFIKASI ERROR / VALIDASI --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                <div class="font-bold flex items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-octagon-fill text-rose-500"></i> Mohon lengkapi survei:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- NOTIFIKASI DRAFT TERPULIHKAN OTOMATIS --}}
        <div id="draftRestoredNotice" class="hidden mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold">
                <i class="bi bi-clock-history text-blue-600 text-lg flex-shrink-0"></i>
                <span>Jawaban sebelumnya berhasil dipulihkan otomatis dari perangkat Anda.</span>
            </div>
            <button type="button" onclick="document.getElementById('draftRestoredNotice').remove()" class="text-blue-500 hover:text-blue-700 text-xs font-bold px-2 py-1">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- FORMULIR SURVEI --}}
        <form action="{{ route('student.survey.store', $school->survey_code) }}" method="POST" id="studentSurveyForm" class="space-y-6">
            @csrf
            <input type="hidden" name="device_token" id="deviceTokenInput" value="">

            {{-- IDENTITAS RINGKAS --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="bi bi-person-badge-fill text-blue-600"></i> Informasi Siswa
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                            Kelas / Tingkat <span class="text-rose-500">*</span>
                        </label>
                        <select name="class_grade" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">-- Pilih Kelas Siswa --</option>
                            <optgroup label="Tingkat SD / Sederajat (Kelas 1 - 6)">
                                <option value="Kelas 1 SD" {{ old('class_grade') == 'Kelas 1 SD' ? 'selected' : '' }}>Kelas 1 SD</option>
                                <option value="Kelas 2 SD" {{ old('class_grade') == 'Kelas 2 SD' ? 'selected' : '' }}>Kelas 2 SD</option>
                                <option value="Kelas 3 SD" {{ old('class_grade') == 'Kelas 3 SD' ? 'selected' : '' }}>Kelas 3 SD</option>
                                <option value="Kelas 4 SD" {{ old('class_grade') == 'Kelas 4 SD' ? 'selected' : '' }}>Kelas 4 SD</option>
                                <option value="Kelas 5 SD" {{ old('class_grade') == 'Kelas 5 SD' ? 'selected' : '' }}>Kelas 5 SD</option>
                                <option value="Kelas 6 SD" {{ old('class_grade') == 'Kelas 6 SD' ? 'selected' : '' }}>Kelas 6 SD</option>
                            </optgroup>
                            <optgroup label="Tingkat SMP / MTs / Sederajat (Kelas 7 - 9)">
                                <option value="Kelas 7 (1 SMP)" {{ old('class_grade') == 'Kelas 7 (1 SMP)' ? 'selected' : '' }}>Kelas 7 (Kelas 1 SMP / MTs)</option>
                                <option value="Kelas 8 (2 SMP)" {{ old('class_grade') == 'Kelas 8 (2 SMP)' ? 'selected' : '' }}>Kelas 8 (Kelas 2 SMP / MTs)</option>
                                <option value="Kelas 9 (3 SMP)" {{ old('class_grade') == 'Kelas 9 (3 SMP)' ? 'selected' : '' }}>Kelas 9 (Kelas 3 SMP / MTs)</option>
                            </optgroup>
                            <optgroup label="Tingkat SMA / SMK / MA / Sederajat (Kelas 10 - 12)">
                                <option value="Kelas 10 (X SMA/SMK)" {{ old('class_grade') == 'Kelas 10 (X SMA/SMK)' ? 'selected' : '' }}>Kelas 10 (X SMA / SMK / MA)</option>
                                <option value="Kelas 11 (XI SMA/SMK)" {{ old('class_grade') == 'Kelas 11 (XI SMA/SMK)' ? 'selected' : '' }}>Kelas 11 (XI SMA / SMK / MA)</option>
                                <option value="Kelas 12 (XII SMA/SMK)" {{ old('class_grade') == 'Kelas 12 (XII SMA/SMK)' ? 'selected' : '' }}>Kelas 12 (XII SMA / SMK / MA)</option>
                            </optgroup>
                            <option value="Lainnya" {{ old('class_grade') == 'Lainnya' ? 'selected' : '' }}>Tingkat Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                            Nama / Inisial <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="student_name" value="{{ old('student_name') }}"
                            placeholder="Contoh: Andi / Siswa A"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            {{-- DAFTAR PERTANYAAN (SKALA LIKERT) --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <i class="bi bi-ui-checks text-blue-600"></i> Kuesioner Literasi Digital
                    </h3>
                    <span class="text-xs text-slate-400">Total {{ $questions->count() }} Pertanyaan</span>
                </div>

                @foreach ($questions as $index => $q)
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:border-blue-200 transition-colors"
                         x-data="{ selected: '{{ old('answers.' . $q->id) }}' }">
                        
                        <div class="flex items-start gap-3 mb-4">
                            <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-700 font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                                    {{ $q->indicator }}
                                </span>
                                <p class="text-slate-800 font-semibold text-sm sm:text-base mt-2 leading-relaxed">
                                    {{ $q->question_text }}
                                </p>
                            </div>
                        </div>

                        {{-- 4 OPSI SKALA LIKERT --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2">
                            {{-- 1: Sangat Tidak Setuju --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="1" required class="sr-only"
                                       x-model="selected">
                                <div class="p-3 text-center rounded-xl border text-xs font-semibold transition-all"
                                     :class="selected == 1 ? 'bg-rose-50 border-rose-400 text-rose-700 shadow-sm ring-2 ring-rose-200' : 'bg-slate-50/70 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                    <span class="block text-sm font-black mb-0.5">STS</span>
                                    <span class="text-[11px] font-normal leading-tight">Sangat Tidak Setuju</span>
                                </div>
                            </label>

                            {{-- 2: Tidak Setuju --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="2" required class="sr-only"
                                       x-model="selected">
                                <div class="p-3 text-center rounded-xl border text-xs font-semibold transition-all"
                                     :class="selected == 2 ? 'bg-amber-50 border-amber-400 text-amber-700 shadow-sm ring-2 ring-amber-200' : 'bg-slate-50/70 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                    <span class="block text-sm font-black mb-0.5">TS</span>
                                    <span class="text-[11px] font-normal leading-tight">Tidak Setuju</span>
                                </div>
                            </label>

                            {{-- 3: Setuju --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="3" required class="sr-only"
                                       x-model="selected">
                                <div class="p-3 text-center rounded-xl border text-xs font-semibold transition-all"
                                     :class="selected == 3 ? 'bg-blue-50 border-blue-400 text-blue-700 shadow-sm ring-2 ring-blue-200' : 'bg-slate-50/70 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                    <span class="block text-sm font-black mb-0.5">S</span>
                                    <span class="text-[11px] font-normal leading-tight">Setuju</span>
                                </div>
                            </label>

                            {{-- 4: Sangat Setuju --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="4" required class="sr-only"
                                       x-model="selected">
                                <div class="p-3 text-center rounded-xl border text-xs font-semibold transition-all"
                                     :class="selected == 4 ? 'bg-emerald-50 border-emerald-400 text-emerald-700 shadow-sm ring-2 ring-emerald-200' : 'bg-slate-50/70 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                    <span class="block text-sm font-black mb-0.5">SS</span>
                                    <span class="text-[11px] font-normal leading-tight">Sangat Setuju</span>
                                </div>
                            </label>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="pt-4 pb-8">
                <button type="submit" id="submitSurveyBtn"
                    class="w-full py-4 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold text-base shadow-lg shadow-blue-500/25 transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-send-fill"></i> Kirim Jawaban Survei
                </button>
                <p class="text-center text-xs text-slate-400 mt-3">
                    Jawaban Anda dikirim secara langsung ke sistem dan dihitung dalam indeks keamanan sekolah.
                </p>
            </div>
        </form>
    </main>

    <script>
        (function() {
            const schoolId = '{{ $school->id }}';
            const storageTokenKey = 'handal_device_token';
            let token = localStorage.getItem(storageTokenKey);
            if (!token) {
                token = 'dt_' + Math.random().toString(36).substring(2, 12) + Date.now().toString(36);
                try { localStorage.setItem(storageTokenKey, token); } catch(e){}
            }
            const tokenInput = document.getElementById('deviceTokenInput');
            if (tokenInput) tokenInput.value = token;

            const submittedKey = 'survey_submitted_school_' + schoolId;
            const isSubmittedLocal = localStorage.getItem(submittedKey) === '1';
            const isSubmittedServer = {{ !empty($hasSubmitted) ? 'true' : 'false' }};

            if (isSubmittedLocal || isSubmittedServer) {
                const notice = document.getElementById('alreadySubmittedNotice');
                if (notice) notice.classList.remove('hidden');

                const submitBtn = document.getElementById('submitSurveyBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.className = "w-full py-4 px-6 rounded-2xl bg-slate-200 text-slate-500 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2 shadow-none border border-slate-300";
                    submitBtn.innerHTML = '<i class="bi bi-check-circle-fill text-emerald-600"></i> Survei Telah Terkirim dari Perangkat Ini';
                }

                // Kunci input agar tidak bisa diedit
                const form = document.getElementById('studentSurveyForm');
                if (form) {
                    form.querySelectorAll('input, select').forEach(el => {
                        if (el.type !== 'hidden') el.disabled = true;
                    });
                }
                return;
            }

            // ==========================================
            // AUTO-SAVE DRAFT & AUTO-RESTORE (LOCALSTORAGE)
            // ==========================================
            const draftKey = 'handal_survey_draft_school_' + schoolId;
            const form = document.getElementById('studentSurveyForm');

            // 1. Pulihkan draft jawaban saat halaman dimuat
            function restoreDraft() {
                try {
                    const raw = localStorage.getItem(draftKey);
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (!saved || !form) return;

                    let hasRestored = false;

                    if (saved.class_grade && form.elements['class_grade'] && !form.elements['class_grade'].value) {
                        form.elements['class_grade'].value = saved.class_grade;
                        hasRestored = true;
                    }
                    if (saved.student_name && form.elements['student_name'] && !form.elements['student_name'].value) {
                        form.elements['student_name'].value = saved.student_name;
                        hasRestored = true;
                    }
                    if (saved.answers && typeof saved.answers === 'object') {
                        Object.keys(saved.answers).forEach(qId => {
                            const val = saved.answers[qId];
                            const radio = form.querySelector(`input[name="answers[${qId}]"][value="${val}"]`);
                            if (radio) {
                                radio.checked = true;
                                radio.dispatchEvent(new Event('change', { bubbles: true }));
                                hasRestored = true;
                            }
                        });
                    }

                    if (hasRestored) {
                        const notice = document.getElementById('draftRestoredNotice');
                        if (notice) notice.classList.remove('hidden');
                    }
                } catch (e) {
                    console.error('Draft restore failed', e);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => setTimeout(restoreDraft, 80));
            } else {
                setTimeout(restoreDraft, 80);
            }

            // 2. Simpan setiap perubahan pilihan secara otomatis real-time
            if (form) {
                const saveDraft = () => {
                    const draft = {
                        class_grade: form.elements['class_grade'] ? form.elements['class_grade'].value : '',
                        student_name: form.elements['student_name'] ? form.elements['student_name'].value : '',
                        answers: {}
                    };
                    form.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                        const match = radio.name.match(/answers\[(\d+)\]/);
                        if (match && match[1]) {
                            draft.answers[match[1]] = radio.value;
                        }
                    });
                    try {
                        localStorage.setItem(draftKey, JSON.stringify(draft));
                    } catch (e) {}
                };

                form.addEventListener('change', saveDraft);
                form.addEventListener('input', saveDraft);

                // Bersihkan draft saat submit berhasil
                form.addEventListener('submit', () => {
                    try { localStorage.removeItem(draftKey); } catch (e) {}
                });
            }
            }
        })();
    </script>

    {{-- FOOTER SEDERHANA --}}
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-100 bg-white">
        &copy; {{ date('Y') }} HANDAL &bull; BBLSDM Komdigi Makassar
    </footer>

</body>
</html>
