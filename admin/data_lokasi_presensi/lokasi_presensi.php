<?php
session_start();


if (!isset($_SESSION['login'])) {
    header("Location: ../../login/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
}
require_once("../../config.php");

$result = mysqli_query($connection, "SELECT*FROM lokasi_presensi ORDER BY id DESC");
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


    <!-- My CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css">

    <title>Absensi</title>
</head>

<body>


    <!-- SIDEBAR -->
    <section id="sidebar"> <a href="#" class="brand">




        </a>
        <ul class="side-menu top">
            <li>
                <a href="../home/home.php">
                    <i class='bx bx-home-alt'></i>
                    <span class="text">Home</span>
                </a>
            </li>
            <li>
                <a href="../data_pegawai/pegawai.php">
                    <i class='bx bx-clipboard'></i>
                    <span class="text">Data Pegawai</span>
                </a>
            </li>
            <li class="has-submenu">
                <a href="#">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text"> Data Admin</span>

                </a>
                <ul class="sub-menu">
                    <li><a href="../data_jabatan/jabatan.php">Jabatan</a></li>
                    <li><a href="lokasi_presensi.php"> Lokasi Absensi</a></li>

                </ul>
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

    <!-- SIDEBAR -->
    <?php include("../layout/navbar.php"); ?>



    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1> Lokasi Absensi</h1>



            </div>
        </div>
        <a href="tambah.php" class="btn-edit"><span class="text"><i class='bx bxs-user-plus'></i>Tanbah data </span></a>
        <div class="table-data">
            <div class="order">
                <div class="head">

                    <table>
                        <thead>
                            <tr>
                                <th>No </th>
                                <th>Nama Lokasi</th>
                                <th>Alamat Lokasi</th>
                                <th>Latutide/Langitude</th>
                                <th>Radius</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) === 0) : ?>
                                <tr>
                                    <td colspan="6">
                                        Maaf data masih kosong
                                    </td>
                                </tr>

                            <?php else: ?>
                                <?php $no = 1;
                                while ($lokasi = mysqli_fetch_array($result)) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $lokasi['nama_lokasi'] ?></td>
                                        <td><?= $lokasi['alamat_lokasi'] ?></td>
                                        <td><?= $lokasi['latitude'] . '/' . $lokasi['longitude'] ?></td>
                                        <td><?= $lokasi['radius'] ?></td>
                                        <td class="text-center">
                                            <a href="detail.php?id=<?= $lokasi['id'] ?>" class="btn-edit">Detail</a>
                                            <a href="edit.php?id=<?= $lokasi['id'] ?>" class="btn-edit">Edit</a>
                                            <a href="hapus.php?id=<?= $lokasi['id'] ?>" class="btn-delete">Hapus</a>
                                        </td>

                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </main>

    <!-- MAIN -->

    </section>
    <!-- CONTENT -->


    <script src="../../assets/java/script.js"></script>
    <?php include("../../assets/swetalert/swetalert.php"); ?>
</body>

</html>