<?php
include "koneksi.php";
include "check.php";

$userid = $_SESSION['userid'];

$user = mysqli_query($connectdb, "SELECT * FROM ng_customer");
$kota = mysqli_query($connectdb, "SELECT * FROM ng_kota");
$quser = mysqli_query($connectdb, "SELECT max(username) as maxuser FROM ng_customer");
$arquser  = mysqli_fetch_array($quser);
$kode = (int) substr($arquser['maxuser'],-6);
$kode++;


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

  <h2>Regristration Form</h2>

  <form action="#" method=post enctype="multipart/form-data" novalidate>
    <span class="section">Personal Info</span>

    <label for="firstname">First Name <span class="required">*</span></label>
    <input id="firstname" name="firstname" placeholder="Input your First Name" required="required" type="text">
    
    <label for="lastname">Last Name <span class="required">*</span></label>
    <input id="lastname" name="lastname" placeholder="Input your Last Name" required="required" type="text" >
                        
    <label for="kota">Kota <span class="required">*</span></label>
    <select id="kota" type="option" name="kota" required>
        <option value=''>Pilih</option>
          <?php 
              while ($col = mysqli_fetch_array($kota))
              {
                echo "<option value=".$col['id'].">".$col['kota']."</option>";
              }
          ?>   
    </select>
                        
    <label for="node">Node <span class="required">*</span></label>
    <select id="node" type="option" name="node" required>
        <option value=''>Pilih</option>
    </select>
                        
    <label for="paket">Paket <span class="required">*</span></label>
    <select id="paket" type="option" name="paket" required>
        <option value=''>Pilih</option>
    </select>
                    
    <label for="alamat">Alamat <span class="required">*</span></label>
    <input id="alamat" required="required" name="alamat"placeholder="Input your Address"></input>
                 
    <label for="email">E-mail<span class="required">*</span></label>
    <input id="email" required="required" name="email" placeholder="Input your E-mail" type="email"></input>
     
    <label for="no_telp">No. Telp<span class="required">*</span></label>
    <input id="no_telp" required="required" name="no_telp" placeholder="Input your Telephone Number" type="tel"></input>
                     
    <label for="identitas">No Identitas <span class="required">*</span></label>
    <input id="identitas" required="required" type="text" name="identitas" placeholder="Input your ID Number KTP/SIM/Pasport"></input>
                        
    <label for="image-file">KTP/SIM/Pasport<span class="required">*</span></label>
    <input type="file" name="image-file">
                      
    <button type="reset" class="btn btn-primary">Cancel</button>
    <button id="send" type="submit" class="btn btn-success">Submit</button>
                        
  </form>

