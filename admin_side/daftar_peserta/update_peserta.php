<?php
// Set header untuk memastikan respons dikenali sebagai JSON
header('Content-Type: application/json');

include "koneksi.php";

// Inisialisasi array respons
 $response = [
    'success' => false,
    'message' => 'Permintaan tidak valid.'
];

// Periksa apakah semua data POST yang diperlukan ada
if (isset($_POST['id'], $_POST['nama'], $_POST['jurusan'], $_POST['prodi'], $_POST['email'])) {

    // Gunakan Prepared Statements untuk mencegah SQL Injection
    $sql = "UPDATE peserta SET nama = ?, jurusan = ?, prodi = ?, email = ? WHERE id = ?";
    
    // Persiapkan statement
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Ikat parameter ke statement
        // 'ssssi' berarti: string, string, string, string, integer
        mysqli_stmt_bind_param($stmt, "ssssi", $_POST['nama'], $_POST['jurusan'], $_POST['prodi'], $_POST['email'], $_POST['id']);
        
        // Jalankan statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika eksekusi berhasil
            $response['success'] = true;
            $response['message'] = 'Data peserta berhasil diperbarui.';
        } else {
            // Jika eksekusi gagal
            $response['message'] = 'Gagal memperbarui data: ' . mysqli_stmt_error($stmt);
        }
        
        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        // Jika persiapan statement gagal
        $response['message'] = 'Gagal mempersiapkan query: ' . mysqli_error($koneksi);
    }

} else {
    // Jika data POST tidak lengkap
    $response['message'] = 'Data yang dikirim tidak lengkap.';
}

// Tutup koneksi database
mysqli_close($koneksi);

// Kembalikan respons dalam format JSON
echo json_encode($response);
?>