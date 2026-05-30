<?php
/**
 * Konfigurasi & Koneksi Database
 * Sistem Manajemen Perpustakaan
 */
 
// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'perpustakaan');
 
// Buat koneksi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Set charset ke UTF-8
$conn->set_charset("utf8mb4");
 
// Cek koneksi
if ($conn->connect_error) {
    // Log error (production)
    error_log("Database connection failed: " . $conn->connect_error);
    
    // Tampilkan pesan user-friendly
    die("
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4>Koneksi Database Gagal</h4>
                <p>Maaf, tidak dapat terhubung ke database. Silakan coba lagi nanti.</p>
            </div>
        </div>
    </body>
    </html>
    ");
}
 
/**
 * Fungsi untuk menutup koneksi database
 */
function closeConnection() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
}
 
/**
 * Fungsi untuk sanitasi input
 */
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}
 
// Set timezone
date_default_timezone_set('Asia/Jakarta');
?>
c. Buat File Test Koneksi

File: test_connection.php

<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Koneksi Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Test Koneksi Database</h4>
            </div>
            <div class="card-body">
                <?php
                // Cek koneksi
                if ($conn) {
                    echo '<div class="alert alert-success">';
                    echo '<h5><i class="bi bi-check-circle"></i> Koneksi Berhasil!</h5>';
                    echo '<p><strong>Server:</strong> ' . DB_SERVER . '</p>';
                    echo '<p><strong>Database:</strong> ' . DB_NAME . '</p>';
                    echo '<p><strong>Charset:</strong> ' . $conn->character_set_name() . '</p>';
                    echo '</div>';
                    
                    // Test query
                    $result = $conn->query("SELECT COUNT(*) as total FROM buku");
                    if ($result) {
                        $row = $result->fetch_assoc();
                        echo '<div class="alert alert-info">';
                        echo '<p><strong>Total Buku di Database:</strong> ' . $row['total'] . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">';
                    echo '<h5>Koneksi Gagal</h5>';
                    echo '</div>';
                }
                
                closeConnection();
                ?>
            </div>
        </div>
    </div>
</body>
</html>