<?php
include "koneksi.php";
include "check.php";

$memolist = mysqli_query($connectdb, "SELECT DISTINCT ng_purchaseorder.poid, 
                                                      ng_internalmemo.memoid, 
                                                      ng_internalmemo.date, 
                                                      ng_internalmemo.approve_date, 
                                                      SUM(ng_internalmemo.price*ng_internalmemo.quantity)AS total, 
                                                      ng_vendor.vendor, 
                                                      ng_userlogin.username 
                                      FROM ng_internalmemo 
                                      INNER JOIN ng_purchaseorder ON ng_purchaseorder.internalmemoid = ng_internalmemo.id
                                      INNER JOIN ng_equipmaster ON ng_equipmaster.id = ng_internalmemo.equipmasterid
                                      INNER JOIN ng_vendor ON ng_vendor.id = ng_equipmaster.vendorid
                                      INNER JOIN ng_userlogin ON ng_userlogin.id = ng_internalmemo.userid
                                      WHERE ng_internalmemo.status = 1
                                      GROUP BY memoid ORDER BY date ASC");

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

    <h3>Memo </h3>

    <table>
      <thead>
        <tr>
          <th>Memo Id</th>
          <th>Date</th>
          <th>Purchase Order Id</th>
          <th>Approve Date</th>
          <th>Approve By</th>
          <th>Total</th>
          <th>Detail Memo</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($dtmemo = mysqli_fetch_assoc($memolist)){?>

          <tr>
            <td><?php echo $dtmemo['memoid']; ?></td>
            <td><?php echo date('d F Y', strtotime($dtmemo['date'])); ?></td>
            <td><?php echo $dtmemo['poid']; ?></td>
            <td><?php echo date('d F Y', strtotime($dtmemo['approve_date'])); ?></td>
            <td><?php echo $dtmemo['username']; ?></td>
            <td align="left"><?php echo $dtmemo['total']; ?></td>
            <td><button id="send" type="submit" onclick="location.href = 'imemofinancedetail.php?memoid=<?php echo $dtmemo['memoid']; ?>&vendor=<?php echo $dtmemo['vendor']; ?>&date=<?php echo $dtmemo['date']; ?>&poid=<?php echo $dtmemo['poid']; ?>&approve_date=<?php echo $dtmemo['approve_date']; ?>&username=<?php echo $dtmemo['username']; ?>'">Detail</button>
            </td>
          </tr>

        <?php } ?>
      
      </tbody>
    </table>
</body>
</html>