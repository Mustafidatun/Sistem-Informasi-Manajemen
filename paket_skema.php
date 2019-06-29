<?php
    include "koneksi.php";
    include "check.php";
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

    <h2>Paket Skema</h2>

    <form action="#" method=post>
                      
        <label  for="skema">Skema <span class="required">*</span></label>
        <select id="skema" type="option" name="skema" required>
            <option value=''>Pilih</option> 
            <option value='1'>Skema A</option> 
            <option value='2'>Skema B</option> 
            <option value='3'>Skema C</option> 
        </select>
                                            
        <label for="paketname">Paket Name <span class="required">*</span></label>
        <input type="text" id="paketname" name="paketname" required="required" placeholder="Input your Paket Name">
                                         
        <div id="skema_A" style="display:none;">
            <table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Bulan ke</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>1 s/d 3</td>
                        <td><input type="text" id="price1" name="price1"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>4 s/d 12</td>
                        <td><input type="text" id="price2" name="price2"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>13 s/d Bulan Berikutnya</td>
                        <td><input type="text" id="price3" name="price3" class="form-control col-md-7 col-xs-12"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="skema_B" style="display:none;">
	        <label  for="price4">Price <span class="required">*</span></label>
	        <input type="text" id="price4" name="price4">
	    </div>

        <div id="skema_C" style="display:none;">
            <table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Bulan ke</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Annual s/d 12</td>
                        <td><input type="text" id="price5" name="price5"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>13 s/d Bulan Berikutnya</td>
                        <td><input type="text" id="price6" name="price6"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="reset">Cancel</button>
        <button id="send" type="submit">Submit</button>

        </form>
</body>
</html>


    <?php
      if($_SERVER["REQUEST_METHOD"] == "POST") {
 
        $skema = $_POST['skema'];
        $paketname = $_POST['paketname']; 
        $price1 = $_POST['price1']; 
        $price2 = $_POST['price2']; 
        $price3 = $_POST['price3']; 
        $price4 = $_POST['price4']; 
        $price5 = $_POST['price5']; 
        $price6 = $_POST['price6']; 

        $jmlpaket = mysqli_query($connectdb, "SELECT COUNT(id) as jmlpaket FROM ng_paket");
        $dtjmlpaket  = mysqli_fetch_array($jmlpaket);
        $kd_prod = substr(date("Y"),-2).'1'.$skema.($dtjmlpaket['jmlpaket']+1);
        
        if($skema == 1){
            $ng_paket = mysqli_query($connectdb, "INSERT INTO ng_paket (skema, paket, kd_prod, price1, price2, price3) VALUES (\"$skema\" ,\"$paketname\",\"$kd_prod\",\"$price1\" ,\"$price2\" ,\"$price3\")");
        }else if($skema == 2){
            $ng_paket = mysqli_query($connectdb, "INSERT INTO ng_paket (skema, paket, kd_prod, price4) VALUES (\"$skema\" ,\"$paketname\" ,\"$kd_prod\" ,\"$price4\")");
        }else if($skema == 3){
            $ng_paket = mysqli_query($connectdb, "INSERT INTO ng_paket (skema, paket, kd_prod, price3, price4) VALUES (\"$skema\" ,\"$paketname\" ,\"$kd_prod\" ,\"$price5\" ,\"$price6\")");
        }     

        echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
       }
       ?>

	<!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript">
    
    var skemaA = document.getElementById('skema_A');
    var skemaB = document.getElementById('skema_B');
    var skemaC = document.getElementById('skema_C');
    var skema = document.getElementById("skema");
    
    document.querySelector("#skema").addEventListener("change",function () {
        if(skema.value == 1){
                skemaA.style.visibility='visible';
                skemaA.style.display ='block';
                skemaB.style.visibility='hidden';
                skemaB.style.display ='none';
                skemaC.style.visibility='hidden';
                skemaC.style.display ='none';
                
        }else if(skema.value == 2){
            skemaA.style.visibility='hidden';
            skemaA.style.display ='none';
            skemaB.style.visibility='visible';
            skemaB.style.display ='block';
            skemaC.style.visibility='hidden';
            skemaC.style.display ='none';
        }else if(skema.value == 3){
            skemaA.style.visibility='hidden';
            skemaA.style.display ='none';
            skemaB.style.visibility='hidden';
            skemaB.style.display ='none';
            skemaC.style.visibility='visible';
            skemaC.style.display ='block';
        }else{
            skemaA.style.display ='none';
            skemaB.style.display ='none';
            skemaC.style.display ='none';
        }
    });


</script>