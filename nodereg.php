<?php
include "koneksi.php";
include "check.php";

$pool = mysqli_query($connectdb, "select id,name from ng_pool where name!='suspend' and nodeid='0'");
$city = mysqli_query($connectdb, "select id,kota from ng_kota");

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

    <h2>Node Regristration Form</h2>

    <form action="#" method=post >
      <span class="section">Node information</span>

      <label for="nodename">Node Name <span class="required">*</span></label>
      <input id="nodename" name="nodename" placeholder="Input Node Name" required="required" type="text">
                        
      <label for="address">Node IP Address <span class="required">*</span></label>
      <input id="address" name="address" placeholder="Input Node Ip Address" required="required" type="text">
                 
      <label for="kota">Kota <span class="required">*</span></label>
      <select id="kota" type="option" name="kota" required>
        <option value=""></option>
          <?php while ($idkota = mysqli_fetch_row($city)){?>
              <option value=<?php echo $idkota[0];?>><?php echo $idkota[1]?></option>
          <?php } ?>
      </select>
                     
      <label for="secret">Secret <span class="required">*</span></label>
      <input id="secret" name="secret" placeholder="Input Node Secret" required="required" type="text">
          
      <label for="type">Type </label>
      <input id="type" name="type" required="required" placeholder="Mikrotik" type="text" >
                   
      <label for="password">Port </label>
      <input id="port" type="text" name="port" class="form-control col-md-7 col-xs-12" required="required" placeholder="3799">
                      
      <label for="pool">Pool <span class="required">*</span></label>
      <select id="pool" type="option" name="pool" required>
        <option value=""></option>
        <?php while ($xpool = mysqli_fetch_row($pool)){?>
        
        <option value=<?php echo $xpool[0];?>><?php echo $xpool[1]; ?></option>
        
        <?php } ?>
      </select>
                   
      <button type="reset">Cancel</button>
      <button id="send" type="submit">Submit</button>
      </form>
</body>
</html>

<?php

 if($_SERVER["REQUEST_METHOD"] == "POST") {
 
	  $nodename = $_POST['nodename'];
      $address = $_POST['address']; 
      $kota = $_POST['kota']; 
      $secret = $_POST['secret']; 
      $type = $_POST['type']; 
      $port = $_POST['port']; 
      $pool = $_POST['pool']; 

      $ng_node = "INSERT INTO ng_node (node,address,kota,secret,type,port,pool) values (\"$nodename\" ,\"$address\",\"$kota\",\"$secret\",\"$type\",\"$port\",\"$pool\")" ; 
	  mysqli_query ($connectdb, $ng_node );
	  
	  $nas = "INSERT INTO nas (nasname,shortname,secret,type,ports) values (\"$address\" ,\"$nodename\",\"$secret\",\"$type\",\"$port\")" ;
	  mysqli_query ($connectdb, $nas );
	  	  
	  sleep(5);
	  
	  $checknode = mysqli_query($connectdb, "select nodeid from ng_node where node =\"$nodename\" and address =\"$address\"");
	  $nodes = mysqli_fetch_row($checknode);
	  
	  $nodenya = $nodes[0];
	  $pol = "UPDATE ng_pool SET nodeid= \"$nodenya\" WHERE id = \"$pool\"";
      echo $pol.'<br>';
	  mysqli_query($connectdb, $pol);
   }

?>
