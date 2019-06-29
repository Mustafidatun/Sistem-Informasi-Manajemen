<!DOCTYPE html>

<?php
include "koneksi.php";
include "check.php";

$ng_vendorlist = mysqli_query($connectdb, "SELECT * FROM ng_vendor");

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

    <h3>Vendor </h3>

    <table>
      <thead>
        <tr>
          <th>Vendor Name</th>
          <th>Address</th>
          <th>No.Telp</th>
          <th>E-mail</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($data = mysqli_fetch_assoc($ng_vendorlist)){?>

          <tr>
            <td><?php echo $data['vendor']; ?></td>
            <td><?php echo $data['alamat']; ?></td>
            <td><?php echo $data['no_telp']; ?></td>
            <td><?php echo $data['email']; ?></td>
          </tr>

        <?php } ?>
      </tbody>
    </table>
</body>
</html>

