<?php
// Mulai sesi
session_start();

// Cek apakah user sudah login dengan memeriksa sesi
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada sesi user, redirect ke halaman login
    header("Location: index.php");
    exit();
}

// Cegah halaman di-cache agar tidak dapat diakses setelah logout menggunakan tombol back
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// Tambahkan konten dashboard di bawah ini
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AriezzNote</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/my_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMB0m7iIbq2E5Dg7F2H/oMx5NHX9W8IefxHc5F" crossorigin="anonymous">
    <link rel="icon" href="../assets/images/aries.png">
    <style>
        body {
            background: linear-gradient(to right, #a7faee, #fff);
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#" style="font-weight:bold; font-size:25px;">
                <img src="../assets/images/book.png" alt="Logo" style="width: 100px; height: auto; margin-right: 10px;">
                AriezzNote <br> <?= $_SESSION['full_name']; ?> (<?= $_SESSION['role']; ?>)
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-light px-4 ms-1" href="../users/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Section -->
    <section id="admin-section" class="section admin-section">
        <div class="container">
            <h1 class="text-center" style="font-weight:bold;">Admin Dashboard</h1>
            <hr style="margin:40px auto; border: 3px solid; width: 250px;">

            <!-- Admin controls -->
            <div class="row justify-content-center align-items-center"">
                <!-- Novel Section -->
                <div class=" col-md-4">
                <div class="card">
                    <img src="../assets/images/novels.jpg" class="card-img-center" alt="Novels">
                    <div class="card-body">
                        <h5 class="card-title">Novels</h5>
                        <p class="card-text">Jelajahi berbagai novel dari berbagai genre dan penulis.</p>
                        <a href="novels.php" class="btn btn-primary">Lihat Novels</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
   <footer class="footer text-light bg-dark p-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-0">&copy; 2024 AriezzNote. All Rights Reserved.</p>
            <div class="social-links d-flex mt-3 mt-md-0">
                <a href="https://www.instagram.com/aprilliamalnn?igsh=OGk0a3lvYTlpMHZr" class="text-light mx-3" title="Instagram" target="_blank">
                    <img src="../assets/images/instagram.png" alt="Instagram" class="social-icon">
                </a>
                <a href="https://wa.me/+62881012712964" class="text-light mx-3" title="WhatsApp" target="_blank">
                    <img src="../assets/images/whatsapp.png" alt="WhatsApp" class="social-icon">
                </a>
                <a href="mailto:aprilliamaln@gmail.com" class="text-light mx-3" title="Email" target="_blank">
                    <img src="../assets/images/gmail.png" alt="Email" class="social-icon">
                </a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>