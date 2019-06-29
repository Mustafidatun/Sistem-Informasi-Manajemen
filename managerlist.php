<?php
include "koneksi.php";
include "check.php";

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

    <h3>Managers</h3>

    <table>
      <thead>
        <tr>
          <th>Username</th>
          <th>Password</th>
          <th>Email</th>
          <th>Level</th>
          </tr>
      </thead>
      <tbody>
        <?php 
            $ng_manager = mysqli_query($connectdb, "SELECT ng_manager.username, ng_manager.password, ng_manager.email, ng_userlogin.level 
                                        FROM ng_manager 
                                        INNER JOIN ng_userlogin ON ng_userlogin.managerid = ng_manager.id
                                        ORDER BY ng_userlogin.level ASC");

            if(!$ng_manager){
                  die ("Query Error: ".mysqli_errno($connectdb).
                     " - ".mysqli_error($connectdb));
                }


            while ($dtmanager = mysqli_fetch_assoc($ng_manager)){
            if($dtmanager['level'] == 1){
              $lvl = 'Manager';
            }else if($dtmanager['level'] == 5){
              $lvl = 'Field Tech';
            }else if($dtmanager['level'] == 10){
              $lvl = 'Finance';
            }else if($dtmanager['level'] == 11){
              $lvl = 'Purchase';
            }
            
            //password
            $countpass = strlen(substr($dtmanager['password'],0,-3));
            $replacepass = '';
            for($i = 0; $i < $countpass; $i++){
              $replacepass .= 'x';
            }
            $password = str_replace(substr($dtmanager['password'],0,-3), $replacepass ,$dtmanager['password']);
            //end password
        ?>
        
        <tr>
          <td><?php echo $dtmanager['username']; ?></td>
          <td><?php echo $password; ?></td>
          <td><?php echo $dtmanager['email']; ?></td>
          <td><?php echo $lvl; ?></td>
        </tr>
        
        <?php } ?>
      </tbody>
    </table>
    
</body>
</html>