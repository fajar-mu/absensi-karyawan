<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
}
require_once("../../config.php");
if (isset($_POST['submit'])) {

    $nama_lokasi = htmlspecialchars($_POST['nama_lokasi']);
    $alamat_lokasi = htmlspecialchars($_POST['alamat_lokasi']);
    $tipe_lokasi = htmlspecialchars($_POST['tipe_lokasi']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $radius = htmlspecialchars($_POST['radius']);
    $zona_waktu = htmlspecialchars($_POST['zona_waktu']);
    $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
    $jam_pulang = htmlspecialchars($_POST['jam_pulang']);

    if (
        empty($nama_lokasi) || empty($alamat_lokasi) || empty($tipe_lokasi) ||
        empty($latitude) || empty($longitude) || empty($radius) ||
        empty($zona_waktu) || empty($jam_masuk) || empty($jam_pulang)
    ) {
        // Set pesan validasi
        $_SESSION['validasi'] = "Semua kolom harus diisi!";
        header("Location: tambah.php");
        exit;
    }

    $result = mysqli_query($connection, "INSERT INTO lokasi_presensi(nama_lokasi, alamat_lokasi, tipe_lokasi, latitude, longitude, radius, zona_waktu, jam_masuk, jam_pulang)
     VALUES('$nama_lokasi', '$alamat_lokasi', '$tipe_lokasi', '$latitude', '$longitude', '$radius', '$zona_waktu', '$jam_masuk', '$jam_pulang')");

    $_SESSION['berhasil'] = "data berhasil di simpan";
    header("Location: lokasi_presensi.php");
    exit;
}


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
                <h1>Tambah Lokasi Data Jabatan</h1>



            </div>
        </div>
        <div class="tesinput">
            <form action="tambah.php" method="POST">

                <div class="form-container">
                    <label for="">Nama Lokasi</label>
                    <input type="text" id="nama_lokasi" name="nama_lokasi" value="<?php if (isset($_POST['nama_lokasi'])) echo $_POST['nama_lokasi'] ?>">


                    <label for="">Alamat Lokasi</label>
                    <input type="text" id="alamat_lokasi" name="alamat_lokasi">


                    <label for="">Tipe Lokasi</label>
                    <select name="tipe_lokasi" id="tipe_lokasi">
                        <option value="">--Pilih Tipe Lokasi--</option>
                        <option value="pusat">Pusat</option>
                        <option value="cabang">Cabang</option>
                    </select>

                    <label for="">Latitude</label>
                    <input type="text" id="latitude" name="latitude">

                    <label for="">Longitude</label>
                    <input type="text" id="longitude" name="longitude">

                    <label for="">Radius</label>
                    <input type="number" id="radius" name="radius">

                    <label for="">Zona Waktu</label>
                    <select name="zona_waktu" id="zona_waktu">
                        <option value="">--Pilih Zona Waktu--</option>
                        <option value="WIB">WIB</option>
                        <option value="WITA">WITA</option>
                        <option value="WIT">WIT</option>
                    </select>

                    <label for="">Jam Masuk</label>
                    <input type="time" id="jam_masuk" name="jam_masuk">

                    <label for="">Jam Pulang</label>
                    <input type="time" id="jam_pulang" name="jam_pulang">

                    <div class="button-container">
                        <button type="submit" name="submit">Simpan</button>
                        <a href="lokasi_presensi.php" class="button">Back</a>
                    </div>
                </div>


            </form>
        </div>


    </main>

    <!-- MAIN -->

    </section>
    <!-- CONTENT -->


    <script src="../../assets/java/script.js"></script>
    <?php include("../../assets/swetalert/swetalert.php"); ?>
</body>

</html>