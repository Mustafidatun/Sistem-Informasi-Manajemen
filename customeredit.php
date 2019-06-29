<?php
include "koneksi.php";
include "check.php";

if (isset($_GET['id'])) {
    
    $userid = $_SESSION['userid'];
    $customerid = $_GET['id'];

    $user = mysqli_query($connectdb, "SELECT ng_customer.firstname, 
                                            ng_customer.lastname, 
                                            ng_customer.alamat, 
                                            ng_customer.node AS nodeid, 
                                            ng_customer.paket AS paketid, 
                                            ng_customer.email, 
                                            ng_customer.no_telp, 
                                            ng_customer.identitas, 
                                            ng_customer.foto, 
                                            ng_kota.kota, 
                                            ng_node.node
                                        FROM ng_customer 
                                        INNER JOIN ng_kota ON ng_kota.id = ng_customer.kota
                                        INNER JOIN ng_node ON ng_node.nodeid = ng_customer.node
                                        WHERE ng_customer.id =\"$customerid\"");
    $data = mysqli_fetch_assoc($user);

    $ng_paket = mysqli_query($connectdb, "SELECT ng_paket.id, 
                                                ng_paket.paket 
                                          FROM ng_paket,ng_childpool,ng_node,ng_pool 
                                          WHERE ng_node.nodeid=ng_pool.nodeid AND 
                                                  ng_childpool.poolid=ng_pool.id AND 
                                                  ng_childpool.kd_prod=ng_paket.kd_prod AND 
                                                  ng_node.nodeid= ".$data['nodeid']." GROUP BY ng_paket.paket");
    
} else {
    
    header("location:userlist.php");
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
      <span class="section">Personal Info</span>

      <label for="firstname">First Name <span class="required">*</span></label>
      <input id="firstname" name="firstname" placeholder="Input your First Name" required="required" type="text" vaLue="<?php echo $data['firstname']; ?>">
                 
      <label for="lastname">Last Name <span class="required">*</span></label>
      <input id="lastname" name="lastname" placeholder="Input your Last Name" required="required" type="text"  vaLue="<?php echo $data['lastname']; ?>">
                      
      <label for="kota">Kota <span class="required">*</span></label>
      <input id="kota" name="kota" type="text"  vaLue="<?php echo $data['kota']; ?>" readonly>
                     
      <label for="node">Node <span class="required">*</span></label>
      <input id="node" name="node" type="text"  vaLue="<?php echo $data['node']; ?>" readonly>
      
      <label for="paket">Paket <span class="required">*</span></label>
      <input id="oldpaket" type="hidden" name="oldpaket" value="<?php echo $data['paketid'] ; ?>">
      <select id="paket" type="option" name="paket" required>
        <option value=''>Pilih</option>
        <?php 
            while ($dtpaket = mysqli_fetch_array($ng_paket)){
        ?>
        <option value="<?php echo $dtpaket['id']; ?>" <?php if($dtpaket['id'] == $data['paketid']) echo 'selected = "selected"'; ?>><?php echo $dtpaket['paket']; ?></option>
        <?php 
          }
        ?>  
      </select>
                        
      <label for="alamat">Alamat <span class="required">*</span></label>
      <input id="alamat" required="required" name="alamat" placeholder="Input your Address" vaLue="<?php echo $data['alamat']; ?>"></input>
                       
      <label for="email">E-mail<span class="required">*</span></label>
      <input id="oldemail" name="oldemail" type="hidden"  vaLue="<?php echo $data['email']; ?>">
      <input id="email" required="required" name="email" placeholder="Input your E-mail" type="email"  vaLue="<?php echo $data['email']; ?>">
                     
      <label for="no_telp">No. Telp<span class="required">*</span></label>
      <input id="no_telp" required="required" name="no_telp" placeholder="Input your Telephone Number" type="tel"  vaLue="<?php echo $data['no_telp']; ?>">
                        
      <label for="identitas">No Identitas <span class="required">*</span></label>
      <input id="oldidentitas" name="oldidentitas" type="hidden"  vaLue="<?php echo $data['identitas']; ?>">
      <input id="identitas" required="required" type="text" name="identitas" placeholder="Input your ID Number KTP/SIM/Pasport" vaLue="<?php echo $data['identitas']; ?>"></input>
                      
      <label for="image-file">KTP/SIM/Pasport<span class="required">*</span></label>
      <input type="file" name="image-file">
      <input id="image-old" type="hidden" name="image-old" value="<?php echo $data['foto'] ; ?>">
                
      <button type="reset">Cancel</button>
      <button id="send" type="submit">Submit</button>
    </form>

</body>
</html>

	<?php

	 if($_SERVER["REQUEST_METHOD"] == "POST") {
 
        	$firstname= $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $oldpaket = $_POST['oldpaket'];
            $paket = $_POST['paket'];
            $oldemail = $_POST['oldemail'];
        	$email = $_POST['email'];
        	$no_telp = $_POST['no_telp'];
            $alamat = $_POST['alamat'];
            $oldidentitas = $_POST['oldidentitas'];
            $identitas = $_POST['identitas'];
            $old_photo = $_POST['image-old'];
            $extension = explode("/", $_FILES["image-file"]["type"]);
            $name_photo = $identitas.".".$extension[1];		
            $temp_photo = $_FILES['image-file']['tmp_name'];
            $size_photo = $_FILES['image-file']['size'];
            $type_photo = $_FILES['image-file']['type'];
            $path = "foto/$name_photo";
            $oldpath = "foto/$old_photo";


		//random childpool
		$netaddress = 24;
		$ng_childpool = mysqli_query($connectdb, "SELECT ng_childpool.id, ng_childpool.start_address, ng_childpool.end_address FROM ng_childpool, ng_paket where ng_childpool.kd_prod=ng_paket.kd_prod and ng_paket.id='".$paket."'");
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
		         				<b>URL Konfirmasi : </b>http://10.10.10.222/ng4dm1n/production/confirmemail.php?username=".$encryp_username."<br>
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


        if($oldidentitas != $identitas){
          $ng_customercheck = mysqli_query($connectdb, "SELECT identitas FROM ng_customer WHERE identitas =\"$identitas\"");
        }
        if($oldemail != $email){
            $ng_emailcheck = mysqli_query($connectdb, "SELECT email FROM ng_customer WHERE email =\"$email\"");
        }

        if(mysqli_fetch_row($ng_customercheck) == NULL ){
		    if(mysqli_fetch_row($ng_emailcheck ) == NULL ){
		        if (!empty($_FILES['image-file']['name'])) {
                    if($type_photo == "image/jpg" || $type_photo == "image/png" || $type_photo == "image/jpeg"){
                        if($size_photo <= 10000000){
                        
                            unlink($oldpath);
                            move_uploaded_file($temp_photo,$path);

                            $ng_customer = mysqli_query($connectdb, "UPDATE ng_customer 
                                                                        SET firstname = \"$firstname\", 
                                                                            lastname = \"$lastname\", 
                                                                            paket = \"$paket\", 
                                                                            alamat = \"$alamat\", 
                                                                            email = \"$email\", 
                                                                            no_telp = \"$no_telp\", 
                                                                            identitas = \"$identitas\", 
                                                                            foto = \"$name_photo\" 
                                                                        WHERE id = \"$customerid\"") ; 
                        
                        }else{ //jika ukuran gambar lebih dari 10 mb
                            echo '<script language="javascript">alert("Ukuran foto tidak boleh lebih dari 10 mb")</script>';
                        }
                    }else{ //jika tipe gambar bukan jpg atau png
                        echo '<script language="javascript">alert("Tipe gambar yang diupload harus JPG atau PNG.")</script>';
                    }
                }else{ 
                    $ng_customer = mysqli_query($connectdb, "UPDATE ng_customer 
                                                                SET firstname = \"$firstname\", 
                                                                    lastname = \"$lastname\", 
                                                                    paket = \"$paket\", 
                                                                    alamat = \"$alamat\", 
                                                                    email = \"$email\", 
                                                                    no_telp = \"$no_telp\", 
                                                                    identitas = \"$identitas\"
                                                                WHERE id = \"$customerid\"") ; 
                }
           
                if($oldpaket != $paket){
                    $radreply = mysqli_query($connectdb, "UPDATE radreply 
                                                            INNER JOIN ng_customer ON ng_customer.username = radreply.username 
                                                            SET value = \"$randaddress\"
                                                            WHERE ng_customer.id = \"$customerid\"");
                    
                    $usedpoolold = mysqli_query($connectdb, "SELECT ng_usedpool.poolid, ng_childpool.available FROM ng_usedpool 
                                                            INNER JOIN ng_customer ON ng_customer.username = ng_usedpool.username
                                                            INNER JOIN ng_childpool ON ng_childpool.id = ng_usedpool.poolid
                                                            WHERE ng_customer.id = \"$customerid\"");

                    $getusedpoolold = mysqli_fetch_assoc($usedpoolold);

                    $update_availableold = mysqli_query($connectdb, "UPDATE ng_childpool SET available = ".$getusedpoolold['available']." + 1 WHERE id = ".$getusedpoolold['poolid']."");

                    $available_childpool = mysqli_query($connectdb, "SELECT available FROM ng_childpool WHERE id =\"$randpooid\"");

                    $getavailable = mysqli_fetch_assoc($available_childpool);
                
                    $update_available = mysqli_query($connectdb, "UPDATE ng_childpool SET available = ".$getavailable['available']." - 1 WHERE id = \"$randpooid\"");

                    $ng_usedpool = mysqli_query($connectdb, "UPDATE ng_usedpool 
                                                                INNER JOIN ng_customer ON ng_customer.username = ng_usedpool.username 
                                                                SET poolid = \"$randpooid\",
                                                                    address = \"$randaddress\"
                                                                WHERE ng_customer.id = \"$customerid\"");
                }
                
                if($oldemail != $email){
                    verifikasiEmail($firstname,$lastname,$username,$email);
                }

		    }else{ //jika data email sudah ada
	    	    echo '<script language="javascript">alert("Email '. $email.' is registered")</script>';
        	}
		}else{ //jika data user sudah ada
	    	    echo '<script language="javascript">alert("User '. $firstname.' '. $lastname .' is registered")</script>';
        }

        echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
   }
  ?>

        