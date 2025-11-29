<?php
require_once 'config.php';

// Set response header agar frontend tahu ini adalah JSON
header('Content-Type: application/json');

try {
    // 1. Ambil SEMUA data dari 'agendas' dan hitung statusnya berdasarkan waktu
    $sql = "SELECT 
                id, judul_rapat, jurusan, tanggal, waktu,
                CASE 
                    WHEN CONCAT(tanggal, ' ', waktu) < NOW() THEN 'berlangsung'
                    ELSE 'akan datang'
                END as calculated_status
            FROM agendas";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // 2. Siapkan statement INSERT/UPDATE yang sangat pintar
        // Ini akan memasukkan data baru, atau memperbarui data yang sudah ada
        $stmt = $conn->prepare(
            "INSERT INTO rapat (id, judul_rapat, jurusan, tanggal, waktu, status) 
             VALUES (?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
               judul_rapat = VALUES(judul_rapat),
               jurusan = VALUES(jurusan),
               tanggal = VALUES(tanggal),
               waktu = VALUES(waktu),
               -- *** INI BAGIAN KRUSIALNYA ***
               -- Jika status di tabel rapat sudah 'selesai', biarkan saja.
               -- Jika belum, update dengan status baru yang dihitung dari waktu.
               status = IF(status = 'selesai', 'selesai', VALUES(status))"
        );
        
        // 3. Ulangi setiap baris dari 'agendas' dan jalankan statement
        while ($row = $result->fetch_assoc()) {
            // VALUES(status) merujuk pada status yang kita hitung di query atas (calculated_status)
            $stmt->bind_param("isssss", 
                $row['id'], 
                $row['judul_rapat'], 
                $row['jurusan'], 
                $row['tanggal'], 
                $row['waktu'], 
                $row['calculated_status'] 
            );
            $stmt->execute();
        }
        $stmt->close();
        
        // 4. Bersihkan: Hapus data rapat yang sudah tidak ada lagi di tabel agendas
        $conn->query("DELETE r FROM rapat r LEFT JOIN agendas a ON r.id = a.id WHERE a.id IS NULL");
        
        echo json_encode(['success' => true, 'message' => 'Sinkronisasi berhasil.']);
    } else {
        // Jika tabel 'agendas' kosong, kosongkan juga tabel 'rapat'
        $conn->query("TRUNCATE TABLE rapat");
        echo json_encode(['success' => true, 'message' => 'Tidak ada data di agendas untuk disinkronisasi.']);
    }

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat sinkronisasi: ' . $e->getMessage()]);
}

 $conn->close();
?>