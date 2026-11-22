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

        {{-- FORMULIR SURVEI --}}
        <form action="{{ route('student.survey.store', $school->survey_code) }}" method="POST" class="space-y-6">
            @csrf

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
                            <option value="">-- Pilih Kelas --</option>
                            <option value="Kelas X" {{ old('class_grade') == 'Kelas X' ? 'selected' : '' }}>Kelas X (10)</option>
                            <option value="Kelas XI" {{ old('class_grade') == 'Kelas XI' ? 'selected' : '' }}>Kelas XI (11)</option>
                            <option value="Kelas XII" {{ old('class_grade') == 'Kelas XII' ? 'selected' : '' }}>Kelas XII (12)</option>
                            <option value="Lainnya" {{ old('class_grade') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                <button type="submit"
                    class="w-full py-4 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold text-base shadow-lg shadow-blue-500/25 transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-send-fill"></i> Kirim Jawaban Survei
                </button>
                <p class="text-center text-xs text-slate-400 mt-3">
                    Jawaban Anda dikirim secara langsung ke sistem dan dihitung dalam indeks keamanan sekolah.
                </p>
            </div>
        </form>
    </main>

    {{-- FOOTER SEDERHANA --}}
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-100 bg-white">
        &copy; {{ date('Y') }} HANDAL &bull; BBLSDM Komdigi Makassar
    </footer>

</body>
</html>
