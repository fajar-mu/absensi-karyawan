<?php
session_start();

if (!isset($_SESSION['login'])) {
   header("Location: ../../login/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != "pegawai") {
   header("Location: ../../login/login.php?pesan=akses_ditolak");
}

require_once("../../config.php");

if (empty($_GET['tanggal_dari'])) {
   $id = $_SESSION['id'];
   $result = mysqli_query($connection, "SELECT * FROM presensi WHERE id_pegawai = '$id' ORDER BY tanggal_masuk DESC");
} else {
   $id = $_SESSION['id'];
   $tanggal_dari = $_GET['tanggal_dari'];
   $tanggal_sampai = $_GET['tanggal_sampai'];
   $result = mysqli_query($connection, "SELECT * FROM presensi WHERE id_pegawai = '$id' AND tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' ORDER BY tanggal_masuk DESC");
}

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$lokasi = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
while ($lokasi_result = mysqli_fetch_array($lokasi)):

   $jam_masuk_kantor = date('H:i:s',  strtotime($lokasi_result['jam_masuk']));

endwhile;


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- Boxicons -->
   <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
   <!-- My CSS -->
   <link rel="stylesheet" href="../../assets/css/style.css">
   <style>
      h4 {
         text-align: center;
      }

      .icon-success {
         color: green;
      }

      .icon-ex {
         color: red;
      }
   </style>



   <title>Absensi</title>
</head>

<body>

   <section id="sidebar"> <a href="#" class="brand">




      </a>
      <ul class="side-menu top">
         <li>
            <a href="../home/home.php">
               <i class='bx bx-home-alt'></i>
               <span class="text">Home</span>
            </a>
         </li>

         <li class="active">
            <a href="data_absensi.php">
               <i class='bx bxs-user-detail'></i>
               <span class="text">Rekap Absensi</span>
            </a>
         </li>

         <ul class="side-menu">

            <li>
               <a href="../../login/logout.php" class="logout">
                  <i class='bx bxs-log-out-circle'></i>
                  <span class="text">logout</span>
               </a>
            </li>
         </ul>
   </section>
   <?php include("../layout/navbar.php"); ?>
   <!-- MAIN -->
   <main>
      <div class="head-title">
         <div class="left">
            <h1>Rekap Absensi</h1>

         </div>

      </div>

      <div class="table-data">
         <div class="order">
            <div class="head-title">
               <div class="left">

               </div>
               <form method="get">
                  <div class="posisi">
                     <div class="left">
                        <input type="date" class="form-control" name="tanggal_dari">
                     </div>
                     <div class="left">
                        <input type="date" class="form-control" name="tanggal_sampai">
                     </div>
                     <div class="left">
                        <button type="submit" class="btn-delete">Tampilkan</button>
                     </div>
                  </div>
               </form>

            </div>
            <div class="head">
               <table>
                  <thead>
                     <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Total Jam Kerja</th>
                     </tr>
                  </thead>
                  <?php if (mysqli_num_rows($result) === 0) { ?>
                     <tr>
                        <td colspan="5">Rekap Absensi Masih Kosong.</td>
                     </tr>
                  <?php } else { ?>

                     <?php
                     //menghitung jam kerja
                     $no = 1;
                     while ($rekap = mysqli_fetch_array($result)) :
                        $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']));
                        $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']));

                        $timestamp_masuk = strtotime($jam_tanggal_masuk);
                        $timestamp_keluar = strtotime($jam_tanggal_keluar);

                        $selisih = $timestamp_keluar - $timestamp_masuk;

                        $total_jam_kerja = floor($selisih / 3600);
                        $selisih -= $total_jam_kerja * 3600;
                        $selisih_menit_kerja = floor($selisih / 60);
                     ?>
                        <tbody>
                           <tr>
                              <td><?= $no++ ?></td>
                              <td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
                              <td><?= $rekap['jam_masuk'] ?></td>
                              <td><?= $rekap['jam_keluar'] ?></td>
                              <td><?= $total_jam_kerja . ' jam ' . $selisih_menit_kerja . ' menit' ?></td>
                           </tr>
                        </tbody>
                     <?php endwhile ?>
                  <?php } ?>

               </table>
            </div>
            <div class="posisi">
               <a href="export_excel.php" class="excel-btn">export excel</a>
            </div>
         </div>
      </div>
   </main>

   <script src="../../assets/java/script.js"></script>
</body>

</html>