# INPUT VALIDATION DETAIL: Minat, Cita-Cita, & Prestasi

## 📋 Ringkasan Improvements

Sistem scoring telah di-upgrade untuk memastikan **setiap input detail benar-benar diperhatikan**:

✅ Improved minat mapping (coverage-based scoring)  
✅ New cita-cita categorization (6 karir categories)  
✅ Enhanced validation untuk semua 3 field  
✅ Detailed explanations dengan input specifics  
✅ Audit logging untuk setiap processing step  

---

## 🔍 MINAT - Input Processing Detail

### Validation Rules:
```php
'minat' => 'required|string|min:3|max:255'
```
- ✅ **Required**: Harus diisi
- ✅ **Min 3 characters**: Minimal 3 huruf (tidak boleh terlalu pendek)
- ✅ **Max 255 characters**: Maksimal 255 huruf

### Processing Pipeline:
```
Raw Input: "saya senang coding dan web development"
    ↓
Trim & Lowercase: "saya senang coding dan web development"
    ↓
Normalize Text: "saya senang coding dan web development"
    (mengganti programmer→programming, developer→development, dll)
    ↓
Coverage-Based Mapping:
  - 'Logika & Komputer': 3 matches (coding, web, development) / 6 = 50% ✅ BEST
  - 'Alam & Tanaman': 0 matches / 6 = 0%
  - 'Pelayanan & Kesehatan': 0 matches / 6 = 0%
  - 'Manajemen & Bisnis': 0 matches / 6 = 0%
  - 'Mesin & Listrik': 0 matches / 6 = 0%
    ↓
Result: 'Logika & Komputer' (highest coverage)
    ↓
Explanation: "✅ Minat Anda (Logika & Komputer) sangat sesuai dan cocok 
             dengan fokus kurikulum Teknologi Informasi. Anda akan 
             mempelajari hal-hal yang Anda sukai."
```

### Keyword Coverage Calculation:
```php
Keywords untuk 'Logika & Komputer':
- coding ✅ (found in "saya senang coding dan web development")
- komputer (not found)
- laptop (not found)
- web ✅ (found)
- aplikasi (not found)
- logika (not found)
- programming ✅ (found after normalization)
- software (not found)
- development (not found - but 'development' matched)
- developer (not found)
- it (not found)
- data (not found)
- ai (not found)

Matched: 3 (coding, web, development)
Denominator: min(13 keywords, 6) = 6
Coverage: 3/6 = 0.50 (50%)
```

### 5 Kategori Minat:
1. **Logika & Komputer**
   - Keywords: coding, programming, komputer, software, web, development, data, ai, dll
   
2. **Alam & Tanaman**
   - Keywords: tanam, kebun, sawah, hewan, ternak, pertanian, agribisnis, hortikultura, dll
   
3. **Pelayanan & Kesehatan**
   - Keywords: kesehatan, medis, gizi, perawat, dokter, rumah sakit, klinik, farmasi, dll
   
4. **Manajemen & Bisnis**
   - Keywords: bisnis, usaha, marketing, keuangan, manajemen, akuntansi, entrepreneur, akuntan, dll
   
5. **Mesin & Listrik**
   - Keywords: mesin, listrik, teknik, otomasi, elektronik, bengkel, las, motor, maintenance, dll

---

## 🎯 CITA-CITA - Input Processing Detail

### Validation Rules:
```php
'cita_cita' => 'required|string|min:3|max:255'
```
- ✅ **Required**: Harus diisi
- ✅ **Min 3 characters**: Minimal 3 huruf
- ✅ **Max 255 characters**: Maksimal 255 huruf