</body>
</html>

	<?php

	 if($_SERVER["REQUEST_METHOD"] == "POST") {
 
	    $firstname= $_POST['firstname'];
	    $lastname = $_POST['lastname'];
	    $password = rdpass($n);
	    $email = $_POST['email'];
	    $no_telp = $_POST['no_telp'];
		$kota = $_POST['kota'];
		$node = $_POST['node'];
		$username = '1'.$kota.''.$node.'000000'+ $kode;
		$paket = $_POST['paket'];
		$alamat = $_POST['alamat'];
		$identitas = $_POST['identitas'];
		$register_date = date("Y-m-d");
		$extension = explode("/", $_FILES["image-file"]["type"]);
    	$name_photo = $identitas.".".$extension[1];		
		$temp_photo = $_FILES['image-file']['tmp_name'];
		$size_photo = $_FILES['image-file']['size'];
    	$type_photo = $_FILES['image-file']['type'];
		$path = "foto/$name_photo";


		//random childpool
		$netaddress = 24;
		$ng_childpool = mysqli_query($connectdb, "SELECT ng_childpool.id, 
														ng_childpool.start_address, 
														ng_childpool.end_address 
													FROM ng_childpool, ng_paket 
													WHERE ng_childpool.kd_prod=ng_paket.kd_prod AND ng_paket.id='".$paket."'");

		$ng_usedpoolcheck = mysqli_query($connectdb, "SELECT address FROM ng_usedpool");
		$getusedpool = mysqli_fetch_assoc($ng_usedpoolcheck );
		
		$dtusedpool = array();
		array_push($dtusedpool,$getusedpool['address']);
		
		function cidr2NetmaskAddr($cidr) {
    		   $ta = substr($cidr, strpos($cidr, '/') + 1) * 1;
                   $netmask = str_split(str_pad(str_pad('', $ta, '1'), 32, '0'), 8);
                   foreach ($netmask as &$element) $element = bindec($element);
                      return join('.', $netmask);
		}

		$data = array();
		while ($dtchildpool = mysqli_fetch_assoc($ng_childpool)){
    
    			$poolid = $dtchildpool['id'];
    			$start_address = $dtchildpool['start_address'];
    			$end_address = $dtchildpool['end_address'];
    			$start = substr($start_address, strrpos($start_address, '.') + 1);
    			$end = substr($end_address, strrpos($end_address, '.') + 1);
    
    			$mask = cidr2NetmaskAddr($start_address.'/'.$netaddress);

    			$ips = ip2long($start_address);
    			$addressmask = ip2long($mask);
    			$ipa = ((~$addressmask) & $ips) ;
    			$network = long2ip(($ips ^ $ipa)).'/'.$netaddress;

    			$index = 0;
    			for ($i = $start ; $i <= $end; $i++) {
        			$ipaddr = long2ip(($ips ^ $ipa) + $i);
        			if(array_search($ipaddr, $dtusedpool) != true){
            			   array_push($data, array(
                                       'poolid'=>$poolid, 
                                       'address'=>$ipaddr,
            		           ));
            		        $index++;
        	                }
    		       }  
    		 }
    		$randomArray = array_rand($data); 
		$randpooid = $data[$randomArray]['poolid'];
		$randaddress = $data[$randomArray]['address'];
		//end random childpool

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
		         				<b>URL Konfirmasi : </b>
		         				<a href='localhost/SI_PKL/confirmemail.php?username=".$encryp_username."'>disini</a><br>
		         				<br>
		      				</div>
		   			</body>";
		// $body             = eregi_replace("[\]",'',$body);
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


      	$ng_customercheck = mysqli_query($connectdb, "SELECT identitas FROM ng_customer WHERE identitas =\"$identitas\"");
		$ng_emailcheck = mysqli_query($connectdb, "SELECT email FROM ng_customer WHERE email =\"$email\"");

        if(mysqli_fetch_row($ng_customercheck) == NULL ){
			if(mysqli_fetch_row($ng_emailcheck ) == NULL ){
				if (!empty($_FILES['image-file']['name'])) {
					if($type_photo == "image/jpg" || $type_photo == "image/png" || $type_photo == "image/jpeg"){
			   			if($size_photo <= 10000000){
							if(move_uploaded_file($temp_photo,$path)){
					

								$ng_customer = mysqli_query($connectdb, "INSERT INTO ng_customer (
																			firstname,
																			lastname,
																			username,
																			password,
																			kota,
																			node,
																			paket,
																			alamat,
																			email,
																			no_telp,
																			identitas,
																			foto,
																			register_date,
																			userid) 
																		VALUES (
																		\"$firstname\" ,
																		\"$lastname\" ,
																		\"$username\" ,
																		\"$password\" ,
																		\"$kota\" ,
																		\"$node\" ,
																		\"$paket\" ,
																		\"$alamat\" ,
																		\"$email\" ,
																		\"$no_telp\" ,
																		\"$identitas\" ,
																		\"$name_photo\" ,
																		\"$register_date\" ,
																		\"$userid\")") ; 
			       
								$radcheck = mysqli_query($connectdb, "INSERT INTO radcheck (
																			username,
																			attribute,
																			op,
																			value) 
																		VALUES (
																			\"$username\" , 
																			\"Cleartext-Password\" , 
																			\":=\" ,\"$password\")");
					
								$radreply = mysqli_query($connectdb, "INSERT INTO radreply(
																			username,
																			attribute,
																			op,
																			value) 
																		VALUES (
																			\"$username\", 
																			\"Framed-Address\", 
																			\":=\" ,
																			\"$randaddress\")");
				
								$available_childpool = mysqli_query($connectdb, "SELECT available 
																				FROM ng_childpool 
																				WHERE id =\"$randpooid\"");
								$getavailable = mysqli_fetch_assoc($available_childpool);
					
								$update_available = mysqli_query($connectdb, "UPDATE ng_childpool 
																				SET available = ".$getavailable['available']." - 1 
																				WHERE id = \"$randpooid\"");

								$ng_usedpool = mysqli_query($connectdb, "INSERT INTO ng_usedpool (
																				poolid,
																				address,
																				username)
																			VALUES (
																			\"$randpooid\", 
																			\"$randaddress\", 
																			\"$username\")");
						
								verifikasiEmail($firstname,$lastname,$username,$email);

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
		    	    echo '<script language="javascript">alert("Please upload KTP/SIM/Pasport")</script>';
			    }
		  	}else{ //jika data email sudah ada
	    		echo '<script language="javascript">alert("Email '. $email.' is registered")</script>';
        	}
		}else{ //jika data user sudah ada
	   	    echo '<script language="javascript">alert("User '. $firstname.' '. $lastname .' is registered")</script>';
        }
    }

    ?>


  <!-- jQuery -->
  <script src="js/jquery.min.js"></script>
	<!-- Selection -->
  <script type="text/javascript">
	$(document).ready(function(){ 
	  $("#kota").change(function(){ 
		$.ajax({
			type: "POST", 
			url: "selection.php", 
			data: {kota : $("#kota").val()}, 
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){
				$("#node").html(response.data_node).show();
			},
			error: function (xhr, ajaxOptions, thrownError) { 
				alert(thrownError); 
			}
		});
   	 });
	
	$("#node").change(function(){ 
		$.ajax({
    			type: "POST", 
    			url: "selection.php", 
    			data: {node : $("#node").val()}, 
    			dataType: "json",
    			beforeSend: function(e) {
      				if(e && e.overrideMimeType) {
        				e.overrideMimeType("application/json;charset=UTF-8");
      				}
    			},
    			success: function(response){
      				$("#paket").html(response.data_paket).show();
    			},
    			error: function (xhr, ajaxOptions, thrownError) { 
      				alert(thrownError); 
    			}
  		});
  	});
    });

    </script>
	
