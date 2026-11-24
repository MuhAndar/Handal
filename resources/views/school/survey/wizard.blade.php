@extends('layouts.app')

@section('content')
    <style>
        @keyframes fadeInCategory {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-category-fade {
            animation: fadeInCategory 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-category-fade">

        {{-- TOP BREADCRUMB & PROGRESS BAR --}}
        <div class="mb-8 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                        Kategori {{ $currentStep }} dari {{ $totalSteps }}
                    </span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-800">{{ $category->name }}</h1>
                </div>
                <div class="text-xs font-bold text-slate-500">
                    Progres Keseluruhan: <span class="text-blue-600 font-extrabold">{{ round(($currentStep / $totalSteps) * 100) }}%</span> Selesai
                </div>
            </div>

            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden p-0.5 border border-slate-200/60">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-2 rounded-full transition-all duration-500 shadow-sm"
                    style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
        </div>

        {{-- 2-COLUMN LAYOUT: QUESTIONS (LEFT) & STICKY NAVIGATOR (RIGHT) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- KOLOM UTAMA: DAFTAR PERTANYAAN (8 COLUMNS) --}}
            <div class="lg:col-span-8 space-y-8">
                <form action="{{ route('school.survey.process', $currentStep) }}" method="POST" id="surveyStepForm">
                    @csrf
                    <input type="hidden" name="jump_to_step" id="jumpToStepField" value="">
                    <input type="hidden" name="action" id="actionField" value="">

                    <div class="space-y-8">
                        @forelse($questions as $index => $question)
                            @php
                                $myAnswer = $existingAnswers[$question->id] ?? null;
                                $savedValue = $myAnswer ? $myAnswer->answer_value : null;
                                $note = $myAnswer ? $myAnswer->validator_note : null;
                                $isRevised = $myAnswer ? (bool)$myAnswer->is_revised : false;
                                $hasNote = !empty($note);
                            @endphp

                            <div id="question-{{ $question->id }}"
                                class="question-container rounded-2xl transition-all duration-300 scroll-mt-28 p-6 sm:p-8
                                {{ $hasNote 
                                    ? ($isRevised 
                                        ? 'border-2 border-emerald-400 bg-emerald-50/20 shadow-md shadow-emerald-500/5 ring-4 ring-emerald-100/70' 
                                        : 'border-2 border-rose-400 bg-rose-50/25 shadow-md shadow-rose-500/5 ring-4 ring-rose-100/70') 
                                    : 'border border-slate-200 bg-white shadow-sm hover:border-slate-300' }}">

                                {{-- HEADER PERTANYAAN KHUSUS REVISI --}}
                                @if ($hasNote)
                                    <div id="revision-header-{{ $question->id }}" class="flex items-center justify-between pb-3 mb-5 border-b {{ $isRevised ? 'border-emerald-200' : 'border-rose-200' }} flex-wrap gap-2">
                                        <div class="flex items-center gap-2">
                                            @if ($isRevised)
                                                <span id="revision-badge-{{ $question->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-600 text-white shadow-sm shadow-emerald-200">
                                                    <i class="bi bi-check-circle-fill"></i> TELAH DIREVISI (CATATAN TETAP TERSIMPAN)
                                                </span>
                                            @else
                                                <span id="revision-badge-{{ $question->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-600 text-white shadow-sm shadow-rose-300 animate-pulse">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> WAJIB DIREVISI OLEH SEKOLAH
                                                </span>
                                            @endif
                                        </div>
                                        <span id="revision-subtext-{{ $question->id }}" class="text-xs font-bold {{ $isRevised ? 'text-emerald-700' : 'text-rose-700' }} flex items-center gap-1">
                                            <i class="bi {{ $isRevised ? 'bi-check2-all' : 'bi-pencil-square' }}"></i>
                                            {{ $isRevised ? 'Jawaban telah Anda perbarui, tetap bisa diubah kembali' : 'Periksa catatan perbaikan validator di bawah' }}
                                        </span>
                                    </div>

                                    {{-- BOX CATATAN VALIDATOR (TIDAK PERNAH HILANG) --}}
                                    <div id="note-card-{{ $question->id }}" class="mb-5 p-4 rounded-xl bg-white border {{ $isRevised ? 'border-emerald-300 shadow-emerald-500/5' : 'border-rose-300' }} shadow-sm text-slate-800 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div id="note-icon-{{ $question->id }}" class="w-9 h-9 rounded-xl {{ $isRevised ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }} flex items-center justify-center flex-shrink-0 text-lg shadow-inner">
                                                <i class="bi bi-chat-quote-fill"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 id="note-title-{{ $question->id }}" class="text-xs font-black uppercase tracking-wider {{ $isRevised ? 'text-emerald-900' : 'text-rose-900' }}">
                                                        Catatan Koreksi dari Tim Validator {{ $isRevised ? '(Telah Anda Koreksi)' : '' }}:
                                                    </h4>
                                                </div>
                                                <p id="note-text-{{ $question->id }}" class="text-sm font-semibold {{ $isRevised ? 'text-emerald-950 bg-emerald-50/80 border-emerald-100' : 'text-rose-950 bg-rose-50/80 border-rose-100' }} mt-1 leading-relaxed p-3 rounded-lg border">
                                                    "{{ $note }}"
                                                </p>
                                                <p class="text-[11px] text-slate-500 mt-2 flex items-center gap-1">
                                                    <i class="bi bi-info-circle text-slate-400"></i> Catatan ini tetap tersimpan sebagai referensi historis agar Anda tidak lupa poin koreksi meskipun berpindah halaman.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-start gap-3 mb-5">
                                    <span id="num-badge-{{ $question->id }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-black flex-shrink-0
                                        {{ $hasNote 
                                            ? ($isRevised ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white') 
                                            : 'bg-blue-100 text-blue-700' }}">
                                        {{ $loop->iteration }}
                                    </span>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-800 leading-snug">
                                        {{ $question->question_text }}
                                    </h3>
                                </div>

                                {{-- OPSI JAWABAN --}}
                                <div class="space-y-3">
                                    @foreach ($question->options as $option)
                                        @php
                                            $isChosen = $savedValue == $option->id;
                                        @endphp

                                        <label
                                            class="flex items-start p-4 border rounded-xl cursor-pointer transition-all duration-200 group
                                            {{ $isChosen 
                                                ? 'bg-blue-50/90 border-blue-500 ring-2 ring-blue-500/20 shadow-sm' 
                                                : 'border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">

                                            <div class="flex items-center h-5 mt-0.5">
                                                <input type="radio" 
                                                    name="answers[{{ $question->id }}]"
                                                    value="{{ $option->id }}" 
                                                    onchange="handleOptionSelected({{ $question->id }}, {{ $loop->parent->iteration }}, {{ $hasNote ? 'true' : 'false' }})"
                                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300"
                                                    {{ $isChosen ? 'checked' : '' }} required>
                                            </div>

                                            <div class="ml-3 flex-1 flex justify-between items-start gap-3">
                                                <span class="text-sm font-medium {{ $isChosen ? 'text-blue-900 font-semibold' : 'text-slate-700' }}">
                                                    {{ $option->option_text }}
                                                </span>
                                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $isChosen ? 'bg-blue-200/60 text-blue-800' : 'bg-slate-100 text-slate-500' }} flex-shrink-0">
                                                    {{ $option->score_value }} Poin
                                                </span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                            </div>

                        @empty
                            <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
                                Tidak ada pertanyaan di kategori ini.
                            </div>
                        @endforelse
                    </div>

                    {{-- TOMBOL NAVIGASI BAWAH (HANYA SEBELUMNYA & LANJUT, TOMBOL KUMPUL CUKUP DI KANAN) --}}
                    <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-slate-200">
                        @if ($currentStep > 1)
                            <button type="button" onclick="jumpToCategory({{ $currentStep - 1 }})"
                                class="w-full sm:w-auto text-slate-600 font-bold hover:text-slate-900 px-6 py-3 rounded-xl hover:bg-slate-100 transition-colors inline-flex items-center justify-center">
                                <i class="bi bi-arrow-left mr-2"></i> Kategori Sebelumnya
                            </button>
                        @else
                            <div></div>
                        @endif

                        <button type="submit"
                            class="w-full sm:w-auto bg-blue-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-blue-500/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all inline-flex items-center justify-center">
                            {{ $currentStep == $totalSteps ? 'Simpan Kategori Terakhir' : 'Simpan & Lanjut Kategori' }}
                            <i class="bi bi-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM KANAN: FLOATING STICKY QUESTION JUMP-NAVIGATOR (4 COLUMNS) --}}
            <div class="lg:col-span-4 sticky top-24 space-y-6">

                {{-- KARTU 1: PETA NOMOR SOAL KATEGORI INI --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-5 sm:p-6">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider flex items-center gap-2">
                                <i class="bi bi-grid-3x3-gap-fill text-blue-600"></i> Peta Nomor Soal
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Klik nomor untuk melompat langsung ke soal</p>
                        </div>
                    </div>

                    {{-- GRID TOMBOL LOMPAT CEPAT --}}
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 mb-4">
                        @foreach($questions as $index => $q)
                            @php
                                $ans = $existingAnswers[$q->id] ?? null;
                                $isAnswered = !empty($ans && $ans->answer_value);
                                $hasQNote = !empty($ans && $ans->validator_note);
                                $isQRevised = $ans ? (bool)$ans->is_revised : false;
                            @endphp

                            <button type="button"
                                onclick="jumpToQuestion('question-{{ $q->id }}', this)"
                                id="nav-btn-{{ $q->id }}"
                                title="Lompat ke No. {{ $loop->iteration }}: {{ Str::limit($q->question_text, 40) }}"
                                class="h-11 rounded-xl font-black text-xs flex flex-col items-center justify-center transition-all relative group
                                {{ $hasQNote 
                                    ? ($isQRevised 
                                        ? 'bg-emerald-600 text-white shadow-xs hover:bg-emerald-700' 
                                        : 'bg-rose-600 text-white shadow-md shadow-rose-300 ring-2 ring-rose-400 animate-pulse hover:bg-rose-700') 
                                    : ($isAnswered 
                                        ? 'bg-blue-600 text-white shadow-xs hover:bg-blue-700' 
                                        : 'bg-slate-50 text-slate-700 border border-slate-200 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50/50') }}">
                                
                                <span>#{{ $loop->iteration }}</span>
                                @if ($hasQNote && !$isQRevised)
                                    <i class="bi bi-exclamation-triangle-fill text-[9px] -mt-0.5"></i>
                                @elseif ($hasQNote && $isQRevised)
                                    <i class="bi bi-check-circle-fill text-[9px] -mt-0.5 text-emerald-200"></i>
                                @elseif ($isAnswered)
                                    <i class="bi bi-check-lg text-[10px] -mt-0.5"></i>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    {{-- STATUS KETERANGAN (LEGEND) --}}
                    <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-[11px] font-semibold text-slate-600">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-rose-600 animate-pulse flex-shrink-0"></span>
                            <span class="text-rose-700">Wajib Revisi</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-600 flex-shrink-0"></span>
                            <span class="text-emerald-700">Telah Direvisi</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-blue-600 flex-shrink-0"></span>
                            <span>Sudah Diisi</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-slate-200 border border-slate-300 flex-shrink-0"></span>
                            <span class="text-slate-500">Belum Diisi</span>
                        </div>
                    </div>
                </div>

                {{-- KARTU 2: SEMUA KATEGORI ASESMEN (DINAikKAN & DAPAT DIKLIK) --}}
                @if (isset($allCategories) && $allCategories->count() > 1)
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-5 sm:p-6">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider flex items-center gap-2">
                                    <i class="bi bi-layers-fill text-indigo-600"></i> Semua Kategori Asesmen
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Klik kategori untuk berpindah langsung</p>
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            @foreach ($allCategories as $catIdx => $cat)
                                @php
                                    $stepIdx = $catIdx + 1;
                                    $isCurrent = $stepIdx === $currentStep;
                                    
                                    // Cek apakah ada soal dalam kategori ini yang perlu revisi dan belum diperbaiki
                                    $categoryHasUnfixedRevision = false;
                                    $categoryAnsweredCount = 0;
                                    foreach ($cat->questions as $catQ) {
                                        $qAns = $existingAnswers[$catQ->id] ?? null;
                                        if (!empty($qAns && $qAns->validator_note) && empty($qAns->is_revised)) {
                                            $categoryHasUnfixedRevision = true;
                                        }
                                        if (!empty($qAns && $qAns->answer_value)) {
                                            $categoryAnsweredCount++;
                                        }
                                    }
                                @endphp

                                <button type="button"
                                    onclick="jumpToCategory({{ $stepIdx }})"
                                    class="w-full text-left p-3 rounded-xl border transition-all flex items-center justify-between group
                                    {{ $isCurrent 
                                        ? 'bg-blue-50/90 border-blue-400 ring-2 ring-blue-500/20 shadow-sm' 
                                        : 'bg-white border-slate-200 hover:border-blue-300 hover:bg-slate-50 hover:shadow-xs' }}">
                                    
                                    <div class="flex items-center gap-3 min-w-0 pr-2">
                                        <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black flex-shrink-0 transition-colors
                                            {{ $isCurrent 
                                                ? 'bg-blue-600 text-white' 
                                                : ($categoryHasUnfixedRevision 
                                                    ? 'bg-rose-100 text-rose-700' 
                                                    : 'bg-slate-100 text-slate-600 group-hover:bg-blue-100 group-hover:text-blue-700') }}">
                                            {{ $stepIdx }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-xs sm:text-sm font-bold truncate {{ $isCurrent ? 'text-blue-900' : 'text-slate-800 group-hover:text-blue-700' }}">
                                                {{ $cat->name }}
                                            </p>
                                            <p class="text-[11px] text-slate-500 mt-0.5">
                                                {{ $categoryAnsweredCount }}/{{ $cat->questions->count() }} Terisi
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if ($categoryHasUnfixedRevision)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 border border-rose-200 animate-pulse flex items-center gap-1">
                                                <i class="bi bi-exclamation-triangle-fill text-[9px]"></i> Revisi
                                            </span>
                                        @elseif ($isCurrent)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">
                                                Aktif
                                            </span>
                                        @else
                                            <i class="bi bi-chevron-right text-slate-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all text-xs"></i>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- KARTU 3: TOMBOL SUBMIT CEPAT / KUMPULKAN ASESMEN (SATU-SATUNYA TOMBOL SUBMIT LANGSUNG) --}}
                <div class="bg-gradient-to-br from-slate-900 to-blue-950 text-white rounded-2xl p-5 shadow-xl border border-slate-800">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-base">
                            <i class="bi bi-send-check-fill"></i>
                        </div>
                        <h4 class="text-sm font-black uppercase tracking-wide">Pengumpulan Asesmen</h4>
                    </div>
                    <p class="text-xs text-slate-300 mb-4 leading-relaxed">
                        Jika Anda sudah selesai mengisi indikator atau menyelesaikan revisi yang diminta validator, Anda dapat langsung mengumpulkan seluruh asesmen sekarang tanpa harus membuka kategori terakhir.
                    </p>
                    <button type="button" onclick="directSubmitSurvey()"
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-sm shadow-lg shadow-emerald-900/40 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <i class="bi bi-check2-circle text-lg"></i> Langsung Kumpulkan Asesmen
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        function triggerLoadingIndicator() {
            const topBar = document.getElementById('top-loading-bar');
            if (topBar) {
                topBar.classList.add('active');
                topBar.style.width = '70%';
            }
        }

        function jumpToQuestion(questionId, buttonElement) {
            const el = document.getElementById(questionId);
            if (!el) return;

            el.scrollIntoView({ behavior: 'smooth', block: 'center' });

            el.classList.add('ring-4', 'ring-blue-400/80', 'transition-all');
            setTimeout(() => {
                el.classList.remove('ring-4', 'ring-blue-400/80');
            }, 1200);
        }

        // Tangani pemilihan opsi radio secara real-time
        function handleOptionSelected(questionId, qNum, hasNote) {
            const container = document.getElementById('question-' + questionId);
            const navBtn = document.getElementById('nav-btn-' + questionId);

            if (hasNote) {
                // Ubah tampilan dari MERAH menjadi HIJAU / EMERALD (informasi revisi tetap utuh)
                if (container) {
                    container.className = "question-container rounded-2xl transition-all duration-300 scroll-mt-28 p-6 sm:p-8 border-2 border-emerald-400 bg-emerald-50/20 shadow-md shadow-emerald-500/5 ring-4 ring-emerald-100/70";
                }

                const badge = document.getElementById('revision-badge-' + questionId);
                if (badge) {
                    badge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-600 text-white shadow-sm shadow-emerald-200";
                    badge.innerHTML = `<i class="bi bi-check-circle-fill"></i> TELAH DIREVISI (CATATAN TETAP TERSIMPAN)`;
                }

                const subtext = document.getElementById('revision-subtext-' + questionId);
                if (subtext) {
                    subtext.className = "text-xs font-bold text-emerald-700 flex items-center gap-1";
                    subtext.innerHTML = `<i class="bi bi-check2-all"></i> Jawaban telah Anda perbarui, tetap bisa diubah kembali`;
                }

                const noteCard = document.getElementById('note-card-' + questionId);
                if (noteCard) {
                    noteCard.className = "mb-5 p-4 rounded-xl bg-white border border-emerald-300 shadow-sm text-slate-800 transition-colors";
                }

                const noteIcon = document.getElementById('note-icon-' + questionId);
                if (noteIcon) {
                    noteIcon.className = "w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 text-lg shadow-inner";
                }

                const noteTitle = document.getElementById('note-title-' + questionId);
                if (noteTitle) {
                    noteTitle.className = "text-xs font-black uppercase tracking-wider text-emerald-900";
                    noteTitle.innerText = "Catatan Koreksi dari Tim Validator (Telah Anda Koreksi):";
                }

                const noteText = document.getElementById('note-text-' + questionId);
                if (noteText) {
                    noteText.className = "text-sm font-semibold text-emerald-950 bg-emerald-50/80 border-emerald-100 mt-1 leading-relaxed p-3 rounded-lg border";
                }

                const numBadge = document.getElementById('num-badge-' + questionId);
                if (numBadge) {
                    numBadge.className = "w-8 h-8 rounded-lg flex items-center justify-center text-sm font-black flex-shrink-0 bg-emerald-600 text-white";
                }

                if (navBtn) {
                    navBtn.className = "h-11 rounded-xl font-black text-xs flex flex-col items-center justify-center transition-all relative group bg-emerald-600 text-white shadow-xs hover:bg-emerald-700";
                    navBtn.innerHTML = `<span>#${qNum}</span><i class="bi bi-check-circle-fill text-[9px] -mt-0.5 text-emerald-200"></i>`;
                }
            } else {
                if (navBtn) {
                    navBtn.className = "h-11 rounded-xl font-black text-xs flex flex-col items-center justify-center transition-all relative group bg-blue-600 text-white shadow-xs hover:bg-blue-700";
                    navBtn.innerHTML = `<span>#${qNum}</span><i class="bi bi-check-lg text-[10px] -mt-0.5"></i>`;
                }
            }
        }

        // Lompat langsung ke kategori lain (auto-save isian yang sudah dipilih saat ini)
        function jumpToCategory(targetStep) {
            triggerLoadingIndicator();
            const form = document.getElementById('surveyStepForm');
            const jumpField = document.getElementById('jumpToStepField');
            jumpField.value = targetStep;

            form.querySelectorAll('input[type="radio"]').forEach(r => r.removeAttribute('required'));
            form.submit();
        }

        // Pengumpulan Langsung Seluruh Asesmen (HANYA DARI TOMBOL KANAN)
        async function directSubmitSurvey() {
            let confirmed = false;

            if (typeof window.handalConfirm === 'function') {
                confirmed = await window.handalConfirm({
                    title: 'Kumpulkan Seluruh Asesmen?',
                    message: 'Pastikan seluruh jawaban dan revisi indikator sudah Anda lengkapi. Asesmen yang dikumpulkan akan langsung masuk ke antrean verifikasi tim validator.',
                    confirmText: 'Ya, Kumpulkan Sekarang',
                    cancelText: 'Periksa Kembali',
                    type: 'info'
                });
            } else {
                confirmed = window.confirm("Kumpulkan Seluruh Asesmen?\n\nPastikan seluruh jawaban dan revisi indikator sudah Anda lengkapi. Asesmen yang dikumpulkan akan langsung masuk ke antrean verifikasi tim validator.");
            }

            if (confirmed) {
                triggerLoadingIndicator();
                const form = document.getElementById('surveyStepForm');
                const actionField = document.getElementById('actionField');
                actionField.value = 'submit_final';
                form.querySelectorAll('input[type="radio"]').forEach(r => r.removeAttribute('required'));
                form.submit();
            }
        }

        // Tangani submit form reguler agar memicu indikator loading halus
        document.getElementById('surveyStepForm')?.addEventListener('submit', function() {
            triggerLoadingIndicator();
        });
    </script>
@endsection