### Processing Pipeline:
```
Raw Input: "menjadi web developer profesional yang sukses"
    ↓
Trim & Lowercase: "menjadi web developer profesional yang sukses"
    ↓
Normalize Text: "menjadi web development professional yang sukses"
    (developer→development, professional→profesional via normalization)
    ↓
Career Category Mapping (6 categories):
  - 'IT & Software': 2 matches (web, development) / 6 = 33% ✅ BEST
  - 'Agriculture': 0 matches / 6 = 0%
  - 'Healthcare': 0 matches / 6 = 0%
  - 'Business': 0 matches / 6 = 0%
  - 'Engineering': 0 matches / 6 = 0%
  - 'Communication': 0 matches / 6 = 0%
    ↓
Result: 'IT & Software' (highest coverage)
    ↓
Keyword Scoring untuk setiap jurusan:
  Teknologi Informasi keywords: 
    ['programmer', 'developer', 'software', 'coding', 'hacker', 'web', 'database', 'it', 'engineer']
  
  Text coverage: "menjadi web development professional yang sukses"
  Matched keywords: web, development (2/9 = 22%)
    ↓
Explanation: "✅ Cita-cita karir Anda sangat sesuai dan aligned dengan 
             standar lulusan Teknologi Informasi. Jurusan ini secara 
             langsung mempersiapkan Anda untuk mencapai cita-cita tersebut."
```

### 6 Kategori Karir untuk Cita-Cita:
1. **IT & Software**
   - Keywords: programmer, developer, software, coding, hacker, web, database, it, engineer
   
2. **Agriculture**
   - Keywords: petani, pertanian, agribisnis, kebun, ternak, peternak, agronomi
   
3. **Healthcare**
   - Keywords: dokter, perawat, medis, gizi, terapis, farmasi, kesehatan
   
4. **Business**
   - Keywords: entrepreneur, manager, marketing, sales, akuntan, keuangan, bisnis
   
5. **Engineering**
   - Keywords: teknik, engineer, mesin, listrik, bengkel, maintenance, industri
   
6. **Communication**
   - Keywords: jurnalis, komunikator, presenter, content, pariwisata, hospitality

---

## 🏆 PRESTASI - Input Processing Detail

### Validation Rules:
```php
'prestasi' => 'nullable|string|min:3|max:255'
```
- ✅ **Optional**: Boleh kosong
- ✅ **Min 3 characters** (jika diisi): Minimal 3 huruf
- ✅ **Max 255 characters**: Maksimal 255 huruf

### Processing Pipeline:
```
Raw Input: "juara 1 kompetisi coding kabupaten"
    ↓
Trim & Lowercase: "juara 1 kompetisi coding kabupaten"
    ↓
Analyze Prestasi Level:
  - Check for 'juara|menang|champion|first|gold|emas|terbaik' 
    → FOUND 'juara'
    → Level: TINGGI (0.90)
    
  OR:
  - Check for 'finalis|semifinal|peringkat|ranking|podium|medali|silver|perak'
    → Level: SEDANG (0.75)
    
  OR:
  - Check for 'sertifikat|training|kursus|workshop|peserta|mengikuti'
    → Level: CUKUP (0.60)
    
  ELSE:
    → Level: MINIMAL (0.30)
    ↓
Result:
{
  'provided': true,
  'level': 'tinggi',
  'score': 0.90,
  'raw': 'juara 1 kompetisi coding kabupaten'
}
    ↓
Keyword Scoring untuk setiap jurusan:
  Teknologi Informasi cita_cita_keywords:
    ['programmer', 'developer', 'software', 'coding', 'hacker', 'it', 'database', 'network']
  
  Text coverage: "juara 1 kompetisi coding kabupaten"
  Matched keywords: coding (1/8 = 12.5%)
  
  Combined score = (75% base) + (25% relevance)
                 = 0.75 * 0.90 + 0.25 * 0.125
                 = 0.68 + 0.03 = 0.71 (COCOK!)
    ↓
Explanation: "✅ Prestasi Anda (TINGGI): 'juara 1 kompetisi coding kabupaten' 
             sangat relevan dengan Teknologi Informasi. Ini menunjukkan Anda 
             memiliki dedication dan capability."
```

