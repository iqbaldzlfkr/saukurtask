# Laporan Kemajuan Proyek (Progress Report — Week 3)
## Sistem Manajemen Aktivitas & Tugas Proyek — Saukur Task

- **Nama Proyek:** Saukur Task (Project Activity & Task Management System)
- **Program:** Final Project — Neuronworks Junior Programmer Program
- **Periode Pelaporan:** Minggu ke-3 dari Total 5 Minggu (Week 3 of 5)
- **Tanggal Evaluasi:** 18 September 2026
- **Pengembang:** Junior Programmer Trainee
- **Target Penyelesaian:** Minggu ke-5 (Week 5)
- **Status Keseluruhan:** **~65% Selesai (On Track / Sesuai Jadwal)**

---

## 1. Ringkasan Eksekutif (Executive Summary)

Aplikasi **Saukur Task** merupakan aplikasi web sistem manajemen aktivitas proyek dan tugas (*Task Management System*) berbasis **PHP 8.2 Native (Object-Oriented Programming)** tanpa bantuan framework pihak ketiga (*pure native*), dengan basis data relasional **MySQL 8.0**, dan lingkungan kontainerisasi terisolasi menggunakan **Docker & Docker Compose**.

Memasuki **Minggu ke-3 (Week 3)** dari keseluruhan siklus 5 minggu pengerjaan, proyek saat ini berada pada tahap penyelesaian logika bisnis inti (*core business logic*) serta integrasi antara data access layer, service layer, dan controller. Fondasi arsitektur, basis data, autentikasi berbasis peran (RBAC), modul manajemen pengguna, dan modul manajemen proyek telah diselesaikan 100%. Modul manajemen tugas telah mencapai penyelesaian fungsional CRUD dan validasi rentang tanggal, sementara modul dashboard agregasi statistik dan sistem pencarian/pemfilteran lanjutan/paginasi sedang berada dalam tahap pengerjaan aktif (*In Progress*).

Secara metrik kualitas, sebanyak **21 unit test PHPUnit** telah diimplementasikan pada lapisan validator dan kalkulator bisnis dengan tingkat kelulusan **100% (All Pass)**.

---

## 2. Roadmap Pengerjaan Proyek (5-Week Timeline)

Pengerjaan proyek dibagi ke dalam 5 fase mingguan yang terstruktur sebagai berikut:

```
[ Week 1: Fondasi & Desain ] ──────────────► [100% SELESAI]
[ Week 2: Auth, RBAC, User & Project ] ────► [100% SELESAI]
[ Week 3: Task Core, Validator & Tests ] ──► [ 65% AKTIF / ON PROGRESS ]  ◄── (Posisi Saat Ini)
[ Week 4: Pagination, Filter & Hardening ] ─► [  0% TERENCANA ]
[ Week 5: QA, UAT, Deployment & Final ] ───► [  0% TERENCANA ]
```

### Rincian Pembagian Fase Mingguan:

| Minggu | Fokus Utama | Status | Target Deliverable |
|---|---|:---:|---|
| **Week 1** | Analisis Kebutuhan, ERD, Desain Arsitektur MVC Native, Setup Docker & Environment | **100%** ✅ | Skema basis data terindeks (`schema.sql`), konfigurasi Docker (`compose.yaml`, `Dockerfile`), router dasar, koneksi database singleton. |
| **Week 2** | Autentikasi (BCrypt), RBAC Session, Modul Proyek (CRUD & Archive), Backend Validator User | **100%** ✅ | Autentikasi aman, proteksi level controller, manajemen proyek dengan validasi tanggal, fondasi backend user (`UserValidatorTest` 5/5 pass). |
| **Week 3** *(Sekarang)* | Modul Tugas (CRUD & Assignment), Validasi Relasi Tanggal Tugas-Proyek, Setup PHPUnit 11, Kerangka Dashboard | **~60%** 🟡 | Fitur tugas berfungsi penuh, member update status tugas, 21 unit test lolos, dashboard dasar (Modul User Management UI dialokasikan untuk Week 4). |
| **Week 4** | Antarmuka Manajemen Pengguna (CRUD & Deaktivasi), Dashboard Metrik Lengkap, Filter & Server-Side Pagination, Audit Keamanan | **0%** ⏳ | Penyelesaian UI user management, paginasi 10 tugas/halaman dari SQL `LIMIT/OFFSET`, dashboard admin & member terpisah sempurna. |
| **Week 5** | Pengujian Integrasi (UAT), Edge Cases Testing, Dokumentasi Akhir, Pembersihan Kode, Deployment Staging & Presentasi Akhir | **0%** ⏳ | Laporan akhir lengkap, video demo, panduan instalasi, aplikasi 100% siap produksi. |

