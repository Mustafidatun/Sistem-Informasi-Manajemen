<?php
    include "../build/mariadb/db.php";
    include "../build/mariadb/check.php";
?>

<?php
      if($_SERVER["REQUEST_METHOD"] == "POST") {
 
        $skema = $_POST['skema'];
        $paketname = $_POST['paketname']; 
        $paketdesc = $_POST['paketdesc']; 
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
            $ng_paket = mysqli_query($connectdb, "INSERT INTO ng_paket (skema, paket, kd_prod,description, price1, price2, price3) VALUES (\"$skema\" ,\"$paketname\",\"$kd_prod\",\"$paketdesc\",\"$price1\" ,\"$price2\" ,\"$price3\")");
			echo $ng_paket;
		}else if($skema == 2){
            $ng_paket = mysqli_query($connectdb, "INSERT INTO ng_paket (skema, paket, kd_prod,description, price4) VALUES (\"$skema\" ,\"$paketname\" ,\"$kd_prod\" ,\"$paketdesc\",\"$price4\")");
        echo $ng_paket;
		}else if($skema == 3){
            $ng_paket = mysqli_query($connectdb, "INSERT INTO ng_paket (skema, paket, kd_prod,description, price3, price4) VALUES (\"$skema\" ,\"$paketname\" ,\"$kd_prod\" ,\"$paketdesc\",\"$price5\" ,\"$price6\")");
        echo $ng_paket;
		}     
		header ("location:paketlist");
       }
	   
       ?>
	   
