<?php
   include("koneksi.php");
   session_start();
   $alert = "<p align='center'> Log in </p>";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
 
	  $myusername = $_POST['username'];
      $mypassword = $_POST['password']; 

      $sql = "SELECT id, level, managerid FROM ng_userlogin WHERE username = \"$myusername\" and password = \"$mypassword\" and level IN (0,1,2,5,10,11)";
      
	  $result = mysqli_query($connectdb, $sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	  
      $count = mysqli_num_rows($result);

      if($count == 1) {
 
         $_SESSION['login_user'] = $myusername;
         $_SESSION['last_activity'] = time();
	 $_SESSION['level'] = $row['level'];
	 $_SESSION['userid'] = $row['id'];
	 $_SESSION['managerid'] = $row['managerid'];
         header("location: index.php");
      }else {
         $alert = "<font color='red'>Wrong Id</font>";
      }
   }
?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
 <form action="#" method=post>
    <input type="text" placeholder="Username" name="username" required="" />
              
    <input type="password" placeholder="Password" name="password" required="" />
             
    <button type="submit">Log In</button>
</body>
</html>

           
             