<!DOCTYPE html>

<?php
include "koneksi.php";
include "check.php";

$node = mysqli_query($connectdb, "SELECT ng_node.nodeid,
                                        ng_kota.kota, 
                                        ng_node.node, 
                                        ng_node.address, 
                                        ng_node.port, 
                                        ng_node.secret, 
                                        ng_node.type 
                                  FROM ng_node
					                        INNER JOIN ng_kota ON ng_kota.id = ng_node.kota");

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

  <h3>Node </h3>

  <table>
    <thead>
      <tr>
        <th>Kota</th>
        <th>Node Name</th>
        <th>Address</th>
        <th>Port</th>
        <th>Secret</th>
        <th>Type</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dtnode = mysqli_fetch_assoc($node)){?>
      
      <tr>
        <td><?php echo $dtnode['kota']; ?></td>
        <td><?php echo $dtnode['node']; ?></td>
        <td><?php echo $dtnode['address']; ?></td>
        <td><?php echo $dtnode['port']; ?></td>
        <td><?php echo $dtnode['secret']; ?></td>
        <td><?php echo $dtnode['type']; ?></td>
        <td>
            <a href="nodeedit.php?id=<?php echo $dtnode['nodeid']; ?>">Edit </a>
        </td>
      </tr>

        <?php } ?>
    </tbody>
  </table>
</body>
</html>

            