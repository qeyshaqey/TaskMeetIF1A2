<?php
require_once '../autentikasi/session.php';
require_once '../autentikasi/functions.php';

// Hanya user yang bisa mengakses
if (!is_logged_in() || is_admin()) {
    redirect('../autentikasi/login.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Rapat Peserta - IF1A2 TaskMeet</title>

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
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
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
      padding: 50px 30px;
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

    /* Responsiveness */
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
        z-index: 1000;
        background: #5b2c83;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
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
    <a href="detail_rapat.php"><i class="bi bi-file-text me-2"></i>Detail Rapat</a>
    <a href="profile_pengguna.php"><i class="bi bi-person-circle me-2"></i> Profil</a>

    <div class="position-absolute bottom-0 w-100">
      <a href="landing.php"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</a>
    </div>
  </div>
  <!-- Toggle Button Mobile -->
  <button class="toggle-btn d-md-none" id="toggleBtn"><i class="bi bi-list"></i></button>
  <!--MAIN CONTENT-->
  <div class="main-content">
    <div class="row g-3">
        <div class="col-lg-12">
            <h3 class="fw-bold">Detail Kehadiran</h3>

    <!-- STATISTIK -->
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-4">
            <div class="card card-stat p-3">
                <h6>Total Hadir</h6>
                <h4 id="totalHadir" class="fw-bold">0</h4>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-stat p-3">
                <h6>Total Tidak Hadir</h6>
                <h4 id="totalTidakHadir" class="fw-bold">0</h4>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-stat p-3">
                <h6>Presentase</h6>
                <h4 id="persentaseKehadiran" class="fw-bold">0%</h4>
            </div>
        </div>

    </div>

    <!-- TABEL RIWAYAT -->
    <div class="card-rail">
        <h4 class="fw-bold mb-4">Riwayat Kehadiran</h4>
        <div class="table-responsive">
            <table id="riwayatTable" class="table table-bordered bg-white table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Judul Rapat</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Rapat Koordinasi Mingguan</td>
                        <td>12 Nov 2025</td>
                        <td>20:00</td>
                        <td>TA 10.7</td>
                        <td><span class="badge bg-success">Hadir</span></td>
                        <td><button class="btn btn-sm btn-outline-primary btn-detail" data-lokasi="">Detail</button></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Evaluasi Project A</td>
                        <td>10 Nov 2025</td>
                        <td>12:00</td>
                        <td>GU 704</td>
                        <td><span class="badge bg-danger">Tidak Hadir</span></td>
                        <td><button class="btn btn-sm btn-outline-primary btn-detail" detailTempat>Detail</button></td>
                    </tr>
                    <tr>
                      <td>3</td>
                        <td>Pembagian Tugas</td>
                        <td>3 Nov 2025</td>
                        <td>13:00</td>
                        <td>Zoom Meeting</td>
                        <td><span class="badge bg-danger">Tidak Hadir</span></td>
                        <td><button class="btn btn-sm btn-outline-primary btn-detail" data-lokasi="">Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Kehadiran (READ ONLY) -->
    <div class="modal fade" id="detailKehadiranModal" tabindex="-1"aria-labelledby="detailKehadiranLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="detailKehadiranLabel">Detail Kehadiran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-2"><small class="text-muted">Judul Rapat</small>
              <div id="detailJudulRapat" class="fw-semibold"></div>
            </div>
            <div class="mb-2"><small class="text-muted">Tanggal</small>
              <div id="detailTanggal"></div>
            </div>
            <div class="mb-2"><small class="text-muted">Waktu</small>
              <div id="detailWaktu"></div>
            </div>
            <div class="mb-2"><small class="text-muted">Tempat / Link Rapat</small>
              <div id="detailTempat"></div>
            </div>
            <div class="mb-2"><small class="text-muted">Status Kehadiran</small>
              <div id="detailStatus"></div>
            </div>
        </div>
      </div>
    </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery (WAJIB) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function() {
      $('#riwayatTable').DataTable({
          paging: true,
          searching: true,
          lengthChange: true,
          pageLength: 10,
          lengthMenu: [10, 25, 50, 100],
          language: {
              lengthMenu: "Tampilkan _MENU_ data",
              search: "Cari:",
              info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
              infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
              zeroRecords: "Tidak ada data ditemukan",
              paginate: {
                  previous: "Sebelumnya",
                  next: "Berikutnya"
              }
          }
      });
  });
  document.getElementById('toggleBtn').onclick = () => {
    document.getElementById('sidebar').classList.toggle('active');
  }

  function showDetailKehadiran(judul, tanggal, waktu, tempat,  status) {
    document.getElementById("detailJudulRapat").innerText = judul;
    document.getElementById("detailTanggal").innerText = tanggal;
    document.getElementById("detailWaktu").innerText = waktu;
    document.getElementById("detailTempat").innerHTML = tempat;
    document.getElementById("detailStatus").innerHTML = status;

    var modal = new bootstrap.Modal(document.getElementById('detailKehadiranModal'));
    modal.show();
  }

  // Event tombol DETAIL
  document.querySelectorAll(".btn-detail").forEach(btn => {
    btn.addEventListener("click", function () {
      const row = this.closest("tr");

      const judul = row.children[1].innerText;   
      const tanggal = row.children[2].innerText; 
      const waktu = row.children[3].innerText; 
      const tempat = row.children[4].innerHTML;  
      const status = row.children[5].innerHTML;  

      showDetailKehadiran(judul, tanggal, waktu, tempat, status);
    });
  });

  // Update kartu statistik
  function updateCards() {
    let totalRows = 0;
    let totalHadir = 0;
    let totalTidakHadir = 0;

    document.querySelectorAll("table tbody tr").forEach(row => {
      totalRows++;

      const statusText = row.children[5].innerText.trim();

      if (statusText === "Hadir") totalHadir++;
      else if (statusText === "Tidak Hadir") totalTidakHadir++;
    });

    document.getElementById("totalHadir").innerText = totalHadir;
    document.getElementById("totalTidakHadir").innerText = totalTidakHadir;

    let presentase = totalRows > 0 ? Math.round((totalHadir / totalRows) * 100) : 0;
    document.getElementById("persentaseKehadiran").innerText = presentase + "%";
  }

  updateCards();
</script>
</body>
</html>