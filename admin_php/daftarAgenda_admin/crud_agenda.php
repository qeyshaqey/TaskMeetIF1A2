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
    $pesertaList = isset($_POST['peserta']) ? $_POST['peserta'] : [];

    if (empty($judul) || empty($jurusan) || empty($tanggal) || empty($waktu) || empty($host) || empty($tempatDetail)) {
        sendResponse(false, 'Semua field wajib diisi!');
    }

    $conn->begin_transaction();
    try {
        $sqlAgenda = "INSERT INTO agendas (judul_rapat, jurusan, tanggal, waktu, tipe_tempat, tempat_detail, host) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtAgenda = $conn->prepare($sqlAgenda);
        $stmtAgenda->bind_param("sssssss", $judul, $jurusan, $tanggal, $waktu, $tipeTempat, $tempatDetail, $host);
        $stmtAgenda->execute();
        $agendaId = $conn->insert_id;

        if (!empty($pesertaList)) {
            $sqlPeserta = "INSERT INTO agenda_peserta (agenda_id, nama_peserta) VALUES (?, ?)";
            $stmtPeserta = $conn->prepare($sqlPeserta);
            foreach ($pesertaList as $peserta) {
                $stmtPeserta->bind_param("is", $agendaId, $peserta);
                $stmtPeserta->execute();
            }
        }
        $conn->commit();
        sendResponse(true, 'Agenda rapat berhasil ditambahkan!');
    } catch (Exception $e) {
        $conn->rollback();
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
    // ... (kode edit yang sudah ada) ...
    $agendaId = $_POST['id'];
    $judul = $_POST['judul_rapat'];
    $jurusan = $_POST['jurusan'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $host = $_POST['host'];
    $tempat = $_POST['tempat'];
    $pesertaStatus = isset($_POST['peserta_status']) ? json_decode($_POST['peserta_status'], true) : [];

    if (empty($agendaId) || empty($judul) || empty($jurusan) || empty($tanggal) || empty($waktu) || empty($host) || empty($tempat)) {
        sendResponse(false, 'Semua field wajib diisi!');
    }

    $conn->begin_transaction();
    try {
        $sqlAgenda = "UPDATE agendas SET judul_rapat=?, jurusan=?, tanggal=?, waktu=?, tempat_detail=?, host=? WHERE id=?";
        $stmtAgenda = $conn->prepare($sqlAgenda);
        $stmtAgenda->bind_param("ssssssi", $judul, $jurusan, $tanggal, $waktu, $tempat, $host, $agendaId);
        $stmtAgenda->execute();

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
    $sql = "DELETE FROM agendas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $agendaId);
    if ($stmt->execute()) {
        sendResponse(true, 'Agenda rapat berhasil dihapus!');
    } else {
        sendResponse(false, 'Gagal menghapus agenda.');
    }
} else {
    sendResponse(false, 'Aksi tidak dikenali!');
}

 $conn->close();
?>