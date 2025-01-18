<?php
session_start();
require_once("../../config.php");

// Validasi parameter `id`
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['validasi'] = "ID tidak ditemukan! Pastikan URL memiliki parameter ?id.";
    header("Location: jabatan.php");
    exit;
}

$id = intval($_GET['id']); // Pastikan $id adalah angka

// Validasi apakah ID ada di database
$result = mysqli_query($connection, "SELECT * FROM jabatan WHERE id = $id");
if (mysqli_num_rows($result) === 0) {
    $_SESSION['validasi'] = "Data tidak ditemukan!";
    header("Location: jabatan.php");
    exit;
}

// Hapus data dari database menggunakan prepared statements
$stmt = $connection->prepare("DELETE FROM jabatan WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['berhasil'] = "Data berhasil dihapus.";
} else {
    $_SESSION['validasi'] = "Terjadi kesalahan saat menghapus data.";
}

$stmt->close();
header("Location: jabatan.php");
exit;
