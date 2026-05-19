<?php

return [
    // Standar kriteria per jurusan dengan bobot dan probabilitas kecocokan
    // Dioptimalkan untuk Polije (Vocational Campus)
    // Weights: nilai, minat, pref, prestasi, cita_cita
    // Preference diselaraskan dengan input form rekomendasi:
    // Sains & Teknologi, Pertanian & Lingkungan, Kesehatan & Ilmu Hayat,
    // Bisnis & Manajemen, Sosial & Humaniora

    'criteria' => [
        'Produksi Pertanian' => [
            'nilai' => 'Sedang',
            'minat' => 'Alam & Tanaman',
            'pref' => ['Pertanian & Lingkungan'],
            'cita_cita_keywords' => ['pertanian', 'petani', 'kebun', 'sawah', 'panen', 'tanaman', 'agronomi', 'perkebunan', 'hortikultura', 'farmer', 'agronomist', 'cultivation'],
            'skills_required' => ['Observasi', 'Kerja Lapangan', 'Pemeliharaan Tanaman'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.80, 'minat' => 0.90, 'pref' => 0.85, 'prestasi' => 0.65, 'cita_cita' => 0.85],
        ],
        'Teknologi Pertanian' => [
            'nilai' => 'Tinggi',
            'minat' => 'Alam & Tanaman',
            'pref' => ['Sains & Teknologi', 'Pertanian & Lingkungan'],
            'cita_cita_keywords' => ['teknologi', 'inovasi', 'otomasi', 'mesin pertanian', 'smart farming', 'teknologi pangan', 'agritech', 'agricultural engineer'],
            'skills_required' => ['Problem Solving', 'Teknologi', 'Inovasi'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.85, 'minat' => 0.90, 'pref' => 0.85, 'prestasi' => 0.75, 'cita_cita' => 0.80],
        ],
        'Peternakan' => [
            'nilai' => 'Sedang',
            'minat' => 'Alam & Tanaman',
            'pref' => ['Pertanian & Lingkungan', 'Kesehatan & Ilmu Hayat'],
            'cita_cita_keywords' => ['ternak', 'hewan', 'peternakan', 'peternak', 'sapi', 'ayam', 'unggas', 'veteriner', 'farm', 'livestock', 'husbandry', 'zootechnist'],
            'skills_required' => ['Perawatan Hewan', 'Kesabaran', 'Manajemen'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.80, 'minat' => 0.88, 'pref' => 0.80, 'prestasi' => 0.65, 'cita_cita' => 0.88],
        ],
        'Manajemen Agribisnis' => [
            'nilai' => 'Sedang',
            'minat' => 'Manajemen & Bisnis',
            'pref' => ['Bisnis & Manajemen', 'Pertanian & Lingkungan'],
            'cita_cita_keywords' => ['bisnis', 'agribisnis', 'usaha', 'entrepreneur', 'pengusaha', 'manajer', 'marketing', 'wirausaha', 'director', 'executive', 'manager'],
            'skills_required' => ['Manajemen', 'Bisnis Acumen', 'Komunikasi'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.75, 'minat' => 0.90, 'pref' => 0.80, 'prestasi' => 0.70, 'cita_cita' => 0.85],
        ],
        'Teknologi Informasi' => [
            'nilai' => 'Tinggi',
            'minat' => 'Logika & Komputer',
            'pref' => ['Sains & Teknologi'],
            'cita_cita_keywords' => ['programmer', 'developer', 'coding', 'software', 'web developer', 'hacker', 'it', 'data analyst', 'ai engineer', 'mobile developer', 'devops', 'cloud engineer', 'scientist', 'architect'],
            'skills_required' => ['Coding', 'Problem Solving', 'Logika'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.90, 'minat' => 0.92, 'pref' => 0.85, 'prestasi' => 0.75, 'cita_cita' => 0.85],
        ],
        'Teknik' => [
            'nilai' => 'Sedang',
            'minat' => 'Mesin & Listrik',
            'pref' => ['Sains & Teknologi'],
            'cita_cita_keywords' => ['mesin', 'bengkel', 'teknisi', 'listrik', 'elektronik', 'otomasi', 'instalasi', 'panel', 'mekatronika', 'maintenance', 'engineer', 'technician', 'supervisor'],
            'skills_required' => ['Mekanik', 'Elektrik', 'Teknik', 'Presisi'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.82, 'minat' => 0.90, 'pref' => 0.85, 'prestasi' => 0.71, 'cita_cita' => 0.85],
        ],
        'Kesehatan' => [
            'nilai' => 'Tinggi',
            'minat' => 'Pelayanan & Kesehatan',
            'pref' => ['Kesehatan & Ilmu Hayat'],
            'cita_cita_keywords' => ['dokter', 'perawat', 'medis', 'gizi', 'kesehatan', 'pelayanan', 'terapis', 'farmasi', 'rekam medis', 'kesehatan masyarakat', 'nurse', 'pharmacist', 'nutritionist', 'clinician'],
            'skills_required' => ['Komunikasi', 'Empati', 'Presisi Medis'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.90, 'minat' => 0.90, 'pref' => 0.80, 'prestasi' => 0.75, 'cita_cita' => 0.90],
        ],
        'Bahasa, Komunikasi, dan Pariwisata' => [
            'nilai' => 'Sedang',
            'minat' => 'Umum',
            'pref' => ['Sosial & Humaniora', 'Bisnis & Manajemen'],
            'cita_cita_keywords' => ['tour guide', 'pariwisata', 'bahasa', 'komunikasi', 'jurnalis', 'marketing', 'public relation', 'content creator', 'hospitality', 'ambassador', 'presenter', 'broadcaster'],
            'skills_required' => ['Komunikasi', 'Bahasa', 'Kepribadian'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.70, 'minat' => 0.85, 'pref' => 0.80, 'prestasi' => 0.70, 'cita_cita' => 0.85],
        ],
        'Bisnis' => [
            'nilai' => 'Sedang',
            'minat' => 'Manajemen & Bisnis',
            'pref' => ['Bisnis & Manajemen'],
            'cita_cita_keywords' => ['manager', 'pimpinan', 'bisnis', 'accounting', 'marketing', 'sales', 'akuntan', 'keuangan', 'bank', 'finance', 'accountant', 'auditor'],
            'skills_required' => ['Manajemen', 'Leadership', 'Keuangan'],
            'weights' => ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040],
            'match_prob' => ['nilai' => 0.75, 'minat' => 0.90, 'pref' => 0.80, 'prestasi' => 0.70, 'cita_cita' => 0.85],
        ],
    ],

    // Mapping preferensi studi sesuai opsi input form rekomendasi
    'pref_mapping' => [
        'Sains & Teknologi' => ['weight' => 1.0, 'score' => 0.95],
        'Pertanian & Lingkungan' => ['weight' => 0.95, 'score' => 0.90],
        'Kesehatan & Ilmu Hayat' => ['weight' => 0.95, 'score' => 0.90],
        'Bisnis & Manajemen' => ['weight' => 0.90, 'score' => 0.85],
        'Sosial & Humaniora' => ['weight' => 0.85, 'score' => 0.80],
    ],

    // Category mapping untuk nilai
    'nilai_category' => [
        'Tinggi' => ['min' => 85, 'max' => 100],
        'Sedang' => ['min' => 70, 'max' => 84],
        'Rendah' => ['min' => 0, 'max' => 69],
    ],
];
