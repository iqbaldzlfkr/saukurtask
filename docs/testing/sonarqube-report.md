# Laporan Hasil Pemeriksaan SonarQube — Saukur Task

- **Tanggal Analisis:** 7 Oktober 2026
- **Alat:** SonarQube Community Edition (LTS 9.9) via Docker
- **Scanner:** SonarScanner CLI 8.1
- **Project Key:** `saukurtask`
- **Dashboard URL:** [http://localhost:9000/dashboard?id=saukurtask](http://localhost:9000/dashboard?id=saukurtask)

---

## 1. Status Ringkasan (Executive Summary)

| Metrik | Nilai | Status / Rating | Keterangan |
|---|---|:---:|---|
| **Quality Gate** | **PASSED (OK)** | ✅ Lulus | Memenuhi seluruh syarat kelulusan pemeriksaan |
| **Vulnerabilities** | **0** | **Rating A** ✅ | Tidak ada kerentanan keamanan kritis |
| **Security Hotspots** | **2 (Low Risk)** | ℹ️ Reviewed | Penggunaan script CDN Lucide Icon (saran SRI hash) |
| **Bugs** | **65 (Minor)** | ℹ️ S2003 | Rekomendasi penggunaan `require_once` dibanding `require` pada View template |
| **Code Smells** | **215** | **Rating A** ✅ | Debt perbaikan minor (style, typing, dan template view) |
| **Duplications** | **3.0%** | ✅ Low (< 5%) | Duplikasi baris sangat rendah dan terkontrol |
| **Lines of Code (NCLOC)** | **2,394 baris** | - | Kode bersih tanpa framework eksternal |

---

## 2. Rincian Temuan & Penjelasan Teknis

### A. Vulnerabilities: 0 (A Rating)
Tidak ditemukan celah keamanan seperti:
- **SQL Injection:** Dicegah 100% menggunakan PDO Prepared Statements dengan parameterized queries di seluruh Repository.
- **Cross-Site Scripting (XSS):** Dicegah melalui sanitasi konsisten fungsi `htmlspecialchars()` pada lapisan View.
- **Session Security:** Dilindungi oleh `session_regenerate_id(true)` saat proses autentikasi berhasil.

### B. Security Hotspots (2 Temuan - Low)
- **Lokasi:** `views/auth/login.php` (baris 350) & `views/layouts/footer.php` (baris 27)
- **Pesan:** *Make sure not using resource integrity feature is safe here.*
- **Analisis:** Menggunakan library eksternal Lucide Icons dari CDN (`https://unpkg.com/lucide@latest`).
- **Rekomendasi / Solusi:** Menambahkan atribut Subresource Integrity (`integrity="..." crossorigin="anonymous"`) atau men-download file icon secara lokal di folder `public/assets/`.

### C. Bugs Minor (php:S2003 - Require vs Require_once)
- **Pesan:** *Replace "require" with "require_once".*
- **Analisis:** Pola MVC PHP native menggunakan `require` di dalam controller untuk me-render view file agar variabel scope controller dapat diteruskan ke layout view. SonarQube mengklasifikasikan `require` sebagai saran perbaikan minor (S2003).

---

## 3. Cara Menjalankan Ulang Scan Lokal

Jalankan skrip yang telah disediakan:
```bash
./scripts/run-sonar.sh
```

Atau manual:
```bash
# 1. Pastikan SonarQube container berjalan
docker start sonarqube 2>/dev/null || docker run -d --name sonarqube -p 9000:9000 sonarqube:lts-community

# 2. Jalankan scanner
docker run --rm \
  -v "$(pwd):/usr/src" \
  sonarsource/sonar-scanner-cli \
  -Dsonar.host.url="http://host.docker.internal:9000" \
  -Dsonar.login="squ_4e70ee77636ea9822b8aae859c27628fa5eaf7af"
```
