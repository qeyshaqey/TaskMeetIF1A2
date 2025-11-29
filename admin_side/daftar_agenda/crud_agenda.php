<?php
header('Content-Type: application/json');
require_once 'config.php';

function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

 $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// --- AKSI TAMBAH ---
if ($action === 'tambah') {
    $judul = $_POST['judul_rapat'];
    $jurusan = $_POST['jurusan'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $host = $_POST['host'];
    $tipeTempat = $_POST['tipe_tempat'];
    $tempatDetail = ($tipeTempat === 'online') ? $_POST['zoom_link'] : $_POST['tempat_offline'];
    $pesertaIds = isset($_POST['peserta_ids']) ? $_POST['peserta_ids'] : []; // Menerima array ID

    if (empty($judul) || empty($jurusan) || empty($tanggal) || empty($waktu) || empty($host) || empty($tempatDetail)) {
        sendResponse(false, 'Semua field wajib diisi!');
    }

    // Gunakan transaksi untuk memastikan semua data tersimpan dengan aman
    $conn->begin_transaction();
    try {
        // 1. Insert data agenda utama ke tabel 'agendas'
        $sqlAgenda = "INSERT INTO agendas (judul_rapat, jurusan, tanggal, waktu, tipe_tempat, tempat_detail, host) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtAgenda = $conn->prepare($sqlAgenda);
        $stmtAgenda->bind_param("sssssss", $judul, $jurusan, $tanggal, $waktu, $tipeTempat, $tempatDetail, $host);
        $stmtAgenda->execute();
        $agendaId = $conn->insert_id; // Dapatkan ID agenda yang baru dibuat

        // 2. Jika ada peserta yang dipilih, simpan ke tabel 'agenda_peserta'
        if (!empty($pesertaIds)) {
            // Ambil nama peserta dari tabel 'peserta' berdasarkan ID yang diterima
            $idsPlaceholder = implode(',', array_fill(0, count($pesertaIds), '?'));
            $sqlGetPeserta = "SELECT id, nama FROM peserta WHERE id IN ($idsPlaceholder)";
            $stmtGetPeserta = $conn->prepare($sqlGetPeserta);
            
            $types = str_repeat('i', count($pesertaIds));
            $stmtGetPeserta->bind_param($types, ...$pesertaIds);
            $stmtGetPeserta->execute();
            $resultPeserta = $stmtGetPeserta->get_result();
            
            // 3. Loop setiap peserta dan insert ke tabel penghubung 'agenda_peserta'
            $sqlPeserta = "INSERT INTO agenda_peserta (agenda_id, peserta_id, nama_peserta) VALUES (?, ?, ?)";
            $stmtPeserta = $conn->prepare($sqlPeserta);
            
            while ($peserta = $resultPeserta->fetch_assoc()) {
                $stmtPeserta->bind_param("iis", $agendaId, $peserta['id'], $peserta['nama']);
                $stmtPeserta->execute();
            }
        }
        
        $conn->commit(); // Jika semua berhasil, simpan perubahan
        sendResponse(true, 'Agenda rapat berhasil ditambahkan!');
    } catch (Exception $e) {
        $conn->rollback(); // Jika ada error, batalkan semua perubahan
        sendResponse(false, 'Gagal menambahkan agenda. Terjadi kesalahan: ' . $e->getMessage());
    }

// --- AKSI DETAIL ---
} elseif ($action === 'detail') {
    $agendaId = $_GET['id'];
    $sqlAgenda = "SELECT * FROM agendas WHERE id = ?";
    $stmtAgenda = $conn->prepare($sqlAgenda);
    $stmtAgenda->bind_param("i", $agendaId);
    $stmtAgenda->execute();
    $resultAgenda = $stmtAgenda->get_result();

    if ($resultAgenda->num_rows > 0) {
        $agendaData = $resultAgenda->fetch_assoc();

        $sqlPeserta = "SELECT nama_peserta, status_kehadiran FROM agenda_peserta WHERE agenda_id = ?";
        $stmtPeserta = $conn->prepare($sqlPeserta);
        $stmtPeserta->bind_param("i", $agendaId);
        $stmtPeserta->execute();
        $resultPeserta = $stmtPeserta->get_result();
        
        $pesertaList = [];
        while ($row = $resultPeserta->fetch_assoc()) {
            $pesertaList[] = $row;
        }
        $agendaData['peserta_detail'] = $pesertaList;

        sendResponse(true, 'Data ditemukan', $agendaData);
    } else {
        sendResponse(false, 'Agenda tidak ditemukan.');
    }

// --- AKSI EDIT ---
} elseif ($action === 'edit') {
    $agendaId = $_POST['id'];
    $judul = $_POST['judul_rapat'];
    $jurusan = $_POST['jurusan'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $host = $_POST['host'];
    $tempat = $_POST['tempat']; // Nama field di form adalah 'tempat'
    $pesertaStatus = isset($_POST['peserta_status']) ? json_decode($_POST['peserta_status'], true) : [];

    if (empty($agendaId) || empty($judul) || empty($jurusan) || empty($tanggal) || empty($waktu) || empty($host) || empty($tempat)) {
        sendResponse(false, 'Semua field wajib diisi!');
    }

    $conn->begin_transaction();
    try {
        // 1. Update data agenda utama di tabel 'agendas'
        $sqlAgenda = "UPDATE agendas SET judul_rapat=?, jurusan=?, tanggal=?, waktu=?, tempat_detail=?, host=? WHERE id=?";
        $stmtAgenda = $conn->prepare($sqlAgenda);
        $stmtAgenda->bind_param("ssssssi", $judul, $jurusan, $tanggal, $waktu, $tempat, $host, $agendaId);
        $stmtAgenda->execute();

        // 2. Update status kehadiran peserta di tabel 'agenda_peserta'
        if (!empty($pesertaStatus)) {
            $sqlPeserta = "UPDATE agenda_peserta SET status_kehadiran = ? WHERE agenda_id = ? AND nama_peserta = ?";
            $stmtPeserta = $conn->prepare($sqlPeserta);
            foreach ($pesertaStatus as $nama => $status) {
                if (in_array($status, ['hadir', 'tidak_hadir'])) {
                    $stmtPeserta->bind_param("sis", $status, $agendaId, $nama);
                    $stmtPeserta->execute();
                }
            }
        }

        // *** LOGIKA BARU: CEK APAKAH SEMUA PESERTA SUDAH DIABSEN ***
        // Hitung total peserta untuk agenda ini
        $sqlTotalPeserta = "SELECT COUNT(*) as total FROM agenda_peserta WHERE agenda_id = ?";
        $stmtTotal = $conn->prepare($sqlTotalPeserta);
        $stmtTotal->bind_param("i", $agendaId);
        $stmtTotal->execute();
        $resultTotal = $stmtTotal->get_result();
        $totalPeserta = $resultTotal->fetch_assoc()['total'];

        // Hitung peserta yang sudah diabsen (statusnya 'hadir' atau 'tidak_hadir')
        $sqlPesertaDihadiri = "SELECT COUNT(*) as hadir FROM agenda_peserta WHERE agenda_id = ? AND status_kehadiran IN ('hadir', 'tidak_hadir')";
        $stmtHadir = $conn->prepare($sqlPesertaDihadiri);
        $stmtHadir->bind_param("i", $agendaId);
        $stmtHadir->execute();
        $resultHadir = $stmtHadir->get_result();
        $pesertaDihadiri = $resultHadir->fetch_assoc()['hadir'];

        // Jika jumlah peserta yang dihadiri sama dengan total peserta, berarti absensi selesai
        if ($totalPeserta > 0 && $totalPeserta == $pesertaDihadiri) {
            // Update status di tabel 'rapat' menjadi 'selesai'
            $sqlUpdateRapat = "UPDATE rapat SET status = 'selesai' WHERE id = ?";
            $stmtUpdateRapat = $conn->prepare($sqlUpdateRapat);
            $stmtUpdateRapat->bind_param("i", $agendaId);
            $stmtUpdateRapat->execute();
        }
        
        $conn->commit();
        sendResponse(true, 'Perubahan berhasil disimpan!');
    } catch (Exception $e) {
        $conn->rollback();
        sendResponse(false, 'Gagal menyimpan perubahan. Terjadi kesalahan: ' . $e->getMessage());
    }
// --- AKSI HAPUS ---
} elseif ($action === 'hapus') {
    $agendaId = $_POST['id'];
    if (empty($agendaId)) {
        sendResponse(false, 'ID agenda tidak ditemukan!');
    }

    // Gunakan transaksi untuk memastikan semua data terhapus dengan aman
    $conn->begin_transaction();
    try {
        // 1. Hapus data peserta terkait dari tabel 'agenda_peserta'
        $sqlPeserta = "DELETE FROM agenda_peserta WHERE agenda_id = ?";
        $stmtPeserta = $conn->prepare($sqlPeserta);
        $stmtPeserta->bind_param("i", $agendaId);
        $stmtPeserta->execute();

        // 2. Hapus data agenda utama dari tabel 'agendas'
        $sqlAgenda = "DELETE FROM agendas WHERE id = ?";
        $stmtAgenda = $conn->prepare($sqlAgenda);
        $stmtAgenda->bind_param("i", $agendaId);
        $stmtAgenda->execute();

        // 3. Hapus data agenda yang bersesuaian dari tabel 'rapat' agar dashboard juga terupdate
        $sqlRapat = "DELETE FROM rapat WHERE id = ?";
        $stmtRapat = $conn->prepare($sqlRapat);
        $stmtRapat->bind_param("i", $agendaId);
        $stmtRapat->execute();

        // Jika semua berhasil, simpan perubahan
        $conn->commit();
        sendResponse(true, 'Agenda rapat dan semua data terkait berhasil dihapus!');
    } catch (Exception $e) {
        // Jika ada error, batalkan semua perubahan
        $conn->rollback();
        sendResponse(false, 'Gagal menghapus agenda. Terjadi kesalahan: ' . $e->getMessage());
    }
}
 $conn->close();
?>