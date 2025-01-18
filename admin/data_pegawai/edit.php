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
    $nip = isset($_POST['nip']) ? $_POST['nip'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $no_hp = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';
    $lokasi_presensi = isset($_POST['lokasi_presensi']) ? $_POST['lokasi_presensi'] : '';
    $foto = isset($_POST['foto']) ? $_POST['foto'] : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $jabatan = isset($_POST['jabatan']) ? $_POST['jabatan'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validasi input kosong
    if (empty($nip) || empty($nama) || empty($username) || empty($jabatan) || empty($role) || empty($status)) {
        $_SESSION["validasi"] = "Semua kolom harus diisi!";
        header("Location: edit.php?id=$id");
        exit;
    }



    // Update data ke database
    $result = mysqli_query($connection,  "UPDATE user 
        JOIN pegawai ON user.id_pegawai = pegawai.id 
        SET nip = '$nip',
            nama = '$nama',
            alamat ='$alamat',
            no_hp = '$no_hp',
            lokasi_presensi = '$lokasi_presensi',
             foto = '$foto',
              jenis_kelamin = '$jenis_kelamin',
            username = '$username',
            password = '$password',
            jabatan = '$jabatan',
            role = '$role',
            status = '$status'
        WHERE user.id_pegawai = $id");

    if ($result) {
        $_SESSION["berhasil"] = "Data pegawai berhasil diperbarui!";
        header("Location: pegawai.php");
        exit;
    } else {
        $_SESSION["validasi"] = "Terjadi kesalahan saat memperbarui data!";
        header("Location: edit.php?id=$id");
        exit;
    }
}

// Ambil data pegawai berdasarkan ID
$id = isset($_GET['id']) ? $_GET['id'] : '';
$result = mysqli_query($connection, "SELECT * FROM user JOIN pegawai ON user.id_pegawai = pegawai.id WHERE user.id_pegawai = $id");

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
                <h1>Edit Data Pegawai</h1>
            </div>
        </div>

        <div class="tesinput">
            <!-- Tampilkan pesan validasi jika ada -->

            <form action="edit.php" method="POST">
                <div class="form-container">
                    <label for="nip">NIP</label>
                    <input type="text" id="nip" name="nip" value="<?= htmlspecialchars($data['nip']); ?>">

                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']); ?>">

                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" value="<?= htmlspecialchars($data['alamat']); ?>">

                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin">
                        <option value="">--Pilih Jenis Kelamin--</option>
                        <option value="laki-laki" <?php echo isset($data['jenis_kelamin']) && $data['jenis_kelamin'] == "laki-laki" ? 'selected' : ''; ?>>Laki-Laki</option>
                        <option value="perempuan" <?php echo isset($data['jenis_kelamin']) && $data['jenis_kelamin'] == "perempuan" ? 'selected' : ''; ?>>Perempuan</option>
                    </select>

                    <label for="no_hp">No hp</label>
                    <input type="text" id="no_hp" name="no_hp" value="<?= htmlspecialchars($data['no_hp']); ?>">

                    <label for="lokasi_presensi"> Lokasi Absensi</label>
                    <select name="lokasi_presensi" id="lokasi_presensi">
                        <option value="">--Pilih Lokasi Absensi--</option>
                        <?php

                        $ambil_nama_lokasi = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY nama_lokasi ASC");


                        $lokasi_terpilih = isset($lokasi_terpilih) ? $lokasi_terpilih : '';


                        while ($nama_lokasi = mysqli_fetch_assoc($ambil_nama_lokasi)) {
                            $nama_nama_lokasi = $nama_lokasi['nama_lokasi'];


                            if ($lokasi_terpilih == $nama_nama_lokasi) {
                                echo '<option value="' . $nama_nama_lokasi . '" selected="selected">' . $nama_nama_lokasi . '</option>';
                            } else {
                                echo '<option value="' . $nama_nama_lokasi . '">' . $nama_nama_lokasi . '</option>';
                            }
                        }
                        ?>
                    </select>

                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($data['username']); ?>">

                    <label for="password">Password</label>
                    <input type="text" id="password" name="password" value="<?= $data['password'] ?>">

                    <label for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" value="<?= htmlspecialchars($data['jabatan']); ?>">

                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="pegawai" <?= $data['role'] == 'pegawai' ? 'selected' : ''; ?>>Pegawai</option>
                    </select>

                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="Aktif" <?= $data['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="nonaktif" <?= $data['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>

                    <label for="foto">foto</label>
                    <input type="file" id="foto" name="foto" value="<?= htmlspecialchars($data['foto']); ?>">

                    <div class="button-container">
                        <button type="submit" name="update">Update</button>
                        <a href="pegawai.php" class="button">Back</a>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?= $data['id_pegawai']; ?>">
            </form>
        </div>

    </main>

    <script src="../../assets/java/script.js"></script>
    <?php include("../../assets/swetalert/swetalert.php"); ?>
</body>

</html>