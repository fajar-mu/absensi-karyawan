<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != "pegawai") {
    header("Location: ../../login/login.php?pesan=akses_ditolak");
}

require_once("../../config.php");

if (isset($_POST['keluar'])) {
    $latitude_pegawai = (float) $_POST['latitude_pegawai'];
    $longitude_pegawai = (float) $_POST['longitude_pegawai'];
    $latitude_kantor = (float) $_POST['latitude_kantor'];
    $longitude_kantor = (float) $_POST['longitude_kantor'];
    $radius = (float) $_POST['radius'];
    $zona_waktu = $_POST['zona_waktu'];
    $tanggal_keluar = $_POST['tanggal_keluar'];
    $jam_keluar = $_POST['jam_keluar'];
}

$perbedaan_koordinat = $longitude_pegawai - $longitude_kantor;
$jarak = sin(deg2rad($latitude_pegawai)) * sin(deg2rad($latitude_kantor)) + cos(deg2rad($latitude_pegawai)) * cos(deg2rad($latitude_kantor)) * cos(deg2rad($perbedaan_koordinat));
$jarak = acos($jarak);
$jarak = rad2deg($jarak);
$mil = $jarak * 60 * 1.1515;
$jarak_km = $mil * 1.609344;
$jarak_meter = $jarak_km * 1000;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Absensi</title>
    <style>
        #map {
            height: 300px;
        }
    </style>

</head>

<body>
    <!-- sidebar -->
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
                <a href="../data_absensi/data_absensi.php">
                    <i class='bx bxs-user-detail'></i>
                    <span class="text">Rekap Absensi</span>
                </a>
            </li>
            <li>
                <ul class="side-menu">

                    <li>
                        <a href="../../login/logout.php" class="logout">
                            <i class='bx bxs-log-out-circle'></i>
                            <span class="text">logout</span>
                        </a>
                    </li>
                </ul>
    </section>
    <!-- sidebar -->
    <?php include("../layout/navbar.php"); ?>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Absensi Karyawan</h1>
            </div>
        </div>

        <div class="tesinput">
            <div class="card-container" style="justify-content: center;">
                <div class="card" style="margin: auto;">
                    <div>
                        <div id="map">

                        </div>
                    </div>
                </div>
                <div class="card" style="margin: auto;">
                    <div>
                        <input type="hidden" id="id" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" id="tanggal_keluar" value="<?= $tanggal_keluar ?>">
                        <input type="hidden" id="jam_keluar" value="<?= $jam_keluar ?>">
                        <div id="my_camera"></div>
                        <div id="my_result"></div>
                        <div><?= date('d F Y', strtotime($tanggal_keluar)) . '-' . $jam_keluar ?></div>
                        <div class="button-container">
                            <button type="submit" name="submit" id="ambil_foto">Keluar</button>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <script language="JavaScript">
            Webcam.set({
                width: 320,
                height: 240,
                dest_width: 320,
                dest_height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90,
                force_flash: false
            });
            Webcam.attach('#my_camera');

            document.getElementById('ambil_foto').addEventListener('click', function() {

                let id = document.getElementById('id').value;
                let tanggal_keluar = document.getElementById('tanggal_keluar').value;
                let jam_keluar = document.getElementById('jam_keluar').value;

                Webcam.snap(function(data_uri) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';
                        if (xhttp.readyState == 4 && xhttp.status == 200) {
                            window.location.href = '../home/home.php';
                        }
                    };
                    xhttp.open("POST", "presensi_keluar_aksi.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send(
                        'foto=' + encodeURIComponent(data_uri) +
                        '&id=' + id +
                        '&tanggal_keluar=' + tanggal_keluar +
                        '&jam_keluar=' + jam_keluar
                    );

                });
            });


            //map
            let latitude_ktr = <?= $latitude_kantor ?>;
            let longitude_ktr = <?= $longitude_kantor ?>;
            let latitude_pgwi = <?= $latitude_pegawai ?>;
            let longitude_pgwi = <?= $longitude_pegawai ?>;
            var map = L.map('map').setView([latitude_ktr, longitude_ktr], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([latitude_pgwi, longitude_pgwi]).addTo(map).bindPopup("Lokasi anda saat ini");
            var circle = L.circle([latitude_ktr, longitude_ktr], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 500
            }).addTo(map).bindPopup("Kantor").openPopup();
        </script>





        <?php include("../../assets/swetalert/swetalert.php"); ?>
</body>

</html>