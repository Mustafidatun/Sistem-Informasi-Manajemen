<?php
include "koneksi.php";
include "check.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
  <?php 
      if($_SESSION['level'] == 0){
        include 'sidemenu_supermanager.html';
      }else if($_SESSION['level'] == 1){
        include 'sidemenu_manager.php';
      }else if($_SESSION['level'] == 2){
        include 'sidemenu_submanager.php';
      }else if($_SESSION['level'] == 10){
        include 'sidemenu_finance.php';
      }else if($_SESSION['level'] == 11){
        include 'sidemenu_purchasing.php';
      }else if($_SESSION['level'] == ""){
        include 'page_404.html'; 
      }
    ?>  

    <h2>Paket Regristration Form</h2>

    <form action="#" method=post>
      <span>Paket Information</span>

      <label for="paketname">Paket Name<span class="required">*</span></label>
      <input id="paketname" name="paketname" placeholder="Input your Paket Name" required="required" type="text">
                      
      <label for="kd_prod">Kode Product<span class="required">*</span></label>
      <input id="kd_prod" name="kd_prod" placeholder="Input your Kode Product" required="required" type="text" >
                  
      <button type="reset">Cancel</button>
      <button id="send" type="submit">Submit</button>
    </form>
</body>
</html>

      <?php
      if($_SERVER["REQUEST_METHOD"] == "POST") {
 
        $paket = $_POST['paketname'];
        $kd_prod = $_POST['kd_prod'];

      	$ng_paketcheck = mysqli_query($connectdb, "select paket, kd_prod from ng_paket where paket=\"$paket\" or kd_prod =\"$kd_prod\"");

	if(mysqli_fetch_row($ng_paketcheck) == NULL ){
	
            $ng_paket = mysqli_query($connectdb, "INSERT INTO ng_paket (paket,kd_prod) VALUES (\"$paket\" ,\"$kd_prod\")") ; 

	 }else{
	     echo '<script language="javascript">alert("Paket '. $paket .' and Kode Product '. $kd_prod .' is registered")</script>';
        }
       }
       ?>
        