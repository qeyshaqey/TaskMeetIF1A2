<?php
// Konfigurasi koneksi database
 $host = "localhost";
 $user = "root"; // Ganti dengan username database Anda
 $pass = ""; // Ganti dengan password database Anda
 $dbname = "db_rapat"; // Nama database yang dibuat

// Buat koneksi
 $conn = new mysqli($host, $user, $pass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset
 $conn->set_charset("utf8mb4");

return $conn;

?>