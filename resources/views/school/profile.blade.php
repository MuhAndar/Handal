@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- HEADER --}}
        <div
            class="relative bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 mb-10 shadow-xl overflow-hidden text-white">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-white/40 shadow-md bg-white/20 flex-shrink-0">
                        <img src="{{ $school->photo_url }}" alt="{{ $school->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h1 class="text-3xl font-black tracking-tight">{{ $school->name }}</h1>
                        <p class="text-blue-100 mt-1 text-base md:text-lg">Kelola identitas, foto profil, dan keamanan akun sekolah Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-8 border border-emerald-100 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100 p-2 rounded-full text-emerald-600">
                        <i class="bi bi-check-lg text-xl"></i>
                    </div>
                    <div>
                        <span class="font-bold block">Berhasil!</span>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-700">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- UPDATE DATA --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="bi bi-pencil-square text-blue-600"></i> Informasi Umum
                        </h2>
                    </div>

                    <div class="p-8">
                        <form action="{{ route('school.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">

                                {{-- FOTO PROFIL SEKOLAH --}}
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-800">
                                                Foto Profil Sekolah
                                            </label>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                Upload logo atau foto gedung sekolah. Anda dapat mengupload foto berulang kali dan sistem menyimpan 5 foto terakhir.
                                            </p>
                                        </div>
                                        @if($school->photo)
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold flex items-center gap-1">
                                                <i class="bi bi-check-circle-fill text-emerald-600"></i> Ada Foto Aktif
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col sm:flex-row items-center gap-6 pt-2">
                                        <div class="relative group flex-shrink-0">
                                            <img id="schoolPhotoPreview"
                                                 src="{{ $school->photo_url }}"
                                                 alt="{{ $school->name }}"
                                                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-2 border-slate-300 shadow-sm bg-white">
                                            <div class="absolute -bottom-2 -right-2 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs shadow">
                                                <i class="bi bi-camera-fill"></i>
                                            </div>
                                        </div>

                                        <div class="flex-1 w-full space-y-2">
                                            <label for="schoolPhotoInput"
                                                class="flex flex-col items-center justify-center px-4 py-4 border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-xl cursor-pointer bg-white hover:bg-blue-50/40 transition-all text-center group">
                                                <i class="bi bi-cloud-arrow-up text-2xl text-slate-400 group-hover:text-blue-600 transition-colors"></i>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700 mt-1" id="schoolPhotoLabel">
                                                    Klik untuk Memilih Foto Baru
                                                </span>
                                                <span class="text-[11px] text-slate-400 mt-0.5">
                                                    Maksimal 2MB (JPG, JPEG, PNG, WEBP)
                                                </span>
                                                <input type="file" name="photo" id="schoolPhotoInput" accept="image/*" class="sr-only"
                                                       onchange="previewSchoolPhoto(this)">
                                            </label>
                                            @error('photo')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-slate-50 p-5 rounded-2xl border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">NPSN</label>
                                        <div
                                            class="flex items-center gap-3 text-slate-600 font-bold bg-white px-4 py-3 rounded-xl border border-slate-200 shadow-sm">
                                            <i class="bi bi-upc-scan text-slate-400"></i>
                                            {{ $school->npsn }}
                                            <i class="bi bi-lock-fill text-slate-300 ml-auto"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama
                                            Sekolah</label>
                                        <div
                                            class="flex items-center gap-3 text-slate-600 font-bold bg-white px-4 py-3 rounded-xl border border-slate-200 shadow-sm">
                                            <i class="bi bi-building text-slate-400"></i>
                                            {{ $school->name }}
                                            <i class="bi bi-lock-fill text-slate-300 ml-auto"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Jenjang Pendidikan</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                                            <i class="bi bi-mortarboard-fill"></i>
                                        </div>
                                        <select name="jenjang"
                                            class="w-full pl-11 pr-4 py-3 bg-white rounded-xl border border-slate-300 text-slate-700 font-medium 
                                               focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 hover:border-blue-400 transition-all duration-200 cursor-pointer">
                                            <optgroup label="Tingkat Dasar (SD / MI / Sederajat)">
                                                <option value="SD" {{ $school->jenjang == 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                                                <option value="MI" {{ $school->jenjang == 'MI' ? 'selected' : '' }}>MI (Madrasah Ibtidaiyah)</option>
                                            </optgroup>
                                            <optgroup label="Tingkat Menengah Pertama (SMP / MTs / Sederajat)">
                                                <option value="SMP" {{ $school->jenjang == 'SMP' ? 'selected' : '' }}>SMP (Sekolah Menengah Pertama)</option>
                                                <option value="MTs" {{ $school->jenjang == 'MTs' ? 'selected' : '' }}>MTs (Madrasah Tsanawiyah)</option>
                                            </optgroup>
                                            <optgroup label="Tingkat Menengah Atas & Kejuruan (SMA / SMK / MA / MAK)">
                                                <option value="SMA" {{ $school->jenjang == 'SMA' ? 'selected' : '' }}>SMA (Sekolah Menengah Atas)</option>
                                                <option value="SMK" {{ $school->jenjang == 'SMK' ? 'selected' : '' }}>SMK (Sekolah Menengah Kejuruan)</option>
                                                <option value="MA" {{ $school->jenjang == 'MA' ? 'selected' : '' }}>MA (Madrasah Aliyah)</option>
                                                <option value="MAK" {{ $school->jenjang == 'MAK' ? 'selected' : '' }}>MAK (Madrasah Aliyah Kejuruan)</option>
                                            </optgroup>
                                            <optgroup label="Pendidikan Khusus & Non-Formal">
                                                <option value="SLB" {{ $school->jenjang == 'SLB' ? 'selected' : '' }}>SLB (Sekolah Luar Biasa)</option>
                                                <option value="Sederajat" {{ $school->jenjang == 'Sederajat' ? 'selected' : '' }}>Pendidikan Sederajat / Lainnya</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    @error('jenjang')
                                        <p class="text-red-500 text-xs mt-1 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Akun</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                                                <i class="bi bi-envelope-fill"></i>
                                            </div>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                                class="w-full pl-11 pr-4 py-3 bg-white rounded-xl border border-slate-300 text-slate-700 placeholder-slate-400 font-medium
                                                   focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 hover:border-blue-400 transition-all duration-200">
                                        </div>
                                        @error('email')
                                            <p class="text-red-500 text-xs mt-1 pl-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                                                <i class="bi bi-telephone-fill"></i>
                                            </div>
                                            <input type="text" name="phone_number"
                                                value="{{ old('phone_number', $school->phone_number ?? '') }}"
                                                class="w-full pl-11 pr-4 py-3 bg-white rounded-xl border border-slate-300 text-slate-700 placeholder-slate-400 font-medium
                                                   focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 hover:border-blue-400 transition-all duration-200"
                                                placeholder="Contoh: 0812...">
                                        </div>
                                        @error('phone_number')
                                            <p class="text-red-500 text-xs mt-1 pl-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                                    <div class="relative">
                                        <div class="absolute top-3 left-4 text-slate-500">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <textarea name="address" rows="3"
                                            class="w-full pl-11 pr-4 py-3 bg-white rounded-xl border border-slate-300 text-slate-700 placeholder-slate-400 font-medium leading-relaxed resize-none
                                               focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 hover:border-blue-400 transition-all duration-200">{{ old('address', $school->address ?? '') }}</textarea>
                                    </div>
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-1 flex items-center gap-2">
                                    <i class="bi bi-save2-fill"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- RIWAYAT 5 FOTO TERAKHIR --}}
                @if (!empty($school->photo_history) && count($school->photo_history) > 0)
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 md:p-8 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 flex-wrap gap-2">
                            <div>
                                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                    <i class="bi bi-images text-blue-600"></i> Riwayat 5 Foto Terakhir
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Klik tombol "Gunakan" jika Anda ingin beralih kembali ke foto yang pernah diunggah sebelumnya.
                                </p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                {{ count($school->photo_history) }}/5 Foto Tersimpan
                            </span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 pt-1">
                            @foreach ($school->photo_history as $index => $historyPath)
                                @php
                                    $isActive = ($school->photo === $historyPath);
                                    $photoUrl = asset('storage/' . $historyPath);
                                @endphp
                                <div class="relative group rounded-2xl overflow-hidden border-2 transition-all p-1.5 flex flex-col justify-between {{ $isActive ? 'border-emerald-500 bg-emerald-50/60 shadow-md ring-2 ring-emerald-200' : 'border-slate-200 bg-slate-50 hover:border-blue-400' }}">
                                    <div class="w-full h-24 rounded-xl overflow-hidden bg-white mb-2 relative">
                                        <img src="{{ $photoUrl }}" alt="Foto Riwayat {{ $index + 1 }}" class="w-full h-full object-cover">
                                        @if ($isActive)
                                            <span class="absolute top-1 left-1 px-1.5 py-0.5 bg-emerald-600 text-white text-[10px] font-bold rounded-md shadow-sm">
                                                Aktif
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1 w-full">
                                        @if (!$isActive)
                                            <form action="{{ route('school.profile.switch_photo') }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="photo_path" value="{{ $historyPath }}">
                                                <button type="submit"
                                                    class="w-full py-1.5 px-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] transition-colors shadow-xs"
                                                    title="Jadikan foto profil aktif saat ini">
                                                    Gunakan
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex-1 py-1 text-center text-[11px] font-extrabold text-emerald-700">
                                                Sedang Aktif
                                            </div>
                                        @endif

                                        <form action="{{ route('school.profile.delete_photo') }}" method="POST"
                                              onsubmit="return confirm('Hapus foto ini dari daftar riwayat?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="photo_path" value="{{ $historyPath }}">
                                            <button type="submit"
                                                class="p-1 px-2 rounded-lg bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 transition-colors text-xs"
                                                title="Hapus foto dari riwayat">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- GANTI PASSWORD --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 md:p-8 sticky top-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xl shadow-sm border border-emerald-200">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Keamanan</h2>
                            <p class="text-xs text-slate-500">Perbarui kata sandi akun.</p>
                        </div>
                    </div>

                    <form action="{{ route('school.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Password
                                    Saat Ini</label>
                                <div class="relative group">
                                    <input type="password" name="current_password" id="current_password" required
                                        class="w-full px-4 py-3 bg-white rounded-xl border border-slate-300 text-slate-700 font-medium
                                           focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 hover:border-emerald-400 transition-all duration-200">
                                    <button type="button" onclick="togglePassword('current_password')"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-emerald-600 transition-colors">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Password
                                    Baru</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-4 py-3 bg-white rounded-xl border border-slate-300 text-slate-700 font-medium
                                           focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 hover:border-emerald-400 transition-all duration-200">
                                    <button type="button" onclick="togglePassword('password')"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-emerald-600 transition-colors">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Ulangi
                                    Password Baru</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        required
                                        class="w-full px-4 py-3 bg-white rounded-xl border border-slate-300 text-slate-700 font-medium
                                           focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 hover:border-emerald-400 transition-all duration-200">
                                    <button type="button" onclick="togglePassword('password_confirmation')"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-emerald-600 transition-colors">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all flex justify-center items-center gap-2 transform hover:-translate-y-0.5">
                                <i class="bi bi-check-lg"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function previewSchoolPhoto(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('schoolPhotoPreview');
                    if (preview) preview.src = e.target.result;

                    const label = document.getElementById('schoolPhotoLabel');
                    if (label) {
                        label.innerText = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB) - Siap Disimpan';
                        label.classList.add('text-blue-600');
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = input.nextElementSibling.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
@endsection
