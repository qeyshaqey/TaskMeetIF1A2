<?php
require_once 'session.php';
require_once 'functions.php';

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke halaman login
redirect('login.php');
?>