<?php
include "koneksi.php";
include "check.php";

$ng_submanager = mysqli_query($connectdb, "SELECT ng_submanager.id, 
                                                  ng_submanager.username AS username_submanager, 
                                                  ng_submanager.password, ng_submanager.email 
                                            FROM ng_submanager
                                            INNER JOIN ng_manager ON ng_manager.id = ng_submanager.managerid");
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
        <?php while ($dtsubmanager = mysqli_fetch_assoc($ng_submanager)){ ?>
          
          <tr>
            <td><?php echo $dtsubmanager['username_submanager']; ?></td>
            <td><?php echo $dtsubmanager['password']; ?></td>
            <td><?php echo $dtsubmanager['email']; ?></td>
            <td><form action="" method=post novalidate>
                  <input id="submanagerid" name="submanagerid" type="hidden" value="<?php echo $dtsubmanager['id']; ?>">
                  <select id="level" type="option" class="form-control col-md-7 col-xs-12" name="level" required>
                      <option value='1'>Manager</option>
                      <option value='10'>Finance</option>
                      <option value='11'>Purchasing</option>
                  </select>
                  <button id="send" type="submit" class="btn btn-success">Submit</button>                                    
                </form>
            </td>
          </tr>
          
          <?php } ?>
        </tbody>
      </table>
</body>
</html>

        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $submanagerid = $_POST['submanagerid'];
            $submanager = mysqli_query($connectdb, "SELECT ng_submanager.*, ng_userlogin.id AS userloginid FROM ng_submanager 
                                                    INNER JOIN ng_userlogin ON ng_userlogin.managerid = ng_submanager.id
                                                    WHERE ng_submanager.id = \"$submanagerid\"");
            $showsubmanager = mysqli_fetch_assoc($submanager);
            $userloginid = $showsubmanager['userloginid'];
            $username = $showsubmanager['username'];
            $password = $showsubmanager['password'];
            $email = $showsubmanager['email'];
            $foto = $showsubmanager['foto'];
            $level = $_POST['level'];

            $assigmentlevel =  mysqli_query($connectdb, "CALL spAssigmentLevel(\"$userloginid\",\"$submanagerid\",\"$username\",\"$password\",\"$email\",\"$foto\",\"$level\")");
          echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
        }
        ?>
        