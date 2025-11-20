<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Agenda Rapat - IF1A2 TASK MEET</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

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
            margin: 20px 8px 18px;
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
                <img src="format_gambar/logo.png" alt="logo" class="brand-logo">
                <span class="text-white fw-semibold fs-5">IF1A2 TASK MEET</span>
            </a>

            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <a class="text-white dropdown-toggle d-flex align-items-center" href="#" id="userMenu"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="profile_admin.php">Profil Saya</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="landing.php">Keluar</a></li>
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
            <a class="nav-link" href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link" href="daftarUser_admin.php"><i class="fas fa-box"></i> Daftar Peserta</a>
            <a class="nav-link active" href="daftarAgenda_admin.php"><i class="fas fa-users"></i> Daftar Agenda Rapat</a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="container-fluid">
            <h4 class="mb-3 fw-semibold">Daftar Agenda Rapat</h4>

            <div class="mb-3 d-flex justify-content-between flex-wrap align-items-center">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fa-solid fa-plus me-2"></i> Tambah Data Agenda Rapat</button>

                <div class="d-flex gap-2 align-items-center mt-2 mt-md-0">
                    <select id="filterJenis" class="form-select" style="width:200px;">
                        <option value="">Semua Jurusan</option>
                        <option>Teknik Informatika</option>
                        <option>Teknik Mesin</option>
                        <option>Teknik Elektro</option>
                        <option>Manajemen Bisnis</option>
                    </select>
                </div>
            </div>

            <div class="card p-3 shadow-sm">
                <div class="table-responsive">
                    <table id="agendaTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr style="vertical-align: middle;">
                                <th style="width:50px;">No</th>
                                <th>Judul Rapat</th>
                                <th>Jurusan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Tempat/Platform</th>
                                <th>Host</th>
                                <th>Peserta</th>
                                <th style="width:80px;">Process</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <button class="btn btn-primary btn-sm text-white fw-semibold btn-detail">Detail</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL TAMBAH DATA -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Data Agenda Rapat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambah">
                        <div class="mb-3">
                            <label class="form-label">Judul Rapat</label>
                            <input type="text" class="form-control" id="judulRapatInput" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select class="form-select" id="jurusanInput" required>
                                <option value="">Pilih Jurusan</option>
                                <option>Teknik Informatika</option>
                                <option>Teknik Mesin</option>
                                <option>Teknik Elektro</option>
                                <option>Manajemen Bisnis</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Rapat</label>
                            <input type="date" class="form-control" id="tanggalInput" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="time" class="form-control" id="waktuInput" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tempat / Platform</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipeTempat" id="offlineRadio" value="offline" checked>
                                <label class="form-check-label" for="offlineRadio">Offline (Ruangan)</label>
                            </div>
                            <input type="text" class="form-control mt-2" id="tempatOfflineInput" placeholder="Contoh: TA 10.4">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="radio" name="tipeTempat" id="onlineRadio" value="online">
                                <label class="form-check-label" for="onlineRadio">Online (Link Zoom)</label>
                            </div>
                            <input type="url" class="form-control mt-2 d-none" id="zoomLinkInput" placeholder="Tempel link Zoom disini https://">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Host</label>
                            <input type="text" class="form-control" id="hostInput" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Peserta</label>
                            <div class="form-control" id="pesertaCheckBoxes">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAllPeserta">
                                    <label for="selectAllPeserta" class="form-check-label">Pilih Semua / Hapus Semua</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="Qeysha" id="peserta1">
                                    <label for="peserta1" class="form-check-label">Qeysha</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="Rizka" id="peserta2">
                                    <label for="peserta2" class="form-check-label">Rizka</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="Nabila" id="peserta3">
                                    <label for="peserta3" class="form-check-label">Nabila</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="Iqbal" id="peserta4">
                                    <label for="peserta4" class="form-check-label">Iqbal</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="Nina" id="peserta5">
                                    <label for="peserta5" class="form-check-label">Nina</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL DETAIL -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Detail Agenda Rapat</h5>
            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p><strong>Judul Rapat:</strong> <span id="detailJudul"></span></p>
            <p><strong>Jurusan:</strong> <span id="detailJurusan"></span></p>
            <p><strong>Tanggal:</strong> <span id="detailTanggal"></span></p>
            <p><strong>Waktu:</strong> <span id="detailWaktu"></span></p>
            <p><strong>Tempat:</strong> <span id="detailTempat"></span></p>
            <p><strong>Host:</strong> <span id="detailHost"></span></p>
            <hr>
            <h6 class="fw-semibold">Peserta:</h6>
            <ul id="detailPesertaList" class="list-group mb-3"></ul>
        </div>
        <div class="modal-footer justify-content-between">
            <button class="btn btn-warning" id="btnEditDetail"><i class="fa fa-pen me-1"></i> Edit</button>
            <button class="btn btn-danger" id="btnHapusDetail"><i class="fa fa-trash me-1"></i> Hapus</button>
        </div>
        </div>
    </div>
    </div>
        <!-- MODAL EDIT -->
        <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Agenda Rapat</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                <div class="mb-3">
                    <label class="form-label">Judul Rapat</label>
                    <input type="text" class="form-control" id="editJudul">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <input type="text" class="form-control" id="editJurusan">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="editTanggal">
                </div>
                <div class="mb-3">
                    <label class="form-label">Waktu</label>
                    <input type="time" class="form-control" id="editWaktu">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tempat</label>
                    <input type="text" class="form-control" id="editTempat">
                </div>
                <div class="mb-3">
                    <label class="form-label">Host</label>
                    <input type="text" class="form-control" id="editHost">
                </div>
                <div class="mb-3">
                    <label class="form-label">Peserta & Kehadiran</label>
                    <ul id="editPesertaList" class="list-group"></ul>
                </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-success" id="btnSimpanEdit"><i class="fa fa-save me-1"></i> Simpan</button>
                <button class="btn btn-primary" id="btnKirimEdit"><i class="fa fa-paper-plane me-1"></i> Kirim</button>
            </div>
            </div>
        </div>
        </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <script>
     $(document).ready(function () {
    // === SIDEBAR TOGGLE ===
    const sidebar = document.getElementById('sidebar');
    const btnToggle = document.getElementById('btnToggleFloating');
    btnToggle.addEventListener('click', () => {
        if (window.innerWidth < 992) {
            sidebar.classList.toggle('show');
        } else {
            sidebar.classList.toggle('hide');
        }
    });

    // === CHECKBOX PESERTA ===
    $('#selectAllPeserta').on('change', function () {
        const checked = $(this).is(':checked');
        $('#pesertaCheckBoxes input[type="checkbox"]').prop('checked', checked);
    });

    $('#pesertaCheckBoxes input[type="checkbox"]').not('#selectAllPeserta').on('change', function () {
        const total = $('#pesertaCheckBoxes input[type="checkbox"]').not('#selectAllPeserta').length;
        const checked = $('#pesertaCheckBoxes input[type="checkbox"]:checked').not('#selectAllPeserta').length;
        $('#selectAllPeserta').prop('checked', total === checked);
    });

    // === TEMPAT/PLATFORM SELECTION ===
    $('#tempatOfflineInput').removeClass('d-none');
    $('#zoomLinkInput').addClass('d-none');
    
    $('input[name="tipeTempat"]').on('change', function() {
        if ($(this).val() === 'offline') {
            $('#tempatOfflineInput').removeClass('d-none');
            $('#zoomLinkInput').addClass('d-none');
            $('#zoomLinkInput').val('');
        } else {
            $('#tempatOfflineInput').addClass('d-none');
            $('#zoomLinkInput').removeClass('d-none');
            $('#tempatOfflineInput').val('');
        }
    });

    // === DATATABLES ===
    const table = $('#agendaTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 5,
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
        order: [[1, 'asc']],
        ajax: {
            url: 'get_agenda.php',
            type: 'POST',
            data: function (d) {
                d.filterJurusan = $('#filterJenis').val();
            }
        },
        columns: [
            { 
                data: null, 
                orderable: false,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'judul_rapat' },
            { data: 'jurusan' },
            { data: 'tanggal' },
            { data: 'waktu' },
            { data: 'tempat' },
            { data: 'host' },
            { data: 'peserta' },
            { 
                data: null, 
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return `<button class="btn btn-primary btn-sm text-white fw-semibold btn-detail" data-id="${row.id}">Detail</button>`;
                }
            }
        ]
    });

    // === FILTER JURUSAN ===
    $('#filterJenis').on('change', function () {
        table.ajax.reload();
    });

    // === TAMBAH DATA ===
 $('#formTambah').on('submit', function (e) {
    // --- DEBUGGING ---
    console.log("DEBUG: Form submit event ter-trigger!");
    alert("DEBUG: Form submit event ter-trigger! Jika Anda melihat alert ini, berarti JavaScript berjalan sampai sini.");

    e.preventDefault(); // Mencegah form submit normal
    console.log("DEBUG: e.preventDefault() sudah dijalankan.");

    // Kumpulkan data peserta yang dipilih
    const pesertaTerpilih = [];
    $('#pesertaCheckBoxes input:checked').not('#selectAllPeserta').each(function() {
        pesertaTerpilih.push($(this).val());
    });

    const formData = {
        action: 'tambah',
        judul_rapat: $('#judulRapatInput').val(),
        jurusan: $('#jurusanInput').val(),
        tanggal: $('#tanggalInput').val(),
        waktu: $('#waktuInput').val(),
        host: $('#hostInput').val(),
        tipe_tempat: $('input[name="tipeTempat"]:checked').val(),
        peserta: pesertaTerpilih
    };

    if (formData.tipe_tempat === 'online') {
        formData.zoom_link = $('#zoomLinkInput').val();
    } else {
        formData.tempat_offline = $('#tempatOfflineInput').val();
    }

    console.log("DEBUG: Data yang akan dikirim:", formData);

    $.ajax({
        url: 'crud_agenda.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log("DEBUG: AJAX Success! Response:", response);
            if (response.success) {
                $('#modalTambah').modal('hide');
                $('#formTambah')[0].reset();
                $('#tempatOfflineInput').removeClass('d-none').val('');
                $('#zoomLinkInput').addClass('d-none').val('');
                table.ajax.reload();
                alert(response.message);
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.log("DEBUG: AJAX Error!", xhr.responseText);
            alert('Terjadi kesalahan saat menghubungi server. Lihat console untuk detail.');
        }
    });
});

    // === DETAIL ===
 $('#agendaTable tbody').on('click', '.btn-detail', function () {
    const id = $(this).data('id');
    
    $.ajax({
        url: 'crud_agenda.php',
        type: 'GET',
        data: { action: 'detail', id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                $('#detailJudul').text(data.judul_rapat);
                $('#detailJurusan').text(data.jurusan);
                $('#detailTanggal').text(data.tanggal);
                $('#detailWaktu').text(data.waktu);
                
                if (data.tipe_tempat === 'online') {
                    $('#detailTempat').html(`<a href="${data.tempat_detail}" target="_blank" class="text-primary text-decoration-underline">Zoom Meeting</a>`);
                } else {
                    $('#detailTempat').text(data.tempat_detail);
                }
                
                $('#detailHost').text(data.host);
                
                let pesertaDetailHTML = '';
                data.peserta_detail.forEach(p => {
                    pesertaDetailHTML += `<li class="list-group-item">${p.nama_peserta}</li>`;
                });
                $('#detailPesertaList').html(pesertaDetailHTML);
                
                $('#modalDetail').data('agenda-id', id);
                $('#modalDetail').data('agenda-data', data); 
                $('#modalDetail').modal('show');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat mengambil data agenda.');
        }
    });
});

