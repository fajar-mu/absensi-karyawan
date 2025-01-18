<?php
session_start(); // Pastikan sesi sudah dimulai

require_once("config.php");
if (!$connection) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Ambil data lokasi_presensi dari sesi
if (!isset($_SESSION['lokasi_presensi'])) {
    die("Error:  Lokasi Absensi belum diset dalam sesi.");
}

$lokasi_presensi = $_SESSION['lokasi_presensi'];

// Query lokasi_presensi untuk mendapatkan data lokasi dan jam_pulang
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
if (!$result) {
    die("Error pada query lokasi_presensi: " . mysqli_error($connection));
}

// Ambil data dari hasil query
$lokasi = mysqli_fetch_assoc($result);
if (!$lokasi) {
    die("Error: Tidak ditemukan data untuk  Lokasi Absensi '$lokasi_presensi'.");
}

// Debug data lokasi
echo "<h2>Debugging Data  Lokasi Absensi</h2>";
echo "<pre>";
print_r($lokasi);
echo "</pre>";

// Ambil jam_pulang
$jam_pulang = $lokasi['jam_pulang'] ?? null;
if (!$jam_pulang) {
    die("Error: Jam pulang tidak ditemukan untuk lokasi '$lokasi_presensi'.");
}

// Debug jam_pulang
echo "<h2>Debugging Jam Pulang</h2>";
echo "Jam Pulang: $jam_pulang<br>";

// Validasi format jam_pulang
if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $jam_pulang)) {
    die("Error: Format jam pulang tidak valid. Harus dalam format HH:mm:ss.");
}

// Debug waktu saat ini
$waktu_sekarang = date('H:i:s');
echo "<h2>Debugging Waktu Sekarang</h2>";
echo "Waktu Sekarang: $waktu_sekarang<br>";

// Bandingkan waktu sekarang dengan jam pulang
if (strtotime($waktu_sekarang) <= strtotime($jam_pulang)) {
    echo "<h2>Debugging Perbandingan Waktu</h2>";
    echo "Belum waktunya pulang.<br>";
} else {
    echo "<h2>Debugging Perbandingan Waktu</h2>";
    echo "Sudah waktunya pulang.<br>";
}

// Tutup koneksi database
mysqli_close($connection);
