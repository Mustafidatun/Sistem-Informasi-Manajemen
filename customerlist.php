<?php
include "koneksi.php";
include "check.php";

$userid = $_SESSION['userid'];
$managerid = $_SESSION['managerid'];

if($_SESSION['level'] == 0){
	$user = mysqli_query($connectdb, "SELECT ng_customer.id, 
                                          ng_customer.firstname, 
                                          ng_customer.lastname, 
                                          ng_customer.username, 
                                          ng_customer.password, 
                                          ng_kota.kota, 
                                          ng_node.node 
                                    FROM ng_customer
                        						INNER JOIN ng_kota ON ng_kota.id = ng_customer.kota
                        						INNER JOIN ng_node ON ng_node.nodeid = ng_customer.node");

}else if($_SESSION['level'] == 1){
	$user = mysqli_query($connectdb, "SELECT ng_customer.id, 
                                          ng_customer.firstname, 
                                          ng_customer.lastname, 
                                          ng_customer.username, 
                                          ng_customer.password, 
                                          ng_kota.kota, 
                                          ng_node.node 
                                    FROM ng_customer
                        						INNER JOIN ng_kota ON ng_kota.id = ng_customer.kota
                        						INNER JOIN ng_node ON ng_node.nodeid = ng_customer.node
                        						INNER JOIN ng_userlogin ON ng_userlogin.id = ng_customer.userid
                        						INNER JOIN ng_manager ON ng_manager.id = ng_userlogin.managerid
                        						WHERE ng_userlogin.id = \"$userid\"
                        					  
                                    UNION

                        					  SELECT ng_customer.firstname, 
                                          ng_customer.lastname, 
                                          ng_customer.username, 
                                          ng_customer.password, 
                                          ng_kota.kota, 
                                          ng_node.node 
                                    FROM ng_customer
                        						INNER JOIN ng_kota ON ng_kota.id = ng_customer.kota
                        						INNER JOIN ng_node ON ng_node.nodeid = ng_customer.node
                        						INNER JOIN ng_userlogin ON ng_userlogin.id = ng_customer.userid
                        						INNER JOIN ng_submanager ON ng_userlogin.managerid = ng_submanager.id 
                        						INNER JOIN ng_manager ON ng_submanager.managerid = ng_manager.id
                        						WHERE ng_manager.id = \"$managerid\"");

}else if($_SESSION['level'] == 2){
	$user = mysqli_query($connectdb, "SELECT ng_customer.id, 
                                          ng_customer.firstname, 
                                          ng_customer.lastname, 
                                          ng_customer.username, 
                                          ng_customer.password, 
                                          ng_kota.kota, 
                                          ng_node.node 
                                    FROM ng_customer
                          					INNER JOIN ng_kota ON ng_kota.id = ng_customer.kota
                          					INNER JOIN ng_node ON ng_node.nodeid = ng_customer.node
                          					INNER JOIN ng_userlogin ON ng_userlogin.id = ng_customer.userid
                          					INNER JOIN ng_submanager ON ng_submanager.id = ng_userlogin.managerid
                                  	WHERE ng_userlogin.id = \"$userid\"");
}
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

  <h3>Users </h3>

  <table>
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Username</th>
        <th>Password</th>
        <th>Kota</th>
        <th>Node</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($userdet = mysqli_fetch_assoc($user)){?>
      
      <tr>
        <td><?php echo $userdet['firstname']; ?></td>
        <td><?php echo $userdet['lastname']; ?></td>
        <td><?php echo $userdet['username']; ?></td>
        <td><?php echo $userdet['password']; ?></td>
        <td><?php echo $userdet['kota']; ?></td>
        <td><?php echo $userdet['node']; ?></td>
        <td>
            <a href="customerdetail.php?id=<?php echo $userdet['id']; ?>"> View </a>
            <a href="customeredit.php?id=<?php echo $userdet['id']; ?>"> Edit </a>
        </td>
      </tr>
      
      <?php } ?>
    </tbody>
  </table>
</body>
</html>