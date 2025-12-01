<?php
require_once 'session.php';
require_once 'auth.php';

 $error = '';
 $success = '';

// Proses permintaan reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    if (empty($email)) {
        $error = 'Email harus diisi';
    } else {
        // Cek apakah email ada di database dan role-nya user
        $sql = "SELECT id, username, email FROM users WHERE email = ? AND role = 'user'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Generate token reset password
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Simpan token ke database
            $sql = "INSERT INTO password_resets (user_id, email, token, expiry) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $user['id'], $user['email'], $token, $expiry);
            
            if ($stmt->execute()) {
                // Untuk development, tampilkan linknya. Untuk production, gunakan fungsi mail()
                $reset_link = "http://localhost/PBL-FIX/autentikasi/reset_password_confirm.php?token=" . $token;
                $success = 'Link reset password telah dibuat. (Untuk testing: <a href="' . $reset_link . '">' . $reset_link . '</a>)';
            } else {
                $error = 'Terjadi kesalahan. Silakan coba lagi.';
            }
        } else {
            // Tetap tampilkan pesan sukses meskipun email tidak ditemukan untuk keamanan
            $success = 'Jika email terdaftar, link reset password akan dikirim.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... (Gunakan style CSS yang sudah Anda buat) ... */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url(https://images.unsplash.com/photo-1557804506-669a67965ba0) no-repeat center center fixed;
            background-size: cover;
        }
        form {
            width: 30%;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }
        h3 {
            text-align: center;
            font-size: 1.17em; 
            font-weight: bold;
            margin-bottom: 20px;
        }
        img {
            display: block;
            margin: 0 auto 20px;
        }
        .input-group {
            position: relative;
            margin-bottom: 15px;
        }
        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            z-index: 10;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            padding-left: 35px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #784B84;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #702963;
        }
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        .back-to-login a {
            color: #784B84;
            text-decoration: none;
            font-size: 14px;
        }
        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Form akan mengirim data ke backend PHP secara normal -->
    <form action="reset_password.php" method="post">
        <img width="150" height="150" src="../admin_side/format_gambar/logo.png" alt="Logo">
        <h3>Atur Ulang Kata Sandi Pengguna</h3>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
        </div>
        
        <button type="submit">Kirim Link Reset</button>
        
        <div class="back-to-login">
            <a href="login.php">
                <i class="fas fa-arrow-left me-1"></i> Kembali masuk
            </a>
        </div>
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- HAPUS JAVASCRIPT YANG MENCEGAH FORM SUBMIT -->
</body>
</html>