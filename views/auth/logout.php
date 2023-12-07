<?php

    // logout untuk menghapus SESSION
    session_start();
    $_SESSION = [];
    session_unset();
    session_destroy();

    // logout untuk menghapus COOKIE
    setcookie("id", "", time() - 3600);
    setcookie("key", "", time() - 3600);

    // mengembalikan user ke halaman login
    header("Location: login.php");
    exit;
?>