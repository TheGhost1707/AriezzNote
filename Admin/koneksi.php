<?php
// config.php
$host = 'localhost'; // Host database
$dbname = 'db_ariezznote'; // Nama database
$username = 'root'; // Username MySQL Anda
$password = ''; // Password MySQL Anda

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode PDO to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>