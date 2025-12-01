<?php
require_once 'session.php';
require_once 'functions.php';

// Jika user belum login, redirect ke halaman login
if (!is_logged_in()) {
    redirect('login.php');
} else {
    // Jika sudah login, redirect ke dashboard
    redirect('../admin_side/dashboard/dashboard.php');
}
?>