<?php
require_once '../autentikasi/session.php';
require_once '../autentikasi/functions.php';
require_once 'koneksi.php';

// Hanya peserta yang boleh masuk (bukan admin)
if (!is_logged_in() || is_admin()) {
    redirect('../autentikasi/login.php');
}

$user_id = $_SESSION['user_id'];

// ==============================
//  QUERY DATA UNTUK DASHBOARD
// ==============================

// --- Rapat Mendatang ---
$sql = "SELECT a.*
        FROM agendas a
        JOIN peserta p ON a.id = p.id
        WHERE p.id = ?
        AND a.tanggal >= CURDATE()
        ORDER BY a.tanggal ASC";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$rapat_mendatang = $stmt->get_result();

// --- Summary: Rapat Akan Datang ---
$sql1 = "SELECT COUNT(*)
        FROM agendas a 
        JOIN peserta p ON a.id = p.id 
        WHERE p.id = ?
        AND a.tanggal >= CURDATE()";
$stmt1 = $koneksi->prepare($sql1);
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$total_upcoming = $stmt1->get_result()->fetch_row()[0];

// --- Summary: Rapat Selesai ---
$sql2 = "SELECT COUNT(*)
        FROM agendas a 
        JOIN peserta p ON a.id = p.id
        WHERE p.id = ?
        AND a.tanggal < CURDATE()";
$stmt2 = $koneksi->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$total_finished = $stmt2->get_result()->fetch_row()[0];

// --- Summary: Kehadiran ---
$sql3 = "SELECT 
            SUM(status_kehadiran='hadir') AS hadir,
            SUM(status_kehadiran='absen') AS absen
        FROM agenda_peserta
        WHERE peserta_id = ?";
$stmt3 = $koneksi->prepare($sql3);
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
$kehadiran = $stmt3->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Peserta - IF1A2 TaskMeet</title>

  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(180deg, #784B84, #D7BFDC);
      min-height: 100vh;
    }

    .sidebar {
      width: 240px;
      background: linear-gradient(180deg, #784B84, #D7BFDC);
      color: #fff;
      height: 100vh;
      position: fixed;
      padding-top: 30px;
      transition: 0.3s;
      z-index: 999;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 25px;
      color: #fff;
      text-decoration: none;
      transition: 0.3s;
    }

    .sidebar a:hover {
      background: rgba(255,255,255,0.2);
      border-radius: 3px;
      transform: translateX(4px);
    }

    .main-content {
      margin-left: 240px;
      padding: 30px;
      transition: 0.3s;
    }

    .card-rail {
      background: #fff;
      border-radius: 10px;
      padding: 18px;
      box-shadow: 0 6px 18px rgba(18, 38, 63, .06);
    }

    .card-click {
      cursor: pointer;
      transition: 0.25s;
    }

    .card-click:hover {
      transform: scale(1.03);
    }

    @media(max-width: 768px) {
      .sidebar {
        left: -240px;
      }
      .sidebar.active {
        left: 0;
      }
      .main-content {
        margin-left: 0;
      }
      .toggle-btn {
        position: fixed;
        top: 15px;
        left: 15px;
        background: #5b2c83;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        z-index: 1000;
      }
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="text-center mb-4">
      <img src="../admin_side/format_gambar/logo.png" width="110" class="rounded-circle mb-2">
      <h5>TaskMeet</h5>
    </div>

    <a href="dashboard_pengguna.php"><i class="bi bi-house-door me-2"></i> Dashboard</a>
    <a href="detail_rapat.php"><i class="bi bi-file-text me-2"></i> Detail Rapat</a>
    <a href="profile_pengguna.php"><i class="bi bi-person-circle me-2"></i> Profil</a>

    <div class="position-absolute bottom-0 w-100">
      <a href="../autentikasi/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</a>
    </div>
  </div>

  <!-- Toggle Button Mobile -->
  <button class="toggle-btn d-md-none" id="toggleBtn"><i class="bi bi-list"></i></button>

  <!-- MAIN CONTENT -->
  <div class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3></h3>
      <div class="d-flex align-items-center fw-bold ">
        <span class="me-2">Selamat Datang!</span>
        <img src="https://via.placeholder.com/35" class="rounded-circle">
      </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row mb-4">

      <div class="col-md-4 mb-3">
        <div class="card card-click" onclick="window.location.href='daftarrapat_pengguna.php'">
          <div class="card-body">
            <h6>Rapat Akan Datang</h6>
            <h3><?= $total_upcoming ?> Rapat</h3>
            <small>Klik untuk melihat daftar</small>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3">
        <div class="card card-click" onclick="window.location.href='riwayatRapat_pengguna.php'">
          <div class="card-body">
            <h6>Rapat Selesai</h6>
            <h3><?= $total_finished ?> Rapat</h3>
            <small>Klik untuk melihat riwayat</small>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3">
        <div class="card card-click" onclick="window.location.href='detailRapat_pengguna.php'">
          <div class="card-body">
            <h6>Kehadiran Saya</h6>
            <h3>Hadir <?= $kehadiran['hadir'] ?? 0 ?> / Absen <?= $kehadiran['absen'] ?? 0 ?></h3>
            <small>Klik untuk melihat detail</small>
          </div>
        </div>
      </div>

    </div>

    <!-- TABEL RAPAT MENDATANG -->
    <div class="row g-3">
      <div class="col-lg-12">
        <div class="card-rail">
          <h5 class="mb-3">Rapat Mendatang</h5>

          <div class="table-responsive">
            <table class="table table-bordered bg-white table-hover">
              <thead class="table-light">
                <tr>
                  <th>No.</th>
                  <th>Judul Rapat</th>
                  <th>Tanggal</th>
                  <th>Waktu</th>
                  <th>Lokasi / Link</th>
                </tr>
              </thead>

              <tbody>
              <?php 
                $no = 1;
                if ($rapat_mendatang->num_rows == 0): 
              ?>
                <tr>
                  <td colspan="5" class="text-center text-muted">Tidak ada rapat mendatang.</td>
                </tr>
              <?php 
                else:
                  while ($row = $rapat_mendatang->fetch_assoc()):
              ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($row['judul']) ?></td>
                  <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                  <td><?= substr($row['waktu'], 0, 5) ?></td>
                  <td><?= htmlspecialchars($row['lokasi']) ?></td>
                </tr>
              <?php 
                  endwhile;
                endif;
              ?>
              </tbody>

            </table>
          </div>

        </div>
      </div>
    </div>

  </div>

  <script>
    document.getElementById('toggleBtn').onclick = () => {
      document.getElementById('sidebar').classList.toggle('active');
    }
  </script>

</body>
</html>
