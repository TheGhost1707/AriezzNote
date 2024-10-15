<?php
// Mulai sesi
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Mencegah caching untuk halaman sebelumnya setelah logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect ke halaman login setelah logout
header("Location: index.php");
exit();
?>