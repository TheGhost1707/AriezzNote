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

// Koneksi ke database
include 'koneksi.php';

// Query untuk mendapatkan nama novel dari tabel novels
$query = "SELECT id, title FROM novels";
$novelResult = $conn->query($query);
$novels = $novelResult->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kumpulan Novel - AriezzNote</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/my_style.css">
    <!-- Add this in the <head> section for Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMB0m7iIbq2E5Dg7F2H/oMx5NHX9W8IefxHc5F" crossorigin="anonymous">
    <link rel="icon" href="../assets/images/aries.png">
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

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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
    <div class="container my-5">
        <!-- Back Button -->
        <button onclick="window.history.back()" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Kembali
        </button>
        <h1 class="text-center mb-3" style="font-weight:bold;">Kumpulan Novel</h1>
        <hr style="margin:40px auto; border: 3px solid; width: 250px;">
        <!-- Button to Open the Modal -->
        <div class="d-flex ms-3 justify-content-center align-items-center">
            <button class="btn btn-success" style="padding:20px; margin-bottom:50px; margin-right:20px;" data-bs-toggle="modal" data-bs-target="#addNovelModal">Tambah Novels</button>
            <button class="btn btn-success" style="padding:20px; margin-bottom:50px;" data-bs-toggle="modal" data-bs-target="#addChapterModal">Tambah Chapters</button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNovelModal" tabindex="-1" aria-labelledby="addNovelModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="addNovelForm" method="POST" action="add_novel.php" enctype="multipart/form-data"> <!-- Tambahkan enctype -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Novel</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul novel" required>
                            </div>
                            <div class="mb-3">
                                <label for="created_at" class="form-label">Tanggal Dibuat</label>
                                <input type="date" class="form-control" id="created_at" name="created_at" required> <!-- Menggunakan name="created_at" -->
                            </div>
                            <div class="mb-3">
                                <label for="synopsis" class="form-label">Sinopsis</label>
                                <textarea class="form-control" id="synopsis" name="synopsis" rows="5" placeholder="Tulis sinopsis di sini" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Novel</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required> <!-- Field untuk input gambar -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Novel</button> <!-- Tombol submit untuk form -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addChapterModal" tabindex="-1" aria-labelledby="addChapterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <!-- Form tambah chapter -->
                        <form id="addChapterForm" method="POST" action="add_chapter.php">
                            <!-- Select novel -->
                            <div class="mb-3">
                                <label for="novel_id" class="form-label">Pilih Novel</label>
                                <select class="form-control" id="novel_id" name="novel_id" required>
                                    <option value="" disabled selected>Pilih Novel</option>
                                    <?php foreach ($novels as $novel) : ?>
                                        <option value="<?= $novel['id']; ?>"><?= htmlspecialchars($novel['title']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Input judul chapter -->
                            <div class="mb-3">
                                <label for="chapter_title" class="form-label">Judul Chapter</label>
                                <input type="text" class="form-control" id="chapter_title" name="chapter_title" placeholder="Masukkan judul chapter" required>
                            </div>

                            <!-- Input nomor chapter -->
                            <div class="mb-3">
                                <label for="chapter_number" class="form-label">Nomor Chapter</label>
                                <input type="number" class="form-control" id="chapter_number" name="chapter_number" placeholder="Masukkan nomor chapter" required>
                            </div>

                            <!-- Input chapter content dengan WYSIWYG editor -->
                            <div class="mb-3">
                                <label for="chapter_content" class="form-label">Isi Chapter</label>
                                <textarea class="form-control" id="chapter_content" name="chapter_content" rows="8" required></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Chapter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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
                        <img src="<?= htmlspecialchars($novel['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($novel['title']) ?>" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($novel['title']) ?></h5>
                            <p class="card-text text-muted"><?= date('d F Y', strtotime($novel['created_at'])) ?></p>
                            <p class="card-text synopsis">Sinopsis:<br><?= htmlspecialchars($novel['synopsis']) ?></p>
                            <a href="read_novels.php?id=<?= $novel['id'] ?>" class="btn btn-success mt-3" style="width:100%; text-align:center;">Baca Sekarang</a>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="edit_novel.php?id=<?= $novel['id'] ?>" class="btn btn-warning" style="width:100%;">Edit</a>
                                <a href="delete_novel.php?id=<?= $novel['id'] ?>" class="btn btn-danger" style="width:100%;" onclick="return confirm('Apakah Anda yakin ingin menghapus novel ini?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
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
    <script>
        function saveNovel() {
            // Ambil nilai dari input
            const title = document.getElementById('title').value;
            const creationDate = document.getElementById('creation_date').value;
            const synopsis = document.getElementById('synopsis').value;

            // Siapkan data untuk dikirim
            const formData = new FormData();
            formData.append('title', title);
            formData.append('created_at', creationDate);
            formData.append('synopsis', synopsis);

            // Kirim data menggunakan fetch
            fetch('add_novel.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload halaman setelah sukses
                        $('#addNovelModal').modal('hide'); // Tutup modal
                    } else {
                        alert(data.message); // Tampilkan pesan kesalahan
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: '#chapter_content',
                plugins: 'lists link image table code wordcount',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table code',
                height: 300,
                readonly: false, // Corrected: added missing comma
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave(); // Ensure TinyMCE content is synced
                    });
                }
            });
        });
    </script>
    <script src="https://cdn.tiny.cloud/1/1njh6m3u9qu67rqovhb6s4lt7fshs4vsgbejnlxs4qpggkvf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</body>

</html>