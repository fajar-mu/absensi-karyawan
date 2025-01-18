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

    $nip = htmlspecialchars($_POST['nip']);
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $lokasi_presensi = htmlspecialchars($_POST['lokasi_presensi']);
    $foto = htmlspecialchars($_POST['foto']);

    if (
        empty($nip) || empty($nama) || empty($username) || empty($password) || empty($jabatan) || empty($role) || empty($status)
    ) {
        // Set pesan validasi
        $_SESSION['validasi'] = "Semua kolom harus diisi!";
        header("Location: tambah.php");
        exit;
    }

    // Insert pegawai data into 'pegawai' table
    $query_pegawai = "INSERT INTO pegawai (nip, nama, jabatan, jenis_kelamin , alamat, no_hp, lokasi_presensi, foto) 
                      VALUES('$nip', '$nama', '$jabatan', '$jenis_kelamin', '$alamat', '$no_hp', '$lokasi_presensi','$foto')";
    mysqli_query($connection, $query_pegawai);

    // Get the last inserted ID (id_pegawai)
    $id_pegawai = mysqli_insert_id($connection);

    // Insert user data into the 'user' table
    $query_user = "INSERT INTO user (id_pegawai, username, password, role, status) 
                   VALUES('$id_pegawai', '$username', '$password', '$role', '$status')";
    mysqli_query($connection, $query_user);

    $_SESSION['berhasil'] = "Data berhasil disimpan!";
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
    <title>Tambah Data Pegawai</title>
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
                    <li><a href="../data_lokasi_presensi/lokasi_presensi.php"> Lokasi Absensi</a></li>
                </ul>
            </li>

            <ul class="side-menu">

                <li>
                    <a href="../../login/logout.php" class="logout">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
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
                <h1>Tambah Data Pegawai</h1>
            </div>
        </div>
        <?php
        $ambil_nip = mysqli_query($connection, "SELECT nip FROM pegawai ORDER BY nip DESC LIMIT 1");
        if (mysqli_num_rows($ambil_nip) > 0) {
            $row = mysqli_fetch_assoc($ambil_nip);
            $nip_db = $row['nip'];
            $nip_db = explode("-", $nip_db);
            $no_baru = (int)$nip_db[1] + 1;
            $nip_baru = "TELKOM-" . str_pad($no_baru, 3, 0, STR_PAD_LEFT);
        } else {
            $nip_baru = "TEKLOM-001";
        }
        ?>
        <div class="tesinput">
            <form action="tambah.php" method="POST">
                <div class="form-container">
                    <label for="nip">NIP</label>
                    <input type="text" id="nip" name="nip" value="<?= $nip_baru ?>">

                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="<?php if (isset($_POST['nama'])) echo $_POST['nama']; ?>">

                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat">

                    <label for="no_hp">No Hp</label>
                    <input type="text" id="no_hp" name="no_hp">

                    <label for="lokasi_presensi"> Lokasi Absensi</label>
                    <select name="lokasi_presensi" id="lokasi_presensi">
                        <option value="">--Pilih Lokasi Absensi--</option>
                        <?php
                        $ambil_lokasi = mysqli_query($connection, "SELECT*FROM lokasi_presensi order by nama_lokasi asc");
                        while ($nama_lokasi = mysqli_fetch_assoc($ambil_lokasi)) {
                            $lokasi = $nama_lokasi['nama_lokasi'];
                            if (isset($_POST['nama_lokasi']) && $_POST['nama_lokasi'] == $lokasi) {
                                echo '<option value="' . $lokasi . '" 
                            selected="selected">' . $lokasi . '</option>';
                            } else {
                                echo '<option value="' . $lokasi . '">' . $lokasi . '</option>';
                            }
                        }

                        ?>
                    </select>


                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin">
                        <option value="">--Pilih Jenis Kelamin--</option>
                        <option <?php if (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == "laki_laki") {
                                    echo 'selected';
                                } ?> value="laki-laki">Laki-Laki</option>
                        <option <?php if (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == "perempuan") {
                                    echo 'selected';
                                } ?> value="perempuan">Perempuan</option>
                    </select>

                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">

                    <label for="jabatan">Jabatan</label>
                    <select name="jabatan" id="jabatan">
                        <option value="">--Pilih Jabatan--</option>
                        <?php
                        $ambil_jabatan = mysqli_query($connection, "SELECT*FROM jabatan order by jabatan asc");
                        while ($jabatan = mysqli_fetch_assoc($ambil_jabatan)) {
                            $nama_jabatan = $jabatan['jabatan'];
                            if (isset($_POST['jabatan']) && $_POST['jabatan'] == $nama_jabatan) {
                                echo '<option value="' . $nama_jabatan . '" 
                            selected="selected">' . $nama_jabatan . '</option>';
                            } else {
                                echo '<option value="' . $nama_jabatan . '">' . $nama_jabatan . '</option>';
                            }
                        }

                        ?>
                    </select>

                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="">--Pilih Role--</option>
                        <option value="admin">Admin</option>
                        <option value="pegawai">Pegawai</option>
                    </select>

                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>

                    <div class="button-container">
                        <button type="submit" name="submit">Simpan</button>
                        <a href="pegawai.php" class="button">Back</a>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <script src="../../assets/java/script.js"></script>
    <?php include("../../assets/swetalert/swetalert.php"); ?>
</body>

</html>