// === HAPUS BARIS ===
 $('#btnHapusDetail').on('click', function () {
    const agendaId = $('#modalDetail').data('agenda-id');
    if (!agendaId) {
        alert('Tidak ada agenda yang dipilih.');
        return;
    }
    if (!confirm('Yakin ingin menghapus agenda ini?')) return;

    $.ajax({
        url: 'crud_agenda.php',
        type: 'POST',
        data: { action: 'hapus', id: agendaId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modalDetail').modal('hide');
                table.ajax.reload();
                alert(response.message);
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat menghubungi server.');
        }
    });
});

// === EDIT DATA ===
 $('#btnEditDetail').on('click', function () {
    const data = $('#modalDetail').data('agenda-data');
    
    $('#editJudul').val(data.judul_rapat);
    $('#editJurusan').val(data.jurusan);
    $('#editTanggal').val(data.tanggal);
    $('#editWaktu').val(data.waktu);
    $('#editHost').val(data.host);
    $('#editTempat').val(data.tempat_detail);

    let pesertaListHTML = '';
    data.peserta_detail.forEach(p => {
        const nama = p.nama_peserta;
        const status = p.status_kehadiran;
        const checkedHadir = (status === 'hadir') ? 'checked' : '';
        const checkedTidakHadir = (status === 'tidak_hadir') ? 'checked' : '';
        
        pesertaListHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-semibold">${nama}</span>
                <div>
                    <label class="me-2">
                        <input type="radio" name="status_${nama}" value="hadir" ${checkedHadir}> Hadir
                    </label>
                    <label>
                        <input type="radio" name="status_${nama}" value="tidak_hadir" ${checkedTidakHadir}> Tidak Hadir
                    </label>
                </div>
            </li>`;
    });
    $('#editPesertaList').html(pesertaListHTML);

    $('#modalEdit').data('agenda-id', data.id);
    $('#modalDetail').modal('hide');
    $('#modalEdit').modal('show');
});

// === SIMPAN EDIT ===
 $('#btnSimpanEdit').on('click', function () {
    const agendaId = $('#modalEdit').data('agenda-id');
    if (!agendaId) return;

    const pesertaStatus = {};
    $('#editPesertaList li').each(function() {
        const nama = $(this).find('span').text();
        const status = $(this).find('input[type="radio"]:checked').val();
        if (status) {
            pesertaStatus[nama] = status;
        }
    });

    const formData = {
        action: 'edit',
        id: agendaId,
        judul_rapat: $('#editJudul').val(),
        jurusan: $('#editJurusan').val(),
        tanggal: $('#editTanggal').val(),
        waktu: $('#editWaktu').val(),
        host: $('#editHost').val(),
        tempat: $('#editTempat').val(),
        peserta_status: JSON.stringify(pesertaStatus)
    };

    $.ajax({
        url: 'crud_agenda.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modalEdit').modal('hide');
                table.ajax.reload();
                alert(response.message);
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat menghubungi server.');
        }
    });
});

    // === KIRIM UNDANGAN ===
    $('#btnKirimEdit').on('click', function () {
        alert('Fitur kirim undangan akan segera tersedia!');
    });
});
    </script>
</body>
</html>