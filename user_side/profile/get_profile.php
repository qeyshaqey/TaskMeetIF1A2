<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$id = $_SESSION['user_id'];

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($query);

// Kirimkan semua data user (termasuk jurusan & prodi)
echo json_encode([
    "username" => $user['username'],
    "email"    => $user['email'],
    "nim"      => $user['nim'],
    "nama"     => $user['nama'],
    "jurusan"  => $user['jurusan'],
    "prodi"    => $user['prodi'],
    "foto"     => $user['foto']
]);
?>
