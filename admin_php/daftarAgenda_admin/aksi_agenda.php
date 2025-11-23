<?php
// aksi_agenda.php

header('Content-Type: application/json');
require_once 'config.php';

// Fungsi untuk mengirim response JSON
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

 $action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'tambah') {
    // --- TAMBAH AGENDA ---
    $judul = $_POST['judul_rapat'];
    $jurusan = $_POST['jurusan'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $host = $_POST['host'];
    $tipeTempat = $_POST['tipe_tempat'];
    $tempatDetail = ($tipeTempat === 'online') ? $_POST['zoom_link'] : $_POST['tempat_offline'];
    $pesertaList = isset($_POST['peserta']) ? $_POST['peserta'] : [];

    // Validasi dasar
    if (empty($judul) || empty($jurusan) || empty($tanggal) || empty($waktu) || empty($host) || empty($tempatDetail)) {
        sendResponse(false, 'Semua field wajib diisi!');
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // 1. Insert data ke tabel agendas
        $sqlAgenda = "INSERT INTO agendas (judul_rapat, jurusan, tanggal, waktu, tipe_tempat, tempat_detail, host) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtAgenda = $conn->prepare($sqlAgenda);
        $stmtAgenda->bind_param("sssssss", $judul, $jurusan, $tanggal, $waktu, $tipeTempat, $tempatDetail, $host);
        $stmtAgenda->execute();
        $agendaId = $conn->insert_id;

        // 2. Insert data peserta ke tabel agenda_peserta
        if (!empty($pesertaList)) {
            $sqlPeserta = "INSERT INTO agenda_peserta (agenda_id, nama_peserta) VALUES (?, ?)";
            $stmtPeserta = $conn->prepare($sqlPeserta);
            
            foreach ($pesertaList as $peserta) {
                $stmtPeserta->bind_param("is", $agendaId, $peserta);
                $stmtPeserta->execute();
            }
        }

        // Jika semua berhasil, commit transaksi
        $conn->commit();
        sendResponse(true, 'Agenda rapat berhasil ditambahkan!');

    } catch (Exception $e) {
        // Jika ada error, rollback transaksi
        $conn->rollback();
        // Dalam produksi, log error ini, jangan tampilkan ke user
        // error_log($e->getMessage());
        sendResponse(false, 'Gagal menambahkan agenda. Terjadi kesalahan pada server.');
    }

} elseif ($action === 'edit') {
    // --- EDIT AGENDA ---
    $agendaId = $_POST['id'];
    $judul = $_POST['judul_rapat'];
    $jurusan = $_POST['jurusan'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $host = $_POST['host'];
    $tempat = $_POST['tempat']; // Di frontend, tempat sudah digabung jadi teks biasa
    $pesertaStatus = $_POST['peserta_status']; // Data dari frontend: {"nama_peserta": "Hadir/Tidak Hadir"}

    // Validasi
    if (empty($agendaId) || empty($judul) || empty($jurusan) || empty($tanggal) || empty($waktu) || empty($host) || empty($tempat)) {
        sendResponse(false, 'Semua field wajib diisi!');
    }

    $conn->begin_transaction();
    try {
        // 1. Update data di tabel agendas
        $sqlAgenda = "UPDATE agendas SET judul_rapat=?, jurusan=?, tanggal=?, waktu=?, tempat_detail=?, host=? WHERE id=?";
        $stmtAgenda = $conn->prepare($sqlAgenda);
        $stmtAgenda->bind_param("ssssssi", $judul, $jurusan, $tanggal, $waktu, $tempat, $host, $agendaId);
        $stmtAgenda->execute();

        // 2. Update status kehadiran peserta
        if (!empty($pesertaStatus)) {
            $pesertaData = json_decode($pesertaStatus, true);
            $sqlPeserta = "UPDATE agenda_peserta SET status_kehadiran = ? WHERE agenda_id = ? AND nama_peserta = ?";
            $stmtPeserta = $conn->prepare($sqlPeserta);

            foreach ($pesertaData as $nama => $status) {
                // Pastikan statusnya valid
                if (in_array($status, ['Hadir', 'Tidak Hadir'])) {
                    $stmtPeserta->bind_param("sis", $status, $agendaId, $nama);
                    $stmtPeserta->execute();
                }
            }
        }
        
        $conn->commit();
        sendResponse(true, 'Perubahan berhasil disimpan!');

    } catch (Exception $e) {
        $conn->rollback();
        sendResponse(false, 'Gagal menyimpan perubahan. Terjadi kesalahan pada server.');
    }

} elseif ($action === 'hapus') {
    // --- HAPUS AGENDA ---
    $agendaId = $_POST['id'];

    if (empty($agendaId)) {
        sendResponse(false, 'ID agenda tidak ditemukan!');
    }

    // Karena sudah ada `ON DELETE CASCADE` di foreign key, kita hanya perlu menghapus dari tabel utama
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