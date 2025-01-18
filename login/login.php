<?php
session_start();
require_once("../config.php");

if (isset($_POST["login"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging: Cek data yang dikirim


    $result = mysqli_query($connection, "SELECT * FROM user JOIN pegawai ON user.id_pegawai = pegawai.id WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if ($password === $row['password']) {

            if ($row['status'] == 'Aktif') {
                $_SESSION["login"] = true;
                $_SESSION["id"] = $row['id'];
                $_SESSION["role"] = $row['role'];
                $_SESSION["nama"] = $row['nama'];
                $_SESSION["nip"] = $row['nip'];
                $_SESSION["jabatan"] = $row['jabatan'];
                $_SESSION["lokasi_presensi"] = $row['lokasi_presensi'];
                if ($row['role'] === 'admin') {
                    header("Location: ../admin/home/home.php");
                    exit();
                } else {
                    header("Location: ../user/home/home.php");
                    exit();
                }
                exit(); // Pastikan eksekusi berhenti setelah header
            } else {
                $_SESSION["gagal"] = "Akun anda belum aktif, silahkan coba lagi";
            }
        } else {
            $_SESSION["gagal"] = "Password salah, silahkan coba lagi";
        }
    } else {
        $_SESSION["gagal"] = "Username tidak di temukan, silahkan coba lagi";
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="body2">

    <div class="login-container">

        <div class="login-box">



            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "belum_login") {
                    $_SESSION['gagal'] = "anda belum login";
                } else if ($_GET['pesan'] == "akses_ditolak") {
                    $_SESSION['gagal'] = "akses di tolak";
                }
            }
            ?>

            <!-- Form login -->
            <form action="login.php" method="POST">
                <h2>Login</h2>

                <div class="textbox">
                    <i class="fa fa-user"></i>
                    <input type="text" name="username" placeholder="Username">


                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Password">
                </div>
                <button class="btn-login" type="submit" name="login">Login</button>
            </form>


        </div>

    </div>

    <!--- switch alert --->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include("../assets/swetalert/swetalert.php"); ?>


</body>

</html>