<?php
include "koneksi.php";
include "check.php";

          
$chpoolid = $_GET['id'];
$paketlist = mysqli_query($connectdb, "SELECT kd_prod, paket FROM ng_paket");
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

    <h2>Assignment Paket Form</h2>

    <form action="#" method=post>
      <span>Assignment Paket</span>

      <label for="paket">Paket<span class="required">*</span></label>
      <select type="option" name="kd_prod" required>
        <option value=''>Pilih</option>

        <?php 
            while ($dtpaket = mysqli_fetch_assoc($paketlist)){
                echo "<option value=".$dtpaket['kd_prod'].">".$dtpaket['paket']."</option>";
            }
        ?>  
      
      </select>
                   
      <button type="reset">Cancel</button>
      <button id="send" type="submit">Submit</button>

      </form>
</body>
</html>
               
      <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
          $kd_prod = $_POST['kd_prod'];

          $updatepaket = mysqli_query($connectdb, "UPDATE ng_childpool SET kd_prod = \"$kd_prod\" WHERE id = \"$chpoolid\"");

          echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
	       }
      ?>
        