<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    echo "ERROR";
    exit();
}

$id = $_SESSION['user_id'];

// Ambil data dari AJAX
$nama     = $_POST['nama'];
$nim      = $_POST['nim'];
$email    = $_POST['email'];
$jurusan  = $_POST['jurusan'];
$prodi    = $_POST['prodi'];

// Handle foto terbaru
$fotoBaru = null;

if (!empty($_FILES['foto']['name'])) {

    $namaFile = $_FILES['foto']['name'];
    $tmpFile  = $_FILES['foto']['tmp_name'];
    $folder   = "uploads/";

    // buat folder kalau belum ada
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $namaBaru = time() . "_" . $namaFile;

    if (move_uploaded_file($tmpFile, $folder . $namaBaru)) {
        $fotoBaru = $namaBaru;

        // hapus foto lama
        $qUser = mysqli_query($koneksi, "SELECT foto FROM users WHERE id='$id'");
        $oldFoto = mysqli_fetch_assoc($qUser)['foto'];

        if ($oldFoto && file_exists("uploads/" . $oldFoto)) {
            unlink("uploads/" . $oldFoto);
        }
    }
}

// Query update
$query = "UPDATE users SET 
            nama='$nama',
            nim='$nim',
            email='$email',
            jurusan='$jurusan',
            prodi='$prodi'";

if ($fotoBaru !== null) {
    $query .= ", foto='$fotoBaru'";
}

$query .= " WHERE id='$id'";

$update = mysqli_query($koneksi, $query);

if ($update) {
    echo "OK";
} else {
    echo "ERROR";
}
?>
