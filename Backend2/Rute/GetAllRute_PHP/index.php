<?php

// Function untuk menangani permintaan GET ke endpoint "/get-all-rute"
function getAllRute() {
    // memeriksa apakah metode permintaan adalah GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Method not allowed"]);
        return;
    }

    // Koneksi ke database MySQL
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "service_rute";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
        return;
    }

    // Query untuk mengambil data rute
    $sql = "SELECT id, lokasi_berangkat, lokasi_tujuan FROM rutes";
    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Query error: " . $conn->error]);
        return;
    }

    $rutes = [];
    while ($row = $result->fetch_assoc()) {
        $rutes[] = $row;
    }

    // Set header konten sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($rutes);

    // Tutup koneksi
    $conn->close();
}

// Menentukan routing
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

if ($request_uri == '/get-all-rute') {
    getAllRute();
} else {
    http_response_code(404); // Not Found
    echo json_encode(["error" => "Not Found"]);
}

// MenJalankan server
if (php_sapi_name() === 'cli-server') {
    // This block is executed only when running with the built-in PHP server
    // To start the server, run: php -S localhost:9006 index.php
    echo "Server running on http://localhost:9006\n";
}
?>
