<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki akses sebagai admin
if (!isset($_SESSION['login'])) {
    header("Location: ../../login/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
    exit;
}

require_once("../../config.php");

// Periksa apakah ID tersedia dalam URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID Pegawai tidak ditemukan.";
    header("Location: pegawai.php");
    exit;
}

$id = htmlspecialchars($_GET['id']);

// Mulai transaksi untuk memastikan penghapusan di kedua tabel
mysqli_begin_transaction($connection);

try {
    // Hapus data dari tabel user berdasarkan id_pegawai
    $query_user = "DELETE FROM user WHERE id_pegawai = ?";
    $stmt_user = mysqli_prepare($connection, $query_user);
    mysqli_stmt_bind_param($stmt_user, 'i', $id);
    mysqli_stmt_execute($stmt_user);

    // Hapus data dari tabel pegawai
    $query_pegawai = "DELETE FROM pegawai WHERE id = ?";
    $stmt_pegawai = mysqli_prepare($connection, $query_pegawai);
    mysqli_stmt_bind_param($stmt_pegawai, 'i', $id);
    mysqli_stmt_execute($stmt_pegawai);

    // Commit transaksi jika berhasil
    mysqli_commit($connection);

    $_SESSION['berhasil'] = "Data pegawai berhasil dihapus.";
} catch (Exception $e) {
    // Rollback jika terjadi error
    mysqli_rollback($connection);
    $_SESSION['error'] = "Gagal menghapus data: " . $e->getMessage();
}

// Redirect kembali ke halaman pegawai
header("Location: pegawai.php");
exit;
