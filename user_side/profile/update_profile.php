<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    echo "NO_SESSION";
    exit();
}

 $id = $_SESSION['user_id'];

// Ambil data user dari database
 $q = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'");
 $user = mysqli_fetch_assoc($q);

if (!$user) {
    echo "USER_NOT_FOUND";
    exit();
}

// Ambil data input
 $nama    = $_POST['nama'];
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

    // Pastikan folder uploads ada
    $folderUpload = __DIR__ . "/uploads/";

    if (!is_dir($folderUpload)) {
        mkdir($folderUpload, 0777, true);
    }

    // Nama file baru
    $namaFile = time() . "_" . basename($_FILES['foto']['name']);
    $tmp = $_FILES['foto']['tmp_name'];
    $targetFile = $folderUpload . $namaFile;

    // Cek error upload
    if ($_FILES['foto']['error'] !== 0) {
        echo "UPLOAD_ERROR";
        exit();
    }

    // Pindahkan file
    if (!move_uploaded_file($tmp, $targetFile)) {
        echo "MOVE_FAILED";
        exit();
    }

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
$sql = "UPDATE users SET 
            full_name='$nama',
            email='$email',
            jurusan='$jurusan',
            prodi='$prodi',
            foto='$fotoBaru'
            $updatePasswordSQL
        WHERE id='$id'";

if (mysqli_query($koneksi, $sql)) {
    // Update session dengan data terbaru
    $_SESSION['full_name'] = $nama;
    $_SESSION['email'] = $email;
    $_SESSION['jurusan'] = $jurusan;
    $_SESSION['prodi'] = $prodi;
    $_SESSION['foto'] = $fotoBaru;
    
    echo "OK";
} else {
    echo "DB_ERROR";
}
?>