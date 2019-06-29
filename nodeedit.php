<?php
include "koneksi.php";
include "check.php";

if (isset($_GET['id'])) {
    
    $nodeid = $_GET['id'];
    $nodelist = mysqli_query($connectdb, "SELECT ng_node.*, 
                                                ng_kota.kota, 
                                                ng_pool.name AS poolname 
                                            FROM ng_node
                                            INNER JOIN ng_kota ON ng_kota.id = ng_node.kota
                                            INNER JOIN ng_pool ON ng_pool.id = ng_node.pool
                                            WHERE ng_node.nodeid = \"$nodeid\"");
    $dtnodelist = mysqli_fetch_assoc($nodelist);
    
} else {
    
    header("location:nodelist.php");
  }
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
      <label for="nodename">Node Name <span class="required">*</span></label>
      <input type="hidden" id="oldnodename" name="oldnodename" value="<?php echo $dtnodelist['node']; ?>">
      <input id="nodename" name="nodename" placeholder="Input Node Name" required="required" type="text" value="<?php echo $dtnodelist['node']; ?>">

      <label for="address">Node IP Address <span class="required">*</span></label>
      <input type="hidden" id="oldaddress" name="oldaddress" value="<?php echo $dtnodelist['address']; ?>">
      <input id="address" name="address" placeholder="Input Node Ip Address" required="required" type="text" value="<?php echo $dtnodelist['address']; ?>">
         
      <label for="kota">Kota <span class="required">*</span></label>
      <input id="kota" name="kota" type="text" vaLue="<?php echo $dtnodelist['kota']; ?>" readonly>
       
      <label for="secret">Secret <span class="required">*</span></label>
      <input id="secret" name="secret" placeholder="Input Node Secret" required="required" type="text" value="<?php echo $dtnodelist['secret']; ?>">
                 
      <label for="type">Type </label>
      <input id="type" name="type" required="required" placeholder="Mikrotik" type="text" value="<?php echo $dtnodelist['type']; ?>">
       
      <label for="password">Port </label>
      <input id="port" type="text" name="port" required="required" placeholder="3799"  value="<?php echo $dtnodelist['port']; ?>">
        
      <label for="pool">Pool <span class="required">*</span></label>
      <input id="poolname" type="text" name="poolname" value="<?php echo $dtnodelist['poolname']; ?>" readonly>
               
      <button type="reset">Cancel</button>
      <button id="send" type="submit">Submit</button>
  </form>
</body>
</html>

<?php

 if($_SERVER["REQUEST_METHOD"] == "POST") {
 
      $nodename = $_POST['nodename'];
      $oldnodename = $_POST['oldnodename'];
      $address = $_POST['address'];
      $oldaddress = $_POST['oldaddress']; 
      $secret = $_POST['secret']; 
      $type = $_POST['type']; 
      $port = $_POST['port']; 

      $ng_node = mysqli_query ($connectdb, "UPDATE ng_node 
                                            SET node = \"$nodename\" ,
                                                address = \"$address\",
                                                secret = \"$secret\",
                                                type = \"$type\", 
                                                port = \"$port\"
                                            WHERE nodeid = \"$nodeid\""); 

      $getnasid = mysqli_query($connectdb, "SELECT id FROM nas WHERE nasname =\"$oldaddress\" AND shortname =\"$oldnodename\"");
      $nasid = mysqli_fetch_assoc($getnasid);
     
	  $nas = mysqli_query ($connectdb, "UPDATE nas 
                                        SET nasname = \"$address\",
                                            shortname = \"$nodename\",
                                            secret = \"$secret\",
                                            type =\"$type\",
                                            ports = \"$port\"
                                        WHERE id = ".$nasid['id'].""); 

        // echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META   
        header("location:nodelist.php");                                 
   }

?>
