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

    <h2>Vendor Regristration Form</h2>

    <form action="#" method=post>
      <span>Vendor Information</span>

      <label for="vendorname">Vendor Name<span class="required">*</span></label>
      <input id="vendorname" name="vendorname" placeholder="Input your Vendor Name" required="required" type="text">
                    
      <label for="alamat">Alamat<span class="required">*</span></label>
      <input id="alamat" name="alamat" placeholder="Input your Address" required="required" type="text" >
             
      <label for="no_telp">No. Telp<span class="required">*</span></label>
      <input id="no_telp" name="no_telp" placeholder="Input your Phone" required="required" type="text" >
                
      <label for="email">E-mail<span class="required">*</span></label>
      <input id="email" required="required" name="email" placeholder="Input your E-mail" type="email"></input>
            
      <button type="reset">Cancel</button>
      <button id="send" type="submit">Submit</button>
    </form>
</body>
</html>             
                  
      <?php
      if($_SERVER["REQUEST_METHOD"] == "POST") {
 
        $vendorname = $_POST['vendorname'];
        $alamat = $_POST['alamat'];
        $no_telp = $_POST['no_telp'];
        $email = $_POST['email'];

      	$ng_vendorcheck = mysqli_query($connectdb, "SELECT vendor FROM ng_vendor WHERE vendor=\"$vendorname\"");

        if(mysqli_fetch_row($ng_vendorcheck) == NULL ){
    
              $ng_vendor = mysqli_query($connectdb, "INSERT INTO ng_vendor (vendor, alamat, no_telp, email) VALUES (\"$vendorname\" ,\"$alamat\" ,\"$no_telp\" ,\"$email\")") ; 

        }else{
	        echo '<script language="javascript">alert("Vendor '. $vendorname .' is registered")</script>';
        }

        echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
       }
       ?>
        