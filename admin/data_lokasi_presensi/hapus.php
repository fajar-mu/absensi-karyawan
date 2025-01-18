<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
    exit;
}

require_once("../../config.php");

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (!$id) {
    $_SESSION['validasi'] = "ID tidak ditemukan atau tidak valid.";
    header("Location: lokasi_presensi.php");
    exit;
}

$result = mysqli_query($connection, "DELETE FROM lokasi_presensi WHERE id = $id");

if ($result) {
    $_SESSION["berhasil"] = "Data  Lokasi Absensi berhasil dihapus!";
    header("Location: lokasi_presensi.php");
    exit;
} else {
    $_SESSION["validasi"] = "Terjadi kesalahan saat menghapus data!";
    header("Location: lokasi_presensi.php");
    exit;
}