### Prestasi Level Categories:
1. **TINGGI (0.90 score)**
   - Keywords: juara, menang, champion, first, gold, emas, terbaik
   
2. **SEDANG (0.75 score)**
   - Keywords: finalis, semifinal, peringkat, ranking, podium, medali, silver, perak
   
3. **CUKUP (0.60 score)**
   - Keywords: sertifikat, training, kursus, workshop, peserta, mengikuti
   
4. **MINIMAL (0.30 score)**
   - Tidak match kategori apapun (default)

---

## 📊 Scoring Impact (Bobot Relatif)

### Without Prestasi (prestasi kosong):
```
Original Weights:
- Nilai: 40%
- Minat: 35%
- Preferensi: 15%
- Cita-cita: 5%
- Prestasi: 5%
Total: 100%

After Normalization (prestasi removed):
- Nilai: 40% / 95% = 42.1%
- Minat: 35% / 95% = 36.8%
- Preferensi: 15% / 95% = 15.8%
- Cita-cita: 5% / 95% = 5.3%
Total: 100%
```

### With Prestasi (prestasi diisi):
```
Weights (unchanged):
- Nilai: 40%
- Minat: 35%
- Preferensi: 15%
- Cita-cita: 5%
- Prestasi: 5%
Total: 100%
```

---

## ✅ Validation Checklist

Untuk setiap scoring request, sistem memastikan:

| Field | Validation | Impact |
|-------|-----------|--------|
| **Minat** | Min 3 char, max 255 | Reject jika < 3 char |
| **Cita-cita** | Min 3 char, max 255 | Reject jika < 3 char |
| **Prestasi** | Min 3 char (opsional) | Skip scoring jika kosong |
| **Preferensi** | Valid enum values | Reject jika invalid |

---

## 🔐 Error Handling

### Validation Errors (HTTP 422):
```
{
  "success": false,
  "message": "Minat harus diisi dengan minimal 3 karakter untuk analisis yang akurat"
}
```

### Specific Messages:
- ❌ Minat < 3 char → "Minat terlalu pendek, jelaskan lebih detail"
- ❌ Cita-cita < 3 char → "Cita-cita terlalu pendek, jelaskan lebih detail"
- ❌ Prestasi < 3 char → "Prestasi terlalu pendek, jelaskan lebih detail"
- ❌ Preferensi invalid → "Preferensi studi tidak valid"

---

## 📈 Audit Logging

Setiap scoring request di-log dengan detail:

```php
Log::debug('Minat Analysis', [
    'input' => 'saya senang coding dan web development',
    'normalized' => 'saya senang coding dan web development',
    'mapped' => 'Logika & Komputer',
]);

Log::debug('Cita-cita Analysis', [
    'input' => 'menjadi web developer profesional',
    'normalized' => 'menjadi web development professional',
    'mapped' => 'IT & Software',
]);

Log::debug('Prestasi Analysis', [
    'input' => 'juara 1 kompetisi coding',
    'is_filled' => true,
    'normalized' => 'juara 1 kompetisi coding',
    'level' => 'tinggi',
    'score' => 0.90,
]);

Log::debug('Keyword Coverage', [
    'text' => 'menjadi web development professional',
    'keywords_count' => 9,
    'coverage' => 0.22,
    'match_prob' => 0.85,
]);
```

---

## 🎯 Kesimpulan

Sistem sekarang:
✅ **Benar-benar memperhatikan** setiap detail input minat, cita-cita, prestasi
✅ **Validate input** dengan ketat (min/max length, enum values)
✅ **Process systematically** dengan coverage-based scoring
✅ **Map ke kategori** untuk consistency dan accuracy
✅ **Score dengan keywords** yang relevan
✅ **Generate explanations** dengan mention spesifik input user
✅ **Log setiap step** untuk audit trail

**Hasilnya: Input yang sama → Output yang sama, konsisten 100%** ✅
