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
$tanggal_keluar = $_POST['tanggal_keluar'];
$jam_keluar = $_POST['jam_keluar'];

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
$nama_file = 'foto/' . $username . '_keluar_' . date('Y-m-d') . '.png';
$file = $username . '_keluar_' . date('Y-m-d') . '.png';

// Simpan file foto
file_put_contents($nama_file, $data);

// Simpan data presensi ke database
$result = mysqli_query(
    $connection,
    "UPDATE presensi 
     SET 
        tanggal_keluar = '$tanggal_keluar', 
        jam_keluar = '$jam_keluar', 
        foto_keluar = '$file' 
     WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tanggal_keluar'"
);
if ($result) {
    $_SESSION['berhasil'] = "Presensi keluar Berhasil";
} else {
    $_SESSION['validasi'] = "Presensi keluar Gagal";
}
