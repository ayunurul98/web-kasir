<?php
    $dbname = "db_prakweb";
    $dbpass = "";
    $dbaddr = "localhost";
    $dbuser = "root";

    $conn = new mysqli($dbaddr, $dbuser, $dbpass, $dbname);
    if($conn->connect_error){
        die("Koneksi Ke Database Gagal".$conn->connect_error);
    }
    else{
        echo "Database terhubung";
    }
?>