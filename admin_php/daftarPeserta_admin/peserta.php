<?php
include 'koneksi.php';

// Ambil semua peserta dari database
$data_peserta = mysqli_query($koneksi, "SELECT * FROM peserta ORDER BY id DESC");
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Peserta - IF1A2 TASK MEET</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">


    <style>
        :root {
            --sidebar-w: 250px;
            --blue-1: #784B84;
            --blue-2: #D7BFDC;
        }

        body {
            background: #f4f7fb;
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        /* === NAVBAR === */
        .navbar {
            background: linear-gradient(90deg, var(--blue-1), var(--blue-2));
            z-index: 1000 !important;
            position: fixed;
            top: 0;
            width: 100%;
        }

        /* === TOMBOL HAMBURGER FLOATING === */
        .toggle-floating {
            position: fixed !important;
            top: 14px;
            left: 14px;
            z-index: 5000 !important;
            background: #ffffff;
            border-radius: 50%;
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blue-1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            margin-left: 70px;
        }

        .navbar-brand img {
            height: 55px;
            width: auto;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 72px 14px 20px 14px;
            background: linear-gradient(180deg, var(--blue-1), var(--blue-2));
            color: #fff;
            overflow-y: auto;
            transition: left .28s ease;
            box-shadow: 2px 0 12px rgba(0, 0, 0, .06);
            z-index: 900 !important;
        }

        .sidebar.hide {
            left: -260px;
        }

        .sidebar h4 {
            color: #fff;
            font-weight: 700;
            margin: 20px 6px 10px;
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, .95);
            border-radius: 8px;
            padding: 10px 12px;
            margin: 6px 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, .14);
            color: #fff;
            transform: translateX(6px);
        }

        /* === CONTENT === */
        .content {
            margin-left: var(--sidebar-w);
            padding: 100px 24px 36px 24px;
            transition: margin-left .28s ease;
        }

        .sidebar.hide+.content {
            margin-left: 0;
        }

        @media (max-width: 992px) {
            .sidebar {
                left: -260px;
                padding-top: 70px;
                width: 260px;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
                padding-top: 100px;
            }
        }

        .table thead th {
            background: #e9eef7;
        }

        .overlay {
            display: none;
        }

        .overlay.show {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 850;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid d-flex align-items-center">
            <button class="toggle-floating" id="btnToggleFloating" title="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>

            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="logo.png" alt="logo" class="brand-logo">
                <span class="text-white fw-semibold fs-5">IF1A2 TASK MEET</span>
            </a>

            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <a class="text-white dropdown-toggle d-flex align-items-center" href="#" id="userMenu"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="profile.html">Profil Saya</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="landing.html">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="overlay" id="overlay"></div>
        <h4>Menu</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard admin.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link active" href="daftar_user.html"><i class="fas fa-box"></i> Daftar Peserta</a>
            <a class="nav-link" href="daftar_agenda.html"><i class="fas fa-users"></i> Daftar Agenda Rapat</a>
        </nav>
    </aside>
    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="container-fluid"> 
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h4 class="fw-semibold mb-2 mb-md-0">Daftar Peserta</h4> 
            </div>
            <!-- Tombol Tambah User -->
            <div class="mb-3">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <button class="btn btn-primary" id="btnTambah"><i class="fa-solid fa-plus me-2"></i>Tambah Data Peserta</button>
                        <select id="filterJenis" class="form-select"style="width:200px;">
                            <option value="">Semua Jurusan</option>
                            <option>Teknik informatika</option>
                            <option>Teknik Mesin</option>
                            <option>Teknik Elektronika</option>
                            <option>Manajemen bisnis</option>
                        </select>
                    </div>
                </div>
                <div class="card p-3 shadow-sm">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-bordered table-striped align-middle">
                            <thead class="table-light text-left">
                                <tr>
                                    <th style="width:50px;">No</th>
                                    <th>Nama Peserta</th>
                                    <th>Jurusan</th>
                                    <th>Prodi</th>
                                    <th>Email</th>
                                    <th style="width:80px;">Proses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($data_peserta)) { ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['nama']; ?></td>
                                    <td><?= $row['jurusan']; ?></td>
                                    <td><?= $row['prodi']; ?></td>
                                    <td><?= $row['email']; ?></td>
                                    <td><button class="btn btn-primary btn-sm btn-detail" data-id="<?= $row['id']; ?>">Detail</button></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- MODAL TAMBAH DATA -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Peserta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambah" action="tambah_peserta.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Peserta</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select class="form-control" name="jurusan" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Teknik informatika">Teknik Informatika</option>
                                <option value="Teknik Mesin">Teknik Mesin</option>
                                <option value="Manajemen Bisnis">Manajemen Bisnis</option>
                                <option value="Teknik Elektronika">Teknik Elektronika</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prodi</label>
                            <input type="text" class="form-control" name="prodi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Detail -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Detail Peserta</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama:</strong> <span id="detailNama"></span></p>
                    <p><strong>Jurusan:</strong> <span id="detailJurusan"></span></p>
                    <p><strong>Prodi:</strong> <span id="detailProdi"></span></p>
                    <p><strong>Email:</strong> <span id="detailEmail"></span></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning btn-sm editBtn" id="btnEditDetail" data-id="5">Edit</button>
                    <a id="btnHapus" class="btn btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </div>
            </div>
        </div>
    </div>
    <!--modal edit-->
    <div class="modal fade" id="modalEditPeserta">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5>Edit Data Peserta</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id"><label>Nama Peserta</label>
                    <input type="text" id="edit_nama" class="form-control">

                    <label>Jurusan</label>
                    <input type="text" id="edit_jurusan" class="form-control">

                    <label>Prodi</label>
                    <input type="text" id="edit_prodi" class="form-control">

                    <label>Email</label>
                    <input type="email" id="edit_email" class="form-control">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btnUpdate">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

<!-- JQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables CORE -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- JSZip (untuk Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDFMake -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<script>
document.getElementById("btnTambah").addEventListener("click", function () {
    var modal = new bootstrap.Modal(document.getElementById("modalTambah"));
    modal.show();
});
</script>
<script>
$(document).ready(function () {

    let table = $('#userTable').DataTable({
        pageLength: 10,
        dom: 'Bfrtip',

        buttons: [
            { extend: 'copy', text: 'Copy' },
            { extend: 'excel', text: 'Excel' },
            { extend: 'pdf', text: 'PDF' },
            { extend: 'print', text: 'Print' },
            { extend: 'colvis', text: 'Pilih Kolom' }
        ],

        language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            search: "Cari:",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        }
    });

    // FILTER JURUSAN
    $('#filterJenis').on('change', function () {
        table.column(2).search(this.value).draw();
    });

});
</script>
<script>
$(document).on('click', '.btn-detail', function () {
    let id = $(this).data('id');
    //ajax detail
    $.ajax({
        url: 'get_peserta.php',
        type: 'GET',
        data: { id: id },
        success: function (data) {
            let p = JSON.parse(data);

            $('#detailNama').text(p.nama);
            $('#detailJurusan').text(p.jurusan);
            $('#detailProdi').text(p.prodi);
            $('#detailEmail').text(p.email);
            $('#btnEditDetail').attr('data-id', id);
            $('#btnHapus').attr('href', 'hapus_peserta.php?id=' + id);

            new bootstrap.Modal('#modalDetail').show();
        }
    });
});
// Ketika tombol Edit diklik
$(document).on('click', '.editBtn', function () {
    let id = $(this).data('id');

    $.ajax({
        url: 'edit_peserta.php',
        type: 'GET',
        data: { id: id },
        success: function (data) {
            let peserta = JSON.parse(data);

            // Isi modal edit
            $('#edit_id').val(peserta.id);
            $('#edit_nama').val(peserta.nama);
            $('#edit_jurusan').val(peserta.jurusan);
            $('#edit_prodi').val(peserta.prodi);
            $('#edit_email').val(peserta.email);

            // tutup modal detail
           let detailModal = bootstrap.Modal.getInstance(document.getElementById('modalDetail'));
            detailModal.hide();
            //buka modal edit
            new bootstrap.Modal(document.getElementById('modalEditPeserta')).show();
        }
    });
});
$('#btnUpdate').click(function () {
    $.ajax({
        url: 'update_peserta.php',
        type: 'POST',
        data: {
            id: $('#edit_id').val(),
            nama: $('#edit_nama').val(),
            jurusan: $('#edit_jurusan').val(),
            prodi: $('#edit_prodi').val(),
            email: $('#edit_email').val()
        },
        success: function () {
            let editModal = bootstrap.Modal.getInstance(document.getElementById('modalEditPeserta'));
            editModal.hide();

            location.reload();
        }
    });
});
</script>

</body>
</html>
