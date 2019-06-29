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

    <form action="#" method=post >
      <span class="section">Pop information</span>

      <label for="apname">Ap Name <span class="required">*</span></label>
      <input id="apname" name="apname" placeholder="Input Ap Name" required="required" type="text">
          
      <label for="aplat">Ap Lat <span class="required">*</span></label>
      <input id="aplat" name="aplat" placeholder="Input Ap Lat" required="required" type="text">
                  
      <label for="aplong">Ap Long <span class="required">*</span></label>
      <input id="aplong" name="aplong" placeholder="Input Ap Long" required="required" type="text">
              
      <button type="reset">Cancel</button>
      <button id="send" type="submit">Submit</button>
    </form>
</body>
</html>

<?php

 if($_SERVER["REQUEST_METHOD"] == "POST") {
 
      $apname = $_POST['apname']; 
      $aplat = $_POST['aplat'];
      $aplong = $_POST['aplong'];

      $jmlid = mysqli_query($connectdb, "SELECT count(id) as jmlid FROM ng_pop");
      $dtjmlid  = mysqli_fetch_array($jmlid);
      $id = $dtjmlid['jmlid'] + 1;
      if($id < 10)
        $apid = '0'.$id.''.$apname;
      else 
        $apid = $id.''.$apname;

      $ng_popcheck = mysqli_query($connectdb, "SELECT aplat, aplong FROM ng_pop WHERE aplat =\"$aplat\" AND aplong =\"$aplong\"");

      if(mysqli_fetch_row($ng_popcheck) == NULL ){
        $ng_pop = mysqli_query($connectdb, "INSERT INTO ng_pop (apid, apname, aplat, aplong) VALUES (\"$apid\" , \"$apname\" , \"$aplat\" ,\"$aplong\")");
      }else{ 
        echo '<script language="javascript">alert("Ap Lat and ap long is registered")</script>';
      }
      
      echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
    }

?>