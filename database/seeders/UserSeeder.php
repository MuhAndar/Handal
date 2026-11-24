<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@handal.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Validator
        User::create([
            'name' => 'Validator',
            'email' => 'validator@handal.com',
            'password' => Hash::make('12345678'),
            'role' => 'validator',
            'status' => 'active',
        ]);

        // Akun Sekolah 1 (SMAN 1 Makassar)
        $schoolUser1 = User::create([
            'name' => 'SMAN 1 Makassar',
            'email' => 'school@handal.com',
            'password' => Hash::make('12345678'),
            'role' => 'school',
            'status' => 'active',
        ]);

        $school1 = \App\Models\School::create([
            'user_id' => $schoolUser1->id,
            'name' => 'SMAN 1 Makassar',
            'address' => 'Jl. Gunung Bawakaraeng No. 53, Makassar',
            'jenjang' => 'SMA',
            'npsn' => '40311956',
            'survey_code' => 'sman1mks',
            'is_student_survey_open' => true,
            'self_assessment_score' => 0,
            'student_survey_score' => 0,
            'student_respondents_count' => 0,
            'current_score' => 0,
        ]);

        // Akun Sekolah 2 (SMKN 2 Makassar)
        $schoolUser2 = User::create([
            'name' => 'SMKN 2 Makassar',
            'email' => 'smkn2@handal.com',
            'password' => Hash::make('12345678'),
            'role' => 'school',
            'status' => 'active',
        ]);

        $school2 = \App\Models\School::create([
            'user_id' => $schoolUser2->id,
            'name' => 'SMKN 2 Makassar',
            'address' => 'Jl. Pancasila No. 15, Makassar',
            'jenjang' => 'SMK',
            'npsn' => '40311988',
            'survey_code' => 'smkn2mks',
            'is_student_survey_open' => true,
            'self_assessment_score' => 79.0,
            'student_survey_score' => 76.5,
            'student_respondents_count' => 38,
            'current_score' => round((0.70 * 79.0) + (0.30 * 76.5), 2),
        ]);

        // Akun Sekolah 3 (MAN 2 Model Makassar)
        $schoolUser3 = User::create([
            'name' => 'MAN 2 Model Makassar',
            'email' => 'man2@handal.com',
            'password' => Hash::make('12345678'),
            'role' => 'school',
            'status' => 'active',
        ]);

        $school3 = \App\Models\School::create([
            'user_id' => $schoolUser3->id,
            'name' => 'MAN 2 Model Makassar',
            'address' => 'Jl. Perintis Kemerdekaan KM. 12, Makassar',
            'jenjang' => 'MA',
            'npsn' => '40312015',
            'survey_code' => 'man2mks',
            'is_student_survey_open' => true,
            'self_assessment_score' => 92.0,
            'student_survey_score' => 90.0,
            'student_respondents_count' => 62,
            'current_score' => round((0.70 * 92.0) + (0.30 * 90.0), 2),
        ]);
    }
}