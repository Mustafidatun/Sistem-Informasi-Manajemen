<?php
include "koneksi.php";
include "check.php";

$ng_vendorlist = mysqli_query($connectdb, "SELECT id, vendor FROM ng_vendor");
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

    <h2>Master Equipment Form</h2>

    <form action="#" method=post >
      <span>Master Equipment</span>

      <label for="merk">Merk <span class="required">*</span></label>
      <input id="merk" name="merk" placeholder="Input Merk Equipment" required="required" type="text">  
                      
      <label for="type">Type <span class="required">*</span></label>
      <input id="type" name="type" placeholder="Input Type Equipment" required="required" type="text">
        
      <label for="price">Price <span class="required">*</span></label>
      <input id="price" name="price" placeholder="Input Price ex.1000000" required="required" type="text">
           
      <label for="vol">Vol <span class="required">*</span></label>
      <label>
        <input type="radio" class="flat" checked="checked" name="vol" value="pcs"> Pcs
      </label>
      <label>
        <input type="radio" class="flat" name="vol" value="unit"> Unit
      </label>
      <label>
        <input type="radio" class="flat" name="vol" value="box"> Box
      </label>
      <label>
        <input type="radio" class="flat" name="vol" value="ls"> Ls
      </label>
             
      <label for="vendor">Vendor Name <span class="required">*</span></label>
      <select id="vendor" type="option" name="vendor" required>
        <option value=''>Pilih</option>
        <?php 
          while ($dtvendor = mysqli_fetch_array($ng_vendorlist)){
              
              echo "<option value=".$dtvendor['id'].">".$dtvendor['vendor']."</option>";
          }
        ?>   
      </select>
                  
      <button type="submit">Cancel</button>
      <button id="send" type="submit">Submit</button>
    </form>
</body>
</html>

<?php

 if($_SERVER["REQUEST_METHOD"] == "POST") {
 
	  $merk = $_POST['merk'];
    $type = $_POST['type']; 
    $price = $_POST['price']; 
    $vendor = $_POST['vendor']; 
    $vol = $_POST['vol']; 

    $ng_brgcheck = mysqli_query($connectdb, "SELECT type, vendorid FROM ng_equipmaster WHERE type =\"$type\" AND vendorid =\"$vendor\"");

    if(mysqli_fetch_row($ng_brgcheck) == NULL ){
      
      $ng_equipmaster = mysqli_query($connectdb, "INSERT INTO ng_equipmaster (type, merk, price, vol, vendorid) VALUES (\"$type\" ,\"$merk\" ,\"$price\" ,\"$vol\" ,\"$vendor\")") ; 

    }else{
      echo '<script language="javascript">alert("Product is registered")</script>';
    }

    echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
  }

?>