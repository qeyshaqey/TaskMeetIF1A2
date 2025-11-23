<?php
include "koneksi.php";

$id     = $_POST['id'];
$nama   = $_POST['nama'];
$jurusan = $_POST['jurusan'];
$prodi  = $_POST['prodi'];
$email  = $_POST['email'];

$db = "UPDATE peserta SET 
            nama='$nama',
            jurusan='$jurusan',
            prodi='$prodi',
            email='$email'
          WHERE id='$id'";

if (mysqli_query($koneksi, $db)) {
    echo "success";
} else {
    echo "error";
}
?>