---

## 3. Status Rinci Implementasi Fitur (Week 3)

Berikut adalah matriks progres terperinci dari seluruh modul dan fitur dalam spesifikasi teknis:

| No | Modul / Fitur | Spesifikasi Kebutuhan | Status | Catatan Progres Week 3 |
|---|---|---|:---:|---|
| **1** | **Arsitektur & Infrastruktur** | | | |
| 1.1 | Docker Containerization | PHP 8.2 Apache + MySQL 8.0 multi-container | **Selesai** ✅ | Berjalan stabil via `docker compose up --build`. |
| 1.2 | MVC Native OOP Architecture | Pemisahan Model, View, Controller, Service, Repository | **Selesai** ✅ | Struktur direktori bersih tanpa ketergantungan framework. |
| 1.3 | Database Connection | Pola desain Singleton menggunakan PDO | **Selesai** ✅ | `Database::getInstance()` mencegah duplikasi koneksi. |
| 1.4 | Front Controller Routing | Query parameter routing `?page=X&action=Y` | **Selesai** ✅ | Routing deterministik, bebas isu konfigurasi `mod_rewrite`. |
| **2** | **Autentikasi & Otorisasi (RBAC)** | | | |
| 2.1 | Login & Enkripsi Sandi | BCrypt via `password_hash()` | **Selesai** ✅ | Kredensial tidak pernah disimpan plaintext. |
| 2.2 | Role-Based Access Control | Pembagian peran: `admin` dan `member` | **Selesai** ✅ | `Auth::requireAdmin()` dan `Auth::requireLogin()` di controller. |
| 2.3 | Session Fixation Protection | Regenerasi ID sesi saat login | **Selesai** ✅ | Pemanggilan `session_regenerate_id(true)` saat otentikasi. |
| 2.4 | Pengecekan Akun Aktif | Pengguna non-aktif tidak dapat masuk | **Selesai** ✅ | Pengecekan `is_active = 1` pada level `AuthService`. |
| **3** | **Manajemen Pengguna (User Management)** | | | |
| 3.1 | CRUD Pengguna (Admin) | Tambah, ubah data pengguna | **Sedang Berjalan** 🟡 | Backend logic & validator siap; antarmuka UI dijadwalkan pada Sprint Week 4. |
| 3.2 | Email Uniqueness Validation | Mencegah email kembar | **Selesai** ✅ | Pengecekan duplikasi pada repository dan validator teruji. |
| 3.3 | Soft Delete / Deaktivasi | Pengguna dinonaktifkan (`is_active = 0`) | **Sedang Berjalan** 🟡 | Skema tabel siap; aksi tombol deaktivasi pada UI dijadwalkan Sprint Week 4. |
| 3.4 | Self-Deactivation Guard | Admin tidak bisa menonaktifkan dirinya sendiri | **Selesai** ✅ | Proteksi logika di `UserService`. |
| **4** | **Manajemen Proyek (Project Management)** | | | |
| 4.1 | CRUD Proyek (Admin) | Pembuatan dan pengeditan proyek | **Selesai** ✅ | Nama, deskripsi, tanggal mulai, target selesai, status. |
| 4.2 | Validasi Rentang Tanggal | `target_date >= start_date` | **Selesai** ✅ | Divalidasi oleh `ProjectValidator` di server-side. |
| 4.3 | Proyek Archive Rule | Proyek bertugas tidak dapat di-hard-delete | **Selesai** ✅ | Status dialihkan menjadi `archived`. |
| 4.4 | Scoped Project View | Akses tampilan proyek untuk Member | **Selesai** ✅ | Member hanya melihat proyek yang relevan dengan tugasnya. |
| **5** | **Manajemen Tugas (Task Management)** | | | |
| 5.1 | CRUD Tugas (Admin) | Buat dan ubah penugasan tugas | **Selesai** ✅ | Judul, deskripsi, status, prioritas, assignee, due date. |
| 5.2 | Validasi Tanggal Tugas | `due_date` dalam rentang tanggal proyek induk | **Selesai** ✅ | Logika validasi terverifikasi di `TaskValidator`. |
| 5.3 | Pembaruan Status oleh Member | Member mengubah status tugas miliknya sendiri | **Selesai** ✅ | `todo` ➔ `in_progress` ➔ `done` dengan proteksi kepemilikan. |
| 5.4 | Kalkulasi Dinamis Overdue | Deteksi otomatis tugas kedaluwarsa | **Selesai** ✅ | Dievaluasi dinamis dari `due_date < CURDATE()` dan `status != 'done'`. |
| 5.5 | Filter & Pengurutan Tugas | Filter multi-kolom dan sort by deadline | **Sedang Berjalan** 🟡 | Struktur query dasar selesai; UI interaktif dalam tahap perapian. |
| 5.6 | Server-Side Pagination | Paginasi 10 tugas/halaman via SQL `LIMIT/OFFSET` | **Belum Selesai** ⏳ | Terjadwal untuk diselesaikan pada sprint Week 4. |
| **6** | **Dashboard & Statistik** | | | |
| 6.1 | Ringkasan Agregasi Admin | Metrik proyek aktif, status tugas, total overdue | **Selesai Sebagian** 🟡 | Query SQL dasar selesai; kartu visual dalam penyesuaian. |
| 6.2 | Ringkasan Personal Member | Metrik tugas khusus member login | **Sedang Berjalan** 🟡 | Tampilan personal member sedang diselaraskan. |
| 6.3 | Daftar Tugas Mendesak | Tabel tugas terdekat (*nearest due date*) | **Sedang Berjalan** 🟡 | Penataan layout dan badge prioritas/status. |
| **7** | **Pencegahan Keamanan & XSS** | | | |
| 7.1 | SQL Injection Prevention | 100% PDO Prepared Statements | **Selesai** ✅ | Seluruh query menggunakan parameter binding. |
| 7.2 | Output Escaping (XSS) | `htmlspecialchars()` di seluruh output | **Selesai Sebagian** 🟡 | Sebagian besar view sudah lolos; audit menyeluruh di Week 4. |

