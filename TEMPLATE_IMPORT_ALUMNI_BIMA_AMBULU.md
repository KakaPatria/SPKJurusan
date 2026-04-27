# 📋 TEMPLATE IMPORT ALUMNI - SMA BIMA AMBULU

## Struktur File Excel yang Diperlukan

**File**: ALUMNI_BIMA_AMBULU_[TAHUN].xlsx

### Sheet 1: "Alumni Data"

| Kolom | Tipe | Contoh | Validasi | Wajib? |
|-------|------|--------|----------|--------|
| A | Nama Alumni | Budi Santoso | Text (max 255 char) | ✅ |
| B | NIS | 12345678 | Angka 8 digit | ✅ |
| C | Kelompok Asal | IPA | Dropdown: IPA / IPS | ✅ |
| D | Tahun Masuk | 2024 | Tahun (4 digit) | ✅ |
| E | MTK (Matematika) | 88 | 0-100 | ✅ |
| F | Fisika | 85 | 0-100 | ⭕ |
| G | Kimia | 90 | 0-100 | ⭕ |
| H | Biologi | 92 | 0-100 | ⭕ |
| I | Ekonomi | 75 | 0-100 | ⭕ |
| J | Geografi | 70 | 0-100 | ⭕ |
| K | Sosiologi | 72 | 0-100 | ⭕ |
| L | Sejarah | 78 | 0-100 | ⭕ |
| M | Nilai Rata-Rata | 82.5 | 0-100 | ⭕ |
| N | Minat | Teknologi, AI | Text | ⭕ |
| O | Cita-Cita | Software Engineer | Text | ⭕ |
| P | Preferensi Studi | Project Based | Dropdown | ⭕ |
| Q | Prestasi | Juara LKS Provinsi | Text | ⭕ |
| R | Jurusan Masuk | Teknologi Informasi | Dropdown (9 jurusan) | ✅ |
| S | Ranking (1-9) | 1 | 1-9 | ⭕ |
| T | Predicted Score | 89.3 | 0-100 | ⭕ |
| U | Tahun Lulus | 2027 | 4 digit | ⭕ |
| V | IPK Lulus | 3.78 | 0-4 | ⭕ |
| W | Karir Outcome | Backend Developer di PT XYZ | Text | ⭕ |
| X | Success Status | Sangat Sukses | Dropdown | ⭕ |
| Y | Catatan | Rekomendasi akurat | Text | ⭕ |

---

## Keterangan:
- ✅ = Wajib diisi
- ⭕ = Opsional tapi disarankan
- Untuk kolom nilai akademik: Isi sesuai jurusan SMA (IPA/IPS)

---

## Contoh Data Baris 1 (IPA):

```
Nama Alumni: Budi Santoso
NIS: 12345678
Kelompok Asal: IPA
Tahun Masuk: 2024
MTK: 88
Fisika: 85
Kimia: 90
Biologi: 92
Ekonomi: 75
Geografi: 70
Sosiologi: 72
Sejarah: 78
Nilai Rata-Rata: 82.5
Minat: Coding, AI, IoT
Cita-Cita: Menjadi Software Engineer
Preferensi Studi: Project Based
Prestasi: Juara LKS Tingkat Provinsi 2024
Jurusan Masuk: Teknologi Informasi
Ranking: 1
Predicted Score: 89.3
Tahun Lulus: 2027
IPK Lulus: 3.78
Karir Outcome: Bekerja di Jago Software sebagai Backend Developer
Success Status: Sangat Sukses
Catatan: Rekomendasi sangat akurat, IPK memuaskan
```

---

## Contoh Data Baris 2 (IPS):

```
Nama Alumni: Siti Rahmawati
NIS: 87654321
Kelompok Asal: IPS
Tahun Masuk: 2024
MTK: 82
Fisika: 70
Kimia: 72
Biologi: 75
Ekonomi: 90
Geografi: 88
Sosiologi: 85
Sejarah: 87
Nilai Rata-Rata: 81.25
Minat: Bisnis, Manajemen
Cita-Cita: Jadi Manajer Marketing
Preferensi Studi: DuDi
Prestasi: Ketua OSIS, Juara Debat
Jurusan Masuk: Manajemen Agribisnis
Ranking: 2
Predicted Score: 85.6
Tahun Lulus: 2027
IPK Lulus: 3.55
Karir Outcome: Kerja di Perusahaan Agro Trader
Success Status: Sukses
Catatan: Ranking 2 tapi sesuai preferensi
```

---

## Format File:

**File Format**: `.xlsx` (Microsoft Excel 2007+)  
**Encoding**: UTF-8  
**Delimiter**: N/A (Excel native format)  
**Sheet Name**: "Alumni Data"  
**Header Row**: Baris 1 (nama kolom)  
**Data Rows**: Mulai baris 2  

---

## Panduan Pengisian Per Kelompok:

### Kelompok IPA - Prioritas Nilai:
1. **MTK** - Wajib (paling penting)
2. Fisika
3. Kimia
4. Biologi
5. (Ekonomi, Geografi, Sosiologi, Sejarah - opsional)

### Kelompok IPS - Prioritas Nilai:
1. **MTK** - Wajib
2. Ekonomi
3. Geografi
4. Sosiologi
5. Sejarah
6. (Fisika, Kimia, Biologi - opsional)

---

## Dropdown Options (STANDARDIZED):

### Kelompok Asal:
- IPA
- IPS

### Preferensi Studi:
- Praktik Langsung
- DuDi
- Project Based
- Blended Learning

### Jurusan Masuk:
1. Teknologi Informasi
2. Teknik
3. Kesehatan
4. Bisnis
5. Peternakan
6. Produksi Pertanian
7. Teknologi Pertanian
8. Manajemen Agribisnis
9. Bahasa, Komunikasi, dan Pariwisata

### Success Status:
- Sangat Sukses
- Sukses
- Cukup
- Kurang Sukses

---

## ⚠️ Validasi Checklist:

Sebelum submit file, pastikan:

- [ ] Semua baris memiliki Nama Alumni
- [ ] Semua NIS tidak duplikat
- [ ] Nilai akademik dalam range 0-100
- [ ] Tidak ada baris kosong di tengah
- [ ] Format tahun: 4 digit (2024, bukan 24)
- [ ] Format IPK: desimal 0-4 (3.78, bukan 378)
- [ ] Kelompok Asal hanya IPA atau IPS
- [ ] Jurusan Masuk sesuai dengan 9 jurusan Polije
- [ ] Preferensi Studi sesuai pilihan dropdown (jika ada)
- [ ] Success Status sesuai pilihan (jika ada)
- [ ] Tidak ada karakter spesial di nama (hanya alfanumerik, spasi, tanda hubung)

---

## 📧 Cara Submit:

1. **Isi file Excel** sesuai template
2. **Simpan sebagai**: `ALUMNI_BIMA_AMBULU_[TAHUN].xlsx`
3. **Kirim ke**: Admin Polije
4. **Atau upload melalui**: Admin Panel > Alumni Import

---

## 📞 Support:

Jika ada pertanyaan tentang format atau kolom, hubungi administrator sistem.
