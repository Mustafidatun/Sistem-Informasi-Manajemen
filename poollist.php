<!DOCTYPE html>

<?php
include "koneksi.php";
include "check.php";

$pole = mysqli_query($connectdb, "SELECT * FROM ng_pool");
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

    <h2>Pool List </h2>

    <table>
      <thead>
        <tr>
          <th>Pool Name</th>
          <th>Prefix</th>
          <th>Availability</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($pooldet = mysqli_fetch_assoc($pole)){?>

          <tr>
            <td><?php echo $pooldet['name']; ?></td>
            <td><?php echo $pooldet['prefix']; ?></td>
            <td><?php echo $pooldet['nodeid']; ?></td>
          </tr>

        <?php } ?>

      </tbody>
    </table>
</body>
</html>
