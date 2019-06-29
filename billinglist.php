<?php
include "koneksi.php";
include "check.php";

$start_billing = mysqli_query($connectdb, "SELECT ng_customer.id, 
                                                  CONCAT_WS(' ',ng_customer.firstname, ng_customer.lastname) AS name, 
                                                  ng_kota.kota, 
                                                  ng_node.node, 
                                                  ng_customer.register_date, 
                                                  ng_customer.start_billing 
                                          FROM ng_customer
                                          INNER JOIN ng_kota ON ng_kota.id = ng_customer.kota
                                          INNER JOIN ng_node ON ng_node.nodeid = ng_customer.node
                                          WHERE ng_customer.active = 1");
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

    <h3>Billing </h3>

    <table>
      <thead>
        <tr>
          <th>Nama Pelanggan</th>
          <th>Kota</th>
          <th>Node</th>
          <th>Register Date</th>
          <th>Online Billing</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($dtbilling = mysqli_fetch_assoc($start_billing)){?>

          <tr>
            <td><?php echo $dtbilling['name']; ?></td>
            <td><?php echo $dtbilling['kota']; ?></td>
            <td><?php echo $dtbilling['node']; ?></td>
            <td><?php echo date('d F Y', strtotime($dtbilling['register_date'])); ?></td>
            <td><?php if($dtbilling['start_billing'] != '0000-00-00'){
                          echo date('d F Y', strtotime($dtbilling['start_billing']));
                      }else{
                ?>
                          <form action="" method=post novalidate>
                            <input id="customerid" name="customerid" type="hidden" value="<?php echo $dtbilling['id']; ?>">
                            <input id="register_date" name="register_date" type="hidden" value="<?php echo $dtbilling['register_date']; ?>">
                            <input id="start_billing" name="start_billing" placeholder="Input your start billing" required="required" type="date" value="<?php echo date('Y-m-d'); ?>">           
                            <button id="send" type="submit">Submit</button>                                    
                          </form>

                  <?php  } ?>
            </td>
          </tr>

      <?php } ?>
    
    </tbody>
  </table>
</body>
</html>


	<?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
          $customerid = $_POST['customerid'];
      	  $register_date = $_POST['register_date'];
      	  $start_billing = date('Y-m-d', strtotime($_POST['start_billing']));

      	  if($start_billing >= $register_date){
              $update_billing = mysqli_query($connectdb, "UPDATE ng_customer SET start_billing = \"$start_billing\" WHERE id = \"$customerid\"");
      	  }else{ 
      	       echo '<script language="javascript">alert("Tanggal harus diatas tanggal registrasi")</script>';
          }


          echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
	     }
  ?>