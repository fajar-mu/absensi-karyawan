<?php
$password = '123'; // Password yang akan di-hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Tampilkan hasil hash
echo "Hash baru: " . $hashed_password . "<br>";
?>
