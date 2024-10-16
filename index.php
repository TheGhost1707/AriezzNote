<?php
include 'users/novel/koneksi.php';
// Start session
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Cek apakah pengguna sudah follow atau belum
$followersCount = 0; // Default followers count
$isFollowing = false; // Default follow status
if ($isLoggedIn) {
    try {
        $followerId = $_SESSION['user_id'];
        $followedId = 1; // Misalnya 1 adalah ID pengguna yang profilnya sedang ditampilkan

        // Query untuk cek apakah sudah follow
        $stmt = $conn->prepare("SELECT * FROM follows WHERE follower_id = :follower_id AND followed_id = :followed_id");
        $stmt->bindParam(':follower_id', $followerId);
        $stmt->bindParam(':followed_id', $followedId);
        $stmt->execute();
        $isFollowing = $stmt->rowCount() > 0;

        // Query untuk mendapatkan jumlah followers
        $followersStmt = $conn->prepare("SELECT COUNT(*) FROM follows WHERE followed_id = :followed_id");
        $followersStmt->bindParam(':followed_id', $followedId);
        $followersStmt->execute();
        $followersCount = $followersStmt->fetchColumn();
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AriezzNote</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="assets/images/aries.png">
    <link rel="stylesheet" href="assets/css/my_style.css">
    <!-- Add this in the <head> section for Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMB0m7iIbq2E5Dg7F2H/oMx5NHX9W8IefxHc5F" crossorigin="anonymous">
    <style>
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#" style="font-weight:bold;">
                <img src="assets/images/book.png" alt="Logo" style="width: 100px; height: auto; margin-right: 10px;">
                AriezzNote <br>
                <?php if (isset($_SESSION['full_name'])) : ?>
                    <?= htmlspecialchars($_SESSION['full_name']); ?>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <?php if (isset($_SESSION['full_name'])) : ?>
                            <a class="nav-link btn btn-danger text-light px-4 ms-1" href="users/logout.php">Logout</a>
                        <?php else : ?>
                            <a class="nav-link btn btn-success text-light px-4 ms-1" href="users/index.php">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Content Section (About) -->
    <section id="about" class="section content-section">
        <div class="container">
            <div class="box-container p-4 border rounded shadow" style="width:100%; background-color: white;">
                <h1 class="text-center mb-5" style="font-weight:bold;">Tentang Saya</h1>
                <div class="row align-items-center">
                    <!-- Profile Image -->
                    <div class="col-md-4 text-center">
                        <img src="assets/images/profile.jpg" alt="Profile Picture" class="rounded-circle img-fluid" style="width: 300px; height: auto; border:8px solid grey;">
                        <!-- Follow Button and Followers Count (Below Image) -->
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            <?php if ($isLoggedIn) : ?>
                                <!-- Tombol Follow -->
                                <button class="btn <?= $isFollowing ? 'btn-secondary' : 'btn-success' ?> me-3" id="follow-btn">
                                    <?= $isFollowing ? 'Mengikuti' : 'Ikuti' ?>
                                </button>
                            <?php else : ?>
                                <!-- Tombol login jika belum login -->
                                <button class="btn btn-success me-3" onclick="showLoginNotification()">Ikuti</button>
                                <script>
                                    function showLoginNotification() {
                                        // Menampilkan notifikasi sebelum diarahkan ke halaman login
                                        alert("Ups! Maaf kamu harus login supaya bisa Follow penulis ini.");
                                        // Arahkan ke halaman login setelah menutup notifikasi
                                        window.location.href = 'users/index.php';
                                    }
                                </script>
                            <?php endif; ?>
                            <!-- Tampilkan jumlah followers -->
                            <span id="followers-count" class="text-muted" style="font-size:20px">
                                <?= htmlspecialchars($followersCount) ?> Followers
                            </span>
                        </div>
                    </div>
                    <!-- Profile Description -->
                    <div class="col-md-8">
                        <h3>Aprilliani Maulina</h3>
                        <p style="text-align: justify;">
                            Aprilliani lahir di kota Bogor pada tahun 2007, dia mulai menjelajahi dunia tulisan semenjak SMP dengan aplikasi Wattpad lalu pindah ke website <strong>AriezzNote</strong>.
                            Website ini adalah platform tempat Anda dapat menjelajahi koleksi tulisan pribadi saya.
                            Dari novel dan cerita pendek yang menawan hingga catatan yang penuh wawasan, semua yang ada di sini saya buat dengan tujuan untuk memberikan kegembiraan dan inspirasi bagi pembaca seperti Anda.
                        </p>
                        <p style="text-align: justify;">
                            Saya harap Anda menikmati menjelajahi konten yang telah saya susun dan menemukan sesuatu yang sesuai dengan Anda.
                            Baik Anda mencari petualangan dalam novel, kebijaksanaan dalam catatan, atau pelarian singkat dalam cerita pendek,
                            saya mengundang Anda untuk membenamkan diri dalam perjalanan sastra ini. Jangan ragu untuk menyelami cerita,
                            mencatat, dan terlibat dengan kreativitas yang mengalir melalui halaman-halaman ini.
                            Ruang ini bukan sekadar kumpulan tulisan; ini adalah komunitas bagi semua penggemar membaca,
                            di mana imajinasi tidak mengenal batas dan inspirasi selalu ada di ujung jari Anda.
                            Bergabunglah dengan saya dalam merayakan keindahan mendongeng dan kegembiraan membaca!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Content Section (Combined) -->
    <section id="content" class="section content-section">
        <div class="container">
            <h1 class="text-center" style="font-weight:bold;">Koleksi Saya</h1>
            <hr style="margin:40px auto; border: 3px solid; width: 250px;">
            <div class="row justify-content-center align-items-center"">
                <!-- Novel Section -->
                <div class=" col-md-4">
                <div class="card">
                    <img src="assets/images/novels.jpg" class="card-img-center" alt="Novels">
                    <div class="card-body">
                        <h5 class="card-title">Novels</h5>
                        <p class="card-text">Jelajahi berbagai novel dari berbagai genre dan penulis.</p>
                        <a href="users/novel/novels.php" class="btn btn-primary">Lihat Novels</a>
                    </div>
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
                    <img src="assets/images/instagram.png" alt="Instagram" class="social-icon">
                </a>
                <a href="https://wa.me/+62881012712964" class="text-light mx-3" title="WhatsApp" target="_blank">
                    <img src="assets/images/whatsapp.png" alt="WhatsApp" class="social-icon">
                </a>
                <a href="mailto:aprilliamaln@gmail.com" class="text-light mx-3" title="Email" target="_blank">
                    <img src="assets/images/gmail.png" alt="Email" class="social-icon">
                </a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Optional: JavaScript to handle follow action -->

    <!-- JavaScript untuk follow/unfollow -->
    <script>
        document.getElementById('follow-btn').addEventListener('click', function() {
            var followBtn = this;
            var followersCountElem = document.getElementById('followers-count');
            var currentCount = parseInt(followersCountElem.textContent);

            // Menggunakan AJAX untuk mengirim permintaan follow/unfollow
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "follow.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.status === 'followed') {
                        followBtn.classList.remove('btn-success');
                        followBtn.classList.add('btn-secondary');
                        followBtn.textContent = 'Mengikuti';
                        followersCountElem.textContent = (currentCount + 1) + ' Followers';
                    } else if (response.status === 'unfollowed') {
                        followBtn.classList.remove('btn-secondary');
                        followBtn.classList.add('btn-success');
                        followBtn.textContent = 'Ikuti';
                        followersCountElem.textContent = (currentCount - 1) + ' Followers';
                    }
                }
            };

            xhr.send(); // Mengirim permintaan tanpa data tambahan
        });
    </script>
</body>

</html>