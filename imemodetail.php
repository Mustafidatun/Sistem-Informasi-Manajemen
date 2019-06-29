<?php
include "koneksi.php";
include "check.php";

$memoid = $_GET['memoid'];
$vendor = $_GET['vendor'];
$date = $_GET['date'];

$memolist = mysqli_query($connectdb, "SELECT ng_equipmaster.type, 
                                            ng_equipmaster.merk, 
                                            ng_internalmemo.price, 
                                            ng_internalmemo.quantity, 
                                            ng_equipmaster.vol,
                                            (ng_internalmemo.price*ng_internalmemo.quantity) AS subtotal, 
                                            ng_internalmemo.status,
                                            IFNULL(ng_userlogin.username, '-') AS username_im, 
                                            IFNULL(ng_internalmemo.approve_date, '-') AS approve_date, 
                                            IFNULL(ng_manager.username, '-') AS username_po, 
                                            ng_vendor.id
                                        FROM ng_internalmemo 
                                        INNER JOIN ng_equipmaster ON ng_equipmaster.id = ng_internalmemo.equipmasterid
                                        INNER JOIN ng_vendor ON ng_vendor.id = ng_equipmaster.vendorid
                                        LEFT JOIN ng_userlogin ON  ng_userlogin.id = ng_internalmemo.userid 
                                        LEFT JOIN ng_manager ON ng_manager.id = ng_internalmemo.financeid
                                        WHERE ng_internalmemo.memoid = \"$memoid\" AND 
                                              ng_internalmemo.date = \"$date\" AND 
                                              ng_vendor.vendor = \"$vendor\"");

$cekstatus = mysqli_query($connectdb, "SELECT ng_internalmemo.status
                                      FROM ng_internalmemo 
                                      INNER JOIN ng_purchaseorder ON ng_purchaseorder.internalmemoid = ng_internalmemo.id
                                      WHERE ng_internalmemo.memoid = \"$memoid\"");
$status = mysqli_fetch_assoc($cekstatus);   

if($status['status'] == 0){
  $persen = '0%';
}else if($status['status'] == 1){
  $persen = '35%';
}else if($status['status'] == 2){
  $persen = '70%';
}else if($status['status'] == 3){
  $persen = '100%';
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

    <h3>Detail Memo </h3>

    <table>
      <tr>
        <td><label class="control-label" >Memo Id</label></td>
        <td> : </td>
        <td><?php echo $memoid; ?></td>
      </tr>
      <tr>
        <td><label class="control-label" >Vendor</label></td>
        <td> : </td>
        <td><?php echo $vendor;?></td>
      </tr>
      <tr>
        <td><label class="control-label" >Date</label></td>
        <td> : </td>
        <td><?php echo date('d F Y', strtotime($date));?></td>
      </tr>
      <tr>
        <td><label class="control-label" >Proses</label></td>
        <td> : </td>
        <td class="project_progress">
          <div class="progress progress_sm">
            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $persen ?>"></div>
          </div>
          <small><?php echo $persen ?>Complete</small>
        </td>
      </tr>
    </table>

    <table>
      <thead>
        <tr>
          <th></th>
          <th>Type</th>
          <th>Merk</th>
          <th>Approve Date</th>
          <th>Manager</th>
          <th>Finance</th>
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
          $vendorid = $dtmemo['id'];

          if($dtmemo['status'] != 2){
            echo "<tr>
                    <td></td>";
          }else{
            echo "<tr>
                    <td><a href='#/check-square'><i class='fa fa-check-square'></i></a></td>";
          }

        ?>

        <td><?php echo $dtmemo['type']; ?></td>
        <td><?php echo $dtmemo['merk']; ?></td>
        <td><?php echo $dtmemo['approve_date']; ?></td>
        <td><?php echo $dtmemo['username_im']; ?></td>
        <td><?php echo $dtmemo['username_po']; ?></td>
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
        <td></td>
        <td></td>
        <td></td>
        <td>Total</td>
        <td align="right"><?php echo $total; ?></td>
      </tr>
    </tfoot>
  </table>

  <?php if($status['status'] == 2) { ?>
    
    <button id='send' type='submit' onclick="location.href = 'purchaseorderreg.php?memoid=<?php echo $memoid; ?>'">Purchase Order</button>
                    
  <?php } ?>
  
</body>
</html>


					
                        
                    
                  