<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Query hapus
$hapus = mysqli_query($koneksi, "DELETE FROM peserta WHERE id = '$id'");

if ($hapus) {
    echo "<script>
        alert('Data berhasil dihapus');
        window.location='peserta.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus data');
        window.location='peserta.php';
    </script>";
}
?>
