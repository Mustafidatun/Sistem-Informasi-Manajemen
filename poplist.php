<?php
include "koneksi.php";
include "check.php";

$pop = mysqli_query($connectdb, "SELECT * FROM ng_pop");

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

    <h3>Pop </h3>

    <table>
      <thead>
        <tr>
          <th>Ap Name</th>
          <th>Ap Lat</th>
          <th>Ap Long</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($dtpop = mysqli_fetch_assoc($pop)){?>
          <tr>
            <td><?php echo $dtpop['apname']; ?></td>
            <td><?php echo $dtpop['aplat']; ?></td>
            <td><?php echo $dtpop['aplong']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
</body>
</html>