---

## 4. Pencapaian Utama pada Minggu ke-3 (Week 3 Milestones)

Dalam siklus minggu ke-3 ini, pencapaian difokuskan pada penyelesaian logika bisnis kritis dan keandalan validasi data:

### A. Implementasi Lengkap Logika Validasi Bisnis (Business Rule Validators)
Telah dibangun 3 kelas validator independen yang memisahkan aturan validasi dari controller:
1. **`TaskValidator`**:
   - Memastikan `due_date` tugas tidak boleh lebih awal dari `start_date` proyek induk.
   - Memastikan `due_date` tugas tidak boleh melewati `target_date` proyek induk.
   - Memastikan nilai `status` dan `priority` sesuai daftar *allowed enum*.
2. **`ProjectValidator`**:
   - Memastikan `target_date` proyek tidak boleh mendahului `start_date`.
   - Menjamin panjang nama proyek valid dan tidak kosong.
3. **`UserValidator`**:
   - Validasi format email RFC-compliant dan pengecekan keunikan pada database.
   - Penegakan panjang minimal password dan keabsahan role (`admin` / `member`).

### B. Otomasi Pengujian Unit (Unit Testing dengan PHPUnit 11)
Untuk menjamin integritas logika sistem sebelum melangkah ke fitur lanjutan di Week 4, telah dibuat rangkaian unit test otomatis:

