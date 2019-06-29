<?php
include "koneksi.php";
include "check.php";

$n=10; 
function rdpass($n) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
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

  <form action="#" method=post enctype="multipart/form-data" novalidate>
    <span class="section">User Info</span>
    
    <label for="username">Username <span class="required">*</span></label>
    <input id="username" name="username" placeholder="Input your Username" type="text" required>
                        
    <label for="email">E-mail<span class="required">*</span></label>
    <input id="email" required="required" name="email" placeholder="Input your E-mail" type="email">
                       
    <label for="level">Level user<span class="required">*</span></label>
    <select id="level" type="option" name="level" required>
      <option value=''>Pilih</option>
      <option value='1'>Manager</option>
      <option value='10'>Finance</option>
      <option value='11'>Purchasing</option>
      <option value='12'>Payment Point</option>
      <option value='5'>Field Tech</option>
    </select>
            
    <label for="image-file">Foto Profil<span class="required">*</span></label>
    <input type="file" name="image-file">
              
    <button type="reset">Cancel</button>
    <button id="send" type="submit">Submit</button>
                       
  </form>

</body>
</html>

<?php

   if($_SERVER["REQUEST_METHOD"] == "POST") {

      $username = $_POST['username'];
      $password = rdpass($n);
      $email = $_POST['email'];
      $level = $_POST['level'];
      $extension = explode("/", $_FILES["image-file"]["type"]);
      $name_photo = $username.".".$extension[1];    
      $temp_photo = $_FILES['image-file']['tmp_name'];
      $size_photo = $_FILES['image-file']['size'];
      $type_photo = $_FILES['image-file']['type'];
      $path = "foto/$name_photo";

    
      $usercheck = mysqli_query($connectdb, "SELECT username, email FROM ng_manager WHERE username =\"$username\" OR email =\"$email\"");

            if(mysqli_fetch_row($usercheck) == NULL ){
                if (!empty($_FILES['image-file']['name'])) {
                        if($type_photo == "image/jpg" || $type_photo == "image/png" || $type_photo == "image/jpeg"){
                            if($size_photo <= 10000000){
                                if(move_uploaded_file($temp_photo,$path)){

                      $user = mysqli_query($connectdb, "INSERT INTO ng_manager (username,password,email,foto) VALUES (\"$username\" ,\"$password\", \"$email\", \"$name_photo\")");
                
                    $usercheckid = mysqli_query($connectdb, "SELECT id FROM ng_manager WHERE username=\"$username\" AND password=\"$password\" AND email=\"$email\"");

                    $getid = mysqli_fetch_assoc($usercheckid);
                    $id = $getid['id'];

                    $ng_userlogin = mysqli_query($connectdb, "INSERT INTO ng_userlogin (username,password,managerid,level) VALUES (\"$username\" ,\"$password\", \"$id\", \"$level\")");
                                
                }else{ //jika gambar gagal diupload
                           echo '<script language="javascript">alert("Foto gagal diupload")</script>';
                      }
              }else{ //jika ukuran gambar lebih dari 10 mb
                       echo '<script language="javascript">alert("Ukuran foto tidak boleh lebih dari 10 mb")</script>';
                }
           }else{ //jika tipe gambar bukan jpg atau png
                    echo '<script language="javascript">alert("Tipe gambar yang diupload harus JPG atau PNG.")</script>';
           }
       }else{ //jika belum upload gambAr
              echo '<script language="javascript">alert("Please upload your photo profile")</script>';
       }
    }else{ //jika data user sudah ada
            echo '<script language="javascript">alert("User is registered")</script>';
          }

        }
       ?>