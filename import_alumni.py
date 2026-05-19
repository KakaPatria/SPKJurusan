#!/usr/bin/env python3
"""
Script untuk Import Data Alumni dari Excel ke Database SPK Jurusan Polije
Membaca file Excel dan memasukkan ke tabel alumni
"""

import pandas as pd
import sys
import os
from pathlib import Path

# Add Laravel project to path
project_path = Path(__file__).parent
sys.path.insert(0, str(project_path))

# Import database connection
import subprocess
import json

def read_excel_file(file_path):
    """
    Membaca file Excel dan return dataframe
    """
    try:
        print(f"📂 Membaca file: {file_path}")
        df = pd.read_excel(file_path)
        print(f"✓ File berhasil dibaca")
        print(f"  Baris: {len(df)}")
        print(f"  Kolom: {list(df.columns)}")
        return df
    except Exception as e:
        print(f"✗ Error membaca file: {e}")
        return None

def normalize_column_names(df):
    """
    Normalize nama kolom Excel ke format database
    """
    # Mapping kemungkinan nama kolom di Excel
    column_mapping = {
        'nama': 'nama_alumni',
        'nama alumni': 'nama_alumni',
        'nis': 'nis',
        'no. induk siswa': 'nis',
        'kelompok asal': 'kelompok_asal',
        'matematika': 'mtk',
        'mtk': 'mtk',
        'math': 'mtk',
        'fisika': 'fisika',
        'physics': 'fisika',
        'kimia': 'kimia',
        'chemistry': 'kimia',
        'biologi': 'biologi',
        'biology': 'biologi',
        'ekonomi': 'ekonomi',
        'economics': 'ekonomi',
        'geografi': 'geografi',
        'geography': 'geografi',
        'sosiologi': 'sosiologi',
        'sociology': 'sosiologi',
        'sejarah': 'sejarah',
        'history': 'sejarah',
        'minat': 'minat',
        'interest': 'minat',
        'cita cita': 'cita_cita',
        'cita-cita': 'cita_cita',
        'dream job': 'cita_cita',
        'preferensi studi': 'preferensi_studi',
        'preference': 'preferensi_studi',
        'prestasi': 'prestasi',
        'achievement': 'prestasi',
        'jurusan masuk': 'major_masuk',
        'major': 'major_masuk',
        'jurusan': 'major_masuk',
        'tahun lulus': 'tahun_lulus_polije',
        'tahun lulus polije': 'tahun_lulus_polije',
        'graduation year': 'tahun_lulus_polije',
        'catatan': 'catatan',
        'notes': 'catatan',
        'keterangan': 'catatan',
    }
    
    # Normalize column names
    df.columns = [col.lower().strip() for col in df.columns]
    df = df.rename(columns=column_mapping, errors='ignore')
    
    return df

def validate_data(df):
    """
    Validasi data sebelum insert
    """
    print("\n🔍 Validasi Data:")
    
    # Cek kolom penting
    required_cols = ['nama_alumni', 'nis', 'kelompok_asal']
    missing_cols = [col for col in required_cols if col not in df.columns]
    
    if missing_cols:
        print(f"✗ Kolom penting tidak ada: {missing_cols}")
        return False
    
    print(f"✓ Kolom penting ditemukan: {required_cols}")
    
    # Cek null values
    null_counts = df.isnull().sum()
    if null_counts.any():
        print(f"⚠ Null values ditemukan:")
        for col, count in null_counts[null_counts > 0].items():
            print(f"  - {col}: {count} baris")
    
    return True

def generate_insert_script(df):
    """
    Generate Laravel seeder script untuk insert data
    """
    print(f"\n📝 Generate Insert Script...")
    
    # Prepare data
    records = []
    for idx, row in df.iterrows():
        record = {
            'nama_alumni': str(row.get('nama_alumni', '')).strip() or f"Alumni {idx+1}",
            'nis': str(row.get('nis', '')).strip() or None,
            'kelompok_asal': str(row.get('kelompok_asal', 'IPA')).strip(),
            'mtk': float(row.get('mtk', 0)) if pd.notna(row.get('mtk')) else 0,
            'fisika': float(row.get('fisika', 0)) if pd.notna(row.get('fisika')) else 0,
            'kimia': float(row.get('kimia', 0)) if pd.notna(row.get('kimia')) else 0,
            'biologi': float(row.get('biologi', 0)) if pd.notna(row.get('biologi')) else 0,
            'ekonomi': float(row.get('ekonomi', 0)) if pd.notna(row.get('ekonomi')) else 0,
            'geografi': float(row.get('geografi', 0)) if pd.notna(row.get('geografi')) else 0,
            'sosiologi': float(row.get('sosiologi', 0)) if pd.notna(row.get('sosiologi')) else 0,
            'sejarah': float(row.get('sejarah', 0)) if pd.notna(row.get('sejarah')) else 0,
            'minat': str(row.get('minat', 'IPA')).strip(),
            'cita_cita': str(row.get('cita_cita', '')).strip() or 'Profesional',
            'preferensi_studi': str(row.get('preferensi_studi', 'Sains & Teknologi')).strip(),
            'prestasi': str(row.get('prestasi', 'Tidak')).strip(),
            'major_masuk': str(row.get('major_masuk', '')).strip() or None,
            'tahun_lulus_polije': int(row.get('tahun_lulus_polije', 2024)) if pd.notna(row.get('tahun_lulus_polije')) else 2024,
            'catatan': str(row.get('catatan', '')).strip() or None,
        }
        records.append(record)
    
    return records

