<?php
include 'koneksi.php'; // Pastikan koneksi database terhubung

// Cek apakah ada ID yang dikirim
if (isset($_GET['id'])) {
    $novel_id = $_GET['id'];

    // Hapus novel dari database
    $delete_stmt = $conn->prepare("DELETE FROM novels WHERE id = ?");
    if ($delete_stmt->execute([$novel_id])) {
        header('Location: novels.php?status=deleted'); // Redirect setelah berhasil
        exit;
    } else {
        echo "Terjadi kesalahan saat menghapus novel.";
    }
} else {
    echo "ID novel tidak ditemukan.";
}
?>
