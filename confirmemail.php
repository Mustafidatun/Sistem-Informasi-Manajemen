<?php
include "koneksi.php";
include "check.php";

$username = $_GET['username'];
$decode_username = base64_decode($username);

if (isset($username)) {
	$find = mysqli_query($connectdb, "SELECT username FROM ng_customer WHERE username ='.$decode_username.'");

	if (mysqli_fetch_row($find) == NULL) {
		$konfirmasi = mysqli_query ($connectdb, "UPDATE ng_customer SET active = 1 WHERE username = \"$decode_username\"");
		$ng_customer = mysqli_query($connectdb, "SELECT password, email FROM ng_customer WHERE username = \"$decode_username\"");
		$data_customer = mysqli_fetch_assoc($ng_customer);
		$password = $data_customer['password'];
		$email = $data_customer['email'];
		verifikasiDataCustomer($decode_username, $password, $email);
		
		if ($konfirmasi) {
			echo '<script language="javascript">alert("Konfirmasi sukses")</script>';
		}

	} else {
		echo '<script language="javascript">alert("Akun tidak terdaftar")</script>';
	}
}

	//verifikasi Email
	function verifikasiDataCustomer($decode_username, $password, $email){
		require_once('PHPMailer/class.phpmailer.php'); //menginclude librari phpmailer

		   $mail             = new PHPMailer();
		   $body             = 
		   "<body style='margin: 10px;'>
	   		<div style='width: 640px; font-family: Helvetica, sans-serif; font-size: 13px; padding:10px; line-height:150%; border:#eaeaea solid 10px;'>
		  	<br>
		  	<strong>Terima Kasih Telah Mendaftar</strong><br>
		  	<b>Username : </b>".$decode_username."<br>
		 	<b>Password : </b>".$password."<br>
		 	<b>URL Login : </b>http://10.10.10.222/ng4dm1n/production/login.php<br>
		 	<br>
	   		</div>
		   </body>";
		   $body             = eregi_replace("[\]",'',$body);
		   $mail->IsSMTP(); 	// menggunakan SMTP
		   $mail->SMTPDebug  = 1;   // mengaktifkan debug SMTP
                   $mail->SMTPSecure = 'tls'; 
                   $mail->SMTPAuth   = true;   // mengaktifkan Autentifikasi SMTP
                   $mail->Host 	     = 'smtp.gmail.com'; // host sesuaikan dengan hosting mail anda
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