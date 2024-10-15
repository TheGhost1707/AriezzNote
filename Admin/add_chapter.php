<?php
include 'koneksi.php';  // Pastikan koneksi terhubung

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $novel_id = $_POST['novel_id'];
    $chapter_title = $_POST['chapter_title'];
    $chapter_number = $_POST['chapter_number'];
    $chapter_content = $_POST['chapter_content'];

    // Validasi data (pastikan tidak ada field yang kosong)
    if (!empty($novel_id) && !empty($chapter_title) && !empty($chapter_number) && !empty($chapter_content)) {
        // Lakukan query untuk memasukkan data ke database
        // Gunakan prepared statements untuk keamanan dari SQL injection
        $sql = "INSERT INTO chapters (novel_id, chapter_title, chapter_number, chapter_content) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Cek apakah statement berhasil dipersiapkan
        if ($stmt === false) {
            die('Error preparing statement: ' . $conn->errorInfo()[2]);
        }

        // Bind parameter
        $stmt->bindParam(1, $novel_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $chapter_title, PDO::PARAM_STR);
        $stmt->bindParam(3, $chapter_number, PDO::PARAM_INT);
        $stmt->bindParam(4, $chapter_content, PDO::PARAM_STR);

        // Eksekusi statement
        if ($stmt->execute()) {
            // Berhasil menyimpan chapter, redirect ke halaman lain atau tampilkan pesan sukses
            header('Location: novels.php?status=success');  // Ganti ke halaman yang sesuai
            exit();
        } else {
            // Tampilkan error jika eksekusi gagal
            echo "Terjadi kesalahan saat menyimpan data: " . $stmt->errorInfo()[2];
        }

        // Tutup statement
        $stmt->closeCursor(); // Ganti close() dengan closeCursor() untuk PDO
    } else {
        // Jika ada field yang kosong, beri pesan error
        echo "Harap isi semua field.";
    }
}

// Tutup koneksi
$conn = null; // Menghentikan koneksi PDO
?>
