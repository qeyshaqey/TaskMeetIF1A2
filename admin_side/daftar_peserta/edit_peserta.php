<?php
// Set header untuk memastikan respons dikenali sebagai JSON
header('Content-Type: application/json');

include 'koneksi.php';

// Inisialisasi array respons
 $response = [
    'success' => false,
    'message' => 'Permintaan tidak valid.',
    'data' => null
];

// 1. Periksa apakah parameter 'id' ada dan merupakan angka yang valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    // 2. Bersihkan input untuk mencegah SQL Injection
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 3. Jalankan query
    $query = "SELECT * FROM peserta WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    // 4. Periksa apakah query berhasil dan menemukan data
    if ($result && mysqli_num_rows($result) > 0) {
        // Ambil data peserta
        $data = mysqli_fetch_assoc($result);
        
        // Siapkan respons sukses
        $response['success'] = true;
        $response['message'] = 'Data peserta ditemukan.';
        $response['data'] = $data;

    } else {
        // Siapkan respons jika data tidak ditemukan
        $response['message'] = 'Peserta dengan ID tersebut tidak ditemukan.';
    }

} else {
    // Respons jika ID tidak valid atau tidak ada
    $response['message'] = 'ID peserta tidak valid atau tidak disertakan.';
}

// 5. Kembalikan respons dalam format JSON
echo json_encode($response);
?>