<!DOCTYPE html>
<html>
    <?php 
    include "head.html"; 
    ?>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <!-- sidebar navigation -->  
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
                    <!-- sidebar navigation -->
                </div>
                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Create Packet Scheme</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                            <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Settings 1</a></li>
                                        <li><a href="#">Settings 2</a></li>
                                    </ul>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <form class="form-horizontal form-label-left" action="#" method=post>
									<div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3" for="paketname">Paket Name <span class="required">*</span>
                                            </label>
                                            <div class="col-md-4 col-sm-4">
                                            <input type="text" id="paketname" name="paketname" required="required" class="form-control col-md-7 col-xs-12" placeholder="Input your Paket Name">
                                            </div>
                                        </div>
									<div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3" for="paketdesc">Paket Description <span class="required">*</span>
                                            </label>
                                            <div class="col-md-4 col-sm-4">
                                            <textarea type="text" id="paketdesc" name="paketdesc" required="required" class="form-control col-md-7 col-xs-12" placeholder="Input your Description"></textarea>
                                            </div>
                                        </div>	
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3" for="skema">Skema Harga<span class="required">*</span>
                                            </label>
                                            <div class="col-md-4 col-sm-4">
											
											A: <input type="radio" name="skema"  value="1" checked="1" required />
											B: <input type="radio" name="skema"  value="2" />
											C: <input type="radio" name="skema" value="3" />
                                            </div>
                                        </div>
                                        
                                        <!-- <div id="log"></div> -->
										<div class="form-group" id="skema_A" style="display:block;">
										<label class="control-label col-md-3 col-sm-3" for="skema">Skema Description<span class="required">*</span>
                                        </label>
                                        <div  class="col-md-6 col-sm-6" >
										<p>Skema harga untuk tipe A, Pelanggan akan dibebankan tarif khusus pada 3 bulan pertama. Setelah itu bulan ke-4 sampai dengan bulan ke-12
										Pelanggan akan dibebankan tarif dasar. Setelah masuk bulan ke-13 dan seterusnya, pelanggan akan mendapat harga khusus</p>
                                        </div>
										</div>
										<div class="form-group" id="skema_A1" style="display:block;">
										<label class="control-label col-md-3 col-sm-3" for="skema">Harga<span class="required">*</span>
                                        </label>
										<div  class="col-md-4 col-sm-4" >
										<table border=0>
										<tr>
										<td class="col-md-5 col-sm-5">
										<label  for="price1">Bulan 1-3
                                        </label></td>
										<td><span >:</span></td>
										<td class="col-md-6 col-sm-6">
										<input type="text" id="price1" name="price1" placeholder="500000" >
										</td>
										<tr>
										<td class="col-md-5 col-sm-5">
										<label for="price2" >Bulan 4-12
                                        </label></td>
										<td><span >:</span></td>
										<td class="col-md-6 col-sm-6">
										<input type="text" id="price2" name="price2" placeholder="300000" >
										</td>
										<tr>
										<td class="col-md-5 col-sm-5">
										<label  for="price3">Bulan 13-dst
                                        </label></td>
										<td><span >:</span></td>
										<td class="col-md-6 col-sm-6">
										<input type="text" id="price3" name="price3" placeholder="250000" >
										</td>
										</tr>
										</table>
										</div>
										</div>
                                        <div id="skema_B" class="form-group" style="display:none;">					
										<label class="control-label col-md-3 col-sm-3" for="skema">Skema Description<span class="required">*</span>
										</label>
										<div  class="col-md-6 col-sm-6" >
										<p>Skema harga untuk tipe B, Pelanggan dibebankan tarif flat selama berlangganan</p>
										</div>
										</div>
                                        <div class="form-group" id="skema_B1" style="display:none;">
                                        <label class="control-label col-md-3 col-sm-3" for="price4">Harga <span class="required">*</span>
                                        </label>
                                        <div  class="col-md-4 col-sm-4" >
										<table border=0>
										<tr>
										<td class="col-md-5 col-sm-5">
										<label  for="price4">Bulanan
                                        </label></td>
										<td><span >:</span></td>
										<td class="col-md-6 col-sm-6">
										<input type="text" id="price4" name="price4" placeholder="500000" >
										</td>
										</tr>
										</table>
										</div>
                                        </div>
                                            
										<div class="form-group" id="skema_C" style="display:none;">
										<label class="control-label col-md-3 col-sm-3" for="skema">Skema Description<span class="required">*</span>
                                        </label>
                                        <div  class="col-md-4 col-sm-4" >
										<p>Skema harga untuk tipe C, Pelanggan akan dibebankan tarif khusus selama 1 tahun. Setelah masuk bulan ke-13 dan seterusnya, pelanggan akan mendapat harga normal</p>
                                        </div>
										</div>
										<div class="form-group" id="skema_C1" style="display:none;">
										<label class="control-label col-md-3 col-sm-3" for="skema">Harga<span class="required">*</span>
                                        </label>
										<div  class="col-md-4 col-sm-4" >
										<table border=0>
										<tr>
										<td class="col-md-5 col-sm-5">
										<label  for="price1">Bulan 1-12
                                        </label></td>
										<td><span >:</span></td>
										<td class="col-md-6 col-sm-6">
										<input type="text" id="price5" name="price5" placeholder="500000" >
										</td>
										</tr>
										<tr>
										<td class="col-md-5 col-sm-5">
										<label  for="price3">Bulan 13-dst
                                        </label></td>
										<td><span >:</span></td>
										<td class="col-md-6 col-sm-6">
										<input type="text" id="price6" name="price6" placeholder="250000" >
										</td>
										</tr>
										</table>
										</div>
										</div>

                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                            <button type="reset" class="btn btn-primary">Cancel</button>
                                            <button id="send" type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->  

    

        </div>
    </div>
	<!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
	<!-- Memanggil jQuery.js -->
	<!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script type="text/javascript">
    
    var skemaA = document.getElementById('skema_A');
    var skemaA1 = document.getElementById('skema_A1');
    var skemaB = document.getElementById('skema_B');
    var skemaB1 = document.getElementById('skema_B1');
    var skemaC = document.getElementById('skema_C');
    var skemaC1 = document.getElementById('skema_C1');

	$( "input:radio[name=skema]" ).click(function() {
	var skemais = $("input[name=skema]:checked").val();

	if( skemais == 1){
                skemaA.style.visibility='visible';
                skemaA1.style.visibility='visible';
                skemaA.style.display ='block';
                skemaA1.style.display ='block';
                skemaB.style.visibility='hidden';
                skemaB1.style.visibility='hidden';
                skemaB.style.display ='none';
                skemaB1.style.display ='none';
                skemaC.style.visibility='hidden';
                skemaC1.style.visibility='hidden';
                skemaC.style.display ='none';
                skemaC1.style.display ='none';
                
    }else if(skemais == 2){
            skemaA.style.visibility='hidden';
            skemaA1.style.visibility='hidden';
            skemaA.style.display ='none';
            skemaA1.style.display ='none';
            skemaB.style.visibility='visible';
            skemaB1.style.visibility='visible';
            skemaB.style.display ='block';
            skemaB1.style.display ='block';
            skemaC.style.visibility='hidden';
            skemaC1.style.visibility='hidden';
            skemaC.style.display ='none';
            skemaC1.style.display ='none';
    }else if(skemais == 3){
            skemaA.style.visibility='hidden';
            skemaA1.style.visibility='hidden';
            skemaA.style.display ='none';
            skemaA1.style.display ='none';
            skemaB.style.visibility='hidden';
            skemaB1.style.visibility='hidden';
            skemaB.style.display ='none';
            skemaB1.style.display ='none';
            skemaC.style.visibility='visible';
            skemaC1.style.visibility='visible';
            skemaC.style.display ='block';
            skemaC1.style.display ='block';
    }else{
            skemaA.style.display ='none';
            skemaA1.style.display ='none';
            skemaB.style.display ='none';
            skemaB1.style.display ='none';
            skemaC.style.display ='none';
            skemaC1.style.display ='none';
    }
	});



</script>
</body>
</html>