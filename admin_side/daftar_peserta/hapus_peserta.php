<?php
include 'koneksi.php';

// Set header untuk memberitahu browser bahwa respons adalah JSON
header('Content-Type: application/json');

 $response = [];

// Ambil ID dari request
 $id = $_GET['id'];

// Cegah SQL Injection (sangat penting!)
 $id = mysqli_real_escape_string($koneksi, $id);

// Query hapus
 $hapus = mysqli_query($koneksi, "DELETE FROM peserta WHERE id = '$id'");

if ($hapus) {
    $response['success'] = true;
    $response['message'] = 'Peserta berhasil dihapus.';
} else {
    $response['success'] = false;
    $response['message'] = 'Gagal menghapus data peserta.';
}

// Kembalikan respons dalam format JSON
echo json_encode($response);
?>