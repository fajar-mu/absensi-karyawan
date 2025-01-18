<?php
session_start();


if (!isset($_SESSION['login'])) {
	header("Location: ../../login/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != "pegawai") {
	header("Location: ../../login/login.php?pesan=akses_ditolak");
}

require_once("../../config.php");

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");

while ($lokasi = mysqli_fetch_array($result)) {
	$latitude_kantor = $lokasi['latitude'];
	$longitude_kantor = $lokasi['longitude'];
	$radius = $lokasi['radius'];
	$zona_waktu = $lokasi['zona_waktu'];
	$jam_pulang = $lokasi['jam_pulang'];
	$jam_masuk = $lokasi['jam_masuk'];
}
if ($zona_waktu == 'WIB') {
	date_default_timezone_set('Asia/Jakarta');
} elseif ($zona_waktu == 'WITA') {
	date_default_timezone_set('Asia/Makassar');
} elseif ($zona_waktu == 'WIT') {
	date_default_timezone_set('Asia/Jayapura');
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



	<title>Absensi</title>
</head>

<body>


	<!-- sidebar -->
	<section id="sidebar"> <a href="#" class="brand">




		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="home.php">
					<i class='bx bx-home-alt'></i>
					<span class="text">Home</span>
				</a>
			</li>
			<li>
				<a href="../data_absensi/data_absensi.php">
					<i class='bx bxs-user-detail'></i>
					<span class="text">Rekap Absensi</span>
				</a>
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
	<!-- sidebar --><?php include("../layout/navbar.php"); ?>



	<!-- MAIN -->
	<main>
		<div class="head-title">
			<div class="left">
				<h1>Absensi Karyawan</h1>

			</div>

		</div>

		<div class="tesinput">
			<div class="card-container" style="justify-content: center;">
				<div class="card">
					<div class="card-header">Masuk</div>
					<div class="card-body">

						<?php

						$id_pegawai = $_SESSION['id'];
						$tgl_masuk = date('Y-m-d');
						$waktu = date('H:i:s');

						$cek_masuk = mysqli_query($connection, "SELECT*FROM presensi WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tgl_masuk'");

						if (strtotime($waktu) <= strtotime($jam_masuk)) { ?>
							<div style="justify-items: center;">
								<i class='bx bx-x-circle bx-tada bx-lg icon-ex'></i>
								<h4>Belum waktunya absen ya</h4>
							</div>
						<?php } else { ?>
							<?php
							if (mysqli_num_rows($cek_masuk) === 0) {;
							?>
								<div class="date">
									<div id="tanggal_masuk"></div>
									<div class="jarak"></div>
									<div id="bulan_masuk"></div>
									<div class="jarak"></div>
									<div id="tahun_masuk"></div>

								</div>
								<div class="jam">
									<div id="jam_masuk"></div>
									<div>:</div>
									<div id="menit_masuk"></div>
									<div>:</div>
									<div id="detik_masuk"></div>

								</div>
								<form method="POST" action="../presensi/presensi_masuk.php">
									<div class="button-container">
										<input type="hidden" id="latitude" name="latitude_pegawai">

										<input type="hidden" id="longitude" name="longitude_pegawai">
										<input type="hidden" value="<?= $latitude_kantor ?>" name="latitude_kantor">
										<input type="hidden" value="<?= $longitude_kantor ?>" name="longitude_kantor">
										<input type="hidden" value="<?= $radius ?>" name="radius">
										<input type="hidden" value="<?= $zona_waktu ?>" name="zona_waktu">
										<input type="hidden" value="<?= date('Y-m-d') ?>" name="tanggal_masuk">
										<input type="hidden" value="<?= date('H:i:s') ?>" name="jam_masuk">
										<button type="submit" name="masuk">Masuk</button>

									</div>
								</form>
							<?php } else { ?>
								<div style="justify-items: center;">
									<i class='bx bx-check-circle bx-tada bx-lg icon-success'></i>
									<h4>Anda sudah absen masuk untuk hari ini</h4>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<div class="card">
					<div class="card-header">keluar</div>
					<div class="card-body">
						<?php

						$id_pegawai = $_SESSION['id'];
						$tgl_keluar = date('Y-m-d');
						$waktu = date('H:i:s');
						$cek_masuk = mysqli_query($connection, "SELECT*FROM presensi WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tgl_masuk'");
						$cek_keluar = mysqli_query($connection, "SELECT*FROM presensi WHERE id_pegawai = '$id_pegawai' AND tanggal_keluar = '$tgl_keluar'");
						if (mysqli_num_rows($cek_masuk) === 0) { ?>
							<div style="justify-items: center;">
								<i class='bx bx-x-circle bx-tada bx-lg icon-ex'></i>
								<h4>Anda belum absen masuk!!</h4>
							</div>
						<?php } else { ?>
							<?php if (strtotime($waktu) <= strtotime($jam_pulang)) { ?>
								<div style="justify-items: center;">
									<i class='bx bx-x-circle bx-tada bx-lg icon-ex'></i>
									<h4>Belum waktunya pulang</h4>
								</div>

							<?php } else { ?>
								<?php
								if (mysqli_num_rows($cek_keluar) === 0) {;
								?>
									<div class="date">
										<div id="tanggal_keluar"></div>
										<div class="jarak"></div>
										<div id="bulan_keluar"></div>
										<div class="jarak"></div>
										<div id="tahun_keluar"></div>

									</div>
									<div class="jam">
										<div id="jam_keluar"></div>
										<div>:</div>
										<div id="menit_keluar"></div>
										<div>:</div>
										<div id="detik_keluar"></div>

									</div>
									<form method="POST" action="../presensi/presensi_keluar.php">
										<div class="button-container">
											<input type="hidden" id="latitude" name="latitude_pegawai">
											<input type="hidden" id="longitude" name="longitude_pegawai">
											<input type="hidden" value="<?= $latitude_kantor ?>" name="latitude_kantor">
											<input type="hidden" value="<?= $longitude_kantor ?>" name="longitude_kantor">
											<input type="hidden" value="<?= $radius ?>" name="radius">
											<input type="hidden" value="<?= $zona_waktu ?>" name="zona_waktu">
											<input type="hidden" value="<?= date('Y-m-d') ?>" name="tanggal_keluar">
											<input type="hidden" value="<?= date('H:i:s') ?>" name="jam_keluar">
											<button type="submit" name="keluar">Keluar</button>
										</div>
									</form>
								<?php } else { ?>
									<div style="justify-items: center;">
										<i class='bx bx-check-circle bx-tada bx-lg icon-success'></i>
										<h4>Sampai jumpa besok ya</h4>
									</div>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

	</main>
	<!-- MAIN -->
	</section>
	<!-- CONTENT -->


	<script src="../../assets/java/script.js"></script>

	<script>
		//wktu masuk
		function waktumasuk() {
			const now = new Date();
			const namaBulan = [
				"Januari", "Februari", "Maret", "April", "Mei", "Juni",
				"Juli", "Agustus", "September", "Oktober", "November", "Desember"
			];

			// Ambil waktu dan tanggal saat ini
			const tanggal_masuk = now.getDate();
			const bulan_masuk = namaBulan[now.getMonth()]; // Bulan dimulai dari 0
			const tahun_masuk = now.getFullYear();
			const jam_masuk = now.getHours();
			const menit_masuk = now.getMinutes();
			const detik_masuk = now.getSeconds();

			// Format angka agar selalu dua digit
			const formatNumber = (num) => (num < 10 ? "0" + num : num);

			// Update elemen dengan waktu dan tanggal
			document.getElementById("tanggal_masuk").textContent = formatNumber(tanggal_masuk);
			document.getElementById("bulan_masuk").textContent = formatNumber(bulan_masuk);
			document.getElementById("tahun_masuk").textContent = tahun_masuk;
			document.getElementById("jam_masuk").textContent = formatNumber(jam_masuk);
			document.getElementById("menit_masuk").textContent = formatNumber(menit_masuk);
			document.getElementById("detik_masuk").textContent = formatNumber(detik_masuk);


		}

		// Jalankan fungsi setiap detik
		setInterval(waktumasuk, 1000);

		// Panggil sekali di awal agar langsung tampil tanpa menunggu satu detik pertama


		function waktukeluar() {
			const now = new Date();
			const namaBulan = [
				"Januari", "Februari", "Maret", "April", "Mei", "Juni",
				"Juli", "Agustus", "September", "Oktober", "November", "Desember"
			];

			// Ambil waktu dan tanggal saat ini
			const tanggal_keluar = now.getDate();
			const bulan_keluar = namaBulan[now.getMonth()]; // Bulan dimulai dari 0
			const tahun_keluar = now.getFullYear();
			const jam_keluar = now.getHours();
			const menit_keluar = now.getMinutes();
			const detik_keluar = now.getSeconds();

			// Format angka agar selalu dua digit
			const formatNumber = (num) => (num < 10 ? "0" + num : num);

			// Update elemen dengan waktu dan tanggal
			document.getElementById("tanggal_keluar").textContent = formatNumber(tanggal_keluar);
			document.getElementById("bulan_keluar").textContent = formatNumber(bulan_keluar);
			document.getElementById("tahun_keluar").textContent = tahun_keluar;
			document.getElementById("jam_keluar").textContent = formatNumber(jam_keluar);
			document.getElementById("menit_keluar").textContent = formatNumber(menit_keluar);
			document.getElementById("detik_keluar").textContent = formatNumber(detik_keluar);


		}

		// Jalankan fungsi setiap detik
		setInterval(waktukeluar, 1000);

		// Panggil sekali di awal agar langsung tampil tanpa menunggu satu detik pertama
		waktukeluar();

		//waktu keluar

		// Fungsi untuk mendapatkan waktu sekarang


		function getCurrentLocation(successCallback, errorCallback) {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
					(position) => {
						const latitude = position.coords.latitude;
						const longitude = position.coords.longitude;

						// Set nilai ke input field
						document.getElementById("latitude").value = latitude;
						document.getElementById("longitude").value = longitude;

						// Callback sukses (jika diperlukan)
						successCallback(latitude, longitude);
					},
					(error) => {
						errorCallback("Gagal mendapatkan lokasi: " + error.message);
					}
				);
			} else {
				errorCallback("Geolocation tidak didukung oleh browser ini.");
			}
		}
	</script>

	<?php include("../../assets/swetalert/swetalert.php"); ?>
</body>

</html>