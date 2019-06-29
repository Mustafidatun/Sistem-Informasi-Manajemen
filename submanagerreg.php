<?php
include "koneksi.php";
include "check.php";


$managerid = $_SESSION['managerid'];
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
      }else if($_SESSION['level'] == 10){
        include 'sidemenu_finance.php';
      }else if($_SESSION['level'] == 11){
        include 'sidemenu_purchasing.php';
      }else if($_SESSION['level'] == ""){
        include 'page_404.html'; 
      }
    ?>  

    <h2>Registration Submanager</h2>

    <form action="#" method=post enctype="multipart/form-data" novalidate>
      <span>Personal Info</span>

      <label for="username">Username <span class="required">*</span></label>
      <input id="username" name="username" placeholder="Input your Username" required="required" type="text">
           
      <label for="email">E-mail<span class="required">*</span></label>
      <input id="email" required="required" name="email" placeholder="Input your E-mail" type="email"></input>
        
      <label for="foto"><span class="required"></span></label>
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
		    $extension = explode("/", $_FILES["image-file"]["type"]);
            $name_photo = $username.".".$extension[1];		
            $temp_photo = $_FILES['image-file']['tmp_name'];
            $size_photo = $_FILES['image-file']['size'];
            $type_photo = $_FILES['image-file']['type'];
		    $path = "foto/$name_photo";

 		
      		$ng_submanagercheck = mysqli_query($connectdb, "SELECT username, email FROM ng_submanager WHERE username =\"$username\" OR email =\"$email\"");

            if(mysqli_fetch_row($ng_managercheck) == NULL ){
		   if (!empty($_FILES['image-file']['name'])) {
                        if($type_photo == "image/jpg" || $type_photo == "image/png" || $type_photo == "image/jpeg"){
                            if($size_photo <= 10000000){
                                if(move_uploaded_file($temp_photo,$path)){

					            $ng_submanager = mysqli_query($connectdb, "INSERT INTO ng_submanager (username,password,email,foto, managerid) VALUES (\"$username\" ,\"$password\", \"$email\", \"$name_photo\", \"$managerid\")");
						    
						    $ng_submanagercheckid = mysqli_query($connectdb, "SELECT id FROM ng_submanager WHERE username=\"$username\" AND password=\"$password\" AND email=\"$email\"");

						    $getid = mysqli_fetch_assoc($ng_submanagercheckid);
						    $id = $getid['id'];

                				    $ng_userlogin = mysqli_query($connectdb, "INSERT INTO ng_userlogin (username,password,managerid,level) VALUES (\"$username\" ,\"$password\", \"$id\", \"2\")");
                
                				    verifikasiEmail($username,$password,$email);

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
	//verifikasi Email
      function verifikasiEmail($username,$password,$email){
      
        require_once('PHPMailer/class.phpmailer.php'); //menginclude librari phpmailer
    
        $encryp_username  = base64_encode($username);
        $mail             = new PHPMailer();
        $body             = 
                "<body style='margin: 10px;'>
                      <div style='width: 640px; font-family: Helvetica, sans-serif; font-size: 13px; padding:10px; line-height:150%; border:#eaeaea solid 10px;'>
                        <br>
                        <strong>Terima Kasih Telah Mendaftar</strong><br>
                        <b>Username : </b>".$username."<br>
                        <b>Password : </b>".$password."<br>
                        <b>URL Konfirmasi : </b>http://10.10.10.222/ng4dm1n/production/login<br>
                        <br>
                      </div>
                </body>";
        $body             = eregi_replace("[\]",'',$body);
        $mail->IsSMTP(); 	// menggunakan SMTP
        $mail->SMTPDebug  = 1;   // mengaktifkan debug SMTP
        $mail->SMTPSecure = 'tls'; 
        $mail->SMTPAuth   = true;   // mengaktifkan Autentifikasi SMTP
        $mail->Host 	= 'smtp.gmail.com'; // host sesuaikan dengan hosting mail anda
        $mail->Port       = 587;  // post gunakan port 25
        $mail->Username   = ""; // username email akun
        $mail->Password   = "";        // password akun
    
        $mail->SetFrom('', 'Hello imax');
    
    
        $mail->Subject    = "Aktivasi Email User";
        $mail->MsgHTML($body);
    
        $address = $email; //email tujuan
        $mail->AddAddress($address, "Hello (Reciever name)");
        $mail->Send();
        }
      //end verifikasi Email
       ?>