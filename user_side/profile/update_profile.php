<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    echo "NO_SESSION";
    exit();
}

$id = $_SESSION['user_id'];

// Ambil data user dari database
$q = mysqli_query($koneksi, "SELECT * FROM user WHERE id='$id'");
$user = mysqli_fetch_assoc($q);

if (!$user) {
    echo "USER_NOT_FOUND";
    exit();
}

// Ambil data input
$nama    = $_POST['nama'];
$nim     = $_POST['nim'];
$email   = $_POST['email'];
$jurusan = $_POST['jurusan'];
$prodi   = $_POST['prodi'];

$oldPass = $_POST['passwordOld'] ?? "";
$newPass = $_POST['passwordNew'] ?? "";
$confirm = $_POST['passwordConfirm'] ?? "";


// ==========================
// HANDLE UPDATE FOTO
// ==========================
$fotoBaru = $user['foto'];

if (!empty($_FILES['foto']['name'])) {
    $namaFile = time() . "_" . $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];

    move_uploaded_file($tmp, "uploads/" . $namaFile);
    $fotoBaru = $namaFile;
}


// ==========================
// VALIDASI UPDATE PASSWORD
// ==========================
$updatePasswordSQL = "";

if ($oldPass != "" || $newPass != "" || $confirm != "") {

    if ($oldPass == "") {
        echo "OLD_EMPTY";
        exit();
    }

    if ($oldPass !== $user['password']) {
        echo "WRONG_OLD";
        exit();
    }

    if ($newPass != $confirm) {
        echo "NEW_MISMATCH";
        exit();
    }

    $updatePasswordSQL = ", password='$newPass'";
}

// ==========================
// UPDATE DATA PROFIL
// ==========================
$sql = "UPDATE user SET 
            nama='$nama',
            nim='$nim',
            email='$email',
            jurusan='$jurusan',
            prodi='$prodi',
            foto='$fotoBaru'
            $updatePasswordSQL
        WHERE id='$id'";

if (mysqli_query($koneksi, $sql)) {
    echo "OK";
} else {
    echo "DB_ERROR";
}
