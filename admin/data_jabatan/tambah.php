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

	$jabatan = isset($_POST['jabatan']) ? ($_POST['jabatan']) : '';
	if ($_SERVER["REQUEST_METHOD"] == 'POST') {
		if (empty($jabatan)) {
			$_SESSION["validasi"] = "Nama Jabatan Wajib Di Isi";
			header("Location: tambah.php");
			exit;
		}
		$result = mysqli_query($connection, "INSERT INTO jabatan(jabatan) VALUES ('$jabatan')");
		if ($result) {
			$_SESSION["berhasil"] = "Data jabatan sukses dibuat";
		}
		// Redirect ke halaman tujuan
		header("Location: jabatan.php");
		exit;
	}
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
				<a href="#">
					<i class='bx bx-clipboard'></i>
					<span class="text">Data Pegawai</span>
				</a>
			</li>
			<li class="has-submenu">
				<a href="../data_pegawai/pegawai.php">
					<i class='bx bxs-doughnut-chart'></i>
					<span class="text"> Data Admin</span>

				</a>
				<ul class="sub-menu">
					<li><a href="jabatan.php">Jabatan</a></li>
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
				<h1>Tambah Data Jabatan</h1>



			</div>
		</div>
		<div class="tesinput">
			<form action="tambah.php" method="POST">

				<div class="form-container">
					<label for="">Nama Jabatan</label>
					<input type="text" id="jabatan" name="jabatan" placeholder="Masukkan nama jabatan">
					<div class="button-container">
						<button type="submit" name="submit">Simpan</button>
						<a href="jabatan.php" class="button">Back</a>
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