<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'monitoring kualitas udara pondok betung';

// Buat koneksi
$konek = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($konek->connect_error) {
    die("Koneksi ke database gagal: " . $konek->connect_error);
}

// Periksa apakah data dikirim dengan metode GET
if (isset($_GET['suhu']) && isset($_GET['kelembaban']) && isset($_GET['co2'])) {
    $suhu = $_GET['suhu'];
    $kelembaban = $_GET['kelembaban'];
    $co2 = $_GET['co2'];

    // Query untuk memasukkan data sensor ke tabel sensor_data
    $sql = "INSERT INTO sensor_data (suhu, kelembaban, co2) VALUES ('$suhu', '$kelembaban', '$co2')";

    if ($konek->query($sql) === TRUE) {
        echo "Data berhasil disimpan";
    } else {
        echo "Error: " . $sql . "<br>" . $konek->error;
    }
} else {
    echo "Data sensor tidak lengkap";
}

// Tutup koneksi
$konek->close();
?>
