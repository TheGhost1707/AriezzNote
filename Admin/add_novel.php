<?php
session_start();
include 'koneksi.php'; // Pastikan Anda menyertakan koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $title = $_POST['title'];
    $created_at = $_POST['created_at']; // Menggunakan name="created_at"
    $synopsis = $_POST['synopsis'];
    
    // Cek apakah file gambar diupload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $target_dir = "../uploads/"; // Pastikan direktori ini ada
        $target_file = $target_dir . basename($image);
        
        // Pindahkan file gambar ke direktori tujuan
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            try {
                // Siapkan pernyataan SQL untuk mencegah SQL injection
                $stmt = $conn->prepare("INSERT INTO novels (title, created_at, synopsis, image) VALUES (:title, :created_at, :synopsis, :image)");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':created_at', $created_at);
                $stmt->bindParam(':synopsis', $synopsis);
                $stmt->bindParam(':image', $target_file); // Menyimpan nama file gambar
                
                // Eksekusi pernyataan dan cek apakah penyisipan berhasil
                if ($stmt->execute()) {
                    // Jika berhasil, tampilkan alert dan alihkan ke halaman lain
                    echo "<script>
                            alert('Novel berhasil ditambahkan!');
                            window.location.href = 'novels.php'; // Ganti dengan halaman yang diinginkan
                          </script>";
                } else {
                    echo "<script>
                            alert('Gagal menambahkan novel.');
                            window.location.href = 'novels.php'; // Ganti dengan halaman yang diinginkan
                          </script>";
                }
            } catch (PDOException $e) {
                // Tangani kesalahan
                echo "<script>
                        alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "');
                        window.location.href = 'novels.php'; // Ganti dengan halaman yang diinginkan
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Gagal mengupload gambar.');
                    window.location.href = 'novels.php'; // Ganti dengan halaman yang diinginkan
                  </script>";
        }
    } else {
        echo "<script>
                alert('Gambar tidak ditemukan atau terjadi kesalahan.');
                window.location.href = 'novels.php'; // Ganti dengan halaman yang diinginkan
              </script>";
    }
} else {
    // Tangani kasus ketika permintaan bukan POST
    echo "<script>
            alert('Invalid request method.');
            window.location.href = 'novels.php'; // Ganti dengan halaman yang diinginkan
          </script>";
}
?>