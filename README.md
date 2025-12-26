# TaskMeetIF1A2
Website Pengelolaan Rapat Berbasis Web  
Politeknik Negeri Batam

## Deskripsi Proyek
TaskMeet adalah aplikasi berbasis web yang dikembangkan untuk membantu proses pengelolaan rapat di lingkungan Politeknik Negeri Batam. Aplikasi ini bertujuan untuk mempermudah administrasi rapat secara terstruktur dan terintegrasi, mulai dari pengelolaan peserta, penjadwalan rapat, hingga pencatatan absensi.

## Tujuan Pengembangan
Tujuan dari pengembangan TaskMeet adalah:
- Mempermudah proses administrasi rapat
- Menyediakan informasi rapat secara terpusat
- Membantu admin dan peserta mengakses informasi rapat dengan cepat
- Mendukung penerapan konsep paperless office

## Fitur Utama
- Registrasi dan login pengguna
- Login khusus admin
- Pengelolaan data peserta oleh admin (view only)
- CRUD agenda rapat (tambah, edit, hapus)
- Pengaturan undangan peserta rapat
- Absensi peserta setelah rapat selesai
- Dashboard admin dan peserta
- Riwayat rapat dan persentase kehadiran
- Pengelolaan profil pengguna

## Struktur Menu
Menu utama pada TaskMeet terdiri dari:
- Beranda
- Fitur
- Tentang
- Kontak
- Login

## Alur Penggunaan Sistem

### 1. Landing Page
Pengguna dapat melihat informasi umum aplikasi melalui menu Beranda, Fitur, Tentang, dan Kontak. Untuk mulai menggunakan sistem, pengguna dapat memilih tombol **Mulai Sekarang**.

### 2. Registrasi dan Login
Pengguna baru diwajibkan melakukan registrasi dengan mengisi data:
- Email
- Nama pengguna
- Kata sandi dan konfirmasi kata sandi
- Nama lengkap
- Jurusan
- Program studi

Setelah registrasi berhasil, pengguna dapat login menggunakan akun yang telah dibuat.  
Admin login menggunakan akun khusus yang telah disediakan oleh sistem.

### 3. Dashboard Admin
Admin memiliki akses ke beberapa menu utama, yaitu:
- Dashboard rapat
- Daftar peserta
- Daftar agenda rapat

Pada menu Daftar Peserta, admin dapat melihat seluruh peserta yang telah melakukan registrasi. Data peserta akan otomatis masuk ke sistem dan hanya dapat dilihat oleh admin.

### 4. Pengelolaan Agenda Rapat
Admin dapat:
- Menambahkan agenda rapat
- Mengatur judul rapat, jurusan, tanggal, waktu, tempat atau platform, host, dan peserta
- Mengedit agenda rapat
- Menghapus agenda rapat
- Melakukan absensi peserta setelah rapat selesai

Agenda rapat yang dibuat akan ditampilkan pada dashboard admin.

### 5. Dashboard dan Detail Rapat Peserta
Peserta hanya dapat melihat rapat yang diundang oleh admin. Pada dashboard peserta, sistem menampilkan:
- Rapat yang akan datang
- Rapat yang sedang berlangsung
- Riwayat rapat

Pada halaman detail rapat, peserta dapat melihat informasi rapat dan hasil absensi, termasuk persentase kehadiran.

### 6. Profil Pengguna
Pengguna dapat mengelola profil pribadi, meliputi:
- Mengubah foto profil
- Mengubah nama pengguna
- Mengubah nama lengkap
- Mengubah email
- Mengubah kata sandi

Jurusan dan program studi tidak dapat diubah untuk menjaga konsistensi data akademik.

## Batasan Sistem
- Sistem tidak menyediakan fitur notulen rapat atau unggah dokumen
- Tidak terdapat fitur video conference atau notifikasi otomatis
- Hak akses hanya terdiri dari admin dan peserta
- Website digunakan untuk keperluan internal Politeknik Negeri Batam

## Penutup
TaskMeet dikembangkan sebagai solusi pengelolaan rapat berbasis web yang terintegrasi. Sistem ini diharapkan dapat meningkatkan efisiensi, kerapian, dan kemudahan dalam pengelolaan rapat bagi admin maupun peserta.
