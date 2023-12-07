<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "inventori");

function view($query){
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    
    return $rows;
}

function createPengguna($data){
    global $conn;

    $nip = $data["nip"];
    $nama = htmlspecialchars($data["nama"]);
    $jabatan = $data["jabatan"];
    $password = mysqli_real_escape_string($conn, $data["password"]);
    
    $result = mysqli_query($conn, "SELECT nip FROM users WHERE nip = '$nip'");
    if(mysqli_fetch_assoc($result)){
        echo "<script>
                alert('NIP telah ada!');
            </script>";
        return false;
    }
    $password = password_hash($password, PASSWORD_DEFAULT);

    // query insert
    $query = "INSERT INTO users VALUES ('', '$nip', '$nama', '$jabatan', '$password')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function updatePengguna($data){
    global $conn;

    $id = $data["idUser"];
    $nip = $data["nip"];
    $nama = htmlspecialchars($data["nama"]);
    $jabatan = $data["jabatan"];
    $password = mysqli_real_escape_string($conn, $data["password"]);
    
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET nip='$nip', nama='$nama', jabatan='$jabatan', password='$password' WHERE idUser=$id";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function deletePengguna($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM users WHERE idUser = $id");

    return mysqli_affected_rows($conn);
}

function createBarangGudang($data){
    global $conn;

    $kodeBarang = $data["kodeBarang"];
    $namaBarang = htmlspecialchars($data["namaBarang"]);
    $jenisBarang = $data["jenisBarang"];
    $stok = $data["stok"];
    $user_id = $data["user_id"];

    $query = "INSERT INTO gudang VALUES ('', '$kodeBarang', '$namaBarang', '$jenisBarang', '$stok', '$user_id')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function updateBarangGudang($data){
    global $conn;

    $id = $data["idBarang"];
    $kodeBarang = $data["kodeBarang"];
    $namaBarang = htmlspecialchars($data["namaBarang"]);
    $jenisBarang = $data["jenisBarang"];
    $stok = $data["stok"];
    $user_id = $data["user_id"];

    $query = "UPDATE gudang SET kodeBarang='$kodeBarang', namaBarang='$namaBarang', jenisBarang='$jenisBarang', stok='$stok', user_id='$user_id' WHERE idBarang=$id";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function deleteBarangGudang($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM gudang WHERE idBarang = $id");

    return mysqli_affected_rows($conn);
}

function createSupplier($data){
    global $conn;

    $kodeSupplier = $data["kodeSupplier"];
    $namaSupplier = htmlspecialchars($data["namaSupplier"]);
    $nohp = $data["nohp"];

    $query = "INSERT INTO supplier VALUES ('', '$kodeSupplier', '$namaSupplier', '$nohp')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function updateSupplier($data){
    global $conn;

    $id = $data["idSupplier"];
    $kodeSupplier = $data["kodeSupplier"];
    $namaSupplier = htmlspecialchars($data["namaSupplier"]);
    $nohp = $data["nohp"];

    $query = "UPDATE supplier SET kodeSupplier='$kodeSupplier', namaSupplier='$namaSupplier', nohp='$nohp' WHERE idSupplier=$id";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function deleteSupplier($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM supplier WHERE idSupplier = $id");

    return mysqli_affected_rows($conn);
}

function createBarangMasuk($data){
    global $conn;

    $kodeTransaksi = $data["kodeTransaksi"];
    $jumlah = $data["jumlah"];
    $tanggal = $data["tanggal"];
    $user_id = $data["user_id"];
    $gudang_id = $data["gudang_id"];
    $supplier_id = $data["supplier_id"];

    if($jumlah < 1){
        echo "
            <script>
                alert('Jumlah Barang Masuk minimal 1 unit');
                document.location.href = 'create.php';
            </script>
        ";
        exit;
    }

    $query = "INSERT INTO barangmasuk VALUES ('', '$kodeTransaksi', '$jumlah', '$tanggal', '$user_id', '$gudang_id', '$supplier_id')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function createBarangKeluar($data){
    global $conn;

    $kodeTransaksi = $data["kodeTransaksi"];
    $jumlah = $data["jumlah"];
    $tanggal = $data["tanggal"];
    $user_id = $data["user_id"];
    $gudang_id = $data["gudang_id"];

    if($jumlah < 1){
        echo "
            <script>
                alert('Jumlah Barang Keluar minimal 1 unit');
                document.location.href = 'create.php';
            </script>
        ";
        exit;
    }

    $querygudang = mysqli_query($conn, "SELECT stok FROM gudang WHERE idBarang = $gudang_id");
    $gudang = mysqli_fetch_assoc($querygudang);
    if( $gudang['stok'] < $jumlah){
        echo "
            <script>
                alert('Jumlah Barang Keluar tidak boleh melebihi Jumlah Stok');
                document.location.href = 'create.php';
            </script>
        ";
        exit;
    }

    $query = "INSERT INTO barangkeluar VALUES ('', '$kodeTransaksi', '$jumlah', '$tanggal', '$user_id', '$gudang_id')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function createBarangBadstock($data){
    global $conn;

    $kodeTransaksi = $data["kodeTransaksi"];
    $jumlah = $data["jumlah"];
    $tanggal = $data["tanggal"];
    $catatan = htmlspecialchars($data["catatan"]);
    $user_id = $data["user_id"];
    $gudang_id = $data["gudang_id"];
    $supplier_id = $data["supplier_id"];

    if($jumlah < 1){
        echo "
            <script>
                alert('Jumlah Barang Badstock minimal 1 unit');
                document.location.href = 'create.php';
            </script>
        ";
        exit;
    }

    $querygudang = mysqli_query($conn, "SELECT stok FROM gudang WHERE idBarang = $gudang_id");
    $gudang = mysqli_fetch_assoc($querygudang);
    if( $gudang['stok'] < $jumlah){
        echo "
            <script>
                alert('Jumlah Barang Badstock tidak boleh melebihi Jumlah Stok');
                document.location.href = 'create.php';
            </script>
        ";
        exit;
    }

    $query = "INSERT INTO barangbadstock VALUES ('', '$kodeTransaksi', '$jumlah', '$tanggal', '$catatan', '$user_id', '$gudang_id', '$supplier_id')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

// include autoloader
use Dompdf\Dompdf;


function cetakSupplier(){
    $html = '
    <style>
    h3{
    font-family: Arial, Helvetica, sans-serif;
    }
    #customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    #customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
    }
    </style>

    <h3>Laporan Supplier</h3>

    <table id="customers">
    <tr>
        <th>No</th>
        <th>Kode Supplier</th>
        <th>Nama Supplier</th>
        <th>Telepon</th>
    </tr>
        ';
            $no = 1; 
            $supplier = view("SELECT * FROM supplier");
        foreach($supplier as $spl){
        $html .='<tr>
            <td>'.$no++.'</td>
            <td>'.$spl["kodeSupplier"].'</td>
            <td>'.$spl["namaSupplier"].'</td>
            <td>'.$spl["nohp"].'</td>
        </tr>
        ';}
        $html .='</table>';

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');
    
    // Render the HTML as PDF
    $dompdf->render();
    
    // Output the generated PDF to Browser
    $dompdf->stream('Laporan_Supplier.pdf');
}

function cetakBarangGudang(){
    $html = '
    <style>
    h3{
    font-family: Arial, Helvetica, sans-serif;
    }
    #customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    #customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
    }
    </style>

    <h3>Laporan Barang Gudang</h3>

    <table id="customers">
    <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Jenis Barang</th>
        <th>Jumlah Stok</th>
        <th>Dibuat Oleh</th>
    </tr>
        ';
            $no = 1; 
            $gudang = view("SELECT * FROM gudang INNER JOIN users ON gudang.user_id = users.idUser");
        foreach($gudang as $gdg){
        $html .='<tr>
            <td>'.$no++.'</td>
            <td>'.$gdg["kodeBarang"].'</td>
            <td>'.$gdg["namaBarang"].'</td>
            <td>'.$gdg["jenisBarang"].'</td>
            <td>'.$gdg["stok"].'</td>
            <td>'.$gdg["nama"].' - <span class="text-uppercase">'.$gdg['jabatan'].'</span></td>
        </tr>
        ';}
        $html .='</table>';

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');
    
    // Render the HTML as PDF
    $dompdf->render();
    
    // Output the generated PDF to Browser
    $dompdf->stream('Laporan_Barang_Gudang.pdf');
}

function cetakBarangMasuk($data){
    $tglawal = date($data['tglawal']);
    $tglakhir = date($data['tglakhir']);
    $html = '
    <style>
    h3{
    font-family: Arial, Helvetica, sans-serif;
    }
    #customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    #customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
    }
    </style>

    <h3>Laporan Barang Masuk</h3>

    <table id="customers">
    <tr>
        <th>No</th>
        <th>Kode Transaksi</th>
        <th>Kode/Nama Barang</th>
        <th>Nama Supplier</th>
        <th>Jumlah Masuk</th>
        <th>Dibuat Oleh</th>
        <th>Tanggal Dibuat</th>
    </tr>
        ';
        $no = 1; 
        if(strlen($tglawal) == 10 && strlen($tglakhir) == 10){
            if(preg_replace("/[^0-9]/", "", $tglawal) <= preg_replace("/[^0-9]/", "", $tglakhir)){
                $barangmasuk = view("SELECT * FROM barangmasuk
                INNER JOIN users ON barangmasuk.user_id = users.idUser
                INNER JOIN gudang ON barangmasuk.user_id = gudang.idBarang
                INNER JOIN supplier ON barangmasuk.user_id = supplier.idSupplier
                WHERE date(tanggal) BETWEEN '$tglawal' AND '$tglakhir'");
            }
            else{
                echo "
                    <script>
                        alert('Tanggal Awal tidak boleh diatas Tanggal Akhir!');
                        document.location.href = 'laporan-barang-masuk.php';
                    </script>
                ";
                echo mysqli_error($conn);
            }
        }
        elseif(strlen($tglawal) == 0 && strlen($tglakhir) == 0){
            $barangmasuk = view("SELECT * FROM barangmasuk
            INNER JOIN users ON barangmasuk.user_id = users.idUser
            INNER JOIN gudang ON barangmasuk.user_id = gudang.idBarang
            INNER JOIN supplier ON barangmasuk.user_id = supplier.idSupplier");
        }
        else{
            echo "
                    <script>
                        alert('Tanggal Awal dan Akhir keduanya harus diisi!');
                        document.location.href = 'laporan-barang-masuk.php';
                    </script>
                ";
            echo mysqli_error($conn);
        }
        foreach($barangmasuk as $bm){
        $html .='<tr>
            <td>'.$no++.'</td>
            <td>'.$bm["kodeTransaksi"].'</td>
            <td>'.$bm["kodeBarang"].' - '.$bm["namaBarang"].'</td>
            <td>'.$bm["namaSupplier"].'</td>
            <td>'.$bm["jumlah"].'</td>
            <td>'.$bm["nama"].' - <span class="text-uppercase">'.$bm['jabatan'].'</span></td>
            <td>'.date('d-M-Y', strtotime($bm["tanggal"])).'</td>
        </tr>
        ';}
        $html .='</table>';

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');
    
    // Render the HTML as PDF
    $dompdf->render();
    
    // Output the generated PDF to Browser
    $dompdf->stream('Laporan_Barang_Masuk.pdf');
}

function cetakBarangKeluar($data){
    $tglawal = date($data['tglawal']);
    $tglakhir = date($data['tglakhir']);
    $html = '
    <style>
    h3{
    font-family: Arial, Helvetica, sans-serif;
    }
    #customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    #customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
    }
    </style>

    <h3>Laporan Barang Keluar</h3>

    <table id="customers">
    <tr>
        <th>No</th>
        <th>Kode Transaksi</th>
        <th>Kode/Nama Barang</th>
        <th>Jumlah Keluar</th>
        <th>Dibuat Oleh</th>
        <th>Tanggal Dibuat</th>
    </tr>
        ';
            $no = 1;
            if(strlen($tglawal) == 10 && strlen($tglakhir) == 10){
                if(preg_replace("/[^0-9]/", "", $tglawal) <= preg_replace("/[^0-9]/", "", $tglakhir)){
                    $barangkeluar = view("SELECT * FROM barangkeluar 
                    INNER JOIN users ON barangkeluar.user_id = users.idUser
                    INNER JOIN gudang ON barangkeluar.user_id = gudang.idBarang
                    WHERE date(tanggal) BETWEEN '$tglawal' AND '$tglakhir'");
                }
                else{
                    echo "
                        <script>
                            alert('Tanggal Awal tidak boleh diatas Tanggal Akhir!');
                            document.location.href = 'laporan-barang-keluar.php';
                        </script>
                    ";
                    echo mysqli_error($conn);
                }
            }
            elseif(strlen($tglawal) == 0 && strlen($tglakhir) == 0){
                $barangkeluar = view("SELECT * FROM barangkeluar 
                INNER JOIN users ON barangkeluar.user_id = users.idUser
                INNER JOIN gudang ON barangkeluar.user_id = gudang.idBarang");
            }
            else{
                echo "
                        <script>
                            alert('Tanggal Awal dan Akhir keduanya harus diisi!');
                            document.location.href = 'laporan-barang-keluar.php';
                        </script>
                    ";
                echo mysqli_error($conn);
            }
            
        foreach($barangkeluar as $bk){
        $html .='<tr>
            <td>'.$no++.'</td>
            <td>'.$bk["kodeTransaksi"].'</td>
            <td>'.$bk["kodeBarang"].' - '.$bk["namaBarang"].'</td>
            <td>'.$bk["jumlah"].'</td>
            <td>'.$bk["nama"].' - <span class="text-uppercase">'.$bk['jabatan'].'</span></td>
            <td>'.date('d-M-Y', strtotime($bk["tanggal"])).'</td>
        </tr>
        ';}
        $html .='</table>';

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');
    
    // Render the HTML as PDF
    $dompdf->render();
    
    // Output the generated PDF to Browser
    $dompdf->stream('Laporan_Barang_Keluar.pdf');
}

function cetakBarangBadstock($data){
    $tglawal = date($data['tglawal']);
    $tglakhir = date($data['tglakhir']);
    $html = '
    <style>
    h3{
    font-family: Arial, Helvetica, sans-serif;
    }
    #customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    #customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
    }
    </style>

    <h3>Laporan Barang Badstock</h3>

    <table id="customers">
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
        ';
            $no = 1;
            if(strlen($tglawal) == 10 && strlen($tglakhir) == 10){
                if(preg_replace("/[^0-9]/", "", $tglawal) <= preg_replace("/[^0-9]/", "", $tglakhir)){
                    $barangbadstock = view("SELECT * FROM barangbadstock 
                    INNER JOIN users ON barangbadstock.user_id = users.idUser
                    INNER JOIN supplier ON barangbadstock.user_id = supplier.idSupplier
                    INNER JOIN gudang ON barangbadstock.user_id = gudang.idBarang
                    WHERE date(tanggal) BETWEEN '$tglawal' AND '$tglakhir'");
                }
                else{
                    echo "
                        <script>
                            alert('Tanggal Awal tidak boleh diatas Tanggal Akhir!');
                            document.location.href = 'laporan-barang-badstock.php';
                        </script>
                    ";
                    echo mysqli_error($conn);
                }
            }
            elseif(strlen($tglawal) == 0 && strlen($tglakhir) == 0){
                $barangbadstock = view("SELECT * FROM barangbadstock 
                INNER JOIN users ON barangbadstock.user_id = users.idUser
                INNER JOIN supplier ON barangbadstock.user_id = supplier.idSupplier
                INNER JOIN gudang ON barangbadstock.user_id = gudang.idBarang");
            }
            else{
                echo "
                        <script>
                            alert('Tanggal Awal dan Akhir keduanya harus diisi!');
                            document.location.href = 'laporan-barang-badstock.php';
                        </script>
                    ";
                echo mysqli_error($conn);
            }

        foreach($barangbadstock as $bds){
        $html .='<tr>
            <td>'.$no++.'</td>
            <td>'.$bds["kodeTransaksi"].'</td>
            <td>'.$bds["kodeBarang"].' - '.$bds["namaBarang"].'</td>
            <td>'.$bds["namaSupplier"].'</td>
            <td>'.$bds["jumlah"].'</td>
            <td>'.$bds["catatan"].'</td>
            <td>'.$bds["nama"].' - <span class="text-uppercase">'.$bds['jabatan'].'</span></td>
            <td>'.date('d-M-Y', strtotime($bds["tanggal"])).'</td>
        </tr>
        ';}
        $html .='</table>';

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');
    
    // Render the HTML as PDF
    $dompdf->render();
    
    // Output the generated PDF to Browser
    $dompdf->stream('Laporan_Barang_Badstock.pdf');
}