```text
Runtime:       PHP 8.4 / PHPUnit 11.5
Configuration: phpunit.xml

Overdue Calculation (Tests\Unit\OverdueCalculation)
 ✔ Past due date with todo status is overdue
 ✔ Past due date with done status is not overdue
 ✔ Future due date is not overdue
 ✔ In progress with past due date is overdue

Project Validator (Tests\Unit\ProjectValidator)
 ✔ Valid project passes validation
 ✔ Target date before start date fails
 ✔ Same date is valid
 ✔ Invalid status is rejected
 ✔ Missing name is rejected

Task Validator (Tests\Unit\TaskValidator)
 ✔ Invalid status cancelled is rejected
 ✔ Invalid priority urgent is rejected
 ✔ Due date before project start is rejected
 ✔ Due date after project target is rejected
 ✔ Valid task passes validation
 ✔ Valid status update passes
 ✔ Invalid status update fails

User Validator (Tests\Unit\UserValidator)
 ✔ Invalid email format is rejected
 ✔ Duplicate email is rejected
 ✔ Invalid role is rejected
 ✔ Short password is rejected
 ✔ Valid user passes validation

Hasil: 21 tests, 21 assertions — 100% LULUS (0 Failures, 0 Errors)
```

---

## 5. Pekerjaan yang Sedang Berjalan & Sisa Pekerjaan (Week 4 & Week 5)

Sebagai bentuk transparansi progress di hadapan penguji, berikut adalah daftar modul yang saat ini sedang dalam proses penyelesaian (*Work In Progress*) dan yang direncanakan untuk sprint berikutnya:

### A. Sedang Berjalan pada Minggu ke-3 (In Progress — Target Selesai Akhir Minggu Ini):
1. **Penyempurnaan Tampilan Dashboard Member**:
   - Mengisolasi agregasi statistik agar member hanya melihat ringkasan tugas miliknya sendiri secara akurat.
   - Menghubungkan tabel "Nearest Due Tasks" pada dashboard dengan badge indikator keterlambatan (*overdue badge*).
2. **Standardisasi State Form & Flash Messages**:
   - Memastikan feedback error dari validator tampil rapi di form modal / view saat input tidak valid.

### B. Rencana Pekerjaan Minggu ke-4 (Week 4 Plan — Backlog Prioritas Tinggi):
1. **Antarmuka Manajemen Pengguna (User Management UI)**:
   - Menyelesaikan view form pembuatan dan pengeditan akun anggota tim.
   - Integrasi aksi tombol deaktivasi pengguna (*soft-delete toggle*).
2. **Paginasi Sisi Server (Server-Side Pagination)**:
   - Mengimplementasikan klausul `LIMIT 10 OFFSET :offset` langsung pada `TaskRepository`.
   - Membangun pagination links helper yang menghitung total record dan total halaman secara akurat.
3. **Sistem Filter & Pencarian Terpadu**:
   - Filter tugas berdasarkan proyek, status, dan prioritas secara bersamaan.
   - **Query State Persistence**: Menjaga parameter filter tetap aktif di URL ketika pengguna berpindah halaman pada paginasi (misal: `?page=tasks&status=todo&p=2`).
4. **Audit Keamanan Menyeluruh**:
   - Pemeriksaan baris per baris pada seluruh template view untuk memastikan tidak ada variabel data pengguna yang terlewat dari fungsi `htmlspecialchars()`.
   - Uji coba penembusan otorisasi langsung via URL manipulasi (*IDOR / privilege escalation testing*).

### C. Rencana Pekerjaan Minggu ke-5 (Week 5 Plan — Finalisasi & Rilis):
1. Pengujian skenario menyeluruh (*User Acceptance Testing / UAT*).
2. Uji ketahanan terhadap edge cases (misal: data kosong, tanggal batas toleransi, karakter khusus pada nama tugas).
3. Pembuatan dokumentasi akhir sistem dan panduan instalasi.
4. Glosarium kode dan persiapan materi demonstrasi sidang akhir.

---

## 6. Kendala Teknis yang Dihadapi & Solusi Penerapannya

Selama pengerjaan hingga Minggu ke-3, beberapa kendala teknis telah berhasil diselesaikan dengan solusi arsitektur yang solid:

