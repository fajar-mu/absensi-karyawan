<!-- validasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION["validasi"])) { ?>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "center",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "warning",
            title: "<?= $_SESSION['validasi']; ?>"
        });
    </script>
    <?php unset($_SESSION['validasi']); ?>
<?php } ?>
<!-- validasi berhasil -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION["berhasil"])) { ?>
    <script>
        const berhasil = Swal.mixin({
            toast: true,
            position: "center",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        berhasil.fire({
            icon: "success",
            title: "<?= $_SESSION['berhasil']; ?>"
        });
    </script>
    <?php unset($_SESSION['berhasil']); ?>
<?php } ?>
<!-- gagal -->
<?php if (isset($_SESSION["gagal"])) { ?>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "<?= $_SESSION['gagal']; ?>",
        });
    </script>
    <?php unset($_SESSION['gagal']); ?>
<?php } ?>

<!-- konfirmasi hapus -->
<script>
    $('.btn-delete').on('click', function() {
        var getLink = $(this).attr('href');
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: "Data tidak akan bisa kembali",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal",
            backdrop: true,

        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = getLink
            }
        });
        return false;
    });
</script>