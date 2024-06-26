<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'monitoring kualitas udara pondok betung';

// Buat koneksi
$konek = mysqli_connect($host, $user, $password, $dbname);

// Cek koneksi
if (!$konek) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Query untuk mengambil 5 data terbaru dari tabel sensor_data
$sql = "SELECT * FROM sensor_data ORDER BY waktu DESC LIMIT 5";
$result = mysqli_query($konek, $sql);

// Cek jika query berhasil dieksekusi
if ($result) {
    // Loop untuk menampilkan data
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['waktu'] . "</td>";
        echo "<td>" . $row['suhu'] . " °C</td>";
        echo "<td>" . $row['kelembaban'] . " %</td>";
        echo "<td>" . $row['co2'] . " ppm</td>";
        echo "</tr>";
    }
    // Bebaskan hasil query
    mysqli_free_result($result);
} else {
    echo "<tr><td colspan='5'>Error: " . $sql . "<br>" . mysqli_error($konek) . "</td></tr>";
}

// Tutup koneksi
mysqli_close($konek);
?>
