-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Jan 2025 pada 08.46
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id` int(11) NOT NULL,
  `jabatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id`, `jabatan`) VALUES
(1, 'admin'),
(2, 'pegawai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(225) NOT NULL,
  `tipe_lokasi` varchar(225) NOT NULL,
  `alamat_lokasi` varchar(50) NOT NULL,
  `latitude` varchar(225) NOT NULL,
  `longitude` varchar(225) NOT NULL,
  `radius` int(11) NOT NULL,
  `zona_waktu` varchar(4) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `tipe_lokasi`, `alamat_lokasi`, `latitude`, `longitude`, `radius`, `zona_waktu`, `jam_masuk`, `jam_pulang`) VALUES
(1, 'kantor pusat', 'pusat', 'kantor', '0.9077581989059199', '108.98061970971295', 100000, 'WIB', '07:00:00', '17:00:00'),
(2, 'kantor cabang', 'cabang', 'semparuk', '1.1882214729919183', '109.09193371263052', 1000000, 'WIB', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(50) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `jabatan` varchar(20) NOT NULL,
  `lokasi_presensi` varchar(225) NOT NULL,
  `foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id`, `nip`, `nama`, `jenis_kelamin`, `alamat`, `no_hp`, `jabatan`, `lokasi_presensi`, `foto`) VALUES
(1, 'TELKOM-001', 'FAJAR', 'pria', 'SEPADU ', '23224245', 'admin', 'KANTOR CABANG', 'KOJET.JPG'),
(2, 'TELKOM-002', 'anto', 'laki-laki', 'seladu', '323', 'admin', 'kantor pusat', ''),
(3, 'TELKOM-003', 'Kojet', 'laki-laki', '123', '123', 'pegawai', 'kantor cabang', ''),
(4, 'TELKOM-004', 'anong', 'laki-laki', 'Desa Seladu, Dusun Sepadu, Rt 3, Rw 1', '083871473349', 'pegawai', 'kantor cabang', ''),
(5, 'TELKOM-005', 'gilang', 'perempuan', 'serunai', '123', 'admin', 'kantor pusat', ''),
(7, 'TELKOM-007', 'sandi', 'laki-laki', 'serunai', '083153437501', 'pegawai', 'kantor cabang', ''),
(8, 'TELKOM-008', 'alvigih', 'laki-laki', 'Desa Seladu, Dusun Sepadu, Rt 3, Rw 1', '123', 'pegawai', 'kantor pusat', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `tanggal_masuk` varchar(50) NOT NULL,
  `jam_masuk` varchar(50) NOT NULL,
  `foto_masuk` varchar(50) NOT NULL,
  `tanggal_keluar` varchar(50) NOT NULL,
  `jam_keluar` varchar(50) NOT NULL,
  `foto_keluar` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `presensi`
--

INSERT INTO `presensi` (`id`, `id_pegawai`, `tanggal_masuk`, `jam_masuk`, `foto_masuk`, `tanggal_keluar`, `jam_keluar`, `foto_keluar`) VALUES
(2, 3, '2025-01-09', '18:30:43', 'kojet_masuk_2025-01-09.png', '', '', ''),
(3, 3, '2025-01-10', '09:49:38', 'kojet_masuk_2025-01-10.png', '2025-01-10', '09:50:50', 'kojet_keluar_2025-01-10.png'),
(5, 4, '2025-01-10', '09:55:01', 'anong_masuk_2025-01-10.png', '2025-01-10', '09:55:05', 'anong_keluar_2025-01-10.png'),
(6, 8, '2025-01-10', '10:14:19', 'alvigi_masuk_2025-01-10.png', '', '', ''),
(7, 3, '2025-01-11', '12:56:20', 'kojet_masuk_2025-01-11.png', '', '', ''),
(8, 8, '2025-01-11', '13:50:18', 'alvigi_masuk_2025-01-11.png', '', '', ''),
(9, 3, '2025-01-14', '16:26:36', 'kojet_masuk_2025-01-14.png', '2025-01-14', '16:27:06', 'kojet_keluar_2025-01-14.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `id_pegawai`, `username`, `password`, `status`, `role`) VALUES
(1, 1, 'admin', '123', 'Aktif', 'admin'),
(2, 2, 'pegawai1', '123', 'Aktif', 'pegawai'),
(3, 3, 'kojet', '123', 'Aktif', 'pegawai'),
(4, 4, 'anong', '123', 'Aktif', 'pegawai'),
(5, 5, 'gilang', '123', 'Aktif', 'pegawai'),
(7, 7, 'sandi', '123', 'Nonaktif', 'pegawai'),
(8, 8, 'alvigi', '123', 'Aktif', 'pegawai');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
