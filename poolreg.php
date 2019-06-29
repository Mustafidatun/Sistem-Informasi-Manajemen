<?php
include "koneksi.php";
include "check.php";

$ng_poolname = mysqli_query($connectdb, "SELECT count(*) FROM ng_pool WHERE name like '%pool%'");
$get_poolname = mysqli_fetch_row($ng_poolname);
$poolname = 'pool'.($get_poolname[0] + 1);
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

    <form action="#" method=post>
      <span>Pool Info</span>
        <label for="poolname">Pool Name<span class="required">*</span></label>
        <input id="poolname" name="poolname" value=<?php echo $poolname ?> type="text" readonly>
         
        <label for="prefix">Prefix <span class="required">*</span></label>
        <input id="prefix" name="prefix" placeholder="Input your prefix" required="required" type="text" >
   
        <button type="reset">Cancel</button>
        <button id="send" type="submit">Submit</button>
      </form>
                 
</body>
</html>

      <?php
      if($_SERVER["REQUEST_METHOD"] == "POST") {
 
        $pool = $_POST['poolname'];
        $prefix = $_POST['prefix']; 
        $netaddress = 24;
    
        
        function cidr2NetmaskAddr($cidr) {
          $ta = substr($cidr, strpos($cidr, '/') + 1) * 1;
          $netmask = str_split(str_pad(str_pad('', $ta, '1'), 32, '0'), 8);
          foreach ($netmask as &$element) $element = bindec($element);
          return join('.', $netmask);
        }
        
        $ip_count = 1 << (32 - $netaddress);
        
        $mask = cidr2NetmaskAddr($prefix.'/'.$netaddress);
        
        $ips = ip2long($prefix);
        $addressmask = ip2long($mask);
        $ipa = ((~$addressmask) & $ips) ;
        $network = long2ip(($ips ^ $ipa)).'/'.$netaddress;

	$ng_poolcheck = mysqli_query($connectdb, "select name, prefix from ng_pool where name=\"$pool\" or prefix =\"$network\"");

	if(mysqli_fetch_row($ng_poolcheck) == NULL ){
	
            $ng_pool = mysqli_query($connectdb, "INSERT INTO ng_pool (name,prefix) VALUES (\"$pool\" ,\"$network\")") ; 

            $ng_poolid = mysqli_query($connectdb, "SELECT id FROM ng_pool WHERE name=\"$pool\" AND prefix =\"$network\"");
            $get_poolid = mysqli_fetch_row($ng_poolid);
            $poolid = $get_poolid[0];

            $no_childpool = 1;
            for ($i = 0; $i < $ip_count; $i++) {
          	$ipaddr_start = long2ip(($ips ^ $ipa) + $i);
          	$i = $i + 15;
          	$ipaddr_end = long2ip(($ips ^ $ipa) + $i);
	  	$poolname = $pool.'_'.$no_childpool++;
	  	$available = 16;
         	$ng_childpool = mysqli_query($connectdb, "INSERT INTO ng_childpool (poolname,poolid,start_address,end_address,available) VALUES (\"$poolname\" ,\"$poolid\" ,\"$ipaddr_start\" ,\"$ipaddr_end\" ,\"$available\")"); ;
            }

	}else{
	     echo '<script language="javascript">alert("Prefix '. $network .' is registered")</script>';
        }
       }
       ?>
        