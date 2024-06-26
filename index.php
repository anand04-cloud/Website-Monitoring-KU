<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Sensor Realtime</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fungsi untuk memuat data secara periodik
            function loadSensorData() {
                $.ajax({
                    url: 'ceksensor.php',
                    type: 'GET',
                    success: function(response) {
                        $('#sensorData').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Memuat data pertama kali
            loadSensorData();

            // Memuat data setiap 5 detik
            setInterval(function() {
                loadSensorData();
            }, 5000); // Memuat data setiap 5 detik
        });
    </script>
    <style>
        body {
            background: linear-gradient(to right, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.5)), url('images/logo kkn 2024.png') no-repeat center center fixed;
            background-size: cover;
            padding-top: 20px;
            color: #333; /* Warna teks */
            font-family: 'Roboto', sans-serif; /* Ganti dengan font yang diinginkan */
        }
        .sensor-table {
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.95); /* Transparansi latar belakang */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Bayangan halus */
            overflow: hidden;
        }
        .sensor-table table {
            width: 100%;
            margin-bottom: 0;
            table-layout: fixed;
        }
        .sensor-table th, .sensor-table td {
            text-align: center;
            padding: 12px; /* Padding lebih besar */
            vertical-align: middle;
            font-size: 16px; /* Ukuran teks */
        }
        .sensor-table thead {
            background-color: #007bff; /* Warna latar belakang header */
            color: #fff; /* Warna teks header */
        }
        .sensor-table tbody tr:nth-child(even) {
            background-color: #f2f2f2; /* Warna latar belakang baris genap */
        }
        .sensor-table tbody tr:hover {
            background-color: #e9ecef; /* Warna latar belakang saat hover */
        }
        h2 {
            font-weight: bold; /* Teks judul lebih tebal */
            color: #004085; /* Warna judul lebih gelap */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); /* Bayangan teks judul */
            font-family: 'Lucida Bright', sans-serif; /* Menggunakan font Lucida Bright */
            font-size: 40px; /* Ukuran teks judul diperbesar */
        }
        .sub-title {
            font-size: 18px; /* Ukuran teks sub judul diperbesar */
            color: #333; /* Warna teks sub judul */
            margin-top: -10px; /* Jarak atas dari sub judul */
        }
    </style>
</head>
<body>
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 text-center">
                    <h2 class="mb-2">Monitoring Kualitas Udara</h2>
                    <p class="sub-title">Kelurahan Pondok Betung, Kota Tangerang Selatan, Banten</p>
                    <div class="sensor-table">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Waktu</th>
                                    <th>Nilai Suhu (°C)</th>
                                    <th>Nilai Kelembaban (%)</th>
                                    <th>Nilai CO2 (ppm)</th>
                                </tr>
                            </thead>
                            <tbody id="sensorData">
                                <!-- Data dari ceksensor.php akan dimuat di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
