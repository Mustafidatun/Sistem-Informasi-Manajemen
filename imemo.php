<?php
include "koneksi.php";
include "check.php";

$vendorlist = mysqli_query($connectdb, "SELECT id, vendor FROM ng_vendor");
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

    <h2>Internal Memo Form</h2>

    <form action="#" method="post" novalidate>
        
        <label for="vendor">Vendor <span class="required">*</span></label>
        <select id="vendor" type="option" name="vendor" required>
            <option value=''>Pilih</option>
            <?php 
                while ($dtvendor = mysqli_fetch_array($vendorlist))
                {
                    echo "<option value=".$dtvendor['id'].">".$dtvendor['vendor']."</option>";
                }
            ?>   
        </select>
        
        <div id="insert-form">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Merk</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td><button type="button" id="btn-tambah-form"> +add</button></td>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td><input type="text" size="8" class="grdtot" id="grandtt" value="" name="" readonly/></input></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="form-group">            
            <button id="send" type="submit" class="btn btn-success">Submit</button>  
        </div>
    </form>

    <input type="hidden" id="jumlah-form" value="0">
</body>
</html>

            <?php
            if($_SERVER["REQUEST_METHOD"] == "POST") {
        
                $jmlmemo = mysqli_query($connectdb, "SELECT SUM(DISTINCT memoid) AS jmlmemo FROM ng_internalmemo GROUP BY memoid ORDER BY memoid DESC LIMIT 1");
                $dtjmlmemo = mysqli_fetch_array($jmlmemo);
                $dtbarang = $_POST['inputs'];
                $purchasingid = $_SESSION['managerid'];
                $date = date("Y-m-d");
                $memoid = ($dtjmlmemo['jmlmemo']+1).'/IM/'.date('m').'/'.date('Y');

		        foreach ($dtbarang as $dt){
                    $equipmasterid = $dt['equipmasterid'];
                    $price = $dt['price'];
                    $quantity = $dt['qty'];
                    $ng_internalmemo = mysqli_query($connectdb, "INSERT INTO ng_internalmemo (memoid,equipmasterid, price, quantity, purchasingid, date, status) VALUES (\"$memoid\",\"$equipmasterid\", \"$price\", \"$quantity\", \"$purchasingid\", \"$date\", \"0\")");
                }

                echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
            }
            ?>
        

	<!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Memanggil Autocomplete.js -->
    <script src="js/jquery.autocomplete.min.js"></script>
	<script>
        $(document).ready(function(){
            var dtvendor = document.getElementById('vendor');
            var tot = 0;
            $("#btn-tambah-form").click(function(){ 
                if (dtvendor.value !== '') {
                    var jumlah = parseInt($("#jumlah-form").val()); 
                    var nextform = jumlah + 1; 
                    var inside = 
                        "<tr id='row"+nextform+"'>" +
                        "<td><input type='hidden' id='equipmasterid"+nextform+"' name='inputs["+nextform+"][equipmasterid]' value=''><input type='text' id='type"+nextform+"' name='type' placeholder='Nama Type' value=''></td>" +
                        "<td><input type='text' id='merk"+nextform+"' name='merk' placeholder='Nama Merk' value='' readonly></td>" +
                        "<td><input type='text' id='price"+nextform+"' name='inputs["+nextform+"][price]' class='prc'  value='0'></td>" +
                        "<td><input type='text' size='3' id='qty"+nextform+"' name='inputs["+nextform+"][qty]' class='qty'  value='0'></td>" +
                        "<td><input type='text' id='sub_total"+nextform+"' name='sub_total' class='subtot'  placeholder='Sub Total' value='0' readonly><button style='margin-left:15px;' type='button' class='btn btn-danger btn-xs' id='btnremove"+nextform+"'>delete</button></td>"
                        "</tr>"
                    $("table tbody").append(inside);

                    $( "#type"+nextform ).autocomplete({
                            serviceUrl: "autocomplete_typebarang.php?vendor=" + dtvendor.value +"&",    
                            dataType: "JSON",           
                            onSelect: function (suggestion) {
                                $( "#equipmasterid"+nextform ).val("" + suggestion.equipmasterid);
                                $( "#type"+nextform ).val("" + suggestion.type);
                                $( "#merk"+nextform ).val("" + suggestion.merk);
                                $( "#price"+nextform).val("" + suggestion.price);
                            }
                    });

                    $("#btnremove"+nextform).click(function(){
                        var subtotal = document.getElementById('sub_total'+nextform).value;
                        var total = document.getElementById('grandtt').value;
                        updatetotal = total - subtotal;
                        
                        $('.grdtot').val(updatetotal.toFixed(0));
                        $('#row'+nextform).remove();
                    });

                    var $tblrows = $("tbody tr");
                    $tblrows.each(function (index) {
                    var $tblrow = $(this);
                    $(document).on('keyup', '#qty'+nextform, function() {
                        var qtx = $tblrow.find('.qty').val();
                        var prx =  $tblrow.find('.prc').val();
                        var subTotal = parseInt(qtx,10) * parseFloat(prx);
                        if (!isNaN(subTotal)) {
                        $tblrow.find('.subtot').val(subTotal.toFixed(0));
                            var grandTotal = 0;
                            $(".subtot").each(function () {
                            var stval = parseFloat($(this).val());
                                grandTotal += isNaN(stval) ? 0 : stval;
                            });
                            $('.grdtot').val(grandTotal.toFixed(0));
                        }	
                    });
					$(document).on('keyup', '#price'+nextform, function() {
                        var qtx = $tblrow.find('.qty').val();
                        var prx =  $tblrow.find('.prc').val();
                        var subTotal = parseInt(qtx,10) * parseFloat(prx);
                        if (!isNaN(subTotal)) {
                        $tblrow.find('.subtot').val(subTotal.toFixed(0));
                            var grandTotal = 0;
                            $(".subtot").each(function () {
                            var stval = parseFloat($(this).val());
                                grandTotal += isNaN(stval) ? 0 : stval;
                            });
                            $('.grdtot').val(grandTotal.toFixed(0));
                        }	
                    });
                });	   
                    $("#jumlah-form").val(nextform); 
                }else{
                    alert("Vendor tidak boleh kosong!")
                }
            });

        });
        </script>