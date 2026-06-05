# Buzzer Monitor - Sistem Deteksi & Analisis Aktivitas Buzzer YouTube

Sistem berbasis web yang dirancang untuk melakukan *scraping* data komentar dari YouTube secara *real-time*, melakukan analisis sentimen, serta mendeteksi indikasi aktivitas mencurigakan yang mengarah pada pola gerakan *buzzer*. 

Proyek ini dikembangkan sebagai bagian dari laporan pelaksanaan **Kerja Praktik (KP)**.

---

## 🚀 Fitur Utama

- **Operations Center (Monitoring Tasks)**: Dashboard manajemen antrean crawling untuk memantau status pengambilan data (*Harvesting* / *Stored*).
- **Real-Time Data Scraper**: Mengambil ribuan data komentar dari tautan (URL) video YouTube target dengan cepat.
- **Analisis Sentimen Terintegrasi**: Memproses isi teks komentar untuk mengklasifikasikan respons publik (Positif, Negatif, Netral).
- **Deteksi Aktivitas Mencurigakan**: Identifikasi akun-akun yang terindikasi bertindak sebagai *buzzer* berdasarkan pola volume data, frekuensi, dan kesamaan teks komentar.
- **Bulk Operations**: Fitur pengelolaan antrean yang efisien dengan kemampuan menghapus banyak *task* sekaligus (*Bulk Delete*).

---

## 🛠️ Tech Stack (Teknologi yang Digunakan)

### Backend & Frontend Framework
- **Laravel (v10.x / v11.x)** - Framework PHP utama untuk logika bisnis dan routing.
- **Tailwind CSS** - Untuk perancangan UI/UX Dashboard yang modern, responsif, dan bernuansa gelap (*Dark Mode UI*).
- **JavaScript (Vanilla)** - Menangani interaktivitas komponen seperti seleksi massal (*Bulk Checkbox*) dan *Asynchronous Request*.

### Data & Intelligence Layer
- **Python (Scraper Engine & NLP)** - Digunakan untuk memproses penambangan data (*crawling*) API YouTube dan menjalankan model klasifikasi teks / analisis sentimen.
- **MySQL / PostgreSQL** - Penyimpanan relasional untuk manajemen *queue*, data *tasks*, dan hasil ekstraksi komentar.

---

## 📂 Struktur Repositori Utama

```text
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── TaskController.php   # Logika kontroler untuk monitoring, create, dan bulk destroy
│   └── Models/
│       └── Task.php                 # Model representasi data antrean task scraper
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php        # Layout utama aplikasi web
│       └── tasks/
│           ├── index.blade.php      # Halaman utama Monitoring (Operations Center)
│           └── create.blade.php     # Halaman input URL / Scraper baru
├── routes/
│   └── web.php                      # Definisi rute web aplikasi
└── python_scraper/                  # Script engine Python untuk crawling data YouTube & Analisis
⚙️ Cara Instalasi dan Menjalankan Proyek
Prasyarat (Prerequisites)
Pastikan komputer Anda sudah terinstal:

PHP (>= 8.1)

Composer

Node.js & NPM

Python (>= 3.9)

MySQL / MariaDB

Langkah-Langkah Setup
Clone Repositori

Bash
git clone [https://github.com/ammar3544/kerja-praktik.git](https://github.com/ammar3544/kerja-praktik.git)
cd kerja-praktik
Instalasi Dependensi PHP & JavaScript

Bash
composer install
npm install && npm run dev
Konfigurasi Environment (.env)
Salin file .env.example menjadi .env dan sesuaikan pengaturan database serta API Key YouTube Anda.

Bash
cp .env.example .env
php artisan key:generate
Migrasi Database

Bash
php artisan migrate
Instalasi Dependensi Scraper Python
Masuk ke folder scraper (jika terpisah) atau instal library Python yang dibutuhkan di sistem Anda:

Bash
pip install requests pandas nltk scikit-learn
Jalankan Server Lokal

Bash
php artisan serve
Aplikasi kini dapat diakses melalui browser di alamat http://127.0.0.1:8000.

📝 Catatan Pengembangan & Kontribusi
Proyek ini dibangun untuk tujuan akademik dan analisis data media sosial. Jika Anda ingin melakukan modifikasi atau berkontribusi:

Lakukan Fork pada repositori ini.

Buat Branch fitur baru (git checkout -b fitur-baru).

Commit perubahan Anda (git commit -m 'Menambah fitur analisis grafik').

Push ke branch tersebut (git push origin fitur-baru).

Buat Pull Request.

🧑‍💻 Pengembang
Ammar Siraj Ananda - Informatics Student ```