def create_seeder_file(records):
    """
    Create Laravel Seeder file
    """
    seeder_content = '''<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Console\\Seeds\\WithoutModelEvents;
use Illuminate\\Database\\Seeder;
use App\\Models\\Alumni;

class AlumniImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumni = [
'''
    
    for record in records:
        seeder_content += f'''            [
                'nama_alumni' => '{record['nama_alumni']}',
                'nis' => {f"'{record['nis']}'" if record['nis'] else 'null'},
                'kelompok_asal' => '{record['kelompok_asal']}',
                'mtk' => {record['mtk']},
                'fisika' => {record['fisika']},
                'kimia' => {record['kimia']},
                'biologi' => {record['biologi']},
                'ekonomi' => {record['ekonomi']},
                'geografi' => {record['geografi']},
                'sosiologi' => {record['sosiologi']},
                'sejarah' => {record['sejarah']},
                'minat' => '{record['minat']}',
                'cita_cita' => '{record['cita_cita']}',
                'preferensi_studi' => '{record['preferensi_studi']}',
                'prestasi' => '{record['prestasi']}',
                'major_masuk' => {f"'{record['major_masuk']}'" if record['major_masuk'] else 'null'},
                'tahun_lulus_polije' => {record['tahun_lulus_polije']},
                'catatan' => {f"'{record['catatan']}'" if record['catatan'] else 'null'},
            ],
'''
    
    seeder_content += '''        ];

        foreach ($alumni as $data) {
            Alumni::create($data);
        }
    }
}
'''
    
    # Write to file
    seeder_file = Path(__file__).parent / 'database' / 'seeders' / 'AlumniImportSeeder.php'
    seeder_file.parent.mkdir(parents=True, exist_ok=True)
    
    with open(seeder_file, 'w', encoding='utf-8') as f:
        f.write(seeder_content)
    
    print(f"✓ Seeder file created: {seeder_file}")
    return str(seeder_file)

def main():
    """
    Main function
    """
    print("=" * 60)
    print("IMPORT DATA ALUMNI KE DATABASE SPK JURUSAN POLIJE")
    print("=" * 60)
    
    # Find Excel file
    excel_files = list(Path(__file__).parent.glob('*.xlsx')) + \
                  list(Path(__file__).parent.glob('DATA *.xlsx')) + \
                  list(Path(__file__).parent.glob('*TAMATAN*.xlsx'))
    
    if not excel_files:
        print("✗ File Excel tidak ditemukan di folder project")
        print("  Cari file dengan nama: DATA RESAPAN TAMATAN.xlsx")
        return False
    
    excel_file = excel_files[0]
    print(f"📄 File ditemukan: {excel_file.name}\n")
    
    # Read Excel
    df = read_excel_file(str(excel_file))
    if df is None:
        return False
    
    # Display first few rows
    print("\n📊 Preview Data (5 baris pertama):")
    print(df.head().to_string())
    
    # Normalize columns
    df = normalize_column_names(df)
    print(f"\n✓ Kolom di-normalize")
    
    # Validate
    if not validate_data(df):
        return False
    
    # Generate records
    records = generate_insert_script(df)
    print(f"✓ {len(records)} records siap untuk di-insert")
    
    # Show sample
    print(f"\n📋 Sample Record (pertama):")
    print(json.dumps(records[0], indent=2, default=str))
    
    # Create seeder
    seeder_file = create_seeder_file(records)
    
    print("\n" + "=" * 60)
    print("✓ IMPORT DATA SIAP!")
    print("=" * 60)
    print("\n📝 Cara menggunakan Seeder:")
    print("1. Jalankan command: php artisan db:seed --class=AlumniImportSeeder")
    print("2. Atau jalankan tanpa argument untuk seed semua: php artisan db:seed")
    print("\n")
    
    return True

if __name__ == '__main__':
    success = main()
    sys.exit(0 if success else 1)
