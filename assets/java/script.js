const allSideMenu = document.querySelectorAll("#sidebar .side-menu.top li a");

allSideMenu.forEach((item) => {
  const li = item.parentElement;

  item.addEventListener("click", function () {
    allSideMenu.forEach((i) => {
      i.parentElement.classList.remove("active");
    });
    li.classList.add("active");
  });
});

document
  .querySelectorAll("#sidebar .side-menu li.has-submenu > a")
  .forEach((menu) => {
    menu.addEventListener("click", function (e) {
      e.preventDefault();
      const parent = this.parentElement;
      const isActive = parent.classList.contains("active");

      // Tutup semua submenu aktif lainnya
      document
        .querySelectorAll("#sidebar .side-menu li.has-submenu")
        .forEach((item) => {
          item.classList.remove("active");
        });

      // Tampilkan sub-menu hanya jika tidak aktif
      if (!isActive) {
        parent.classList.add("active");
      }
    });
  });

// TOGGLE sidebar
const menuBar = document.querySelector("#content nav .bx.bx-menu");
const sidebar = document.getElementById("sidebar");

menuBar.addEventListener("click", function () {
  sidebar.classList.toggle("hide");
});

function updateTime() {
  const now = new Date();

  // Ambil waktu dan tanggal saat ini
  const tanggal = now.getDate();
  const bulan = now.getMonth() + 1; // Bulan dimulai dari 0
  const tahun = now.getFullYear();
  const jam = now.getHours();
  const menit = now.getMinutes();
  const detik = now.getSeconds();

  // Format angka agar selalu dua digit
  const formatNumber = (num) => (num < 10 ? "0" + num : num);

  // Update elemen dengan waktu dan tanggal
  document.getElementById("tanggal").textContent = formatNumber(tanggal);
  document.getElementById("bulan").textContent = formatNumber(bulan);
  document.getElementById("tahun").textContent = tahun;
  document.getElementById("jam").textContent = formatNumber(jam);
  document.getElementById("menit").textContent = formatNumber(menit);
  document.getElementById("detik").textContent = formatNumber(detik);
}

// Jalankan fungsi setiap detik
setInterval(updateTime, 1000);

// Panggil sekali di awal agar langsung tampil tanpa menunggu satu detik pertama
updateTime();
