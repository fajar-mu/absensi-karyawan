<?php
session_start();
ob_start();

// Cek apakah pengguna sudah login dan memiliki hak akses 'admin'
if (!isset($_SESSION['login'])) {
    header("Location: ../../login/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
    exit;
}

require_once("../../config.php");

// Proses update data
if (isset($_POST['update'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    $nama_lokasi = isset($_POST['nama_lokasi']) ? $_POST['nama_lokasi'] : '';
    $alamat_lokasi = isset($_POST['alamat_lokasi']) ? $_POST['alamat_lokasi'] : '';
    $tipe_lokasi = isset($_POST['tipe_lokasi']) ? $_POST['tipe_lokasi'] : '';
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';
    $radius = isset($_POST['radius']) ? $_POST['radius'] : '';
    $zona_waktu = isset($_POST['zona_waktu']) ? $_POST['zona_waktu'] : '';
    $jam_masuk = isset($_POST['jam_masuk']) ? $_POST['jam_masuk'] : '';
    $jam_pulang = isset($_POST['jam_pulang']) ? $_POST['jam_pulang'] : '';

    // Validasi input kosong
    if (
        empty($nama_lokasi) || empty($alamat_lokasi) || empty($tipe_lokasi) ||
        empty($latitude) || empty($longitude) || empty($radius) ||
        empty($zona_waktu) || empty($jam_masuk) || empty($jam_pulang)
    ) {
        $_SESSION["validasi"] = "Semua kolom harus diisi!";
        header("Location: edit.php?id=$id");
        exit;
    }

    // Update data ke database
    $result = mysqli_query($connection,  "UPDATE lokasi_presensi 
        SET nama_lokasi = '$nama_lokasi',
            alamat_lokasi = '$alamat_lokasi',
            tipe_lokasi = '$tipe_lokasi',
            latitude = '$latitude',
            longitude = '$longitude',
            radius = '$radius',
            zona_waktu = '$zona_waktu',
            jam_masuk = '$jam_masuk',
            jam_pulang = '$jam_pulang'
        WHERE id = $id");

    if ($result) {
        $_SESSION["berhasil"] = "Data  Lokasi Absensi berhasil diperbarui!";
        header("Location: lokasi_presensi.php");
        exit;
    } else {
        $_SESSION["validasi"] = "Terjadi kesalahan saat memperbarui data!";
        header("Location: edit.php?id=$id");
        exit;
    }
}

// Ambil data lokasi berdasarkan ID
$id = isset($_GET['id']) ? $_GET['id'] : '';
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
                <h1>Edit Lokasi Absensi</h1>
            </div>
        </div>

        <div class="tesinput">
            <!-- Tampilkan pesan validasi jika ada -->
            <?php if (isset($_SESSION['validasi'])): ?>
                <div style="color: red;"><?= $_SESSION['validasi']; ?></div>
                <?php unset($_SESSION['validasi']); ?>
            <?php endif; ?>

            <form action="edit.php" method="POST">
                <div class="form-container">
                    <label for="nama_lokasi">Nama Lokasi</label>
                    <input type="text" id="nama_lokasi" name="nama_lokasi"
                        value="<?= htmlspecialchars($data['nama_lokasi']); ?>">

                    <label for="alamat_lokasi">Alamat Lokasi</label>
                    <input type="text" id="alamat_lokasi" name="alamat_lokasi"
                        value="<?= htmlspecialchars($data['alamat_lokasi']); ?>">

                    <label for="tipe_lokasi">Tipe Lokasi</label>
                    <select name="tipe_lokasi" id="tipe_lokasi">
                        <option value="pusat" <?= $data['tipe_lokasi'] == 'pusat' ? 'selected' : ''; ?>>Pusat</option>
                        <option value="cabang" <?= $data['tipe_lokasi'] == 'cabang' ? 'selected' : ''; ?>>Cabang</option>
                    </select>

                    <label for="latitude">Latitude</label>
                    <input type="text" id="latitude" name="latitude"
                        value="<?= htmlspecialchars($data['latitude']); ?>">

                    <label for="longitude">Longitude</label>
                    <input type="text" id="longitude" name="longitude"
                        value="<?= htmlspecialchars($data['longitude']); ?>">

                    <label for="radius">Radius</label>
                    <input type="number" id="radius" name="radius"
                        value="<?= htmlspecialchars($data['radius']); ?>">

                    <label for="zona_waktu">Zona Waktu</label>
                    <select name="zona_waktu" id="zona_waktu">
                        <option value="WIB" <?= $data['zona_waktu'] == 'WIB' ? 'selected' : ''; ?>>WIB</option>
                        <option value="WITA" <?= $data['zona_waktu'] == 'WITA' ? 'selected' : ''; ?>>WITA</option>
                        <option value="WIT" <?= $data['zona_waktu'] == 'WIT' ? 'selected' : ''; ?>>WIT</option>
                    </select>

                    <label for="jam_masuk">Jam Masuk</label>
                    <input type="time" id="jam_masuk" name="jam_masuk"
                        value="<?= htmlspecialchars($data['jam_masuk']); ?>">

                    <label for="jam_pulang">Jam Pulang</label>
                    <input type="time" id="jam_pulang" name="jam_pulang"
                        value="<?= htmlspecialchars($data['jam_pulang']); ?>">

                    <div class="button-container">
                        <button type="submit" name="update">Update</button>
                        <a href="lokasi_presensi.php" class="button">Back</a>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?= $data['id']; ?>">
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