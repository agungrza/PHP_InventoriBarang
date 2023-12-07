<?php
    session_start();

    if( !isset($_SESSION["login"]) ){
        header("Location: auth/login.php");
        exit;
    }

    if($_SESSION['jabatan'] != "admin" && $_SESSION['jabatan'] != "pimpinan"){
        header("Location: ../index.php");
        exit;
    }

    include '../db.php';

    require_once '../../dompdf/autoload.inc.php';

    $barangbadstock = view("SELECT * FROM barangbadstock 
    INNER JOIN users ON barangbadstock.user_id = users.idUser
    INNER JOIN supplier ON barangbadstock.user_id = supplier.idSupplier
    INNER JOIN gudang ON barangbadstock.user_id = gudang.idBarang");

    if(isset($_POST["submit"])){
        cetakBarangBadstock($_POST);
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../img/icon_FAS.png"/>
    <title>Inventori Barang | Laporan Barang Badstock</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
</head>

<body id="page-top">
<div id="wrapper">
    <ul class="navbar-nav merah sidebar sidebar-dark accordion" id="accordionSidebar">
        <img src="../../img/logo2_FAS_white.png" alt="Logo" class="tinggi">     
        <li class="nav-item">
            <a class="nav-link" href="../">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <?php if($_SESSION["jabatan"] == "admin" || $_SESSION["jabatan"] == "pegawai"){ ?>
        <div class="sidebar-heading">
            Pilih Menu
        </div>

        <li class="nav-item">
            <a class="nav-link" href="../data-pengguna/">
                <i class="fas fa-fw fa-table"></i>
                <span>Data Pengguna</span></a>
        </li>
                
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Data Master</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Data Master</h6>
                    <a class="collapse-item" href="../data-barang-gudang/">Data Barang Gudang</a>
                    <a class="collapse-item" href="../data-supplier/">Data Supplier</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Data Transaksi</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Data Transaksi</h6>
                    <a class="collapse-item" href="../data-barang-masuk/">Data Barang Masuk</a>
                    <a class="collapse-item" href="../data-barang-keluar/">Data Barang Keluar</a>
                    <a class="collapse-item" href="../data-barang-badstock/">Data Barang Badstock</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider">
        <?php } ?>

        <div class="sidebar-heading">
            Laporan
        </div>

        <li class="nav-item active">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Laporan</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Menu Laporan</h6>
                    <a class="collapse-item" href="laporan-supplier.php">Supplier</a>
                    <a class="collapse-item" href="laporan-barang-gudang.php">Barang Gudang</a>
                    <a class="collapse-item" href="laporan-barang-masuk.php">Barang Masuk</a>
                    <a class="collapse-item" href="laporan-barang-keluar.php">Barang Keluar</a>
                    <a class="collapse-item" href="laporan-barang-badstock.php">Barang Badstock</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider d-none d-md-block">
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?= $_SESSION['nama'] ?>
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <a class="dropdown-item" href="../auth/logout.php">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
        <!-- MAIN CONTENT -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-dark">Laporan Barang Badstock</h6>
            </div>
            <div class="card-body">
            <form action="" method="POST" class="d-flex justify-content-between">
                <div>
                    <label for="tglawal">Tanggal Awal :</label>
                    <input id="tglawal" type="date" name="tglawal">
                    <label for="tglakhir">Tanggal Akhir :</label>
                    <input id="tglakhir" type="date" name="tglakhir">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">
                    Cetak Laporan <i class="fas fa-print"></i>
                </button>
            </form>
            <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Kode/Nama Barang</th>
                                <th>Nama Supplier</th>
                                <th>Jumlah Badstock</th>
                                <th>Catatan</th>
                                <th>Dibuat Oleh</th>
                                <th>Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach($barangbadstock as $bds){ ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $bds['kodeTransaksi'] ?></td>
                                <td><?= $bds['kodeBarang'] ?> - <?= $bds['namaBarang'] ?></td>
                                <td><?= $bds['namaSupplier'] ?></td>
                                <td><?= $bds['jumlah'] ?> Unit</td>
                                <td><?= $bds['catatan'] ?></td>
                                <td><?= $bds['nama'] ?> - <span class="text-uppercase"><?= $bds['jabatan'] ?></span></td>
                                <td><?= date('d-M-Y', strtotime($bds['tanggal'])) ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END CONTENT -->
        </div>
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>INVENTORI BARANG PT FUTURE AGUNG SENTOSA</span>
            </div>
        </div>
    </footer>
    </div>
</div>


    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/chart-area-demo.js"></script>
    <script src="../../js/demo/chart-pie-demo.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/datatables-demo.js"></script>

</body>

</html>