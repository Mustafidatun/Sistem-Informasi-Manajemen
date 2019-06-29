<?php
include "koneksi.php";
include "check.php";

$customerid = $_GET['id'];

$customerlist = mysqli_query($connectdb, "SELECT ng_customer.firstname, 
                                                ng_customer.lastname, 
                                                ng_customer.username, 
                                                ng_customer.alamat, 
                                                ng_customer.email,
                                                ng_customer.no_telp, 
                                                ng_customer.identitas, 
                                                ng_customer.foto, 
                                                ng_kota.kota, 
                                                ng_node.node, 
                                                ng_paket.paket
                                            FROM ng_customer
                                            INNER JOIN ng_kota ON ng_kota.id = ng_customer.kota
                                            INNER JOIN ng_node ON ng_node.nodeid = ng_customer.node
                                            INNER JOIN ng_paket ON ng_paket.id = ng_customer.paket
                                            WHERE ng_customer.id = \"$customerid\"");
$dtcustomerlist = mysqli_fetch_assoc($customerlist);

$customerinvoicelist = mysqli_query($connectdb, "SELECT ng_invoice.invoiceid, 
                                                        ng_invoice.date, 
                                                        ng_invoice.due_date, 
                                                        ng_invoice.paydate,
                                                        ng_invoice.ammount 
                                                  FROM ng_invoice
                                                  INNER JOIN ng_customer ON  ng_customer.id = ng_invoice.customerid
                                                  WHERE ng_customer.id = \"$customerid\"");

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

     <h3><?php echo $dtcustomerlist['firstname'].' '.$dtcustomerlist['lastname']; ?></h3>

      <ul>
        <li><?php echo $dtcustomerlist['alamat'].', '.$dtcustomerlist['kota']; ?></li>
        <li><?php echo $dtcustomerlist['email']; ?> </li>
        <li><?php echo $dtcustomerlist['no_telp']; ?> </li>
        <li><?php echo $dtcustomerlist['paket']; ?> </li>
      </ul>

      <a href="customeredit.php?id=<?php echo $customerid; ?>">Edit Profile</a>
      <br />

      <h2>User Activity Report</h2>

      <h4>Invoice</h4>
      <table>
        <thead>
          <tr>
            <th>No.</th>
            <th>Invoice Number</th>
            <th>Date Ammount</th>
            <th>Due Date</th>
            <th>Pay Date</th>
            <th>Ammount</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $i = 1;
            while ($dtcustomerinvoice = mysqli_fetch_assoc($customerinvoicelist)){ 
          ?>

          <tr>
            <th scope="row"><?php echo $i++; ?></th>
            <td><a href="https://sidara.imax5.id/invoice?ADJLAsjljsKDSLSJd=<?php echo $dtcustomerinvoice['invoiceid']; ?>&ZFhObGNtNWhiV1U9=<?php echo $dtcustomerlist['username']; ?>&SLSJdKASdaE67daSE21=<?php echo $dtcustomerinvoice['ammount']; ?>" target="_blank"><?php echo $dtcustomerinvoice['invoiceid']; ?></td>

            <td><?php echo $dtcustomerinvoice['date']; ?></td>
            <td><?php echo $dtcustomerinvoice['due_date']; ?></td>
            <td><?php echo $dtcustomerinvoice['paydate']; ?></td>
            <td><?php echo $dtcustomerinvoice['ammount']; ?></td>
          </tr>

          <?php
            }
          ?>

        </tbody>
      </table>



      <h4>KTP/SIM/Pasport</h4>
      <img class="img-responsive avatar-view" src="foto/<?php echo $dtcustomerlist['foto']; ?>" alt="Avatar" title="Change the avatar">
</body>
</html>
