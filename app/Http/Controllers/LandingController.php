<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    /**
     * Data umum sekolah dan peta presisi (digunakan bersama oleh Landing V1 & V2).
     */
    private function getLandingData()
    {
        $rankings = Cache::remember('school_rankings', 3600, function () {
            return School::orderBy('current_score', 'desc')->get();
        });

        // Hitung statistik ringkas
        $stats = [
            'total_schools'    => School::count(),
            'average_score'    => round(School::where('current_score', '>', 0)->avg('current_score') ?? 0, 1),
            'verified_count'   => School::where('current_score', '>', 0)->count(),
            'total_students'   => School::sum('student_respondents_count') ?? 0,
        ];

        // Koordinat GPS presisi sesuai Google Maps & Referensi Kemendikdasmen
        $mapLocations = $rankings->map(function ($school, $idx) {
            $addr = strtolower($school->address . ' ' . $school->name);

            if (str_contains($addr, 'sman 1') || str_contains($addr, 'bawakaraeng')) {
                // SMAN 1 Makassar: Jl. Gunung Bawakaraeng No. 53, Gaddong, Kec. Bontoala
                $lat = -5.1347; $lng = 119.4185;
                $city = 'Kota Makassar (Bontoala)';
            } elseif (str_contains($addr, 'smkn 2') || str_contains($addr, 'pancasila') || str_contains($addr, 'mannuruki')) {
                // SMKN 2 Makassar: Jl. Pancasila No. 15, Mannuruki, Kec. Tamalate
                $lat = -5.1697; $lng = 119.4299;
                $city = 'Kota Makassar (Tamalate)';
            } elseif (str_contains($addr, 'man 2') || str_contains($addr, 'perintis')) {
                // MAN 2 Model Makassar: Jl. Perintis Kemerdekaan KM. 12, Kec. Tamalanrea
                $lat = -5.1328; $lng = 119.4975;
                $city = 'Kota Makassar (Tamalanrea)';
            } elseif (str_contains($addr, 'gowa')) {
                $lat = -5.2014; $lng = 119.4533;
                $city = 'Kab. Gowa';
            } elseif (str_contains($addr, 'maros')) {
                $lat = -5.0064; $lng = 119.5746;
                $city = 'Kab. Maros';
            } elseif (str_contains($addr, 'parepare') || str_contains($addr, 'pare-pare')) {
                $lat = -4.0150; $lng = 119.6260;
                $city = 'Kota Parepare';
            } elseif (str_contains($addr, 'palopo')) {
                $lat = -2.9926; $lng = 120.1963;
                $city = 'Kota Palopo';
            } elseif (str_contains($addr, 'jakarta')) {
                $lat = -6.2088; $lng = 106.8456;
                $city = 'DKI Jakarta';
            } elseif (str_contains($addr, 'surabaya')) {
                $lat = -7.2575; $lng = 112.7521;
                $city = 'Kota Surabaya';
            } else {
                $baseLats = [-5.1380, -5.1620, -5.1480, -5.1720];
                $baseLngs = [119.4250, 119.4350, 119.4600, 119.4400];
                $pos = $idx % count($baseLats);
                $lat = $baseLats[$pos];
                $lng = $baseLngs[$pos];
                $city = 'Kota Makassar';
            }

            return [
                'id'            => $school->id,
                'name'          => $school->name,
                'jenjang'       => $school->jenjang,
                'npsn'          => $school->npsn,
                'address'       => $school->address,
                'province'      => 'Sulawesi Selatan',
                'city'          => $city,
                'lat'           => $lat,
                'lng'           => $lng,
                'score'         => $school->current_score,
                'self_score'    => $school->self_assessment_score,
                'student_score' => $school->student_survey_score,
                'respondents'   => $school->student_respondents_count,
                'tier'          => $school->rank_label,
                'tier_color'    => $school->rank_color,
            ];
        });

        $regionStats = [
            'Sulawesi Selatan' => [
                'count'      => $rankings->count(),
                'avg_score'  => round($rankings->avg('current_score') ?? 0, 1),
                'center'     => [-5.1477, 119.4327],
                'active'     => true,
            ]
        ];

        return compact('rankings', 'stats', 'mapLocations', 'regionStats');
    }

    /**
     * Halaman Beranda Utama (Layout Klasik/Original dengan Peta Sebaran Interaktif Terintegrasi).
     */
    public function index()
    {
        $data = $this->getLandingData();
        return view('landing', $data);
    }

    /**
     * Halaman Beranda WPR (Versi Sidebar Editorial & Metodologi Lengkap).
     */
    public function indexV2()
    {
        $data = $this->getLandingData();
        return view('landing_v2', $data);
    }

    public function registrationPage()
    {
        return view('pages.registration');
    }

    public function rankingPage()
    {
        $rankings = Cache::remember('school_rankings', 3600, function () {
            return School::orderBy('current_score', 'desc')->get();
        });
        return view('pages.ranking', compact('rankings'));
    }

    public function rankingPageV2()
    {
        $rankings = Cache::remember('school_rankings', 3600, function () {
            return School::orderBy('current_score', 'desc')->get();
        });
        return view('pages.ranking_v2', compact('rankings'));
    }

    public function storeRegistration(Request $request)
    {
        $validated = $request->validate([
            'school_name'    => 'required|string|max:255',
            'jenjang'        => 'required|in:SMA,SMK,MA',
            'province'       => 'required|string',
            'city'           => 'required|string',
            'district'       => 'required|string',
            'village'        => 'required|string',
            'address'        => 'required|string',
            'contact_number' => 'required|numeric',
            'email'          => 'required|email|unique:registrations,email',
            'npsn'           => 'required|numeric|unique:registrations,npsn',
            'assessment_letter' => 'required|file|mimes:pdf|max:2048',
        ], [
            'npsn.unique' => 'NPSN ini sudah terdaftar sebelumnya.',
            'email.unique' => 'Email ini sudah digunakan untuk pendaftaran.',
            'assessment_letter.max' => 'Ukuran file surat asesmen maksimal 2MB.',
            'assessment_letter.mimes' => 'Format file harus PDF.'
        ]);

        if ($request->hasFile('assessment_letter')) {
            $filePath = $request->file('assessment_letter')->store('registration_letters', 'public');
            $validated['assessment_letter'] = $filePath;
        }

        $validated['status'] = 'pending';
        Registration::create($validated);

        return back()->with('success', 'Permohonan akun berhasil dikirim! Silakan tunggu verifikasi validator via email.');
    }
}
