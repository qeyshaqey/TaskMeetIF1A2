<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$id = $_SESSION['user_id'];

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id='$id'");
$user = mysqli_fetch_assoc($query);

echo json_encode([
    "nama"    => $user['nama'],
    "username"=> $user['username'],
    "email"   => $user['email'],
    "nim"     => $user['nim'],
    "jurusan" => $user['jurusan'],
    "prodi"   => $user['prodi'],
    "foto"    => $user['foto']
]);
?>
