<!DOCTYPE html>

<?php
include "koneksi.php";
include "check.php";

$ng_paket = mysqli_query($connectdb, "SELECT * FROM ng_paket");

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

    <h3>Paket </h3>

    <table>
      <thead>
        <tr>
          <th>Paket Name</th>
          <th>Kode Product</th>
          <th>Price A</th>
          <th>Price A</th>
          <th>Price A</th>
          <th>Price B</th>
          <th>Price B</th>
          <th>Price C</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($data = mysqli_fetch_assoc($ng_paket)){?>

          <tr>
            <td><?php echo $data['paket']; ?></td>
            <td><?php echo $data['kd_prod']; ?></td>
            <td><?php echo $data['price1']; ?></td>
            <td><?php echo $data['price2']; ?></td>
            <td><?php echo $data['price3']; ?></td>
            <td><?php echo $data['price4']; ?></td>
            <td><?php echo $data['price5']; ?></td>
            <td><?php echo $data['price6']; ?></td>
          </tr>

        <?php } ?>

      </tbody>
    </table>
</body>
</html>

           