<?php
// Include koneksi database
require_once("../../config.php");

// Ambil filter tanggal dari URL
$tanggal_dari = $_GET['tanggal_dari'] ?? null;
$tanggal_sampai = $_GET['tanggal_sampai'] ?? null;

// Query data presensi
$query = "SELECT pegawai.nama, presensi.tanggal_masuk, presensi.jam_masuk, presensi.jam_keluar 
          FROM presensi 
          JOIN pegawai ON presensi.id_pegawai = pegawai.id"; // Sesuaikan kolom relasi pegawai_id dan id

if (!empty($tanggal_dari) && !empty($tanggal_sampai)) {
    $query .= " WHERE presensi.tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
}
$query .= " ORDER BY presensi.tanggal_masuk DESC";

$result = mysqli_query($connection, $query);

// Header untuk membuat file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=data_pegawai.xls");
header("Cache-Control: max-age=0");

// Membuat tabel HTML sebagai output Excel
echo "<table border='1'>";
echo "<thead>";
echo "<tr><th colspan='6'>Rekap Absensi Teknisi</th> </tr>";
echo "<tr>";
echo "<th>No</th>";
echo "<th>Tanggal</th>";
echo "<th>Username</th>";
echo "<th>Jam Masuk</th>";
echo "<th>Jam Keluar</th>";
echo "<th>Total Jam Kerja</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

$no = 1;
while ($rekap = mysqli_fetch_array($result)) {
    $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']));
    $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_keluar']));

    $timestamp_masuk = strtotime($jam_tanggal_masuk);
    $timestamp_keluar = strtotime($jam_tanggal_keluar);

    $selisih = $timestamp_keluar - $timestamp_masuk;

    $total_jam_kerja = floor($selisih / 3600);
    $selisih -= $total_jam_kerja * 3600;
    $selisih_menit_kerja = floor($selisih / 60);

    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . date('d F Y', strtotime($rekap['tanggal_masuk'])) . "</td>";
    echo "<td>" . $rekap['nama'] . "</td>";
    echo "<td>" . $rekap['jam_masuk'] . "</td>";
    echo "<td>" . $rekap['jam_keluar'] . "</td>";
    echo "<td>" . $total_jam_kerja . " jam " . $selisih_menit_kerja . " menit</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
