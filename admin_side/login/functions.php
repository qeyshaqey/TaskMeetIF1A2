<?php
// Fungsi untuk membersihkan input
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if (isset($conn)) {
        $data = $conn->real_escape_string($data);
    }
    return $data;
}

// Fungsi untuk redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Fungsi untuk menampilkan pesan
function display_message($type, $message) {
    return "<div class='alert alert-$type'>$message</div>";
}

// Fungsi untuk memeriksa apakah user sudah login
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk memeriksa apakah user adalah admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}
?>