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
    header("Location: pegawai.php");
    exit;
}

// Ambil data pegawai berdasarkan ID
$result = mysqli_query($connection, "SELECT user.id_pegawai, user.username, user.password, user.status, user.role, pegawai.* FROM user JOIN pegawai ON user.id_pegawai = pegawai.id WHERE pegawai.id = $id");
if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
} else {
    $_SESSION['validasi'] = "Data pegawai tidak ditemukan.";
    header("Location: pegawai.php");
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
    <title>Detail Pegawai</title>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar"> <a href="#" class="brand">




        </a>
        <ul class="side-menu top">
            <li><a href="../home/home.php"><i class='bx bx-home-alt'></i><span class="text">Home</span></a></li>
            <li class="active"><a href="../data_pegawai/pegawai.php"><i class='bx bx-clipboard'></i><span class="text">Data Pegawai</span></a></li>
            <li class="has-submenu">
                <a href="#"><i class='bx bxs-doughnut-chart'></i><span class="text"> Data Admin</span></a>
                <ul class="sub-menu">
                    <li><a href="../data_jabatan/jabatan.php">Jabatan</a></li>
                    <li><a href="../data_lokasi_presensi/lokasi_presensi.php"> Lokasi Absensi</a></li>
                </ul>
            </li>

        </ul>
        <ul class="side-menu">

            <li><a href="../../login/logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">logout</span></a></li>
        </ul>
    </section>

    <?php include("../layout/navbar.php"); ?>

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Detail Pegawai</h1>
            </div>
        </div>

        <div class="tesinput">

            <label for="nip">NIP</label>
            <input type="text" id="nip" name="nip" value="<?= htmlspecialchars($data['nip']); ?>" readonly>

            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" readonly>

            <label for="jenis_kelamin">Jenis Kelamin</label>
            <input type="text" id="jenis_kelamin" name="jenis_kelamin" value="<?= htmlspecialchars($data['jenis_kelamin']); ?>" readonly>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($data['username']); ?>" readonly>

            <label for="password">Password</label>
            <input type="text" id="password" name="password" value="<?= htmlspecialchars($data['password']); ?>" readonly>

            <label for="jabatan">Jabatan</label>
            <input type="text" id="jabatan" name="jabatan" value="<?= htmlspecialchars($data['jabatan']); ?>" readonly>

            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" value="<?= htmlspecialchars($data['alamat']); ?>" readonly>

            <label for="no_hp">No Hp</label>
            <input type="text" id="no_hp" name="no_hp" value="<?= htmlspecialchars($data['no_hp']); ?>" readonly>

            <label for="role">Role</label>
            <input type="text" id="role" name="role" value="<?= htmlspecialchars($data['role']); ?>" readonly>

            <label for="lokasi_presensi"> Lokasi Absensi</label>
            <input type="text" id="lokasi_presensi" name="lokasi_presensi" value="<?= htmlspecialchars($data['lokasi_presensi']); ?>" readonly>

            <label for="status">Status</label>
            <input type="text" id="status" name="status" value="<?= htmlspecialchars($data['status']); ?>" readonly>

            <div class="button-container">
                <a href="pegawai.php" class="button">Back</a>
            </div>

        </div>

    </main>

    <script src="../../assets/java/script.js"></script>
    <?php include("../../assets/swetalert/swetalert.php"); ?>

</body>

</html>