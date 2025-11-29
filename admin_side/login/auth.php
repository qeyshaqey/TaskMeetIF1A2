<?php
require_once '../daftar_agenda/config.php';
require_once 'functions.php';

// Fungsi login untuk user biasa
function login($username, $password) {
    global $conn;
    
    // Bersihkan input
    $username = clean_input($username);
    
    // Query untuk mendapatkan user
    $sql = "SELECT id, username, password, full_name, role FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            
            // Catat login attempt berhasil
            log_login_attempt($username, $_SERVER['REMOTE_ADDR'], true);
            
            return true;
        }
    }
    
    // Catat login attempt gagal
    log_login_attempt($username, $_SERVER['REMOTE_ADDR'], false);
    
    return false;
}

// Fungsi registrasi untuk user biasa
function register($username, $email, $password, $full_name) {
    global $conn;
    
    // Bersihkan input
    $username = clean_input($username);
    $email = clean_input($email);
    $full_name = clean_input($full_name);
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Query untuk insert user baru dengan role 'user'
    $sql = "INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, 'user')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $full_name);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Fungsi untuk mencatat login attempt
function log_login_attempt($username, $ip_address, $success) {
    global $conn;
    
    $sql = "INSERT INTO login_attempts (username, ip_address, success) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $ip_address, $success);
    $stmt->execute();
}
?>