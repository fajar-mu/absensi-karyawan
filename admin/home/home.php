<?php
session_start();
require_once("../../config.php");


if (!isset($_SESSION['login'])) {
	header("Location: ../../login/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
	header("Location: ../../login/login.php?pesan=akses_ditolak");
}

$pegawai = mysqli_query($connection, "SELECT pegawai.*, user.status FROM pegawai JOIN user ON pegawai.id = user.id_pegawai WHERE user.status = 'Aktif'
");
$total_aktif = mysqli_num_rows($pegawai);
$jumblah_pegawai = mysqli_query($connection, "SELECT pegawai.*, user.status FROM pegawai JOIN user ON pegawai.id = user.id_pegawai");
$total_pegawai = mysqli_num_rows($jumblah_pegawai);

// rekap absensi

if (empty($_GET['tanggal_dari'])) {
	// Query tanpa filter tanggal, menggunakan JOIN
	$result = mysqli_query($connection, "SELECT pegawai.nama, presensi.tanggal_masuk, presensi.jam_masuk, presensi.tanggal_keluar, presensi.jam_keluar FROM presensi JOIN pegawai  ON presensi.id_pegawai = pegawai.id ORDER BY presensi.tanggal_masuk DESC");
} else {
	// Query dengan filter tanggal, menggunakan JOIN
	$tanggal_dari = $_GET['tanggal_dari'];
	$tanggal_sampai = $_GET['tanggal_sampai'];
	$result = mysqli_query($connection, "SELECT pegawai.nama, presensi.tanggal_masuk, presensi.jam_masuk, presensi.tanggal_keluar, presensi.jam_keluar FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE presensi.tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' ORDER BY presensi.tanggal_masuk DESC ");
}

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$lokasi = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
while ($lokasi_result = mysqli_fetch_array($lokasi)):

	$jam_masuk_kantor = date('H:i:s',  strtotime($lokasi_result['jam_masuk']));

endwhile;

$nama = mysqli_query($connection, "SELECT * FROM pegawai ORDER BY nama desc");

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
	<style>
		h4 {
			text-align: center;
		}

		.icon-success {
			color: green;
		}

		.icon-ex {
			color: red;
		}
	</style>
</head>

<body>


	<!-- SIDEBAR -->
	<section id="sidebar"> <a href="#" class="brand">


		</a>
		<ul class="side-menu top">
			<li class="active">
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
				<h1>Dashboard</h1>

			</div>
			<a>
				<i></i>
				<span></span>
			</a>
		</div>
		<ul class="box-info">
			<li>
				<i class='bx bx-user'></i>
				<span class="text">
					<h3><?= $total_aktif ?></h3>
					<p>Pegawai Aktif</p>
				</span>
			</li>
			<li>
				<i class='bx bx-user-check'></i>
				<span class="text">
					<h3><?= $total_pegawai ?></h3>
					<p>Jumblah Pegawai</p>
				</span>
			</li>

		</ul>

		<div class="table-data">
			<div class="order">

				<label style="font-size: 20px; text-align: center; margin-bottom: 20px;">Data Pegawai</label>

				<div class="head-title">
					<form method="get">
						<div class="left">
							<div class="posisi">
								<div class="left">
									<input type="date" class="form-control" name="tanggal_dari">
								</div>
								<div class="left">
									<input type="date" class="form-control" name="tanggal_sampai">
								</div>
								<div class="left">
									<button type="submit" class="btn-delete">Tampilkan</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="head">
					<table>
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal</th>
								<th>Username</th>
								<th>Jam Masuk</th>
								<th>Jam Keluar</th>
								<th>Total Jam Kerja</th>
							</tr>
						</thead>
						<?php if (mysqli_num_rows($result) === 0) { ?>
							<tr>
								<td colspan="5">Rekap Absensi Masih Kosong.</td>
							</tr>
						<?php } else { ?>

							<?php
							//menghitung jam kerja
							$no = 1;
							while ($rekap = mysqli_fetch_array($result)) :
								$jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']));
								$jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']));

								$timestamp_masuk = strtotime($jam_tanggal_masuk);
								$timestamp_keluar = strtotime($jam_tanggal_keluar);

								$selisih = $timestamp_keluar - $timestamp_masuk;

								$total_jam_kerja = floor($selisih / 3600);
								$selisih -= $total_jam_kerja * 3600;
								$selisih_menit_kerja = floor($selisih / 60);
							?>
								<tbody>
									<tr>
										<td><?= $no++ ?></td>
										<td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
										<td><?= $rekap['nama'] ?></td>
										<td><?= $rekap['jam_masuk'] ?></td>
										<td><?= $rekap['jam_keluar'] ?></td>
										<td><?= $total_jam_kerja . ' jam ' . $selisih_menit_kerja . ' menit' ?></td>
									</tr>
								</tbody>
							<?php endwhile ?>
						<?php } ?>
					</table>
				</div>
				<a href="export_excel.php?tanggal_dari=<?= $_GET['tanggal_dari'] ?? '' ?>&tanggal_sampai=<?= $_GET['tanggal_sampai'] ?? '' ?>" class="excel-btn">Export Excel</a>
			</div>
		</div>
		<?php include("../layout/footer.php"); ?>
	</main>


	<!-- MAIN -->

	</section>
	<!-- CONTENT -->

	<script src="../../assets/java/script.js"></script>
</body>

</html>