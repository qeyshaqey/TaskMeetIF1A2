<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id = $_SESSION['user_id'];
$query = "SELECT * FROM user WHERE id='$id'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile - IF1A2 TaskMeet</title>

  <!-- Bootstrap & Icons -->
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
      box-shadow: 2px 0 10px rgba(0, 0, 0, .1);
      z-index: 999;
      transition: 0.3s;
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
      background: rgba(255, 255, 255, .2);
      border-radius: 4px;
      transform: translateX(4px);
    }

    .main-content {
      margin-left: 240px;
      padding: 40px 30px;
      transition: .3s;
    }

    .profile-card {
      background: #fff;
      display: flex;
      align-items: center;
      gap: 30px;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(18, 38, 63, .06);
    }

    .profile-img {
      width: 180px;
      height: 180px;
      border-radius: 12px;
      object-fit: cover;
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
        z-index: 1000;
        background: #5b2c83;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
      }

      .profile-card {
        flex-direction: column;
        text-align: center;
      }
    }
  </style>
</head>

<body>

  <!-- SIDEBAR (TIDAK DIUBAH) -->
  <div class="sidebar" id="sidebar">
    <div class="text-center mb-4">
      <img src="format_gambar/logo.png" width="110" class="rounded-circle mb-2">
      <h5>IF1A2 TaskMeet</h5>
    </div>

    <a href="dashboard_pengguna.html"><i class="bi bi-house-door me-2"></i> Dashboard</a>
    <a href="daftarrapat_pengguna.html"><i class="bi bi-calendar me-2"></i> Daftar Rapat</a>
    <a href="riwayatRapat_pengguna.html"><i class="bi bi-clock-history me-2"></i> Riwayat Rapat</a>
    <a href="detailRapat_pengguna.html"><i class="bi bi-file-text me-2"></i>Detail Kehadiran</a>
    <a href="profil_pengguna.html"><i class="bi bi-person-circle me-2"></i> Profile</a>

    <div class="position-absolute bottom-0 w-100">
      <a href="landing.html"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</a>
    </div>
  </div>

  <!-- TOGGLE -->
  <button class="toggle-btn d-md-none" id="toggleBtn"><i class="bi bi-list"></i></button>

  <!-- MAIN -->
  <div class="main-content">
    <h2 class="mb-4"><i class="bi bi-gear-fill me-2"></i>Pengaturan Profile</h2>

    <section class="profile-card">
      <div>
        <img src="https://via.placeholder.com/300x300.png?text=Foto+Profil" id="profileImage" class="profile-img">
      </div>

      <div class="flex-grow-1">
        <h3 id="profileName">-</h3>

        <p class="mb-1"><strong>NIM:</strong> <span id="profileNim">-</span></p>
        <p class="mb-3"><strong>Email:</strong> <span id="profileEmail">-</span></p>
        <p class="mb-1"><strong>Jurusan:</strong> <span id="profileJurusan">-</span></p>
        <p class="mb-3"><strong>Prodi:</strong> <span id="profileProdi">-</span></p>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
          <i class="bi bi-pencil-square me-1"></i> Edit Profil
        </button>
      </div>
    </section>
  </div>

  <!-- MODAL EDIT PROFIL -->
  <div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content rounded-3">

        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>Edit Profil</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form id="editProfileForm">

            <div class="row gx-4">

              <div class="col-md-4 text-center">
                <img id="previewImg" src="https://via.placeholder.com/300" class="profile-img mb-2">
                <input class="form-control" type="file" id="editPhoto" accept="image/*">
              </div>

              <div class="col-md-8">

                <div class="mb-3">
                  <label class="form-label">Nama</label>
                  <input id="editName" class="form-control">
                </div>

                <div class="mb-3">
                  <label class="form-label">NIM</label>
                  <input id="editNim" class="form-control">
                </div>

                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input id="editEmail" class="form-control" type="email">
                </div>

                <div class="mb-3">
                  <label class="form-label">Jurusan</label>
                  <input id="editJurusan" class="form-control">
                </div>

                <div class="mb-3">
                  <label class="form-label">Prodi</label>
                  <input id="editProdi" class="form-control">
                </div>

                <hr>

                <div class="mb-3">
                  <label class="form-label">Password Saat Ini</label>
                  <input id="passwordOld" class="form-control" type="password">
                </div>

                <div class="mb-3">
                  <label class="form-label">Password Baru</label>
                  <input id="passwordNew" class="form-control" type="password">
                </div>

                <div class="mb-3">
                  <label class="form-label">Konfirmasi Password Baru</label>
                  <input id="passwordConfirm" class="form-control" type="password">
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2 mt-3">
                  <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                  <button class="btn btn-success" type="submit">Simpan</button>
                </div>

              </div>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.getElementById('toggleBtn').onclick = () =>
      document.getElementById('sidebar').classList.toggle('active');

    // ELEMENT
    const profileImage = document.getElementById('profileImage');
    const profileName = document.getElementById('profileName');
    const profileNim = document.getElementById('profileNim');
    const profileEmail = document.getElementById('profileEmail');
    const profileJurusan = document.getElementById('profileJurusan');
    const profileProdi = document.getElementById('profileProdi');

    const editPhoto = document.getElementById('editPhoto');
    const previewImg = document.getElementById('previewImg');
    const editName = document.getElementById('editName');
    const editNim = document.getElementById('editNim');
    const editEmail = document.getElementById('editEmail');
    const editJurusan = document.getElementById('editJurusan');
    const editProdi = document.getElementById('editProdi');

    // LOAD PROFIL
    async function loadProfile() {
      const res = await fetch("get_profile.php");
      const user = await res.json();

      profileName.textContent = user.nama ?? "-";
      profileNim.textContent = user.nim ?? "-";
      profileEmail.textContent = user.email ?? "-";
      profileJurusan.textContent = user.jurusan ?? "-";
      profileProdi.textContent = user.prodi ?? "-";

      if (user.foto) {
        profileImage.src = "uploads/" + user.foto;
        previewImg.src = "uploads/" + user.foto;
      }
    }

    // PREVIEW FOTO
    editPhoto.addEventListener("change", e => {
      const file = e.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = ev => previewImg.src = ev.target.result;
      reader.readAsDataURL(file);
    });

    // ISI MODAL
    document.getElementById("editProfileModal")
      .addEventListener("show.bs.modal", () => {
        editName.value = profileName.textContent;
        editNim.value = profileNim.textContent;
        editEmail.value = profileEmail.textContent;
        editJurusan.value = profileJurusan.textContent;
        editProdi.value = profileProdi.textContent;

        previewImg.src = profileImage.src;

        // reset password input
        document.getElementById("passwordOld").value = "";
        document.getElementById("passwordNew").value = "";
        document.getElementById("passwordConfirm").value = "";
      });


    // SIMPAN
    document.getElementById("editProfileForm")
      .addEventListener("submit", async function(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append("nama", editName.value);
        formData.append("nim", editNim.value);
        formData.append("email", editEmail.value);
        formData.append("jurusan", editJurusan.value);
        formData.append("prodi", editProdi.value);
        formData.append("foto", editPhoto.files[0]);
        formData.append("passwordOld", document.getElementById("passwordOld").value);
        formData.append("passwordNew", document.getElementById("passwordNew").value);
        formData.append("passwordConfirm", document.getElementById("passwordConfirm").value);


        const req = await fetch("update_profile.php", {
          method: "POST",
          body: formData
        });

        const res = await req.text();

        if (res === "OK") {
          loadProfile();
          bootstrap.Modal.getInstance(document.getElementById("editProfileModal")).hide();
        } else if (res === "WRONG_OLD") {
          alert("Password lama salah!");
        } else if (res === "NEW_MISMATCH") {
          alert("Password baru dan konfirmasi tidak sama!");
        } else if (res === "OLD_EMPTY") {
          alert("Password lama harus diisi!");
        } else {
          alert("Terjadi kesalahan: " + res);
        }

      });

    loadProfile();
  </script>

</body>

</html>