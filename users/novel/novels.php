<?php
// Mulai sesi
session_start();

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
    <title>Kumpulan Novel - AriezzNote</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/my_style.css">
    <link rel="icon" href="../../assets/images/aries.png">
    <!-- Add this in the <head> section for Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMB0m7iIbq2E5Dg7F2H/oMx5NHX9W8IefxHc5F" crossorigin="anonymous">
    <style>
        /* Styling untuk card dan animasi */
        body {
            background: linear-gradient(to right, #a7faee, #fff);
        }

        .card {
            width: 300px;
            border-radius: 20px;
            transition: all 0.3s ease-in-out;
            margin: 0px auto;
        }

        .card-img-top {
            border-radius: 10px 10px 0 0;
        }

        /* Styling untuk title, text, dan synopsis */
        .card-title {
            font-size: 15px;
            font-weight: bold;
            text-align: left;
        }

        .card-text {
            font-size: 10px;
            color: #555;
            text-align: justify;
        }

        .synopsis {
            height: 80px;
            display: -webkit-box;
            -webkit-line-clamp: 25;
            /* Tampilkan hanya 4 baris */
            -webkit-box-orient: vertical;
        }

        /* Responsive Design */
        @media (max-width: 576px) {

            /* Menyusun ulang grid untuk tampilan HP */
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 20px;
            }

            .card {
                width: 350px;
                margin: 35px auto;
            }

            .card-title {
                font-size: 18px;
                /* Ukuran font lebih kecil di HP */
            }

            .card-text {
                font-size: 14px;
                text-align: left;
            }

            .card-body {
                width: 350px;
                padding: 15px;
            }

            /* Menyesuaikan tinggi gambar agar lebih proporsional */
            .card-img-top {
                height: 200px;
                object-fit: cover;
            }

            /* Adjust button padding and font size */
            .btn-primary {
                font-size: 16px;
                padding: 10px 20px;
            }

            .btn-danger {
                font-size: 16px;
                padding: 10px 20px;
            }

            .btn-warning {
                font-size: 16px;
                padding: 10px 20px;
            }

            /* Batasi panjang sinopsis pada tampilan HP */
            .synopsis {
                -webkit-line-clamp: 3;
                /* Batasi hanya 3 baris untuk layar kecil */
                height: auto;
            }

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
                <img src="../../assets/images/book.png" alt="Logo" style="width: 100px; height: auto; margin-right: 10px;">
                AriezzNote <br>
                <?php if (isset($_SESSION['full_name'])) : ?>
                    <?= htmlspecialchars($_SESSION['full_name']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <?php if (isset($_SESSION['full_name'])) : ?>
                            <a class="nav-link btn btn-danger text-light px-4 ms-1" href="../../users/logout.php">Logout</a>
                        <?php else : ?>
                            <a class="nav-link btn btn-success text-light px-4 ms-1" href="../../users/index.php">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <!-- Back Button -->
        <button onclick="window.history.back()" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Kembali
        </button>
        <h1 class="text-center mb-3" style="font-weight:bold;">Kumpulan Novel</h1>
        <hr style="margin:40px auto; border: 3px solid; width: 250px;">
        <?php
        include 'koneksi.php';
        // Fetch novels from the database
        $stmt = $conn->query("SELECT * FROM novels");
        $novels = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="row justify-content-center align-items-center">
            <?php foreach ($novels as $novel) : ?>
                <div class="col-md-4 mb-2">
                    <div class="card h-100 shadow">
                        <!-- Menggunakan gambar yang diupload dan disimpan di database -->
                        <img src="../../uploads/<?= htmlspecialchars($novel['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($novel['title']) ?>" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($novel['title']) ?></h5>
                            <p class="card-text text-muted"><?= date('d F Y', strtotime($novel['created_at'])) ?></p>
                            <p class="card-text synopsis">Sinopsis: <br><?= htmlspecialchars($novel['synopsis']) ?></p>
                            <a href="read_novels.php?id=<?= $novel['id'] ?>" class="btn btn-success mt-3" style="width:100%; text-align:center;">Baca Sekarang</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
   <!-- Footer -->
   <footer class="footer text-light bg-dark p-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-0">&copy; 2024 AriezzNote. All Rights Reserved.</p>
            <div class="social-links d-flex mt-3 mt-md-0">
                <a href="https://www.instagram.com/aprilliamalnn?igsh=OGk0a3lvYTlpMHZr" class="text-light mx-3" title="Instagram" target="_blank">
                    <img src="../../assets/images/instagram.png" alt="Instagram" class="social-icon">
                </a>
                <a href="https://wa.me/+62881012712964" class="text-light mx-3" title="WhatsApp" target="_blank">
                    <img src="../../assets/images/whatsapp.png" alt="WhatsApp" class="social-icon">
                </a>
                <a href="mailto:aprilliamaln@gmail.com" class="text-light mx-3" title="Email" target="_blank">
                    <img src="../../assets/images/gmail.png" alt="Email" class="social-icon">
                </a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>