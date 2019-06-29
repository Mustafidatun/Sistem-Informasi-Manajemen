<?php
$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password=""; // Mysql password 
$db_name="si_administrasi"; // Database name

 
$connectdb= mysqli_connect($host, $username, $password, $db_name);

  if(!$connectdb){
    die ("Koneksi dengan database gagal: ".mysqli_connect_errno().
    " - ".mysqli_connect_error());
  }
 ?>
