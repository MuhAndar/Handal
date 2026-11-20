<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Survei Siswa HANDAL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="min-h-full flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl p-8 text-center shadow-xl border border-slate-100">
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
            <i class="bi bi-check-lg"></i>
        </div>

        <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full mb-3 border border-emerald-200">
            Survei Berhasil Dikirim
        </span>

        <h1 class="text-2xl font-black text-slate-800 mb-2">Terima Kasih Banyak!</h1>
        <p class="text-slate-500 text-sm leading-relaxed mb-6">
            Partisipasi kamu telah berhasil terekam dan langsung berkontribusi terhadap indeks kesiapan digital <span class="font-bold text-slate-700">{{ $school->name }}</span>.
        </p>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-500 mb-6">
            <i class="bi bi-shield-lock-fill text-blue-600 mr-1"></i>
            Data survei dihitung secara otomatis dan dirahasiakan tanpa mencatat data pribadi sensitif.
        </div>

        <a href="/"
            class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm transition-all shadow-md">
            <i class="bi bi-house-door-fill"></i> Kembali ke Beranda
        </a>
    </div>

</body>
</html>
