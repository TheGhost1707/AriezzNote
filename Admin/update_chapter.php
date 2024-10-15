<?php
// Include file koneksi database
include 'koneksi.php'; // Pastikan file ini mengandung konfigurasi koneksi ke database

// Cek apakah data POST sudah ada
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $chapter_id = intval($_POST['chapter_id']);
    $novel_id = intval($_POST['novel_id']);
    $chapter_title = strip_tags($_POST['chapter_title']);
    $chapter_content = $_POST['chapter_content']; // Ambil isi dari textarea

    // Debugging
    var_dump($_POST); // Tambahkan ini untuk melihat data yang diterima

    // Pastikan chapter_content tidak kosong
    if (empty($chapter_content)) {
        echo "Chapter content cannot be empty.";
        exit();
    }

    try {
        // Buat query untuk update chapter
        $query = "UPDATE chapters SET chapter_title = :chapter_title, chapter_content = :chapter_content WHERE id = :chapter_id";

        // Siapkan statement
        $stmt = $conn->prepare($query);

        // Ikat parameter
        $stmt->bindParam(':chapter_title', $chapter_title);
        $stmt->bindParam(':chapter_content', $chapter_content);
        $stmt->bindParam(':chapter_id', $chapter_id, PDO::PARAM_INT);

        // Eksekusi statement
        $stmt->execute();

        // Jika berhasil, redirect ke halaman novel atau tampilkan pesan sukses
        header("Location: read_novels.php?id=$novel_id&success=Chapter updated successfully.");
        exit();
    } catch (PDOException $e) {
        // Jika gagal, tampilkan pesan error
        echo "Error updating chapter: " . $e->getMessage();
    }
}
// Tutup koneksi (optional, PDO akan otomatis menutup koneksi saat script selesai)
$conn = null;
?>
