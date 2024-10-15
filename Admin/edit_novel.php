<?php
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

include 'koneksi.php';  // Pastikan koneksi terhubung

// Ambil ID novel dari query string
$novel_id = $_GET['id'] ?? null;

if ($novel_id) {
    // Query untuk mendapatkan data novel berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM novels WHERE id = ?");
    $stmt->execute([$novel_id]);
    $novel = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika novel tidak ditemukan
    if (!$novel) {
        die("Novel tidak ditemukan.");
    }
}

// Proses update novel saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $synopsis = $_POST['synopsis'];

    // Mengambil file gambar yang diupload
    $image = $_FILES['image']['name'];
    $target_dir = "../uploads/"; // Folder untuk menyimpan gambar
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;

    // Validasi data
    if (!empty($title) && !empty($synopsis)) {
        // Jika gambar baru diupload
        if (!empty($image)) {
            // Cek apakah gambar bisa diupload
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Update query dengan gambar baru
                $update_sql = "UPDATE novels SET title = ?, synopsis = ?, image = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->execute([$title, $synopsis, $image, $novel_id]);
            } else {
                $error_message = "Maaf, terjadi kesalahan saat mengupload gambar.";
            }
        } else {
            // Update query tanpa mengubah gambar
            $update_sql = "UPDATE novels SET title = ?, synopsis = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute([$title, $synopsis, $novel_id]);
        }

        // Jika berhasil
        header("Location: novels.php?status=success");
        exit();
    } else {
        $error_message = "Harap isi semua field.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/my_style.css">
    <!-- Add this in the <head> section for Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMB0m7iIbq2E5Dg7F2H/oMx5NHX9W8IefxHc5F" crossorigin="anonymous">
    <link rel="icon" href="../assets/images/aries.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Novel - AriezzNote</title>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#" style="font-weight:bold;">
                <img src="../assets/images/book.png" alt="Logo" style="width: 100px; height: auto; margin-right: 10px;">
                AriezzNote <br> <?= $_SESSION['full_name']; ?> (<?= $_SESSION['role']; ?>)
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-light px-4 ms-1" href="logout.php">Logout</a>
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
        <div class="container mt-5">
            <h2>Edit Novel</h2>
            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Novel</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($novel['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="synopsis" class="form-label">Sinopsis</label>
                    <textarea class="form-control" id="synopsis" name="synopsis" rows="4" required><?= htmlspecialchars($novel['synopsis']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Novel</label>
                    <div>
                        <?php if (!empty($novel['image'])) : ?>
                            <img src="../uploads/<?= htmlspecialchars($novel['image']); ?>" alt="Gambar Novel" style="max-width: 500px; margin-bottom: 10px;">
                        <?php endif; ?>
                    </div>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="novels.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer text-light bg-dark p-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-0">&copy; 2024 AriezzNote. All Rights Reserved.</p>
            <div class="social-links d-flex mt-3 mt-md-0">
                <a href="https://www.instagram.com" class="text-light mx-3" title="Instagram" target="_blank">
                    <img src="../assets/images/instagram.png" alt="Instagram" class="social-icon">
                </a>
                <a href="https://wa.me/your-number" class="text-light mx-3" title="WhatsApp" target="_blank">
                    <img src="../assets/images/whatsapp.png" alt="WhatsApp" class="social-icon">
                </a>
                <a href="mailto:your-email@example.com" class="text-light mx-3" title="Email" target="_blank">
                    <img src="../assets/images/gmail.png" alt="Email" class="social-icon">
                </a>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>