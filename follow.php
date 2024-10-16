<?php
session_start();
include 'users/novel/koneksi.php'; // Pastikan file ini mengandung konfigurasi koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $followerId = $_SESSION['user_id'];
    $followedId = 1; // Misalnya 1 adalah ID pengguna yang profilnya sedang ditampilkan

    // Cek apakah pengguna sudah follow
    $stmt = $conn->prepare("SELECT * FROM follows WHERE follower_id = :follower_id AND followed_id = :followed_id");
    $stmt->bindParam(':follower_id', $followerId);
    $stmt->bindParam(':followed_id', $followedId);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Jika sudah follow, maka unfollow
        $deleteStmt = $conn->prepare("DELETE FROM follows WHERE follower_id = :follower_id AND followed_id = :followed_id");
        $deleteStmt->bindParam(':follower_id', $followerId);
        $deleteStmt->bindParam(':followed_id', $followedId);
        $deleteStmt->execute();

        echo json_encode(['status' => 'unfollowed']);
    } else {
        // Jika belum follow, maka follow
        $insertStmt = $conn->prepare("INSERT INTO follows (follower_id, followed_id) VALUES (:follower_id, :followed_id)");
        $insertStmt->bindParam(':follower_id', $followerId);
        $insertStmt->bindParam(':followed_id', $followedId);
        $insertStmt->execute();

        echo json_encode(['status' => 'followed']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>