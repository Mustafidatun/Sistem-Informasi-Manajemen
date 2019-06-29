<?php
include "koneksi.php";
include "check.php";

$managerid = $_SESSION['managerid'];
$ng_submanager = mysqli_query($connectdb, "SELECT ng_submanager.username AS username_submanager, 
                                                  ng_submanager.password, 
                                                  ng_submanager.email 
                                          FROM ng_submanager
                                          INNER JOIN ng_manager ON ng_manager.id = ng_submanager.managerid
                                          WHERE ng_manager.id = \"$managerid\"");
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

    <h3>Submanagers </h3>

    <table>
      <thead>
        <tr>
          <th>Username</th>
          <th>Password</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($dtsubmanager = mysqli_fetch_assoc($ng_submanager)){?>

          <tr>
            <td><?php echo $dtsubmanager['username_submanager']; ?></td>
            <td><?php echo $dtsubmanager['password']; ?></td>
            <td><?php echo $dtsubmanager['email']; ?></td>
          </tr>

        <?php } ?>
      </tbody>
    </table>
</body>
</html>

