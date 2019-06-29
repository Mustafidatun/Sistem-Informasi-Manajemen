<?php
include "koneksi.php";
include "check.php";

$ng_equipmasterlist = mysqli_query($connectdb, "SELECT ng_equipmaster.*, 
                                                      ng_vendor.vendor 
                                                FROM ng_equipmaster
                                                INNER JOIN ng_vendor ON ng_vendor.id = ng_equipmaster.vendorid");

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

    <h3>Master Equipment </h3>

    <table>
      <thead>
        <tr>
          <th>Merk</th>
          <th>Type</th>
          <th>Price</th>
          <th>Vol</th>
          <th>Vendor Name</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($data = mysqli_fetch_assoc($ng_equipmasterlist)){?>

          <tr>
            <td><?php echo $data['merk']; ?></td>
            <td><?php echo $data['type']; ?></td>
            <td align="right"><?php echo $data['price']; ?></td>
            <td><?php echo $data['vol']; ?></td>
            <td><?php echo $data['vendor']; ?></td>
          </tr>
            
        <?php } ?>

      </tbody>
    </table>
    
</body>
</html>

 
                   