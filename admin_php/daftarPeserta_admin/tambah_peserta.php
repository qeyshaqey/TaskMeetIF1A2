<?php
include 'koneksi.php';
$nama    = $_POST['nama'];
$jurusan = $_POST['jurusan'];
$prodi   = $_POST['prodi'];
$email   = $_POST['email'];

$input = mysqli_query($koneksi, "INSERT INTO peserta (nama, jurusan, prodi, email) VALUES('$nama', '$jurusan', '$prodi', '$email')") or die(mysqli_error($koneksi));


if($input){
    echo "<script>
    alert('Data Berhasil Disimpan');
    window.location.href = 'peserta.php';
    </script>";
} else {
    echo "<script>
    alert('Gagal Menyimpan Data');
    window.location.href = 'peserta.php';
    </script>";
}
?>
