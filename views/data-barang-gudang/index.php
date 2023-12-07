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

    $gudang = view("SELECT * FROM gudang INNER JOIN users ON gudang.user_id = users.idUser");
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
    <title>Inventori Barang | Data Barang Gudang</title>
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

        <div class="sidebar-heading">
            Pilih Menu
        </div>

        <li class="nav-item">
            <a class="nav-link" href="../data-pengguna/">
                <i class="fas fa-fw fa-table"></i>
                <span>Data Pengguna</span></a>
        </li>
                
        <li class="nav-item active">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Data Master</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Data Master</h6>
                    <a class="collapse-item" href="index.php">Data Barang Gudang</a>
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
        <?php if($_SESSION["jabatan"] == "admin" || $_SESSION["jabatan"] == "pimpinan"){ ?>
        <div class="sidebar-heading">
            Laporan
        </div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Laporan</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Menu Laporan</h6>
                    <a class="collapse-item" href="../laporan/laporan-supplier.php">Supplier</a>
                    <a class="collapse-item" href="../laporan/laporan-barang-gudang.php">Barang Gudang</a>
                    <a class="collapse-item" href="../laporan/laporan-barang-masuk.php">Barang Masuk</a>
                    <a class="collapse-item" href="../laporan/laporan-barang-keluar.php">Barang Keluar</a>
                    <a class="collapse-item" href="../laporan/laporan-barang-badstock.php">Barang Badstock</a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider d-none d-md-block">
        <?php } ?>
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
                <h6 class="m-0 font-weight-bold text-dark">Data Barang Gudang</h6>
            </div>
            <div class="card-body">
                <button class="btn btn-success"><a href="create.php" class="text-reset text-decoration-none">Buat Barang Gudang</a></button><hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jenis Barang</th>
                                <th>Jumlah Stok</th>
                                <th>Dibuat oleh</th>
                                <th class="width150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach($gudang as $gdg){ ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $gdg["kodeBarang"]; ?></td>
                                <td><?= $gdg["namaBarang"]; ?></td>
                                <td><?= $gdg["jenisBarang"]; ?></td>
                                <td><?= $gdg["stok"]; ?> Unit</td>
                                <td><?= $gdg["nama"]; ?></td>
                                <td class="d-flex">
                                    <a href="update.php?id=<?= $gdg["idBarang"]; ?>" class="btn btn-warning btn-sm mr10px">Ubah</a>
                                    <a href="delete.php?id=<?= $gdg['idBarang']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
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