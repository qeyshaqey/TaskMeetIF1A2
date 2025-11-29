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
if (isset($_POST['nama'], $_POST['jurusan'], $_POST['prodi'], $_POST['email'])) {

    // Gunakan Prepared Statements untuk mencegah SQL Injection
    $sql = "INSERT INTO peserta (nama, jurusan, prodi, email) VALUES (?, ?, ?, ?)";
    
    // Persiapkan statement
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Ikat parameter ke statement
        // 'ssss' berarti: string, string, string, string
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['nama'], $_POST['jurusan'], $_POST['prodi'], $_POST['email']);
        
        // Jalankan statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika eksekusi berhasil
            $response['success'] = true;
            $response['message'] = 'Data peserta berhasil ditambahkan.';
            $response['inserted_id'] = mysqli_insert_id($koneksi); // Ambil ID dari data yang baru ditambahkan
        } else {
            // Jika eksekusi gagal
            $response['message'] = 'Gagal menambah data: ' . mysqli_stmt_error($stmt);
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