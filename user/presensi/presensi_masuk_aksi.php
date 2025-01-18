<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != "pegawai") {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
}

require_once("../../config.php");

$file_foto = $_POST['foto'];
$id_pegawai = $_POST['id'];
$tanggal_masuk = $_POST['tanggal_masuk'];
$jam_masuk = $_POST['jam_masuk'];

// Ambil username berdasarkan id_pegawai
$query = "SELECT username FROM user WHERE id_pegawai = '$id_pegawai'";
$result_user = mysqli_query($connection, $query);

if ($result_user && mysqli_num_rows($result_user) > 0) {
    $row = mysqli_fetch_assoc($result_user);
    $username = $row['username'];
} else {
    $_SESSION['validasi'] = "Gagal mengambil username";
    header("Location: halaman_presensi.php");
    exit;
}

// Decode foto dari base64
$foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
$foto = str_replace(' ', '+', $foto);
$data = base64_decode($foto);

// Buat nama file berdasarkan username dan tanggal
$nama_file = 'foto/' . $username . 'masuk_' . date('Y-m-d') . '.png';
$file = $username . '_masuk_' . date('Y-m-d') . '.png';

// Simpan file foto
file_put_contents($nama_file, $data);

// Simpan data presensi ke database
$result = mysqli_query($connection, "INSERT INTO presensi (id_pegawai, tanggal_masuk, jam_masuk, foto_masuk) VALUES ('$id_pegawai', '$tanggal_masuk', '$jam_masuk', '$file')");

if ($result) {
    $_SESSION['berhasil'] = "Presensi Masuk Berhasil";
} else {
    $_SESSION['validasi'] = "Presensi Masuk Gagal";
}
