@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto pb-32">

        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <a href="{{ route('validator.dashboard') }}"
                    class="text-sm text-slate-500 hover:text-blue-600 mb-2 inline-block">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Verifikasi Asesmen</h1>
                <p class="text-slate-500">Sekolah: <span class="font-bold text-blue-600">{{ $survey->school->name }}</span>
                </p>
            </div>
            <div class="bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-sm text-right">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Skor Sementara</div>
                <div class="text-3xl font-bold text-emerald-600">{{ number_format($survey->total_score, 1) }}</div>
            </div>
        </div>

        <form action="{{ route('validator.survey.store', $survey->id) }}" method="POST" id="verificationForm">
            @csrf
            <input type="hidden" name="action" id="actionField" value="approve">

            <div class="space-y-8">
                @foreach ($categories as $category)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800">{{ $category->name }}</h3>
                            <span class="text-xs font-semibold bg-slate-200 text-slate-600 px-2 py-1 rounded">
                                {{ $category->questions->count() }} Soal
                            </span>
                        </div>

                        <div class="p-6 space-y-8">
                            @foreach ($category->questions as $question)
                                @php
                                    $schoolAnswer = $answers[$question->id] ?? null;
                                    $selectedOptionId = $schoolAnswer ? $schoolAnswer->answer_value : null;
                                @endphp

                                <div class="question-block pb-6 border-b border-slate-100 last:border-0 last:pb-0 transition-colors rounded-xl p-3"
                                    id="verify-q-{{ $question->id }}"
                                    data-question-id="{{ $question->id }}"
                                    data-question-text="{{ $question->question_text }}"
                                    data-original-choice="{{ $selectedOptionId }}">
                                    <div class="flex items-start justify-between gap-3 mb-4">
                                        <h4 class="text-base font-bold text-slate-800 flex-1">
                                            {{ $loop->iteration }}. {{ $question->question_text }}
                                        </h4>
                                        <span id="badge-changed-{{ $question->id }}"
                                            class="hidden text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-300 flex-shrink-0 items-center gap-1 shadow-xs">
                                            <i class="bi bi-pencil-fill text-[10px]"></i> Nilai Diubah
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                                        <div class="lg:col-span-2 space-y-3">
                                            @foreach ($question->options as $option)
                                                @php
                                                    $isChosen = $selectedOptionId == $option->id;
                                                @endphp

                                                <label
                                                    class="relative flex items-start p-3 border rounded-xl cursor-pointer transition-all group
                                                    {{ $isChosen ? 'bg-blue-50 border-blue-500 ring-1 ring-blue-500 z-10' : 'border-slate-200 hover:bg-slate-50 hover:border-blue-300' }}">

                                                    <div class="flex items-center h-5">
                                                        <input type="radio"
                                                            name="validation[{{ $question->id }}][answer_id]"
                                                            value="{{ $option->id }}"
                                                            onchange="checkScoreChange({{ $question->id }})"
                                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                            {{ $isChosen ? 'checked' : '' }}>
                                                    </div>

                                                    <div class="ml-3 w-full">
                                                        <div class="flex justify-between">
                                                            <span
                                                                class="text-sm font-medium {{ $isChosen ? 'text-blue-800' : 'text-slate-700' }}">
                                                                {{ $option->option_text }}
                                                            </span>
                                                            <span
                                                                class="text-xs font-bold {{ $isChosen ? 'text-blue-600' : 'text-slate-400' }}">
                                                                {{ $option->score_value }} Poin
                                                            </span>
                                                        </div>

                                                        @if ($isChosen)
                                                            <div class="mt-1">
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700">
                                                                    <i class="bi bi-person-check-fill mr-1"></i> Pilihan
                                                                    Sekolah
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        <div class="lg:col-span-1">
                                            <div id="note-box-{{ $question->id }}" class="bg-amber-50/80 p-4 rounded-xl border border-amber-200/90 h-full flex flex-col shadow-sm transition-all">
                                                <label
                                                    class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2 flex items-center justify-between">
                                                    <span class="flex items-center gap-1.5"><i class="bi bi-pencil-square text-amber-700"></i> Catatan Koreksi</span>
                                                    <span id="note-required-{{ $question->id }}" class="hidden text-[10px] text-rose-600 font-extrabold uppercase">Wajib</span>
                                                </label>
                                                <textarea name="validation[{{ $question->id }}][note]" id="note-input-{{ $question->id }}" rows="4"
                                                    class="w-full text-xs md:text-sm rounded-lg border-amber-300 focus:ring-amber-500 focus:border-amber-500 bg-white text-slate-800 placeholder-slate-400 shadow-inner transition-all"
                                                    placeholder="Tuliskan catatan perbaikan atau alasan jika mengubah nilai...">{{ $schoolAnswer->validator_note ?? '' }}</textarea>
                                                <p id="note-hint-{{ $question->id }}" class="text-[11px] text-amber-800 font-medium mt-2 leading-relaxed">
                                                    *Jika Anda meminta revisi atau mengubah opsi nilai, tuliskan arahan di sini.
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div
                class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-[0_-4px_20px_rgba(0,0,0,0.1)] z-40">
                <div class="max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-xs text-slate-500 hidden md:block">
                        <i class="bi bi-info-circle mr-1"></i> Pastikan semua jawaban telah diperiksa sebelum memverifikasi.
                    </div>

                    <div class="flex w-full md:w-auto gap-3">
                        <button type="button" onclick="confirmVerificationAction('reject')"
                            class="flex-1 md:flex-none px-6 py-3 bg-white border-2 border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 font-bold rounded-xl transition-all shadow-sm">
                            <i class="bi bi-arrow-counterclockwise mr-2"></i> Minta Revisi
                        </button>

                        <button type="button" onclick="confirmVerificationAction('approve')"
                            class="flex-1 md:flex-none px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                            <i class="bi bi-check-circle-fill mr-2"></i> Simpan & Verifikasi
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script>
        function checkScoreChange(qId) {
            const block = document.getElementById('verify-q-' + qId);
            if (!block) return;

            const orig = block.dataset.originalChoice;
            const checkedRadio = block.querySelector(`input[name="validation[${qId}][answer_id]"]:checked`);
            const current = checkedRadio ? checkedRadio.value : orig;
            const isChanged = current != orig;

            const badge = document.getElementById('badge-changed-' + qId);
            const reqLabel = document.getElementById('note-required-' + qId);
            const noteBox = document.getElementById('note-box-' + qId);
            const noteInput = document.getElementById('note-input-' + qId);

            if (isChanged) {
                if (badge) {
                    badge.classList.remove('hidden');
                    badge.classList.add('inline-flex');
                }
                if (reqLabel) reqLabel.classList.remove('hidden');
                if (noteBox) {
                    noteBox.classList.add('border-amber-400', 'ring-2', 'ring-amber-200');
                }
                if (noteInput && !noteInput.value.trim()) {
                    noteInput.classList.add('border-amber-400', 'bg-amber-50/30');
                }
            } else {
                if (badge) {
                    badge.classList.add('hidden');
                    badge.classList.remove('inline-flex');
                }
                if (reqLabel) reqLabel.classList.add('hidden');
                if (noteBox) {
                    noteBox.classList.remove('border-amber-400', 'ring-2', 'ring-amber-200');
                }
                if (noteInput) {
                    noteInput.classList.remove('border-amber-400', 'bg-amber-50/30');
                }
            }
        }

        async function confirmVerificationAction(action) {
            const form = document.getElementById('verificationForm');
            const actionInput = document.getElementById('actionField');
            actionInput.value = action;

            // Validasi 1: Jika nilai/skor diubah dari pilihan sekolah, validator WAJIB menulis catatan
            let missingNoteBlock = null;
            let hasAnyNote = false;

            const questionBlocks = document.querySelectorAll('.question-block');
            questionBlocks.forEach(block => {
                const qId = block.dataset.questionId;
                const orig = block.dataset.originalChoice;
                const checkedRadio = block.querySelector(`input[name="validation[${qId}][answer_id]"]:checked`);
                const current = checkedRadio ? checkedRadio.value : orig;
                const noteInput = document.getElementById('note-input-' + qId);
                const noteVal = noteInput ? noteInput.value.trim() : '';

                if (noteVal !== '') {
                    hasAnyNote = true;
                }

                if (current != orig && noteVal === '') {
                    if (!missingNoteBlock) {
                        missingNoteBlock = { block, noteInput };
                    }
                }
            });

            if (missingNoteBlock) {
                missingNoteBlock.block.scrollIntoView({ behavior: 'smooth', block: 'center' });
                missingNoteBlock.noteInput.classList.add('border-rose-500', 'ring-2', 'ring-rose-300');
                missingNoteBlock.noteInput.focus();

                await window.handalConfirm({
                    title: 'Catatan Koreksi Wajib Diisi',
                    message: 'Anda telah mengubah skor/opsi penilaian pada salah satu soal. Sesuai aturan, Anda wajib memberikan catatan alasan koreksi sebelum melanjutkan.',
                    confirmText: 'Saya Mengerti',
                    cancelText: 'Tutup',
                    type: 'warning'
                });
                return;
            }

            // Validasi 2: Minta revisi hanya dapat dilakukan jika ada minimal 1 catatan revisi
            if (action === 'reject') {
                if (!hasAnyNote) {
                    await window.handalConfirm({
                        title: 'Belum Ada Catatan Koreksi',
                        message: 'Untuk meminta revisi, Anda wajib mengisi minimal 1 catatan perbaikan agar pihak sekolah mengetahui indikator mana yang perlu diperbaiki.',
                        confirmText: 'Lengkapi Catatan',
                        cancelText: 'Tutup',
                        type: 'warning'
                    });
                    return;
                }

                const confirmed = await window.handalConfirm({
                    title: 'Minta Revisi Asesmen?',
                    message: 'Asesmen ini akan dikembalikan ke pihak sekolah dengan status Perlu Revisi. Catatan perbaikan Anda akan langsung ditampilkan ke pihak sekolah.',
                    confirmText: 'Ya, Kembalikan untuk Revisi',
                    cancelText: 'Batal',
                    type: 'warning'
                });
                if (confirmed) {
                    form.submit();
                }
            } else {
                const confirmed = await window.handalConfirm({
                    title: 'Simpan & Verifikasi Asesmen?',
                    message: 'Hasil asesmen mandiri akan disahkan dan skor resmi sekolah akan diterbitkan secara publik di sistem. Lanjutkan?',
                    confirmText: 'Ya, Terbitkan Verifikasi',
                    cancelText: 'Periksa Kembali',
                    type: 'info'
                });
                if (confirmed) {
                    form.submit();
                }
            }
        }
    </script>
@endsection
