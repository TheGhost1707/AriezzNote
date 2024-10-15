<?php
session_start();
include 'koneksi.php'; // Pastikan Anda menyertakan koneksi ke database

// Cek apakah ID bab dan novel diterima
if (isset($_GET['id']) && isset($_GET['novel_id'])) {
    $chapter_id = $_GET['id'];
    $novel_id = $_GET['novel_id'];

    try {
        // Siapkan pernyataan SQL untuk menghapus chapter berdasarkan ID
        $stmt = $conn->prepare("DELETE FROM chapters WHERE id = :chapter_id AND novel_id = :novel_id");
        $stmt->bindParam(':chapter_id', $chapter_id);
        $stmt->bindParam(':novel_id', $novel_id);
        
        // Eksekusi pernyataan
        if ($stmt->execute()) {
            // Set session message untuk notifikasi
            $_SESSION['notification'] = "Chapter berhasil dihapus!";
        } else {
            $_SESSION['notification'] = "Gagal menghapus chapter.";
        }
    } catch (PDOException $e) {
        // Tangani kesalahan
        $_SESSION['notification'] = "Terjadi kesalahan: " . addslashes($e->getMessage());
    }
} else {
    // Jika ID tidak ditemukan
    $_SESSION['notification'] = "ID chapter atau novel tidak valid.";
}

// Alihkan kembali ke halaman daftar bab atau halaman novel
header("Location: read_novels.php?id=" . $novel_id); // Ganti dengan halaman yang sesuai
exit();
?>
