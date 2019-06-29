<?php
header("Content-Type: application/json; charset=UTF-8");
include "koneksi.php";

// Deklarasi variable keyword.
$type = $_GET["query"];
$vendor = $_GET["vendor"];

// Query ke database.
$query  = mysqli_query($connectdb, "SELECT * FROM ng_equipmaster WHERE vendorid = '$vendor' AND type LIKE '%$type%' ORDER BY type DESC");

// Cek apakah ada yang cocok atau tidak.
    while($data= mysqli_fetch_assoc($query)){
        $output['suggestions'][] = [
            'value' => $data['type'],
            'type'  => $data['type'],
            'merk'  => $data['merk'],
            'equipmasterid'  => $data['id'],
            'price'  => $data['price']
        ];
    }


    // Encode ke JSON.
    echo json_encode($output);
?>