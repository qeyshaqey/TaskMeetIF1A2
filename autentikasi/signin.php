<?php
require_once 'session.php';
require_once 'auth.php';

// Jika user sudah login, redirect ke dashboard user
if (is_logged_in()) {
    redirect('../user_side/dashboard_pengguna.php');
}

 $error = '';
 $success = '';

// Proses registrasi (Backend)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    
    // Validasi
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'Semua field harus diisi';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } else {
        // Panggil fungsi register dari auth.php
        if (register($username, $email, $password, $full_name)) {
            // Jika berhasil, redirect ke halaman login dengan pesan sukses
            redirect('login.php?registered=1');
        } else {
            $error = 'Registrasi gagal. Username atau email mungkin sudah digunakan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... (Gunakan style CSS yang sudah Anda buat) ... */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url(https://images.unsplash.com/photo-1557804506-669a67965ba0) no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }
        form {
            width: 35%;
            max-width: 450px;
            margin: 30px auto;
            padding: 30px;
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
            max-width: 100%;
            height: auto;
        }
        .input-group {
            position: relative;
            margin-bottom: 15px;
        }
        .input-group i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            z-index: 10;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            padding-left: 40px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #784B84;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #702963;
        }
        .sign p {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 0;
        }
        .terms {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .terms input {
            margin-right: 0;
        }
        .terms label {
            margin-bottom: 0;
            display: inline;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Form akan mengirim data ke backend PHP secara normal -->
    <form action="signin.php" method="post">
        <img width="150" height="150" src="../admin_side/format_gambar/logo.png" alt="Logo">
        <h3>Daftar ke sesi Anda</h3>
        
        <!-- Tampilkan pesan error dari backend PHP -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>
        
        <div class="input-group">
            <i class="fas fa-user-circle"></i>
            <input type="text" id="username" name="username" placeholder="Nama Pengguna" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Kata Sandi" required>
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" id="confirmPassword" name="confirm_password" placeholder="Konfirmasi Kata Sandi" required>
        </div>

        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" id="full_name" name="full_name" placeholder="Nama Lengkap" required>
        </div>
        
        <div class="terms">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">Saya setuju dengan<a href="#" style="color: #784B84;"> syarat dan ketentuan</a></label>
        </div>
        
        <button type="submit">Daftar</button>
        
        <div class="sign">
            <p>Sudah ada akun?<a href="login.php"> Masuk</a></p>
        </div>
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- HAPUS JAVASCRIPT YANG MENCEGAH FORM SUBMIT -->
</body>
</html>