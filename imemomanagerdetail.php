<?php
include "koneksi.php";
include "check.php";

$userid = $_SESSION['userid'];
$memoid = $_GET['memoid'];
$vendor = $_GET['vendor'];
$date = $_GET['date'];

$memolist = mysqli_query($connectdb, "SELECT ng_internalmemo.id AS im_id, 
                                            ng_equipmaster.id AS equipmasterid, 
                                            ng_equipmaster.type, ng_equipmaster.merk, 
                                            ng_equipmaster.vol, ng_vendor.vendor, 
                                            ng_internalmemo.price, 
                                            ng_internalmemo.quantity, 
                                            (ng_internalmemo.price*ng_internalmemo.quantity) AS subtotal
                                        FROM ng_internalmemo 
                                        INNER JOIN ng_equipmaster ON ng_equipmaster.id = ng_internalmemo.equipmasterid
                                        INNER JOIN ng_vendor ON ng_vendor.id = ng_equipmaster.vendorid
                                        WHERE ng_internalmemo.memoid = \"$memoid\" AND 
                                                ng_internalmemo.date = \"$date\" AND 
                                                ng_vendor.vendor = \"$vendor\" AND 
                                                ng_internalmemo.status = 0");

?>

<!DOCTYPE html>
<html>
<head>
    <title> </title>
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
                <td>Memo Id</td>
                <td> : </td>
                <td><?php echo $memoid; ?></td>
            </tr>
            <tr>
                <td>Vendor</td>
                <td> : </td>
                <td><?php echo $vendor;?></td>
            </tr>
            <tr>
                <td>Date</td>
                <td> : </td>
                <td><?php echo date('d F Y', strtotime($date));?></td>
            </table>
                    
            <form action="#" method="post" novalidate>   
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total = 0;
                        $index = 0;
                        while ($dtmemo = mysqli_fetch_assoc($memolist)){
                            $total += $dtmemo['subtotal'];
                        ?>
                    
                    <tr >
                        <td><?php echo $dtmemo['type']; ?></td>
                        <td><?php echo $dtmemo['merk']; ?></td>
                        <td><?php echo $dtmemo['vendor']; ?></td>
                        <td><?php echo $dtmemo['price']; ?></td>
                        <td><?php echo $dtmemo['quantity']; ?></td>
                        <td><?php echo $dtmemo['vol']; ?></td>
                        <td><?php echo $dtmemo['subtotal']; ?></td>
                        <td>
                            <input type="checkbox" name="inputs[<?php echo $index; ?>][im_id]" value="<?php echo $dtmemo['im_id']; ?>" checked>
                        </td>
                    </tr>
                    
                    <?php 
                        $index++;
                        } 
                    ?>
                        
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td><?php echo $total; ?></td>
                    </tr>
                </tfoot>
            </table>
            <div class="form-group">  
                <button id="send" type="submit">Submit</button>  
            </div>

        </form>
</body>
</html>

        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {

          $jmlpo = mysqli_query($connectdb, "SELECT SUM(DISTINCT poid) AS jmlpo FROM ng_purchaseorder GROUP BY poid ORDER BY poid DESC LIMIT 1");
          $dtjmlpo = mysqli_fetch_array($jmlpo);

          $dtchecklist_barang = $_POST['inputs'];
          
          // $date = date("Y-m-d");
          $poid = ($dtjmlpo['jmlpo']+1).'/PO/'.date('m').'/'.date('Y');

		        foreach ($dtchecklist_barang as $dt){

                    $im_id = $dt['im_id'];
                    
                    $ng_internalmemo = mysqli_query($connectdb, "SELECT equipmasterid, price FROM ng_internalmemo WHERE id = \"$im_id\"");
                    $dtim = mysqli_fetch_array($ng_internalmemo);
                    $equipmasterid = $dtim['equipmasterid'];
                    $price = $dtim['price'];

                    $update_internalmemo = mysqli_query($connectdb, "UPDATE ng_internalmemo SET status = \"1\", approve_date = DATE(NOW()), userid = \"$userid\" WHERE id = \"$im_id\"");

                    $checkprice = mysqli_query($connectdb, "SELECT price FROM ng_equipmaster 
                                                            WHERE ng_equipmaster.id = \"$equipmasterid\" AND price NOT IN ( 
                                                                SELECT price FROM ng_internalmemo WHERE ng_internalmemo.id = \"$im_id\")");

                    if(mysqli_fetch_row($checkprice) != NULL ){
                      $update_equipmaster = mysqli_query($connectdb, "UPDATE ng_equipmaster SET price = \"$price\" WHERE id = \"$equipmasterid\"");
                    }

                    $ng_po = mysqli_query($connectdb, "INSERT INTO ng_purchaseorder(internalmemoid, poid) VALUES (\"$im_id\", \"$poid\")") ; 

                    // $approval_internalmemo = mysqli_query($connectdb, "CALL spApprovalInternalMemo(\"$userid\",\"$im_id\")");
                }

                echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
            }
            ?>
        