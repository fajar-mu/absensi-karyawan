<?php
session_start();


if (!isset($_SESSION['login'])) {
    header("Location: ../../login/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
}
require_once("../../config.php");

$result = mysqli_query($connection, "SELECT user.id_pegawai, user.username, user.password, user.status, user.role, pegawai.* FROM user JOIN pegawai ON user.id_pegawai = pegawai.id"); ?>



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
            <li class="active">
                <a href="pegawai.php">
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
                    <li><a href="../data_lokasi_presensi/lokasi_presensi.php"> Lokasi Absensi</a></li>

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
                <h1>Data Pegawai</h1>



            </div>
        </div>
        <a href="tambah.php" class="btn-edit"><span class="text"><i class='bx bxs-user-plus'></i>Tanbah data </span></a>
        <div class="table-data">
            <div class="order">
                <div class="head">


                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nip</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Jabatan</th>
                                <th> Lokasi Absensi</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) === 0) : ?>
                                <tr>
                                    <td colspan="9">Maaf data masih kosong</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; ?>
                                <?php while ($pegawai = mysqli_fetch_array($result)) : ?>
                                    <tr>

                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $pegawai['nip'] ?></td>
                                        <td><?= $pegawai['nama'] ?></td>
                                        <td><?= $pegawai['username'] ?></td>
                                        <td><?= $pegawai['password'] ?></td>
                                        <td><?= $pegawai['jabatan'] ?></td>
                                        <td><?= $pegawai['lokasi_presensi'] ?></td>
                                        <td><?= $pegawai['role'] ?></td>
                                        <td class="text-center">
                                            <a href="detail.php?id=<?= $pegawai['id'] ?>" class="btn-edit">Detail</a>
                                            <a href="edit.php?id=<?= $pegawai['id'] ?>" class="btn-edit">Edit</a>
                                            <a href="hapus.php?id=<?= $pegawai['id'] ?>" class="btn-delete">Hapus</a>
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