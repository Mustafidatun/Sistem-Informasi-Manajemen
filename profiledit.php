<?php
include "koneksi.php";
include "check.php";

$userid = $_SESSION['userid'];
$managerid = $_SESSION['managerid'];

if($_SESSION['level'] == 1 || $_SESSION['level'] == 10 || $_SESSION['level'] == 11){
    $user = mysqli_query($connectdb, "SELECT ng_manager.* 
                                      FROM ng_manager
                                      INNER JOIN ng_userlogin ON ng_userlogin.managerid = ng_manager.id
                                      WHERE ng_userlogin.id = \"$userid\" AND ng_manager.id = \"$managerid\"");
}else if($_SESSION['level'] == 2){
    $user = mysqli_query($connectdb, "SELECT ng_submanager.* 
                                      FROM ng_submanager
                                      INNER JOIN ng_userlogin ON ng_userlogin.managerid = ng_submanager.id
                                      WHERE ng_userlogin.id = \"$userid\" AND ng_manager.id = \"$managerid\"");
}
$dtuser = mysqli_fetch_assoc($user);
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

    <h2>Edit Profile</h2>

    <form action="#" method=post enctype="multipart/form-data" novalidate>
      <span>Personal Info</span>

      <input id="id" name="id" type="hidden" value="<?php echo $dtuser['id']; ?>">
      
      <input id="image-old" type="hidden" name="image-old" value="<?php echo $dtuser['foto'] ; ?>">
      
      <label for="username">Username <span class="required">*</span></label>
      <input id="username" name="username" type="text" value="<?php echo $dtuser['username']; ?>" readonly>
        
      <label for="email">E-mail<span class="required">*</span></label>
      <input id="email" required="required" name="email" placeholder="Input your E-mail" type="email" value="<?php echo $dtuser['email']; ?>">
      
      <label for="password">Old Password<span class="required">*</span></label>
      <input id="oldpassword" required="required" name="oldpassword" placeholder="Input your Old Password" type="password">
                 
      <label for="password">New Password<span class="required">*</span></label>
      <input id="newpassword" required="required" name="newpassword" placeholder="Input your New Password" type="password">
                    
      <label for="password2">Repeat Password</label>
      <input id="newpassword2" type="password" name="newpassword2" data-validate-linked="newpassword" placeholder="Input your Repeat Password" required="required">
            
      <label for="image-file">Foto Profil<span class="required">*</span></label>
      <input type="file" name="image-file">
              
      <button type="reset">Cancel</button>
      <button id="send" type="submit">Submit</button>
    </form>
</body>
</html>


	<?php

	 if($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
		$username = $_POST['username'];
    $oldpassword = $_POST['oldpassword'];
		$newpassword = $_POST['newpassword'];
    $email = $_POST['email'];
		$old_photo = $_POST['image-old'];
		$extension = explode("/", $_FILES["image-file"]["type"]);
    $name_photo = $username.".".$extension[1];		
    $temp_photo = $_FILES['image-file']['tmp_name'];
    $size_photo = $_FILES['image-file']['size'];
    $type_photo = $_FILES['image-file']['type'];
		$path = "/var/www/html/ng4dm1n/production/foto/$name_photo";
		$oldpath = "/var/www/html/ng4dm1n/production/foto/$old_photo";

    		$passwordcheck = mysqli_query($connectdb, "SELECT password FROM ng_userlogin WHERE password =\"$oldpassword\" AND id = \"$userid\"");
		
		if(mysqli_fetch_row($passwordcheck) != NULL ){
 	        if (!empty($_FILES['image-file']['name'])) {
                        if($type_photo == "image/jpg" || $type_photo == "image/png" || $type_photo == "image/jpeg"){
                            if($size_photo <= 10000000){
                               
					unlink($oldpath);
					move_uploaded_file($temp_photo,$path);

					if($_SESSION['level'] == 1 || $_SESSION['level'] == 10 || $_SESSION['level'] == 11){	
    						$updateuser = mysqli_query($connectdb, "UPDATE ng_manager SET password = \"$newpassword\", email= \"$email\", foto = \"$name_photo\"
                                        					WHERE id = \"$id\"");
						$updateuserlogin = mysqli_query($connectdb, "UPDATE ng_userlogin SET password = \"$newpassword\"
                                        					WHERE id = \"$userid\"");

					}else if($_SESSION['level'] == 2){
    						$updateuser = mysqli_query($connectdb, "UPDATE ng_submanager SET password = \"$newpassword\", email= \"$email\", foto = \"$name_photo\"
										WHERE id = \"$id\"");
						$updateuserlogin = mysqli_query($connectdb, "UPDATE ng_userlogin SET password = \"$newpassword\"
                                        					WHERE id = \"$userid\"");

					}
						                                    
			    }else{ //jika ukuran gambar lebih dari 10 mb
	    	               echo '<script language="javascript">alert("Ukuran foto tidak boleh lebih dari 10 mb")</script>';
        	    	    }
		       }else{ //jika tipe gambar bukan jpg atau png
	    	            echo '<script language="javascript">alert("Tipe gambar yang diupload harus JPG atau PNG.")</script>';
 		       }
		   }else{ //jika tidak upload gambar
	    	      if($_SESSION['level'] == 1 || $_SESSION['level'] == 10 || $_SESSION['level'] == 11){
    				$updateuser = mysqli_query($connectdb, "UPDATE ng_manager SET password = \"$newpassword\", email= \"$email\"
                                        					WHERE id = \"$id\"");
				$updateuserlogin = mysqli_query($connectdb, "UPDATE ng_userlogin SET password = \"$newpassword\"
                                        					WHERE id = \"$userid\"");

		      }else if($_SESSION['level'] == 2){
    				$updateuser = mysqli_query($connectdb, "UPDATE ng_submanager SET password = \"$newpassword\", email= \"$email\"
										WHERE id = \"$id\"");
				$updateuserlogin = mysqli_query($connectdb, "UPDATE ng_userlogin SET password = \"$newpassword\"
                                        					WHERE id = \"$userid\"");

		      }
		   }
		}else{ //jika password lama salah
	    	     echo '<script language="javascript">alert("Password lama Salah")</script>';
 		}

	echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
       	}
       ?>

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){ 
	  $("#send").on('click', function() {
		var len = {min:8,max:10};
		var oldpassword = $("#oldpassword").val();
		var newpassword = $("#newpassword").val();
		if (oldpassword.length < len.min || oldpassword.length > len.max) {
    			alert("Old Password must be between 8 and 10");
    			return false;
  		}
		if (newpassword.length < len.min || newpassword.length > len.max) {
    			alert("New Passwordmust be between 8 and 10");
    			return false;
  		}	
  	});
    });
	</script>