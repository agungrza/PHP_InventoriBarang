<?php
    session_start();

    if( !isset($_SESSION["login"]) ){
        header("Location: ../auth/login.php");
        exit;
    }

    if($_SESSION['jabatan'] != "admin" && $_SESSION['jabatan'] != "pegawai"){
        header("Location: ../index.php");
        exit;
    }

    include '../db.php';

    $id = $_GET["id"];
    if(deleteBarangGudang($id) > 0){
        echo "
            <script>
                alert('Data berhasil dihapus!');
                document.location.href = 'index.php';
            </script>
        ";
    }
    else{
        echo "
            <script>
                alert('Data gagal dihapus!');
                document.location.href = 'index.php';
            </script>
        ";
        echo "<br>";
        echo mysqli_error($conn);
    }