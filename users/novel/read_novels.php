<?php
include 'koneksi.php';  // Pastikan koneksi ke database sudah dilakukan
// Start session
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

// Cegah halaman di-cache agar tidak dapat diakses setelah logout menggunakan tombol back
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include 'koneksi.php';  // Pastikan koneksi ke database sudah dilakukan

// Ambil ID novel dari URL
$novel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch novel details
$novel_stmt = $conn->prepare("SELECT title, created_at, synopsis FROM novels WHERE id = ?");
$novel_stmt->execute([$novel_id]);
$novel = $novel_stmt->fetch(PDO::FETCH_ASSOC);

// Fetch chapters for the selected novel
$chapter_stmt = $conn->prepare("SELECT id, chapter_title, chapter_number, chapter_content FROM chapters WHERE novel_id = ? ORDER BY chapter_number ASC");
$chapter_stmt->execute([$novel_id]);
$chapters = $chapter_stmt->fetchAll(PDO::FETCH_ASSOC);

// Cek apakah novel ditemukan
if (!$novel) {
    echo "<h2>Novel tidak ditemukan.</h2>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Novels - AriezzNote</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/my_style.css">
    <link rel="icon" href="../../assets/images/aries.png">
    <!-- Font Awesome and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMB0m7iIbq2E5Dg7F2H/oMx5NHX9W8IefxHc5F" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(to right, #a7faee, #fff);
        }

        .novel-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 50px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2.novel-card-title {
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 15px;
            color: #333333;
        }

        .novel-card-text {
            font-size: 18px;
            color: #555555;
            text-align: justify;
            margin: 30px;
        }

        p.text-muted {
            font-size: 14px;
            color: #999999;
            text-align: center;
        }

        .chapter-title {
            text-align: center;
            font-size: 28px;
            margin-top: 30px;
            margin-bottom: 5px;
            color: #444444;
            font-weight: 600;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #565656;
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }

        .progress {
            height: 15px;
            margin-bottom: 20px;
        }

        .progress-bar {
            background-color: #d9534f;
        }

        .novel-container a.btn:hover {
            background-color: #e74c3c;
        }

        @media (max-width: 768px) {
            .novel-container {
                padding: 15px;
            }

            h2.novel-card-title {
                font-size: 28px;
            }

            .novel-card-text {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 15px;
            }
        }
    </style>
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/1njh6m3u9qu67rqovhb6s4lt7fshs4vsgbejnlxs4qpggkvf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea', // Target textarea
            plugins: 'lists link image table code', // Plugin yang ingin digunakan
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code', // Toolbar
            height: 300, // Tinggi editor
            menubar: false, // Menyembunyikan menu bar
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }' // Gaya konten
        });
    </script>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-left" href="#" style="font-weight:bold;">
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
    <!-- Box Container for Novel Details -->
    <div class="novel-container mt-5">
        <button onclick="window.history.back()" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Kembali
        </button>

        <h2 class="novel-card-title"><?= htmlspecialchars($novel['title']); ?></h2>
        <p class="text-muted"><?= htmlspecialchars($novel['created_at']); ?></p>
        <p class="novel-card-text" style="text-align:center;"><strong>Sinopsis:</strong> <br><?= htmlspecialchars($novel['synopsis']); ?></p>

        <!-- Progress Bar -->
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%;" id="progressBar">0%</div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card novel-card shadow mb-4">
                    <div class="card-body">
                        <!-- Chapters Loop -->
                        <?php foreach ($chapters as $key => $chapter) : ?>
                            <h4 class="chapter-title" id="chapterTitle<?= $key ?>" <?php if ($key != 0) echo 'style="display:none;"'; ?>>
                                CHAPTER <?= htmlspecialchars($chapter['chapter_number']); ?> : <?= htmlspecialchars($chapter['chapter_title']); ?>
                            </h4>

                            <div class="novel-card-text" id="chapterContent<?= $key ?>" style="white-space: pre-line; <?php if ($key != 0) echo 'display:none;'; ?>">
                                <?= nl2br(htmlspecialchars(strip_tags($chapter['chapter_content']))); ?>
                            </div>

                            <div class="text-end mt-4" id="nextBtnDiv<?= $key ?>" <?php if ($key != 0 || $key == count($chapters) - 1) echo 'style="display:none;"'; ?>>
                                <?php if ($key < count($chapters) - 1) : ?>
                                    <button class="btn btn-secondary" id="nextBtn<?= $key ?>">Next: Chapter <?= htmlspecialchars($chapter['chapter_number'] + 1); ?></button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container tambahan untuk menampilkan daftar chapter berikutnya -->
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="text-center" style="font-weight:normal;">Daftar chapter cerita ini</h5>
                    <hr>
                    <ul class="list-group">
                        <!-- Loop untuk tiap chapter dalam daftar chapter -->
                        <?php foreach ($chapters as $key => $chapter) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>BAB <?= htmlspecialchars($chapter['chapter_number']); ?>: <?= htmlspecialchars($chapter['chapter_title']); ?></span>
                                <button class="btn btn-primary btn-sm" id="readChapter<?= $key ?>">Baca</button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
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
    <script>
        // Menghitung total chapter
        const totalChapters = <?= count($chapters); ?>;

        // Mengupdate progress bar
        function updateProgressBar(currentIndex) {
            const progressPercentage = ((currentIndex + 1) / totalChapters) * 100;
            const progressBar = document.getElementById('progressBar');
            progressBar.style.width = progressPercentage + '%';
            progressBar.innerText = Math.round(progressPercentage) + '%';
        }

        <?php foreach ($chapters as $key => $chapter) : ?>
            // Tombol untuk menampilkan bab selanjutnya
            document.getElementById('nextBtn<?= $key ?>')?.addEventListener('click', function() {
                // Sembunyikan chapter saat ini
                document.getElementById('chapterTitle<?= $key ?>').style.display = 'none';
                document.getElementById('chapterContent<?= $key ?>').style.display = 'none';
                document.getElementById('nextBtnDiv<?= $key ?>').style.display = 'none';

                // Tampilkan chapter berikutnya
                document.getElementById('chapterTitle<?= $key + 1 ?>').style.display = 'block';
                document.getElementById('chapterContent<?= $key + 1 ?>').style.display = 'block';
                document.getElementById('nextBtnDiv<?= $key + 1 ?>').style.display = 'block';

                // Update progress bar
                updateProgressBar(<?= $key + 1 ?>);
            });

            // Tombol untuk membaca chapter dari daftar sebelah
            document.getElementById('readChapter<?= $key ?>')?.addEventListener('click', function() {
                // Sembunyikan semua chapter
                <?php foreach ($chapters as $index => $chap) : ?>
                    document.getElementById('chapterTitle<?= $index ?>').style.display = 'none';
                    document.getElementById('chapterContent<?= $index ?>').style.display = 'none';
                    document.getElementById('nextBtnDiv<?= $index ?>').style.display = 'none';
                <?php endforeach; ?>

                // Tampilkan chapter yang dipilih
                document.getElementById('chapterTitle<?= $key ?>').style.display = 'block';
                document.getElementById('chapterContent<?= $key ?>').style.display = 'block';
                document.getElementById('nextBtnDiv<?= $key ?>').style.display = 'block';

                // Update progress bar
                updateProgressBar(<?= $key ?>);
            });
        <?php endforeach; ?>
    </script>
</body>

</html>