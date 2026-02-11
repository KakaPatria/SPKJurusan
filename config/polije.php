<?php

return [
    // Standar kriteria per jurusan dengan bobot dan probabilitas kecocokan
    // Dioptimalkan untuk Polije (Vocational Campus)
    // Weights: nilai, minat, pref, prestasi, cita_cita
    // Preference: Praktik Langsung, DuDi, Project Based, Blended

    'criteria' => [
        'Produksi Pertanian' => [
            'nilai' => 'Sedang',
            'minat' => 'Alam & Tanaman',
            'pref' => ['Praktik Langsung', 'DuDi', 'Project Based'],
            'cita_cita_keywords' => ['pertanian', 'petani', 'kebun', 'sawah', 'panen', 'tanaman'],
            'skills_required' => ['Observasi', 'Kerja Lapangan', 'Pemeliharaan Tanaman'],
            'weights' => ['nilai' => 0.40, 'minat' => 0.35, 'pref' => 0.15, 'prestasi' => 0.05, 'cita_cita' => 0.05],
            'match_prob' => ['nilai' => 0.80, 'minat' => 0.90, 'pref' => 0.85, 'prestasi' => 0.65, 'cita_cita' => 0.85],
        ],
        'Teknologi Pertanian' => [
            'nilai' => 'Tinggi',
            'minat' => 'Alam & Tanaman',
            'pref' => ['Praktik Langsung', 'Project Based', 'DuDi'],
            'cita_cita_keywords' => ['teknologi', 'inovasi', 'otomasi', 'mesin pertanian'],
            'skills_required' => ['Problem Solving', 'Teknologi', 'Inovasi'],
            'weights' => ['nilai' => 0.50, 'minat' => 0.25, 'pref' => 0.15, 'prestasi' => 0.05, 'cita_cita' => 0.05],
            'match_prob' => ['nilai' => 0.85, 'minat' => 0.90, 'pref' => 0.85, 'prestasi' => 0.75, 'cita_cita' => 0.80],
        ],
        'Peternakan' => [
            'nilai' => 'Sedang',
            'minat' => 'Alam & Tanaman',
            'pref' => ['Praktik Langsung', 'DuDi', 'Project Based'],
            'cita_cita_keywords' => ['ternak', 'hewan', 'peternakan', 'peeternak', 'sapi', 'ayam', 'unggas'],
            'skills_required' => ['Perawatan Hewan', 'Kesabaran', 'Manajemen'],
            'weights' => ['nilai' => 0.40, 'minat' => 0.40, 'pref' => 0.10, 'prestasi' => 0.05, 'cita_cita' => 0.05],
            'match_prob' => ['nilai' => 0.80, 'minat' => 0.88, 'pref' => 0.80, 'prestasi' => 0.65, 'cita_cita' => 0.88],
        ],
        'Manajemen Agribisnis' => [
            'nilai' => 'Sedang',
            'minat' => 'Manajemen & Bisnis',
            'pref' => ['Project Based', 'DuDi', 'Blended'],
            'cita_cita_keywords' => ['bisnis', 'agribisnis', 'usaha', 'entrepreneur', 'pengusaha'],
            'skills_required' => ['Manajemen', 'Bisnis Acumen', 'Komunikasi'],
            'weights' => ['nilai' => 0.35, 'minat' => 0.40, 'pref' => 0.15, 'prestasi' => 0.05, 'cita_cita' => 0.05],
            'match_prob' => ['nilai' => 0.75, 'minat' => 0.90, 'pref' => 0.80, 'prestasi' => 0.70, 'cita_cita' => 0.85],
        ],
        'Teknologi Informasi' => [
            'nilai' => 'Tinggi',
            'minat' => 'Logika & Komputer',
            'pref' => ['Praktik Langsung', 'Project Based', 'DuDi'],
            'cita_cita_keywords' => ['programmer', 'developer', 'coding', 'software', 'web developer', 'hacker', 'it'],
            'skills_required' => ['Coding', 'Problem Solving', 'Logika'],
            'weights' => ['nilai' => 0.45, 'minat' => 0.35, 'pref' => 0.12, 'prestasi' => 0.05, 'cita_cita' => 0.03],
            'match_prob' => ['nilai' => 0.90, 'minat' => 0.92, 'pref' => 0.85, 'prestasi' => 0.75, 'cita_cita' => 0.85],
        ],
        'Teknik' => [
            'nilai' => 'Sedang',
            'minat' => 'Mesin & Listrik',
            'pref' => ['Praktik Langsung', 'DuDi', 'Project Based'],
            'cita_cita_keywords' => ['mesin', 'bengkel', 'teknisi', 'listrik', 'elektronik', 'automasi', 'instalasi', 'panel'],
            'skills_required' => ['Mekanik', 'Elektrik', 'Teknik', 'Presisi'],
            'weights' => ['nilai' => 0.42, 'minat' => 0.38, 'pref' => 0.12, 'prestasi' => 0.05, 'cita_cita' => 0.03],
            'match_prob' => ['nilai' => 0.82, 'minat' => 0.90, 'pref' => 0.85, 'prestasi' => 0.71, 'cita_cita' => 0.85],
        ],
        'Kesehatan' => [
            'nilai' => 'Tinggi',
            'minat' => 'Pelayanan & Kesehatan',
            'pref' => ['Praktik Langsung', 'DuDi', 'Project Based'],
            'cita_cita_keywords' => ['dokter', 'perawat', 'medis', 'gizi', 'kesehatan', 'pelayanan', 'terapis'],
            'skills_required' => ['Komunikasi', 'Empati', 'Presisi Medis'],
            'weights' => ['nilai' => 0.45, 'minat' => 0.35, 'pref' => 0.10, 'prestasi' => 0.05, 'cita_cita' => 0.05],
            'match_prob' => ['nilai' => 0.90, 'minat' => 0.90, 'pref' => 0.80, 'prestasi' => 0.75, 'cita_cita' => 0.90],
        ],
        'Bahasa, Komunikasi, dan Pariwisata' => [
            'nilai' => 'Sedang',
            'minat' => 'Umum',
            'pref' => ['Project Based', 'DuDi', 'Praktik Langsung'],
            'cita_cita_keywords' => ['tour guide', 'pariwisata', 'bahasa', 'komunikasi', 'jurnalis', 'marketing'],
            'skills_required' => ['Komunikasi', 'Bahasa', 'Kepribadian'],
            'weights' => ['nilai' => 0.30, 'minat' => 0.40, 'pref' => 0.15, 'prestasi' => 0.08, 'cita_cita' => 0.07],
            'match_prob' => ['nilai' => 0.70, 'minat' => 0.85, 'pref' => 0.80, 'prestasi' => 0.70, 'cita_cita' => 0.85],
        ],
        'Bisnis' => [
            'nilai' => 'Sedang',
            'minat' => 'Manajemen & Bisnis',
            'pref' => ['Project Based', 'DuDi', 'Blended'],
            'cita_cita_keywords' => ['manager', 'pimpinan', 'bisnis', 'accounting', 'marketing', 'sales'],
            'skills_required' => ['Manajemen', 'Leadership', 'Keuangan'],
            'weights' => ['nilai' => 0.35, 'minat' => 0.40, 'pref' => 0.15, 'prestasi' => 0.05, 'cita_cita' => 0.05],
            'match_prob' => ['nilai' => 0.75, 'minat' => 0.90, 'pref' => 0.80, 'prestasi' => 0.70, 'cita_cita' => 0.85],
        ],
    ],

    // Vocational Learning Preference Mapping
    'pref_mapping' => [
        'Praktik Langsung' => ['weight' => 1.0, 'score' => 0.95],
        'DuDi' => ['weight' => 0.95, 'score' => 0.90],
        'Project Based' => ['weight' => 0.90, 'score' => 0.85],
        'Blended' => ['weight' => 0.80, 'score' => 0.75],
    ],

    // Category mapping untuk nilai
    'nilai_category' => [
        'Tinggi' => ['min' => 85, 'max' => 100],
        'Sedang' => ['min' => 70, 'max' => 84],
        'Rendah' => ['min' => 0, 'max' => 69],
    ],
];
