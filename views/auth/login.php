<?php
    session_start();
    
    include '../db.php';

    if( isset($_SESSION["login"]) ){
        header("Location: ../index.php");
        exit;
    }

    if( isset($_POST["login"]) ){
        $nip = $_POST["nip"];
        $password = $_POST["password"];
        $result = mysqli_query($conn, "SELECT * FROM users WHERE nip = '$nip'");

        if( mysqli_num_rows($result) === 1 ){
            $row = mysqli_fetch_assoc($result);

            if( password_verify($password, $row["password"]) ){
                global $conn;
                $_SESSION["login"] = true;

                // Menampilkan data yang sedang login
                $myquery  = "SELECT idUser FROM users WHERE nip = '$nip'";
                $myresult = mysqli_query($conn, $myquery);
                //hasil dari fecth assoc adalah array
                $id = mysqli_fetch_assoc($myresult);
                $_SESSION['id'] = $id['idUser'];

                $myquery  = "SELECT nama FROM users WHERE nip = '$nip'";
                $myresult = mysqli_query($conn, $myquery);
                //hasil dari fecth assoc adalah array
                $nama = mysqli_fetch_assoc($myresult);
                $_SESSION['nama'] = $nama['nama'];

                $myquery2  = "SELECT jabatan FROM users WHERE nip = '$nip'";
                $myresult2 = mysqli_query($conn, $myquery2);
                //hasil dari fecth assoc adalah array
                $jabatan = mysqli_fetch_assoc($myresult2);
                $_SESSION['jabatan'] = $jabatan['jabatan'];

                header("Location: ../index.php");
                exit;
            }
            else{
                echo "
                    <script>
                        alert('NIP atau Password salah!');
                        document.location.href = 'login.php';
                    </script>
                ";
                exit;
            }
        }
        else{
            echo "
                <script>
                    alert('NIP atau Password salah!');
                    document.location.href = 'login.php';
                </script>
            ";
            exit;
        }
        $error = true;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../img/icon_FAS.png"/>
    <title>Selamat Datang !</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex flex-col items-center">
            <div class="w-[450px] h-[600px] p-6 flex flex-col rounded border shadow-lg mt-[10%]">
                <div class="grid justify-items-center">
                    <img src="../../img/logo_FAS_white.png" alt="Logo" class="w-[400px]">
                </div>
                <hr>
                <form method="POST" action="">

                    <div class="mt-8">
                        <label for="nip">NIP</label><br>

                        <input id="nip" type="text" class="w-[100%] h-[32px] rounded-sm border focus:outline-none focus:ring-1 focus:ring-black" name="nip" required autofocus>
                    </div>

                    <div class="mt-8">
                        <label for="password">Password</label><br>

                        <input id="password" type="password" class="w-[100%] h-[32px] rounded-sm border focus:outline-none focus:ring-1 focus:ring-black" name="password" required autocomplete="current-password">

                        <div class="flex items-end">
                            <input type="checkbox" onclick="myFunction()" class="w-[20px] h-[20px] mt-2">
                            <span class="pl-2">Lihat Password</span>
                        </div>
                    </div>

                    <button type="submit" name="login">
                        <div class="w-[80px] h-[28px] mt-8 text-center text-white bg-slate-800 hover:bg-black rounded-sm">Login</div>
                    </button>
                </form>
        </div>
    </div>


<script>
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>
</html>