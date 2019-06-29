<?php 
include "koneksi.php";
session_start();
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
      	}else if($_SESSION['level'] == 5){
        	include 'sidemenu_fieldtec.php';
      	}else if($_SESSION['level'] == 10){
        	include 'sidemenu_finance.php';
      	}else if($_SESSION['level'] == 11){
			include 'sidemenu_purchasing.php';
		}else if($_SESSION['level'] == ""){
			include 'page_404.html'; 
		}
	?>  
	<p>Selamat datang....</p>
</body>
</html>