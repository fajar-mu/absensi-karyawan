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

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    $_SESSION['validasi'] = "ID tidak ditemukan atau tidak valid.";
    header("Location: lokasi_presensi.php");
    exit;
}

// Ambil data lokasi berdasarkan ID
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE id = $id");
if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
} else {
    $_SESSION['validasi'] = "Data lokasi tidak ditemukan.";
    header("Location: lokasi_presensi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Detail Lokasi Absensi</title>
</head>

<body>

    <section id="sidebar"> <a href="#" class="brand">




        </a>
        <ul class="side-menu top">
            <li><a href="../home/home.php"><i class='bx bx-home-alt'></i><span class="text">Home</span></a></li>
            <li><a href="../data_pegawai/pegawai.php"><i class='bx bx-clipboard'></i><span class="text">Data Pegawai</span></a></li>
            <li class="has-submenu">
                <a href="#"><i class='bx bxs-doughnut-chart'></i><span class="text"> Data Admin</span></a>
                <ul class="sub-menu">
                    <li><a href="../data_jabatan/jabatan.php">Jabatan</a></li>
                    <li><a href="lokasi_presensi.php"> Lokasi Absensi</a></li>
                </ul>
            </li>

        </ul>
        <ul class="side-menu">

            <li><a href="../../login/logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">logout</span></a></li>
        </ul>
    </section>

    <?php include("../layout/navbar.php"); ?>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Detail Lokasi Absensi</h1>
            </div>
        </div>

        <div class="tesinput">

            <label for="nama_lokasi">Nama Lokasi</label>
            <input type="text" id="nama_lokasi" name="nama_lokasi" value="<?= htmlspecialchars($data['nama_lokasi']); ?>" readonly>

            <label for="alamat_lokasi">Alamat Lokasi</label>
            <input type="text" id="alamat_lokasi" name="alamat_lokasi" value="<?= htmlspecialchars($data['alamat_lokasi']); ?>" readonly>

            <label for="tipe_lokasi">Tipe Lokasi</label>
            <input type="text" id="tipe_lokasi" name="tipe_lokasi" value="<?= htmlspecialchars($data['tipe_lokasi']); ?>" readonly>

            <label for="latitude">Latitude</label>
            <input type="text" id="latitude" name="latitude" value="<?= htmlspecialchars($data['latitude']); ?>" readonly>

            <label for="longitude">Longitude</label>
            <input type="text" id="longitude" name="longitude" value="<?= htmlspecialchars($data['longitude']); ?>" readonly>

            <label for="radius">Radius</label>
            <input type="number" id="radius" name="radius" value="<?= htmlspecialchars($data['radius']); ?>" readonly>

            <label for="zona_waktu">Zona Waktu</label>
            <input type="text" id="zona_waktu" name="zona_waktu" value="<?= htmlspecialchars($data['zona_waktu']); ?>" readonly>

            <label for="jam_masuk">Jam Masuk</label>
            <input type="time" id="jam_masuk" name="jam_masuk" value="<?= htmlspecialchars($data['jam_masuk']); ?>" readonly>

            <label for="jam_pulang">Jam Pulang</label>
            <input type="time" id="jam_pulang" name="jam_pulang" value="<?= htmlspecialchars($data['jam_pulang']); ?>" readonly>
            <label for="maps_preview">Google Maps Preview</label>
            <div>
                <iframe
                    src="https://maps.google.com/maps?q=<?= htmlspecialchars($data['latitude']); ?>,<?= htmlspecialchars($data['longitude']); ?>&z=15&output=embed"
                    width="100%"
                    height="460"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>

            </div>


            <div class="button-container">
                <a href="lokasi_presensi.php" class="button">Back</a>
            </div>

        </div>




    </main>

    <script src="../../assets/java/script.js"></script>
    <?php include("../../assets/swetalert/swetalert.php"); ?>

    <script src="../../assets/java/script.js"></script>
    <?php include("../../assets/swetalert/swetalert.php"); ?>
</body>

</html>