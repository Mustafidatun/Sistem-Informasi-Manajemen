<?php
include "koneksi.php";
include "check.php";

$managerid = $_SESSION['managerid'];
$memoid = $_GET['memoid'];
$vendor = $_GET['vendor'];
$date = $_GET['date'];
$poid = $_GET['poid'];
$approve_date = $_GET['approve_date'];
$username = $_GET['username'];

$memolist = mysqli_query($connectdb, "SELECT ng_equipmaster.type, ng_equipmaster.merk, 
                                            ng_equipmaster.vol,
                                            ng_vendor.vendor, 
                                            ng_internalmemo.price,
                                            ng_internalmemo.quantity, 
                                            (ng_internalmemo.price*ng_internalmemo.quantity) AS subtotal
                                        FROM ng_internalmemo 
                                        INNER JOIN ng_purchaseorder ON ng_purchaseorder.internalmemoid = ng_internalmemo.id
                                        INNER JOIN ng_equipmaster ON ng_equipmaster.id = ng_internalmemo.equipmasterid
                                        INNER JOIN ng_vendor ON ng_vendor.id = ng_equipmaster.vendorid
                                        WHERE ng_internalmemo.memoid = \"$memoid\" AND 
                                                ng_internalmemo.date = \"$date\" AND 
                                                ng_vendor.vendor = \"$vendor\" AND 
                                                ng_purchaseorder.poid = \"$poid\" AND 
                                                ng_internalmemo.approve_date = \"$approve_date\" AND
                                                ng_internalmemo.status = 1 ");

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

    <h3>Detail Memo</h3>

    <table>
      <tr>
        <td><label>Memo Id</label></td>
        <td> : </td>
        <td><?php echo $memoid; ?></td>
      </tr>
      <tr>
        <td><label>Vendor</label></td>
        <td> : </td>
        <td><?php echo $vendor;?></td>
      </tr>
      <tr>
        <td><label>Date</label></td>
        <td> : </td>
        <td><?php echo date('d F Y', strtotime($date));?></td>
      </tr>
      <tr>
        <td><label>Purchase Order id</label></td>
        <td> : </td>
        <td><?php echo $poid;?></td>
      </tr>
      <tr>
        <td><label>Approve Date</label></td>
        <td> : </td>
        <td><?php echo date('d F Y', strtotime($approve_date));?></td>
      </tr>
      <tr>
        <td><label>Approve By</label></td>
        <td> : </td>
        <td><?php echo $username;?></td>
      </tr>
    </table>
                        
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Merk</th>
          <th>Vendor</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Vol</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody align="center">

        <?php 
          $total = 0;
          while ($dtmemo = mysqli_fetch_assoc($memolist)){
            $total += $dtmemo['subtotal'];
        ?>

        <tr >
          <td><?php echo $dtmemo['type']; ?></td>
          <td><?php echo $dtmemo['merk']; ?></td>
          <td><?php echo $dtmemo['vendor']; ?></td>
          <td align="right"><?php echo $dtmemo['price']; ?></td>
          <td size="3"><?php echo $dtmemo['quantity']; ?></td>
          <td><?php echo $dtmemo['vol']; ?></td>
          <td align="right"><?php echo $dtmemo['subtotal']; ?></td>
        </tr>

        <?php } ?>

      </tbody>
      <tfoot>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Total</td>
          <td align="right"><?php echo $total; ?></td>
        </tr>
      </tfoot>
    </table>

    
    <form action="" method=post novalidate>
    <input id="poid" name="poid" type="hidden" value="<?php echo $poid; ?>">
    <button id="send" type="submit" class="btn btn-success">Submit</button>  
    </form>
    
</body>
</html>


        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
          
            $update_internalmemo = mysqli_query($connectdb, "UPDATE ng_internalmemo SET status = \"2\", approve_date = DATE(NOW()), financeid = \"$managerid\" WHERE memoid = \"$memoid\" AND date = \"$date\" AND ng_internalmemo.status = 1 ");

            echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
        }
        ?>
        