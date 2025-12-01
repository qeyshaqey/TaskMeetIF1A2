<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IF1A2 TaskMeet - Pengelolaan Rapat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f9f9f9;
    }

    header {
      background: linear-gradient(180deg, #784B84, #D7BFDC);
      color: white;
      padding: 20px 0;
      text-align: center;
    }

    nav {
      background-color: #311432;
      text-align: center;
      padding: 10px 0;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .hero {
      background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url(https://p2m.polibatam.ac.id/wp-content/uploads/2020/01/foto-poltek-kompress-Copy-1536x811.jpg) center/cover;
      color: white;
      text-align: center;
      padding: 200px 20px;
    }

    .hero h1 {
      font-size: 35px;
      margin-bottom: 15px;
    }

    .hero p {
      font-size: 18px;
      margin-bottom: 25px;
    }

    .hero a {
      background: #D7BFDC;
      color: black;
      padding: 12px 25px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }

    .hero a:hover {
      background: #784B84;
    }

    .features {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      padding: 40px 20px;
      background: white;
    }

    .feature-box {
      background: #F3E5F5;
      border-radius: 10px;
      padding: 20px;
      margin: 15px;
      width: 280px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .feature-box h3 {
      font-size: medium;
      font-weight: bold;
      color: #311432;
      text-align: center;
    }
    .about-section {
  background-color: #f8f8ff;
  padding: 60px 20px;
  text-align: center;
    }

    .about-section h2 {
  color: #311432;
  font-weight: 700;
  margin-bottom: 20px;
    }

    .about-section p {
  color: #333;
  font-size: 16px;
  line-height: 1.7;
  margin: 10px auto;
  max-width: 800px;
    }
    footer {
      text-align: center;
      padding: 15px;
      background: #784B84;
      color: white;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <header>
    <h1>TaskMeet</h1>
    <p>Platform Pengelolaan Rapat yang Efisien</p>
  </header>

  <nav>
    <a href="#beranda">Beranda</a>
    <a href="#fitur">Fitur</a>
    <a href="#tentang">Tentang</a>
    <a href="#kontak">Kontak</a>
  </nav>

  <section class="hero" id="beranda">
    <h1>Kelola Rapat Lebih Mudah Bersama IF1A2 TaskMeet</h1>
    <p>Atur jadwal dan koordinasikan tim Anda secara online.</p>
    <a href="login.php">Mulai Sekarang</a>
  </section>

  <section class="features" id="fitur">
    <div class="feature-box">
      <h3>📅 Penjadwalan Otomatis</h3>
      <p>Atur jadwal rapat dengan sistem pengingat otomatis dan sinkronisasi kalender.</p>
    </div>
    <div class="feature-box">
      <h3>👥 Manajemen Anggota</h3>
      <p>Tambah, hapus, dan kelola anggota tim dengan akses yang terkontrol.</p>
    </div>
  </section>
  <div class="container my-5" id="tentang">
    <h2 class="text-center mb-4 fw-bold">Anggota Tim PBL</h2>
<div class="row">
    <div class="col-md-3 mb-3">
    <div class="card shadow-sm">
        <img src="../admin_side/format_gambar/qeypp.png" class="card-img-top" alt="Foto Ketua">
    <div class="card-body text-center">
        <h5 class="card-title fw-bold">Ketua Kelompok</h5>
        <p class="card-text">Kontribusi: Homepage dan Dashboard Pengguna.</p>
    <a href="#"></a>
</div></div> </div>
    <div class="col-md-3 mb-3">
    <div class="card shadow-sm">
        <img src="../admin_side/format_gambar/rizkapp.png" class="card-img-top" alt="Foto Anggota 1">
    <div class="card-body text-center">
        <h5 class="card-title fw-bold">Anggota 1</h5>
        <p class="card-text">Kontribusi: Wireframe dan Dashboard Admin.</p>
    <a href="#"></a>
</div></div> </div>
    <div class="col-md-3 mb-3">
    <div class="card shadow-sm">
        <img src="../admin_side/format_gambar/iqbalpp.png" class="card-img-top" alt="Foto Anggota 2">
    <div class="card-body text-center">
        <h5 class="card-title fw-bold">Anggota 2</h5>
        <p class="card-text">Kontribusi: Daftar User dan History Pengguna.</p>
    <a href="#"></a>
</div></div> </div>
<div class="col-md-3 mb-3">
    <div class="card shadow-sm">
        <img src="../admin_side/format_gambar/nabilapp.png" class="card-img-top" alt="Foto Anggota 3">
    <div class="card-body text-center">
        <h5 class="card-title fw-bold">Anggota 3</h5>
        <p class="card-text">Kontribusi: Login, sign in page, dan Daftar Agenda Rapat.</p>
    <a href="#"></a>
</div> </div> </div></div></div>
<section id="kontak" class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold">Hubungi Kami</h2>
      <p class="text-muted">Hubungi kami melalui kolom di bawah ini!</p>
    </div>
    <div class="card-shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <form>
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" placeholder="Masukkan Nama Anda" required>
        </div>
            <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" placeholder="Masukkan email Anda" required>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">Pesan</label>
            <textarea class="form-control" id="message" rows="5" placeholder="Tuliskan pesan Anda" required></textarea>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary px-4">Kirim Pesan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
  <footer>
    &copy; 2025 IF1A2 TaskMeet | Dibuat untuk Proyek Pengelolaan Rapat
  </footer>
</body>
</html>
