<?php
include "koneksi.php";
include "check.php";

$chpole = mysqli_query($connectdb, "SELECT ng_childpool.*, ng_paket.paket FROM ng_childpool 
			LEFT JOIN ng_paket ON ng_paket.kd_prod = ng_childpool.kd_prod
			ORDER BY id, poolid ASC");
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

    <h2>Pool List </h2>

    <table>
      <thead>
        <tr>
          <th>Pool Name</th>
          <th>Start Address</th>
          <th>End Address</th>
          <th>Availability</th>
          <th>Paket</th> 
        </tr>
      </thead>
      <tbody>
        <?php while ($chpooldet = mysqli_fetch_assoc($chpole)){?>

          <tr>
            <td><?php echo $chpooldet['poolname']; ?></td>
            <td><?php echo $chpooldet['start_address']; ?></td>
            <td><?php echo $chpooldet['end_address']; ?></td>
            <td><?php echo $chpooldet['available']; ?></td>
            <td><?php if($chpooldet['paket'] != NULL){
                        echo $chpooldet['paket'];
                      }else{
                  ?>
                      <button id="send" type="submit" onclick="location.href = 'assignmentchpool.php?id=<?php echo $chpooldet['id'] ?>'">Add Paket</button>
                  <?php  } ?>
            </td>
          </tr>
      
      <?php } ?>

    </tbody>
  </table>
  
</body>
</html>
