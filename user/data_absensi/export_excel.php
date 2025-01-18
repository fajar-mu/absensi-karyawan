<?php
session_start();

// Cek login
if (!isset($_SESSION['login']) || $_SESSION['role'] != "pegawai") {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
    exit;
}

require_once("../../config.php");

// Ambil ID pegawai dari sesi
$id = $_SESSION['id'];

// Ambil parameter tanggal jika ada
$tanggal_dari = isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : null;
$tanggal_sampai = isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : null;

// Query data presensi
if (empty($tanggal_dari)) {
    $query = "SELECT tanggal_masuk, jam_masuk, jam_keluar 
              FROM presensi 
              WHERE id_pegawai = '$id' 
              ORDER BY tanggal_masuk DESC";
} else {
    $query = "SELECT tanggal_masuk, jam_masuk, jam_keluar 
              FROM presensi 
              WHERE id_pegawai = '$id' AND tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' 
              ORDER BY tanggal_masuk DESC";
}
$result = mysqli_query($connection, $query);

// Header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_absensi.xls");
header("Cache-Control: max-age=0");

// Tulis data ke dalam tabel HTML (untuk Excel)
echo "<table border='1'>";
echo "<tr><th colspan='5'>Rekap Absensi</th> </tr>";
echo "<tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Jam Masuk</th>
        <th>Jam Keluar</th>
        <th>Total Jam Kerja</th>
      </tr>";

$no = 1;
while ($rekap = mysqli_fetch_assoc($result)) {
    // Hitung total jam kerja
    $jam_tanggal_masuk = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']);
    $jam_tanggal_keluar = strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_keluar']);
    $selisih = $jam_tanggal_keluar - $jam_tanggal_masuk;

    $total_jam_kerja = floor($selisih / 3600);
    $selisih -= $total_jam_kerja * 3600;
    $selisih_menit_kerja = floor($selisih / 60);

    // Tulis baris data
    echo "<tr>
            <td>{$no}</td>
            <td>" . date('d F Y', strtotime($rekap['tanggal_masuk'])) . "</td>
            <td>{$rekap['jam_masuk']}</td>
            <td>{$rekap['jam_keluar']}</td>
            <td>{$total_jam_kerja} jam {$selisih_menit_kerja} menit</td>
          </tr>";
    $no++;
}

echo "</table>";
exit;
