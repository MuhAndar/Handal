@extends('layouts.admin')

@section('title', 'Kelola Speed Coding Scratch - Admin Handal')

@section('content')
    {{-- SHEETJS LIBRARY UNTUK PARSING EXCEL & PREVIEW INSTAN DI BROWSER --}}
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- FLASH ALERTS --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2 text-sm font-semibold">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2 text-sm font-semibold">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        {{-- HEADER UTAMA --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div>
                <div class="flex items-center gap-2.5 mb-1.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 font-black flex items-center justify-center text-sm shadow-md shadow-amber-500/20">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </span>
                    <h2 class="text-2xl font-black text-slate-800">Manajemen Data Speed Coding Scratch</h2>
                </div>
                <p class="text-sm text-slate-500">
                    Kelola peserta babak kualifikasi, finalis, dan podium juara. Input data secara manual atau upload file Excel dengan pratinjau instan.
                </p>
            </div>

            {{-- TOMBOL AKSI UTAMA --}}
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="openUploadModal()"
                        class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/15 transition-all flex items-center gap-2">
                    <i class="bi bi-file-earmark-excel-fill text-sm"></i> Upload Excel / CSV
                </button>

                <div class="inline-flex rounded-xl shadow-sm border border-emerald-300 bg-emerald-50/70 p-0.5">
                    <button type="button" onclick="downloadExcelTemplate()"
                            class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all flex items-center gap-1.5"
                            title="Unduh Format Template Excel Resmi (.xlsx)">
                        <i class="bi bi-download"></i> Unduh Format Excel
                    </button>
                    <a href="{{ route('admin.speed_coding.template') }}"
                       class="px-2.5 py-2 rounded-lg hover:bg-emerald-200/60 text-emerald-800 font-bold text-xs transition-colors flex items-center gap-1"
                       title="Unduh Format Alternatif (.csv)">
                        .CSV
                    </a>
                </div>

                <button type="button" onclick="openCreateModal()"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/15 transition-all flex items-center gap-2">
                    <i class="bi bi-person-plus-fill"></i> Tambah Peserta Manual
                </button>
            </div>
        </div>

        {{-- STATS & YEAR SWITCHER --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            {{-- PEMILIH TAHUN/BATCH --}}
            <div class="md:col-span-4 bg-slate-900 text-white p-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-2">
                    <i class="bi bi-calendar3 text-amber-400 text-lg"></i>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Pilih Edisi / Batch Lomba:</span>
                </div>

                <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                    @foreach($availableYears as $year)
                        <a href="{{ route('admin.speed_coding.index', ['year' => $year]) }}"
                           class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ $selectedYear == $year ? 'bg-amber-500 text-slate-950 shadow-md font-black' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                            <i class="bi bi-trophy-fill"></i>
                            Edisi {{ $year }} ({{ $year == '2026' ? 'Batch 2' : ($year == '2025' ? 'Batch 1' : 'Batch ' . $year) }})
                        </a>
                    @endforeach

                    <button type="button" onclick="openNewYearModal()"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 text-amber-400 hover:bg-slate-700 border border-slate-700 transition-all flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Tambah Tahun
                    </button>
                </div>
            </div>

            {{-- METRIK 1: TOTAL PESERTA --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Peserta</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Babak Kualifikasi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>

            {{-- METRIK 2: FINALIS TOP 10 --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lolos ke Final</span>
                    <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['finalists'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Pengerjaan Babak 2</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-award-fill"></i>
                </div>
            </div>

            {{-- METRIK 3: PODIUM JUARA --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Podium Juara</span>
                    <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $stats['winners'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Juara 1, 2, dan 3</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>

            {{-- METRIK 4: KOMPOSISI JENJANG --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jenjang Terbuka</span>
                    <h3 class="text-lg font-black text-slate-800 mt-1">
                        <span class="text-blue-600">{{ $stats['sd'] }} SD</span> / <span class="text-indigo-600">{{ $stats['smp'] }} SMP</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Digabung 1 Kategori</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
            </div>

        </div>

        {{-- TABEL DATA PESERTA SPEED CODING --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                        <span>Tabel Peserta & Hasil Lomba — Edisi {{ $selectedYear }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                            {{ $participants->count() }} Data
                        </span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Menampilkan perolehan waktu dan skor babak kualifikasi (30 peserta) dan babak final (10 peserta).
                    </p>
                </div>

                {{-- FILTER CEPAT --}}
                <div class="flex items-center gap-2">
                    <input type="text" id="tableSearch" placeholder="Cari nama / sekolah..."
                           onkeyup="filterParticipantTable()"
                           class="px-3.5 py-2 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:outline-none w-56">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="participantsTable">
                    <thead class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3.5 font-bold text-center">Rank</th>
                            <th class="px-4 py-3.5 font-bold">Nama Peserta & Sekolah</th>
                            <th class="px-4 py-3.5 font-bold">Jenjang / Kelas</th>
                            <th class="px-4 py-3.5 font-bold text-center bg-blue-50/60">Kualifikasi (Waktu & Skor)</th>
                            <th class="px-4 py-3.5 font-bold text-center bg-amber-50/60">Final (Waktu & Skor)</th>
                            <th class="px-4 py-3.5 font-bold">Status Badge</th>
                            <th class="px-4 py-3.5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($participants as $p)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $p->is_winner ? 'bg-amber-50/20' : '' }}">
                                {{-- PERINGKAT --}}
                                <td class="px-4 py-4 text-center">
                                    @if($p->final_rank === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-400 text-slate-950 font-black text-sm shadow-md shadow-amber-400/30">1</span>
                                    @elseif($p->final_rank === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-300 text-slate-900 font-black text-sm shadow-md">2</span>
                                    @elseif($p->final_rank === 3)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-700 text-white font-black text-sm shadow-md">3</span>
                                    @elseif($p->final_rank)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs">{{ $p->final_rank }}</span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-xs">{{ $p->kualifikasi_rank ?? '-' }}</span>
                                    @endif
                                </td>

                                {{-- NAMA & SEKOLAH --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold shadow-sm flex-shrink-0 {{ $p->final_rank === 1 ? 'bg-amber-400 text-slate-950' : ($p->jenjang === 'SD' ? 'bg-sky-100 text-sky-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ $p->avatar ?? 'SC' }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $p->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $p->school }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- JENJANG & KELAS --}}
                                <td class="px-4 py-4">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $p->jenjang === 'SD' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                                        {{ $p->jenjang }}
                                    </span>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $p->kelas ?? '-' }}</div>
                                </td>

                                {{-- KUALIFIKASI --}}
                                <td class="px-4 py-4 text-center bg-blue-50/20 font-mono text-xs">
                                    <div class="font-bold text-slate-800">{{ $p->kualifikasi_time ?? '-' }}</div>
                                    <div class="text-[11px] text-blue-600 font-semibold">{{ $p->kualifikasi_score ? $p->kualifikasi_score . ' Pts' : '-' }}</div>
                                </td>

                                {{-- FINAL --}}
                                <td class="px-4 py-4 text-center bg-amber-50/20 font-mono text-xs">
                                    @if($p->is_finalist && $p->final_time)
                                        <div class="font-bold text-slate-900">{{ $p->final_time }}</div>
                                        <div class="text-[11px] text-emerald-600 font-bold">{{ $p->final_score ? $p->final_score . ' Pts' : '-' }}</div>
                                    @else
                                        <span class="text-slate-400 text-xs italic">Gugur Kualifikasi</span>
                                    @endif
                                </td>

                                {{-- STATUS BADGE --}}
                                <td class="px-4 py-4">
                                    @if(str_contains($p->status, 'Juara 1'))
                                        <span class="px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 flex items-center gap-1 w-max">
                                            {{ $p->status }}
                                        </span>
                                    @elseif(str_contains($p->status, 'Juara 2'))
                                        <span class="px-2.5 py-1 rounded-full text-xs font-black bg-slate-200 text-slate-800 flex items-center gap-1 w-max">
                                            {{ $p->status }}
                                        </span>
                                    @elseif(str_contains($p->status, 'Juara 3'))
                                        <span class="px-2.5 py-1 rounded-full text-xs font-black bg-amber-800/10 text-amber-900 flex items-center gap-1 w-max">
                                            {{ $p->status }}
                                        </span>
                                    @elseif($p->is_finalist)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1 w-max">
                                            ✓ Finalis Top 10
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 w-max">
                                            Peserta Kualifikasi
                                        </span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-4 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" onclick="openEditModal({{ json_encode($p) }})"
                                                class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors"
                                                title="Edit Peserta">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form action="{{ route('admin.speed_coding.destroy', $p->id) }}" method="POST"
                                              onsubmit="event.preventDefault(); window.handalConfirmForm(this, 'Hapus data peserta {{ $p->name }} dari Edisi {{ $selectedYear }}? Data yang dihapus tidak dapat dikembalikan.', 'Hapus Peserta', 'danger');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors"
                                                    title="Hapus Peserta">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <i class="bi bi-inbox text-4xl block text-slate-300 mb-2"></i>
                                    Belum ada data peserta untuk Edisi {{ $selectedYear }}.<br>
                                    Silakan klik tombol <strong>"Upload Excel"</strong> atau <strong>"Tambah Peserta Manual"</strong> di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL 1: UPLOAD FILE EXCEL DENGAN LIVE PREVIEW SEBELUM SUBMIT --}}
    {{-- ========================================================================= --}}
    <div id="uploadExcelModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl w-full max-w-4xl max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="px-6 py-5 bg-gradient-to-r from-emerald-600 to-teal-700 text-white flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black flex items-center gap-2">
                        <i class="bi bi-file-earmark-excel-fill text-xl"></i> Upload Data Excel Speed Coding
                    </h3>
                    <p class="text-xs text-emerald-100 mt-0.5">
                        Unggah file .xlsx, .xls, atau .csv. Sistem akan menampilkan pratinjau tabel terlebih dahulu sebelum disimpan.
                    </p>
                </div>
                <button type="button" onclick="closeUploadModal()" class="text-emerald-200 hover:text-white transition-colors">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto flex-1 space-y-5">

                {{-- KARTU DOWNLOAD TEMPLATE FORMAT EXCEL --}}
                <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-md shadow-emerald-600/20">
                            <i class="bi bi-file-earmark-excel-fill"></i>
                        </div>
                        <div>
                            <h5 class="font-black text-slate-800 text-xs">Unduh Format Excel Resmi</h5>
                            <p class="text-[11px] text-slate-500 mt-0.5">Format tabel telah disesuaikan dengan struktur sistem kami. Lengkap dengan petunjuk pengisian.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button type="button" onclick="downloadExcelTemplate()"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-500/15 transition-all flex items-center gap-1.5 whitespace-nowrap">
                            <i class="bi bi-download"></i> Format Excel (.xlsx)
                        </button>
                        <a href="{{ route('admin.speed_coding.template') }}"
                           class="px-3.5 py-2 bg-white hover:bg-slate-100 text-emerald-800 rounded-xl text-xs font-bold border border-emerald-200 transition-colors flex items-center gap-1 whitespace-nowrap"
                           title="Unduh format tabel dalam bentuk CSV">
                            <i class="bi bi-filetype-csv"></i> Format (.csv)
                        </a>
                    </div>
                </div>

                {{-- DRAG & DROP ZONE --}}
                <div id="dropZone"
                     class="border-2 border-dashed border-slate-300 hover:border-emerald-500 bg-slate-50/70 hover:bg-emerald-50/30 rounded-2xl p-8 text-center cursor-pointer transition-all">
                    <input type="file" id="excelFileInput" accept=".xlsx, .xls, .csv" class="hidden" onchange="handleFileSelect(event)">
                    <div class="space-y-2">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mx-auto shadow-inner">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm">Pilih berkas Excel (.xlsx / .csv) atau seret ke sini</h4>
                        <p class="text-xs text-slate-500">Maksimal ukuran file 10MB. Otomatis mengenali baris dan kolom.</p>
                        <button type="button" onclick="document.getElementById('excelFileInput').click()"
                                class="mt-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                            Pilih File dari Komputer
                        </button>
                    </div>
                </div>

                {{-- TAHUN TUJUAN & METODE IMPORT --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Target Edisi / Tahun:
                        </label>
                        <input type="text" id="importYearInput" value="{{ $selectedYear }}"
                               class="w-full px-3.5 py-2 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Metode Simpan Data:
                        </label>
                        <select id="importModeSelect" class="w-full px-3.5 py-2 text-xs font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500">
                            <option value="replace" selected>Ganti Semua Data Tahun Ini (Bersihkan dulu)</option>
                            <option value="append">Tambahkan ke Data yang Sudah Ada</option>
                        </select>
                    </div>
                </div>

                {{-- PREVIEW CONTAINER (MUNCUL SETELAH FILE DIPILIH) --}}
                <div id="previewContainer" class="hidden space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h4 class="font-black text-slate-800 text-sm">
                                Pratinjau Data: <span id="previewFileName" class="text-emerald-700"></span>
                            </h4>
                            <span id="previewRowCount" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                0 Baris
                            </span>
                        </div>
                        <span class="text-xs text-slate-400">Silakan periksa sebelum menekan Simpan</span>
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden max-h-72 overflow-y-auto">
                        <table class="w-full text-left text-xs" id="previewTable">
                            <thead class="bg-slate-100 text-slate-600 uppercase font-bold sticky top-0">
                                <tr>
                                    <th class="px-3 py-2 text-center">No</th>
                                    <th class="px-3 py-2">Nama</th>
                                    <th class="px-3 py-2">Sekolah</th>
                                    <th class="px-3 py-2">Jenjang</th>
                                    <th class="px-3 py-2">Kelas</th>
                                    <th class="px-3 py-2 text-center">Kualifikasi</th>
                                    <th class="px-3 py-2 text-center">Final</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody" class="divide-y divide-slate-100">
                                {{-- Baris preview di-inject via JavaScript --}}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <button type="button" onclick="closeUploadModal()"
                        class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-200 font-bold text-xs transition-colors">
                    Batal
                </button>

                <div class="flex items-center gap-2">
                    <button type="button" id="btnSubmitImport" onclick="submitImportData()" disabled
                            class="px-5 py-2.5 rounded-xl bg-slate-300 text-slate-500 font-bold text-xs cursor-not-allowed transition-all flex items-center gap-2">
                        <i class="bi bi-cloud-check-fill"></i> Konfirmasi & Simpan ke Database
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL 2: TAMBAH / EDIT PESERTA MANUAL --}}
    {{-- ========================================================================= --}}
    <div id="participantModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-slate-200 overflow-hidden">
            
            <form id="participantForm" method="POST" action="{{ route('admin.speed_coding.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="year" id="participantYear" value="{{ $selectedYear }}">

                <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black" id="modalParticipantTitle">Tambah Peserta Baru</h3>
                        <p class="text-xs text-blue-100 mt-0.5">Edisi Tahun {{ $selectedYear }}</p>
                    </div>
                    <button type="button" onclick="closeParticipantModal()" class="text-blue-200 hover:text-white transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    
                    {{-- NAMA & ASAL SEKOLAH --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Peserta <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="pName" required placeholder="Contoh: Ahmad Zaki"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Asal Sekolah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="school" id="pSchool" required placeholder="Contoh: SMP Islam Athirah"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    {{-- JENJANG & KELAS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jenjang Lomba <span class="text-red-500">*</span>
                            </label>
                            <select name="jenjang" id="pJenjang" required
                                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                                <option value="SMP">SMP (Kelas 1 - 3)</option>
                                <option value="SD">SD (Kelas 5 - 6)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kelas
                            </label>
                            <input type="text" name="kelas" id="pKelas" placeholder="Contoh: Kelas 9 SMP"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    {{-- DATA BABAK 1: KUALIFIKASI --}}
                    <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 space-y-3">
                        <div class="flex items-center gap-1.5 text-xs font-black text-blue-800 uppercase tracking-wider">
                            <i class="bi bi-stopwatch-fill"></i> Babak 1: Kualifikasi (30 Peserta)
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Rank Kualifikasi</label>
                                <input type="number" name="kualifikasi_rank" id="pKualifikasiRank" min="1" max="50" placeholder="1-30"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Waktu (mm:ss)</label>
                                <input type="text" name="kualifikasi_time" id="pKualifikasiTime" placeholder="03:45"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Skor (0-100)</label>
                                <input type="number" step="0.5" name="kualifikasi_score" id="pKualifikasiScore" min="0" max="100" placeholder="98.0"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                            </div>
                        </div>
                    </div>

                    {{-- DATA BABAK 2: FINAL --}}
                    <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs font-black text-amber-900 uppercase tracking-wider">
                                <i class="bi bi-trophy-fill text-amber-600"></i> Babak 2: Final (10 Finalis)
                            </div>
                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="checkbox" name="is_finalist" id="pIsFinalist" value="1" class="rounded text-amber-600">
                                <span>Lolos ke Final</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Rank Final (1-10)</label>
                                <input type="number" name="final_rank" id="pFinalRank" min="1" max="10" placeholder="1 = Juara 1"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Waktu Final</label>
                                <input type="text" name="final_time" id="pFinalTime" placeholder="03:14"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Skor Final</label>
                                <input type="number" step="0.5" name="final_score" id="pFinalScore" min="0" max="100" placeholder="100.0"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                            </div>
                        </div>
                    </div>

                    {{-- STATUS BADGE --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Status Badge (Opsional, kosongkan untuk otomatis)
                        </label>
                        <input type="text" name="status" id="pStatus" placeholder="Contoh: Juara 1 🥇 atau Finalis Top 10"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                    <button type="button" onclick="closeParticipantModal()"
                            class="px-5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-200 font-bold text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all flex items-center gap-1.5">
                        <i class="bi bi-check-circle-fill"></i> Simpan Data Peserta
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL 3: TAMBAH TAHUN BARU --}}
    {{-- ========================================================================= --}}
    <div id="newYearModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-sm shadow-2xl border border-slate-200 p-6 space-y-4">
            <h3 class="font-black text-slate-800 text-base">Tambah Edisi Tahun Baru</h3>
            <p class="text-xs text-slate-500">Masukkan tahun kompetisi Speed Coding yang ingin dibuat (misal: 2027).</p>
            <input type="number" id="newYearInput" min="2020" max="2050" value="{{ date('Y') + 1 }}"
                   class="w-full px-4 py-2.5 text-sm font-bold rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500">
            <div class="flex gap-2">
                <button type="button" onclick="document.getElementById('newYearModal').classList.add('hidden')"
                        class="flex-1 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl">
                    Batal
                </button>
                <button type="button" onclick="redirectToNewYear()"
                        class="flex-1 py-2 text-xs font-bold bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-xl shadow-md">
                    Buka Edisi Ini
                </button>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT HANDLERS & EXCEL SHEETJS PROCESSING --}}
    <script>
        let parsedExcelRows = [];

        // 1. FILTER PENCARIAN DI TABEL PESERTA
        function filterParticipantTable() {
            const input = document.getElementById('tableSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#participantsTable tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        }

        // 2. MODAL UPLOAD EXCEL
        function openUploadModal() {
            document.getElementById('uploadExcelModal').classList.remove('hidden');
        }
        function closeUploadModal() {
            document.getElementById('uploadExcelModal').classList.add('hidden');
            resetUploadState();
        }

        function resetUploadState() {
            parsedExcelRows = [];
            document.getElementById('previewContainer').classList.add('hidden');
            document.getElementById('previewTableBody').innerHTML = '';
            document.getElementById('excelFileInput').value = '';
            const btn = document.getElementById('btnSubmitImport');
            btn.disabled = true;
            btn.className = "px-5 py-2.5 rounded-xl bg-slate-300 text-slate-500 font-bold text-xs cursor-not-allowed transition-all flex items-center gap-2";
        }

        // 3. PARSING EXCEL DENGAN SHEETJS (INSTAN DI CLIENT-SIDE)
        function handleFileSelect(e) {
            const file = e.target.files[0];
            if (!file) return;

            document.getElementById('previewFileName').innerText = file.name;

            const reader = new FileReader();
            reader.onload = async function(event) {
                try {
                    const data = new Uint8Array(event.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const rawJson = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                    if (!rawJson || rawJson.length < 2) {
                        await window.handalAlert({
                            title: 'Berkas Excel Kosong',
                            message: 'Berkas yang dipilih tidak memiliki baris data. Silakan unduh format template resmi kami terlebih dahulu.',
                            type: 'warning'
                        });
                        return;
                    }

                    processParsedExcel(rawJson);
                } catch (err) {
                    await window.handalAlert({
                        title: 'Gagal Membaca File',
                        message: 'Terjadi galat saat memproses berkas Excel: ' + err.message,
                        type: 'danger'
                    });
                }
            };
            reader.readAsArrayBuffer(file);
        }

        // Normalisasi header dan baris data
        function processParsedExcel(matrix) {
            const headers = matrix[0].map(h => (h || '').toString().toLowerCase().trim());
            const rows = matrix.slice(1);

            parsedExcelRows = [];
            const tbody = document.getElementById('previewTableBody');
            tbody.innerHTML = '';

            let validCount = 0;

            rows.forEach((row, idx) => {
                if (!row || row.length === 0 || !row[1] && !row[0]) return;

                // Ambil kolom dengan fleksibilitas nama header
                const getVal = (colNames, defaultIndex) => {
                    for (const name of colNames) {
                        const colIdx = headers.findIndex(h => h.includes(name));
                        if (colIdx !== -1 && row[colIdx] !== undefined) return row[colIdx];
                    }
                    return row[defaultIndex] !== undefined ? row[defaultIndex] : '';
                };

                const name = getVal(['nama', 'peserta', 'siswa'], 1);
                const school = getVal(['sekolah', 'instansi'], 2);
                if (!name && !school) return;

                const jenjangRaw = (getVal(['jenjang', 'tingkat'], 3) || '').toString().toUpperCase();
                const jenjang = jenjangRaw.includes('SD') ? 'SD' : 'SMP';
                const kelas = getVal(['kelas'], 4) || (jenjang === 'SD' ? 'Kelas 6 SD' : 'Kelas 8 SMP');
                
                const kualRank = parseInt(getVal(['rank kualifikasi', 'peringkat kualifikasi', 'no'], 0)) || (idx + 1);
                const kualTime = (getVal(['waktu kualifikasi', 'time kualifikasi'], 5) || '04:00').toString();
                const kualScore = parseFloat(getVal(['skor kualifikasi', 'score kualifikasi'], 6)) || 90;

                const finalRankRaw = getVal(['rank final', 'peringkat final', 'juara'], 7);
                const finalRank = finalRankRaw ? parseInt(finalRankRaw) : null;
                const finalTime = (getVal(['waktu final', 'time final'], 8) || '').toString();
                const finalScore = finalRankRaw ? (parseFloat(getVal(['skor final', 'score final'], 9)) || 95) : null;

                const isFinalist = finalRank !== null || (finalTime && finalTime !== '-');
                let status = (getVal(['status', 'badge', 'keterangan'], 10) || '').toString();
                if (!status) {
                    if (finalRank === 1) status = 'Juara 1 🥇';
                    else if (finalRank === 2) status = 'Juara 2 🥈';
                    else if (finalRank === 3) status = 'Juara 3 🥉';
                    else if (isFinalist) status = 'Finalis Top 10 🎖️';
                    else status = 'Peserta Kualifikasi';
                }

                parsedExcelRows.push({
                    name, school, jenjang, kelas,
                    kualifikasi_rank: kualRank,
                    kualifikasi_time: kualTime,
                    kualifikasi_score: kualScore,
                    final_rank: finalRank,
                    final_time: finalTime,
                    final_score: finalScore,
                    is_finalist: isFinalist,
                    status
                });

                validCount++;

                // Render Baris Pratinjau
                const tr = document.createElement('tr');
                tr.className = isFinalist ? "bg-amber-50/40" : "";
                tr.innerHTML = `
                    <td class="px-3 py-2 text-center font-bold text-slate-500">${validCount}</td>
                    <td class="px-3 py-2 font-bold text-slate-800">${name}</td>
                    <td class="px-3 py-2 text-slate-600">${school}</td>
                    <td class="px-3 py-2"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold ${jenjang === 'SD' ? 'bg-sky-100 text-sky-700' : 'bg-indigo-100 text-indigo-700'}">${jenjang}</span></td>
                    <td class="px-3 py-2 text-slate-500">${kelas}</td>
                    <td class="px-3 py-2 text-center font-mono text-slate-700">${kualTime} (${kualScore})</td>
                    <td class="px-3 py-2 text-center font-mono font-bold ${finalRank ? 'text-amber-700' : 'text-slate-400'}">${finalTime || '-'}</td>
                    <td class="px-3 py-2"><span class="px-2 py-0.5 rounded text-[10px] font-bold ${finalRank <= 3 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700'}">${status}</span></td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('previewRowCount').innerText = `${validCount} Baris Terdeteksi`;
            document.getElementById('previewContainer').classList.remove('hidden');

            const btn = document.getElementById('btnSubmitImport');
            if (validCount > 0) {
                btn.disabled = false;
                btn.className = "px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all flex items-center gap-2 cursor-pointer";
            }
        }

        // 4. SUBMIT HASIL PARSING EXCEL KE SERVER VIA AJAX
        async function submitImportData() {
            if (parsedExcelRows.length === 0) {
                await window.handalAlert({
                    title: 'Data Kosong',
                    message: 'Tidak ada baris data valid yang siap diimpor.',
                    type: 'warning'
                });
                return;
            }

            const year = document.getElementById('importYearInput').value;
            const mode = document.getElementById('importModeSelect').value;
            const btn = document.getElementById('btnSubmitImport');

            const modeText = mode === 'replace'
                ? 'Seluruh data lama tahun ' + year + ' akan dibersihkan dan diganti dengan data baru ini.'
                : 'Data baru akan ditambahkan tanpa menghapus data yang sudah ada.';

            const confirmed = await window.handalConfirm({
                title: 'Konfirmasi Upload Excel',
                message: `Apakah Anda yakin ingin mengimpor ${parsedExcelRows.length} data peserta ke Edisi ${year}? ${modeText}`,
                confirmText: 'Ya, Simpan ke Database',
                cancelText: 'Periksa Kembali',
                type: 'info'
            });

            if (!confirmed) return;

            btn.disabled = true;
            btn.innerHTML = `<span class="animate-spin inline-block mr-1">⌛</span> Menyimpan data...`;

            fetch("{{ route('admin.speed_coding.import') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    year: year,
                    batch_name: year === '2026' ? 'Batch 2' : 'Batch 1',
                    replace_all: mode === 'replace',
                    rows: parsedExcelRows
                })
            })
            .then(res => res.json())
            .then(async data => {
                if (data.success) {
                    await window.handalAlert({
                        title: 'Import Berhasil!',
                        message: data.message,
                        type: 'success'
                    });
                    window.location.href = data.redirect;
                } else {
                    await window.handalAlert({
                        title: 'Gagal Menyimpan Data',
                        message: data.message,
                        type: 'danger'
                    });
                    btn.disabled = false;
                    btn.innerText = "Konfirmasi & Simpan ke Database";
                }
            })
            .catch(async err => {
                await window.handalAlert({
                    title: 'Terjadi Kesalahan Jaringan',
                    message: err.message,
                    type: 'danger'
                });
                btn.disabled = false;
                btn.innerText = "Konfirmasi & Simpan ke Database";
            });
        }

        // 5. UNDUH FORMAT TEMPLATE EXCEL RESMI (.xlsx) DENGAN SHEETJS MULTI-SHEET
        function downloadExcelTemplate() {
            // SHEET 1: DATA PESERTA CONTOH RESMI
            const sampleData = [
                ["No", "Nama Peserta", "Asal Sekolah", "Jenjang", "Kelas", "Waktu Kualifikasi", "Skor Kualifikasi", "Rank Final", "Waktu Final", "Skor Final", "Status"],
                [1, "Ahmad Zaki Fauzan", "SMP Islam Athirah Makassar", "SMP", "Kelas 9 SMP", "03:45", 98.0, 1, "03:14", 100.0, "Juara 1 🥇"],
                [2, "Zahra Amalia Khairunnisa", "SD Unggulan BTN Pemda", "SD", "Kelas 6 SD", "03:38", 99.0, 2, "03:42", 98.0, "Juara 2 🥈"],
                [3, "Siti Nurhaliza", "SMPN 6 Makassar", "SMP", "Kelas 8 SMP", "04:12", 94.0, 3, "04:05", 95.5, "Juara 3 🥉"],
                [4, "Muhammad Kenzo Alvaro", "SD Telkom Makassar", "SD", "Kelas 5 SD", "04:35", 92.0, 4, "04:22", 93.0, "Finalis Top 10 🎖️"],
                [5, "Nayla Putri Wardani", "SD Islam Athirah Makassar", "SD", "Kelas 6 SD", "04:20", 93.5, 5, "04:38", 91.5, "Finalis Top 10 🎖️"],
                [6, "Contoh Siswa Babak 1", "SMPN 1 Makassar", "SMP", "Kelas 7 SMP", "04:45", 88.0, "", "", "", "Peserta Kualifikasi"]
            ];

            const ws = XLSX.utils.aoa_to_sheet(sampleData);
            // Atur lebar kolom agar rapi saat dibuka di Microsoft Excel
            ws['!cols'] = [
                { wch: 5 },  // No
                { wch: 28 }, // Nama Peserta
                { wch: 32 }, // Asal Sekolah
                { wch: 10 }, // Jenjang
                { wch: 15 }, // Kelas
                { wch: 18 }, // Waktu Kualifikasi
                { wch: 16 }, // Skor Kualifikasi
                { wch: 12 }, // Rank Final
                { wch: 14 }, // Waktu Final
                { wch: 14 }, // Skor Final
                { wch: 22 }  // Status
            ];

            // SHEET 2: PETUNJUK & ATURAN FORMAT
            const guideData = [
                ["Nama Kolom", "Wajib / Opsional", "Format Pengisian", "Keterangan"],
                ["No", "Opsional", "Angka (1, 2, ...)", "Nomor urut peserta lomba"],
                ["Nama Peserta", "WAJIB", "Teks (Nama Siswa)", "Nama lengkap peserta"],
                ["Asal Sekolah", "WAJIB", "Teks (Nama Sekolah)", "Nama resmi sekolah siswa"],
                ["Jenjang", "WAJIB", "SD atau SMP", "Pilih salah satu: SD atau SMP"],
                ["Kelas", "Opsional", "Teks", "Contoh: Kelas 6 SD atau Kelas 9 SMP"],
                ["Waktu Kualifikasi", "WAJIB", "Format mm:ss (Contoh: 03:45)", "Catatan waktu pengerjaan babak kualifikasi"],
                ["Skor Kualifikasi", "WAJIB", "Angka 0 - 100 (Contoh: 98.0)", "Nilai akurasi pengerjaan babak kualifikasi"],
                ["Rank Final", "Opsional", "Angka 1 - 10", "Khusus 10 finalis terbaik. Kosongkan jika gugur di kualifikasi"],
                ["Waktu Final", "Opsional", "Format mm:ss (Contoh: 03:14)", "Catatan waktu babak final. Kosongkan jika tidak lolos"],
                ["Skor Final", "Opsional", "Angka 0 - 100 (Contoh: 100.0)", "Nilai akurasi babak final. Kosongkan jika tidak lolos"],
                ["Status", "Opsional", "Teks", "Contoh: Juara 1 🥇, Juara 2 🥈, Juara 3 🥉, Finalis Top 10 🎖️, atau Peserta Kualifikasi. Jika dikosongkan, sistem akan mengisinya otomatis."]
            ];

            const wsGuide = XLSX.utils.aoa_to_sheet(guideData);
            wsGuide['!cols'] = [
                { wch: 20 },
                { wch: 18 },
                { wch: 32 },
                { wch: 55 }
            ];

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Data Peserta");
            XLSX.utils.book_append_sheet(wb, wsGuide, "Petunjuk Pengisian");

            XLSX.writeFile(wb, "Format_Upload_Speed_Coding_HANDAL.xlsx");
        }

        // 6. MODAL MANUAL CREATE / EDIT
        function openCreateModal() {
            document.getElementById('modalParticipantTitle').innerText = "Tambah Peserta Baru";
            document.getElementById('formMethod').value = "POST";
            document.getElementById('participantForm').action = "{{ route('admin.speed_coding.store') }}";
            document.getElementById('participantForm').reset();
            document.getElementById('participantYear').value = "{{ $selectedYear }}";
            document.getElementById('participantModal').classList.remove('hidden');
        }

        function openEditModal(p) {
            document.getElementById('modalParticipantTitle').innerText = "Edit Data Peserta: " + p.name;
            document.getElementById('formMethod').value = "PUT";
            let url = "{{ route('admin.speed_coding.update', ':id') }}";
            document.getElementById('participantForm').action = url.replace(':id', p.id);

            document.getElementById('participantYear').value = p.year;
            document.getElementById('pName').value = p.name || '';
            document.getElementById('pSchool').value = p.school || '';
            document.getElementById('pJenjang').value = p.jenjang || 'SMP';
            document.getElementById('pKelas').value = p.kelas || '';
            document.getElementById('pKualifikasiRank').value = p.kualifikasi_rank || '';
            document.getElementById('pKualifikasiTime').value = p.kualifikasi_time || '';
            document.getElementById('pKualifikasiScore').value = p.kualifikasi_score || '';
            document.getElementById('pFinalRank').value = p.final_rank || '';
            document.getElementById('pFinalTime').value = p.final_time || '';
            document.getElementById('pFinalScore').value = p.final_score || '';
            document.getElementById('pIsFinalist').checked = !!p.is_finalist;
            document.getElementById('pStatus').value = p.status || '';

            document.getElementById('participantModal').classList.remove('hidden');
        }

        function closeParticipantModal() {
            document.getElementById('participantModal').classList.add('hidden');
        }

        // 7. TAMBAH TAHUN BARU
        function openNewYearModal() {
            document.getElementById('newYearModal').classList.remove('hidden');
        }
        function redirectToNewYear() {
            const yr = document.getElementById('newYearInput').value;
            if (yr) {
                window.location.href = "{{ route('admin.speed_coding.index') }}?year=" + yr;
            }
        }
    </script>
@endsection