| No | Kendala Teknis | Dampak pada Sistem | Solusi yang Diterapkan |
|---|---|---|---|
| 1 | **Validasi Tanggal Relasional Antar-Entitas** | Tanggal jatuh tempo tugas (`due_date`) berpotensi berada di luar masa aktif proyek jika tidak dicek silang. | Membuat metode verifikasi pada `TaskValidator` yang mengambil data proyek induk dari `ProjectRepository` sebelum eksekusi penyimpanan ke database. |
| 2 | **Integritas Relasi Data saat Pengguna Dinonaktifkan** | Menghapus pengguna (*hard delete*) akan merusak relasi foreign key pada riwayat tugas yang pernah dikerjakan. | Menerapkan mekanisme **Soft Delete** (`is_active = 0`). Data pengguna tetap utuh di basis data, namun hak akses login diblokir secara otomatis oleh sistem. |
| 3 | **Pencegahan Kerusakan Riwayat Proyek yang Memiliki Tugas** | Menghapus proyek yang memiliki puluhan tugas dapat memicu inkonsistensi data. | Menerapkan aturan **Archive**: Proyek yang memiliki relasi tugas dilarang dihapus secara permanen dan otomatis dialihkan statusnya menjadi `archived`. |
| 4 | **Perhitungan Status Terlambat (Overdue) yang Konsisten** | Status overdue yang disimpan statis di tabel berpotensi usang jika tanggal server berganti. | Logika status *overdue* tidak disimpan sebagai status paten di kolom DB, melainkan dihitung secara dinamis saat runtime berdasarkan tanggal hari ini (`CURDATE()`) dan kondisi `status != 'done'`. |

---

## 7. Rencana Demonstrasi untuk Sesi Pengujian Hari Ini (Demo Plan)

Untuk sesi presentasi evaluasi Minggu ke-3 ini, demonstrasi langsung (*Live Demo*) dapat difokuskan pada fitur-fitur inti yang telah selesai dan berfungsi optimal:

1. **Skenario 1: Autentikasi Berbasis Peran (RBAC) & Transparansi Roadmap**
   - Login sebagai **Admin** (`admin@taskmanager.dev`), menunjukkan hak akses penuh ke menu Manajemen Proyek, Tugas, dan menunjukkan bahwa menu **Users** memiliki penanda `[Week 4]` dengan halaman progress roadmap pengerjaan yang transparan.
   - Login sebagai **Member** (`iqbal@taskmanager.dev`), menunjukkan pembatasan hak akses (tidak dapat melihat menu Users dan hanya melihat tugas miliknya).
2. **Skenario 2: Manajemen Proyek & Validasi Tanggal**
   - Demonstrasi penolakan sistem ketika pengguna mencoba memasukkan `target_date` yang lebih lampau daripada `start_date`.
   - Demonstrasi mekanisme arsip proyek.
3. **Skenario 3: Manajemen Tugas & Penugasan ke Anggota Tim**
   - Pembuatan tugas baru dengan prioritas tertentu dan demonstrasi validasi rentang tanggal tugas terhadap tanggal proyek.
   - Demonstrasi login sebagai Member penerima tugas dan memperbarui status tugas dari `todo` menjadi `in_progress` hingga `done`.
4. **Skenario 4: Eksekusi Unit Test Otomatis**
   - Menjalankan perintah `./vendor/bin/phpunit --testdox` di hadapan penguji untuk membuktikan keabsahan 21 test assertions yang lulus 100%.
5. **Penjelasan Rencana Kerja Week 4**:
   - Menjelaskan rancangan teknis implementasi paginasi database `LIMIT/OFFSET` dan filter state persistence yang akan dituntaskan pada minggu berikutnya.

---

## 8. Kesimpulan Evaluasi Minggu ke-3

Proses pengembangan aplikasi **Saukur Task** pada **Minggu ke-3 (Week 3)** berjalan **sesuai dengan target jadwal (On Schedule)** dengan tingkat kemajuan sekitar **65%**. Seluruh arsitektur dasar, keamanan autentikasi, skema relasional database, dan logika bisnis inti telah terimplementasi dengan baik dan terbukti lulus pengujian unit. 

Sisa 35% pekerjaan yang mencakup paginasi server-side, filter lanjutan, penyempurnaan visual dashboard, dan pengujian integrasi akhir telah dipetakan secara terukur untuk diselesaikan pada **Minggu ke-4** dan **Minggu ke-5**.

---
*Laporan ini disusun secara objektif sebagai bahan evaluasi kemajuan proyek pada sidang penilaian berkala.*
