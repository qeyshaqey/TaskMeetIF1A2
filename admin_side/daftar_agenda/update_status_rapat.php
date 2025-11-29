<?php
require_once 'config.php';

// Update status rapat berdasarkan waktu saat ini
// Logika baru:
// - Jika sudah waktunya, ubah status menjadi 'berlangsung'
// - Jika status sudah 'selesai', jangan ubah apa-apa
 $sql = "UPDATE rapat SET status = 
        CASE 
            -- Jika status sudah selesai, biarkan saja
            WHEN status = 'selesai' THEN 'selesai'
            -- Jika waktu rapat sudah lewat dan status belum selesai, ubah jadi 'berlangsung'
            WHEN CONCAT(tanggal, ' ', waktu) < NOW() THEN 'berlangsung'
            -- Selain itu, statusnya 'akan datang'
            ELSE 'akan datang'
        END
        -- Hanya update rapat yang statusnya belum 'selesai' untuk efisiensi
        WHERE status != 'selesai'";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Status rapat berhasil diperbarui']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status: ' . $conn->error]);
}

 $conn->close();
?>