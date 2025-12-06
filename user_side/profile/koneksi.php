<?php
$koneksi = mysqli_connect("localhost", "root", "", "taskmeet_db");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
