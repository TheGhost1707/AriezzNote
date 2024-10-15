<?php
// Mulai sesi
session_start();

// Sertakan file konfigurasi untuk koneksi ke database
require 'novel/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek apakah input email dan password sudah diisi
    if (!empty($email) && !empty($password)) {
        // Query untuk mengambil user berdasarkan email
        $stmt = $conn->prepare("SELECT id, full_name, email, password, role FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Cek apakah user dengan email tersebut ditemukan
        if ($stmt->rowCount() == 1) {
            // Ambil data user
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Bandingkan password secara langsung (tanpa password_verify)
            if ($password == $user['password']) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect sesuai dengan role user
                if ($user['role'] == 'pembaca') {
                    header("Location: ../index.php");
                } else if ($user['role'] == 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                // Jika password salah
                $_SESSION['error'] = "Password salah.";
                header("Location: index.php");
                exit();
            }
        } else {
            // Jika email tidak ditemukan
            $_SESSION['error'] = "Email tidak ditemukan.";
            header("Location: index.php");
            exit();
        }
    } else {
        // Jika email atau password tidak diisi
        $_SESSION['error'] = "Email dan password wajib diisi.";
        header("Location: index.php");
        exit();
    }
}
