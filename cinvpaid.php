<!DOCTYPE HTML>
<?php
include "../build/mariadb/db.php";
include "../build/mariadb/check.php";

//$pool = mysqli_query($connectdb, "select id,name from ng_pool where name!='suspend' and nodeid='0'");
$invoice = $_GET['ADJLAsjljsKDSLSJd'];

$cinv = mysqli_query ($connectdb, "select * from ng_invoice where invoiceid=\"$invoice\"");

if($_SERVER["REQUEST_METHOD"] == "POST") {
	  $paiddate = date('Y-m-d',strtotime($_POST['paiddate']));
      $invc = $_POST['idiv']; 
      $ng_cq = "update ng_invoice set paydate=\"$paiddate\" , status=1 where id=\"$invc\"" ; 
	  mysqli_query ($connectdb, $ng_cq );
	  header ("location:customer_invoice");
   }
?>
<html lang="en">
  <?php 
  require_once 'head.html';
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
        <!-- page content -->
        <div class="right_col" role="main">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Customer Invoice Paid</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <form class="form-horizontal form-label-left" action="#" method=post >
                      <span class="section">mark customer invoice as paid</span>
						<?php while ($datacq = mysqli_fetch_row($cinv)){?>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Invoice Number <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="idiv" class="form-control col-md-7 col-xs-12" name="idiv" type="hidden" value="<?php echo $datacq[0]; ?>">
                          <input id="invid" class="form-control col-md-7 col-xs-12" name="invid" readonly type="text" value="<?php echo $datacq[1]; ?>">
                        </div>
					  </div>
					  <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">Customer Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="alamat" class="form-control col-md-7 col-xs-12" name="fname" readonly value="<?php 
						  $cid = $datacq[2];
						  $customer = mysqli_fetch_row(mysqli_query($connectdb, "select firstname, lastname from ng_customer where id=\"$cid\""));
						  echo $customer[0].' '.$customer[1]; 
						  ?>" required="required" type="text">
                        </div>
                      </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" >Invoice Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="alamat" class="form-control col-md-7 col-xs-12" name="dateinv" readonly value="<?php echo $datacq[3]; ?>" type="text">
                        </div>
                      </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" >Invoice Due Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="alamat" class="form-control col-md-7 col-xs-12" name="duedate" readonly value="<?php echo $datacq[4]; ?>" type="text">
                        </div>
                      </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Invoice </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="phone" class="form-control col-md-7 col-xs-12" name="phone" readonly value="<?php echo 'IDR '.number_format($datacq[6]); ?>" type="text" >
                        </div>
                      </div>
                     <div class="item form-group">
					 <label class="control-label col-md-3 col-sm-3 col-xs-12">Payment Date </label>
					 <div class="col-md-6 col-sm-6 col-xs-12 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="single_cal2" name="paiddate" aria-describedby="inputSuccess2Status2">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                     </div>
					 </div>
	                      <div class="ln_solid"></div>
						<?php } ?>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <a href="customer_invoice" class="btn btn-primary">Cancel</a>
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


        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
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
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../vendors/starrr/dist/starrr.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	<!-- Image Upload View-->
	

	
  </body>
</html>