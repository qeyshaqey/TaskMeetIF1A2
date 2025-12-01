<?php
require_once '../../autentikasi/session.php';
require_once '../../autentikasi/functions.php';

// Jika admin belum login, redirect ke halaman login
if (!is_logged_in()) {
    redirect('../../autentikasi/login.php');
}
?>
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
            /* === NOTIFIKASI TOAST === */
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
            }

            .toast {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                border-left: 4px solid;
                min-width: 300px;
                max-width: 350px;
                opacity: 0.9;
            }

            .toast.success {
                border-left-color: #28a745;
            }

            .toast.error {
                border-left-color: #dc3545;
            }

            .toast.warning {
                border-left-color: #ffc107;
            }

            .toast.info {
                border-left-color: #17a2b8;
            }

            .toast-header {
                background-color: transparent;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                padding: 12px 15px;
                display: flex;
                align-items: center;
            }

            .toast-title {
                font-weight: 600;
                margin-right: auto;
            }

            .toast-close {
                background: none;
                border: none;
                font-size: 1.2rem;
                cursor: pointer;
                color: #6c757d;
                padding: 0;
                margin-left: 10px;
            }

            .toast-body {
                padding: 10px 15px;
            }
            
            /* === PERBAIKAN UNTUK MODAL KONFIRMASI === */
            .modal-confirm {
                animation: slideIn 0.3s ease-out;
            }

            @keyframes slideIn {
                from {
                    transform: translateY(-50px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .modal-confirm .modal-content {
                border: none;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }

            .modal-confirm .modal-header {
                border-top-left-radius: 12px;
                border-top-right-radius: 12px;
            }

            .modal-confirm .btn-danger {
                background-color: #dc3545;
                border-color: #dc3545;
            }

            .modal-confirm .btn-danger:hover {
                background-color: #c82333;
                border-color: #bd2130;
            }
        </style>
    </head>
    <body>
        <div class="toast-container" id="toastContainer"></div>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container-fluid d-flex align-items-center">
                <button class="toggle-floating" id="btnToggleFloating" title="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>

                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="../format_gambar/logo.png" alt="logo" class="brand-logo">
                    <span class="text-white fw-semibold fs-5">TASK MEET</span>
                </a>
            </div>
        </nav>

        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="overlay" id="overlay"></div>
            <h4>Menu</h4>
            <nav class="nav flex-column">
                <a class="nav-link" href="../dashboard/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a class="nav-link active" href="peserta.php"><i class="fas fa-box"></i> Daftar Peserta</a>
                <a class="nav-link" href="../daftar_agenda/daftarAgenda_admin.php"><i class="fas fa-users"></i> Daftar Agenda Rapat</a>
                <hr style="border-color: rgba(255,255,255,0.3); margin: 20px 6px;">
                <a class="nav-link" href="../../autentikasi/logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
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
                                <option>Teknik Elektro</option>
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
                                <select class="form-select" name="jurusan" id="jurusanSelectTambah" required>
                                    <option value="">-- Pilih Jurusan --</option>
                                    <option value="Teknik Informatika">Teknik Informatika</option>
                                    <option value="Teknik Mesin">Teknik Mesin</option>
                                    <option value="Teknik Elektro">Teknik Elektro</option>
                                    <option value="Manajemen Bisnis">Manajemen Bisnis</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Program Studi</label>
                                <select class="form-select" name="prodi" id="prodiSelectTambah" required>
                                    <option value="">-- Pilih Jurusan Terlebih Dahulu --</option>
                                </select>
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
    <!-- MODAL KONFIRMASI KUSTOM (DIPERCANTIK) -->
    <div class="modal fade modal-confirm" id="modalConfirm" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p id="confirmMessage" class="mt-3 mb-0">Apakah Anda yakin ingin melakukan tindakan ini?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmYes">Ya, Lanjutkan</button>
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
                        <a id="btnHapus" class="btn btn-sm btn-danger">Hapus</a>
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

                        <div class="mb-3">
                            <label>Jurusan</label>
                            <select class="form-select" id="edit_jurusan">
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Teknik Mesin">Teknik Mesin</option>
                                <option value="Teknik Elektro">Teknik Elektro</option>
                                <option value="Manajemen Bisnis">Manajemen Bisnis</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Program Studi</label>
                            <select class="form-select" id="edit_prodi">
                                <option value="">-- Pilih Jurusan Terlebih Dahulu --</option>
                            </select>
                        </div>

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
    $(document).ready(function () {
        // --- VARIABEL GLOBAL ---
        let table;
        let currentEditingId;
        let currentRow; // Untuk menyimpan baris yang akan dihapus/diedit

        // --- INISIALISASI DATATABLES ---
        table = $('#userTable').DataTable({
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', text: 'Salin' },
                { extend: 'excel', text: 'Excel' },
                { extend: 'pdf', text: 'PDF' },
                { extend: 'print', text: 'Cetak' },
                { extend: 'colvis', text: 'Pilih Kolom' }
            ],
            language: {
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                search: "Cari:",
                paginate: { previous: "Sebelumnya", next: "Berikutnya" }
            }
        });

        // --- SIDEBAR TOGGLE ---
        const sidebar = document.getElementById('sidebar');
        const btnToggle = document.getElementById('btnToggleFloating');
        const overlay = document.getElementById('overlay');
        
        if (btnToggle) {
            btnToggle.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('hide');
                }
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // --- FILTER JURUSAN ---
        $('#filterJenis').on('change', function () {
            table.column(2).search(this.value).draw();
        });

        // --- FUNGSI UMUM ---
        function showToast(message, type = 'info', title = '') {
            const toastContainer = document.getElementById('toastContainer');
            let icon = '';
            switch(type) {
                case 'success': icon = '<i class="fas fa-check-circle text-success me-2"></i>'; break;
                case 'error': icon = '<i class="fas fa-exclamation-circle text-danger me-2"></i>'; break;
                case 'warning': icon = '<i class="fas fa-exclamation-triangle text-warning me-2"></i>'; break;
                default: icon = '<i class="fas fa-info-circle text-info me-2"></i>';
            }
            const toastId = 'toast-' + Date.now();
            const toastHTML = `
                <div id="${toastId}" class="toast ${type}" role="alert">
                    <div class="toast-header">
                        ${icon}
                        <strong class="toast-title">${title || (type.charAt(0).toUpperCase() + type.slice(1))}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">${message}</div>
                </div>
            `;
            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
            toast.show();
            toastElement.addEventListener('hidden.bs.toast', function () { toastElement.remove(); });
        }

        function showConfirmModal(message, onConfirm) {
            $('#confirmMessage').text(message);
            $('#btnConfirmYes').off('click').on('click', function() {
                $('#modalConfirm').modal('hide');
                if (typeof onConfirm === 'function') {
                    onConfirm();
                }
            });
            new bootstrap.Modal(document.getElementById('modalConfirm')).show();
        }

        // --- FUNGSI UNTUK MEMPERBAIKI NOMOR URUT ---
        function updateRowNumbersOnCurrentPage() {
            let info = table.page.info();
            table.rows({ page: 'current' }).every(function (rowIdx, tableLoop, rowLoop) {
                // rowLoop adalah indeks baris pada halaman saat ini (dimulai dari 0)
                // Nomor urut yang benar adalah info.start + rowLoop + 1
                this.cell(rowIdx, 0).data(info.start + rowLoop + 1);
            });
            // Gambar ulang tabel untuk menampilkan perubahan nomor
            table.draw(false);
        }
        
        // --- EVENT LISTENER UNTUK MODAL TAMBAH ---
        $('#btnTambah').click(function () {
            // Kosongkan form setiap kali modal dibuka
            $('#formTambah')[0].reset();
            $('#prodiSelectTambah').empty().append('<option value="">-- Pilih Jurusan Terlebih Dahulu --</option>');
            new bootstrap.Modal(document.getElementById('modalTambah')).show();
        });

        // Event listener untuk perubahan jurusan di modal tambah
        $('#jurusanSelectTambah').on('change', function() {
            loadProdiOptions('#jurusanSelectTambah', '#prodiSelectTambah');
        });

        // --- EVENT LISTENER UNTUK FORM TAMBAH (MENGGUNAKAN AJAX) ---
        $('#formTambah').on('submit', function (e) {
            // Cegah form submit secara default (yang menyebabkan reload halaman)
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'), // Ambil URL dari atribut action form
                type: 'POST',
                data: $(this).serialize(), // Ambil semua data dari form
                dataType: 'json',
                success: function (response) {
                    let tambahModal = bootstrap.Modal.getInstance(document.getElementById('modalTambah'));
                    tambahModal.hide();

                    if (response.success) {
                        // Tampilkan notifikasi sukses
                        showToast(response.message, 'success', 'Berhasil');

                        // Ambil data dari form untuk ditambahkan ke tabel
                        let newNama = $('#formTambah input[name="nama"]').val();
                        let newJurusan = $('#formTambah select[name="jurusan"]').val();
                        let newProdi = $('#formTambah select[name="prodi"]').val();
                        let newEmail = $('#formTambah input[name="email"]').val();
                        
                        // Ambil info halaman untuk menghitung nomor urut baru
                        let pageInfo = table.page.info();
                        let newRecordNumber = pageInfo.recordsTotal + 1;

                        // Tambahkan baris baru ke DataTables
                        let newNode = table.row.add([
                            newRecordNumber, // PERBAIKAN: Hitung nomor urut baru
                            newNama,
                            newJurusan,
                            newProdi,
                            newEmail,
                            '<button class="btn btn-primary btn-sm btn-detail" data-id="' + response.inserted_id + '">Detail</button>'
                        ]).draw();

                        // Pindahkan ke halaman terakhir di mana baris baru ditambahkan
                        table.page('last').draw('page');

                        // Kosongkan form setelah berhasil
                        $('#formTambah')[0].reset();

                    } else {
                        // Tampilkan notifikasi error jika gagal
                        showToast(response.message, 'error', 'Kesalahan');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan saat menghubungi server.', 'error', 'Kesalahan Jaringan');
                }
            });
        });

        // --- EVENT LISTENER UNTUK TOMBOL DETAIL (DIPERBAIKI) ---
        $(document).on('click', '.btn-detail', function () {
            // SIMPAN BARIS (<tr>) DAN ID YANG SEDANG DILIKI
            currentRow = $(this).closest('tr');
            currentEditingId = $(this).data('id');

            $.ajax({
                url: 'get_peserta.php',
                type: 'GET',
                data: { id: currentEditingId },
                success: function (data) {
                    let p = JSON.parse(data);
                    $('#detailNama').text(p.nama);
                    $('#detailJurusan').text(p.jurusan);
                    $('#detailProdi').text(p.prodi);
                    $('#detailEmail').text(p.email);
                    new bootstrap.Modal('#modalDetail').show();
                }
            });
        });

        // --- EVENT LISTENER UNTUK TOMBOL HAPUS (DIPERBAIKI) ---
        $('#btnHapus').on('click', function () {
            let detailModal = bootstrap.Modal.getInstance(document.getElementById('modalDetail'));
            detailModal.hide();

            showConfirmModal('Apakah Anda yakin ingin menghapus peserta ini?', function() {
                $.ajax({
                    url: 'hapus_peserta.php',
                    type: 'GET',
                    data: { id: currentEditingId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success', 'Berhasil');
                            
                            // Hapus baris dari tabel DataTables
                            table.row(currentRow).remove();
                            
                            // PERBAIKAN: Perbarui nomor urut pada halaman ini
                            updateRowNumbersOnCurrentPage();

                        } else {
                            showToast(response.message, 'error', 'Kesalahan');
                        }
                    },
                    error: function() {
                        showToast('Terjadi kesalahan saat menghubungi server.', 'error', 'Kesalahan Jaringan');
                    }
                });
            });
        });

        // --- EVENT LISTENER UNTUK TOMBOL EDIT ---
        $(document).on('click', '.editBtn', function () {
            let detailModal = bootstrap.Modal.getInstance(document.getElementById('modalDetail'));
            detailModal.hide();

            $.ajax({
                url: 'edit_peserta.php',
                type: 'GET',
                data: { id: currentEditingId },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        let peserta = response.data;
                        $('#edit_id').val(peserta.id);
                        $('#edit_nama').val(peserta.nama);
                        $('#edit_email').val(peserta.email);
                        $('#edit_jurusan').val(peserta.jurusan);
                        loadProdiOptions('#edit_jurusan', '#edit_prodi', peserta.prodi);
                        new bootstrap.Modal(document.getElementById('modalEditPeserta')).show();
                    } else {
                        showToast(response.message, 'error', 'Kesalahan');
                    }
                },
                error: function() {
                    showToast('Gagal mengambil data peserta.', 'error', 'Kesalahan Server');
                }
            });
        });

        $('#edit_jurusan').on('change', function() {
            loadProdiOptions('#edit_jurusan', '#edit_prodi');
        });

        $('#btnUpdate').click(function () {
            // Ambil data dari form
            let formData = {
                id: $('#edit_id').val(),
                nama: $('#edit_nama').val(),
                jurusan: $('#edit_jurusan').val(),
                prodi: $('#edit_prodi').val(),
                email: $('#edit_email').val()
            };

            $.ajax({
                url: 'update_peserta.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    let editModal = bootstrap.Modal.getInstance(document.getElementById('modalEditPeserta'));
                    editModal.hide();

                    if (response.success) {
                        showToast(response.message || 'Data peserta berhasil diperbarui.', 'success', 'Berhasil');
                        
                        // Perbarui data di tabel secara langsung
                        let rowToUpdate = table.row(currentRow);
                        rowToUpdate.data([
                            rowToUpdate.data()[0], // Kolom No (tidak berubah)
                            formData.nama,         // Kolom Nama (indeks 1)
                            formData.jurusan,      // Kolom Jurusan (indeks 2)
                            formData.prodi,        // Kolom Prodi (indeks 3)
                            formData.email,        // Kolom Email (indeks 4)
                            rowToUpdate.data()[5]  // Kolom Proses (tidak berubah)
                        ]);

                        // Gambar ulang tabel untuk menampilkan perubahan
                        table.draw(false);

                    } else {
                        showToast(response.message || 'Gagal memperbarui data peserta.', 'error', 'Kesalahan');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan saat menghubungi server.', 'error', 'Kesalahan Jaringan');
                }
            });
        });

        // --- FUNGSI UNTUK MEMUAT OPSI PRODI ---
        function loadProdiOptions(jurusanSelectId, prodiSelectId, selectedProdi = null) {
            const selectedJurusan = $(jurusanSelectId).val();
            const prodiSelect = $(prodiSelectId);
            prodiSelect.empty().append('<option value="">-- Memuat --</option>');

            if (selectedJurusan) {
                $.ajax({
                    url: 'get_prodi_by_jurusan.php',
                    type: 'GET',
                    data: { jurusan: selectedJurusan },
                    dataType: 'json',
                    success: function(prodiList) {
                        prodiSelect.empty().append('<option value="">-- Pilih Program Studi --</option>');
                        $.each(prodiList, function(index, prodi) {
                            prodiSelect.append(`<option value="${prodi}">${prodi}</option>`);
                        });
                        if (selectedProdi) {
                            $(prodiSelectId).val(selectedProdi);
                        }
                    },
                    error: function() {
                        prodiSelect.empty().append('<option value="">-- Gagal Memuat --</option>');
                    }
                });
            } else {
                prodiSelect.empty().append('<option value="">-- Pilih Jurusan Terlebih Dahulu --</option>');
            }
        }
    });
    </script>
    </body>
    </html>