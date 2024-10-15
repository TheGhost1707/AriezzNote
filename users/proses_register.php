<?php
// Mulai sesi
session_start();

// Koneksi ke database
$host = 'localhost'; // Host database
$dbname = 'db_ariezznote'; // Nama database
$username = 'root'; // Username MySQL Anda
$password = ''; // Password MySQL Anda

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "INSERT INTO users (full_name, email, password, role) VALUES (:full_name, :email, :password, :role)";
    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    try {
        if ($stmt->execute()) {
            // Set alert message and redirect
            echo "<script>
                    alert('Registrasi berhasil! Kamu sekarang bisa log in.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        }
    } catch (PDOException $e) {
        echo "<script>
                alert('Error: " . $e->getMessage() . "');
                window.history.back();
              </script>";
        exit();
    }
}

$conn = null;
