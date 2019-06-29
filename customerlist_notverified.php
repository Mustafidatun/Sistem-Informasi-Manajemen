<?php
include "koneksi.php";
include "check.php";

$userid = $_SESSION['userid'];
$managerid = $_SESSION['managerid'];

if($_SESSION['level'] == 0){
	$ng_user_verifikasi = mysqli_query($connectdb, "SELECT firstname, 
                                                        lastname, 
                                                        username, 
                                                        password, 
                                                        email, 
                                                        active 
                                                  FROM ng_customer 
                                                  WHERE active = 0");

}else if($_SESSION['level'] == 1){
	$ng_user_verifikasi = mysqli_query($connectdb, "SELECT ng_customer.firstname, 
                                                        ng_customer.lastname, 
                                                        ng_customer.username, 
                                                        ng_customer.password, 
                                                        ng_customer.email, 
                                                        ng_customer.active 
                                                  FROM ng_customer 
                                  								INNER JOIN ng_userlogin ON ng_userlogin.id = ng_customer.userid
                                  								INNER JOIN ng_manager ON ng_manager.id = ng_userlogin.managerid
                                  								WHERE ng_userlogin.id = \"$userid\" AND ng_customer.active = 0
							                                   
                                                  UNION
							   
                                                  SELECT ng_customer.firstname, 
                                                        ng_customer.lastname, 
                                                        ng_customer.username, 
                                                        ng_customer.password, 
                                                        ng_customer.email, 
                                                        ng_customer.active 
                                                  FROM ng_customer
                                  								INNER JOIN ng_userlogin ON ng_userlogin.id = ng_customer.userid
                                  								INNER JOIN ng_submanager ON ng_userlogin.managerid = ng_submanager.id 
                                  								INNER JOIN ng_manager ON ng_submanager.managerid = ng_manager.id
                                  								WHERE ng_manager.id = \"$userid\" AND ng_customer.active = 0 ");

}else if($_SESSION['level'] == 2){
	$ng_user_verifikasi = mysqli_query($connectdb, "SELECT ng_customer.firstname, 
                                                        ng_customer.lastname, 
                                                        ng_customer.username, 
                                                        ng_customer.password, 
                                                        ng_customer.email, 
                                                        ng_customer.active 
                                                  FROM ng_customer 
                                  								INNER JOIN ng_userlogin ON ng_userlogin.id = ng_customer.userid
                                  								INNER JOIN ng_submanager ON ng_submanager.id = ng_userlogin.managerid
                                  								WHERE ng_userlogin.id = \"$userid\" AND ng_customer.active = 0");

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

  <table>
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Username</th>
        <th>Password</th>
        <th>Email</th>
        <th>Verify Email</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dtuser = mysqli_fetch_assoc($ng_user_verifikasi)){?>
                        
      <tr>
        <td><?php echo $dtuser['firstname']; ?></td>
        <td><?php echo $dtuser['lastname']; ?></td>
        <td><?php echo $dtuser['username']; ?></td>
        <td><?php echo $dtuser['password']; ?></td>
        <td><?php echo $dtuser['email']; ?></td>
        <td><form action="#" method=post novalidate>
              <input id="firstname" name="firstname" type="hidden" value="<?php echo $dtuser['firstname']; ?>">
              <input id="lastname" name="lastname" type="hidden" value="<?php echo $dtuser['lastname']; ?>">
              <input id="username" name="username" type="hidden" value="<?php echo $dtuser['username']; ?>">
              <input id="email" name="email" type="hidden" value="<?php echo $dtuser['email']; ?>">       
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

		$firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		
		verifikasiEmail($firstname,$lastname,$username,$email);
		echo '<script language="javascript">alert("Email has been send")</script>';
	
	}
	
	//verifikasi Email
	function verifikasiEmail($firstname,$lastname,$username,$email){
		
		require_once('PHPMailer/class.phpmailer.php'); //menginclude librari phpmailer

		$encryp_username  = base64_encode($username);
		$mail             = new PHPMailer();
		$body             = 
		   			"<body style='margin: 10px;'>
		      				<div style='width: 640px; font-family: Helvetica, sans-serif; font-size: 13px; padding:10px; line-height:150%; border:#eaeaea solid 10px;'>
		         				<br>
		         				<strong>Terima Kasih Telah Mendaftar</strong><br>
		         				<b>Nama Anda : </b>".$firstname." ".$lastname."<br>
		         				<b>Email : </b>".$email."<br>
		         				<b>URL Konfirmasi : </b><a href='localhost/SI_PKL/confirmemail.php?username=".$encryp_username."'>disini</a><br>
		         				<br>
		      				</div>
		   			</body>";
		// $body             =  preg_replace_callback("/([^A-Za-z0-9!*+\/ -])/e",'',$body);
		$mail->IsSMTP(); 	// menggunakan SMTP
		$mail->SMTPDebug  = 1;   // mengaktifkan debug SMTP
		$mail->SMTPSecure = 'tls'; 
 		$mail->SMTPAuth   = true;   // mengaktifkan Autentifikasi SMTP
		$mail->Host 	= 'smtp.gmail.com'; // host sesuaikan dengan hosting mail anda
		$mail->Port       = 587;  // post gunakan port 25
		$mail->Username   = "admproduction96@gmail.com"; // username email akun
		$mail->Password   = "admin2019.";        // password akun

		$mail->SetFrom('admproduction96@gmail.com', 'Verifikasi Email');


		$mail->Subject    = "Verifikasi Email";
    $mail->MsgHTML($body);

    $address = $email; //email tujuan
    $mail->AddAddress($address, "Hello ".$firstname." ".$lastname);
		$mail->Send();
	}
	//end verifikasi Email
	?>
  