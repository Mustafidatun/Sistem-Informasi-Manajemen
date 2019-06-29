<!DOCTYPE html>

<?php
include "koneksi.php";
include "check.php";

$customerinvoicelist = mysqli_query($connectdb, "SELECT ng_customer.id AS customerid, 
                                                        CONCAT_WS(' ',ng_customer.firstname, ng_customer.lastname) AS name, 
                                                        ng_customer.register_date, 
                                                        ng_invoice.invoiceid, 
                                                        ng_invoice.date, 
                                                        ng_invoice.due_date, 
                                                        ng_invoice.paydate,
                                                        ng_invoice.ammount 
                                                FROM ng_customer
                                                LEFT JOIN ng_invoice ON ng_invoice.customerid = ng_customer.id");
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

  <h3>Customer Invoice</h3>

  <form action="" method="post" enctype="multipart/form-data">
                      
    <label for="file_csv">CSV File </label> 
    <input id="uploadbtncsv" type="file" name="file_csv" accept=".csv">
    <input id="uploadfilecsv" type="text" readonly="readonly" placeholder="Choose File...">
                        
    <label for="file_excel">Excel File </label> 
    <input id="uploadbtnexcel" type="file" name="file_excel" accept=".xls">
    <input id="uploadfileexcel" type="text" readonly="readonly" placeholder="Choose File...">
    
    <button type="reset">Cancel</button>
    <button id="send" type="submit">Submit</button>
                      
  </form>
                    
  <table>
    <thead>
      <tr>
        <th>Nama Pelanggan</th>
        <th>Register Date</th>
        <th>Invoice Number</th>
        <th>Date Ammount</th>
        <th>Due Date</th>
        <th>Pay Date</th>
        <th>Ammount</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        while ($dtcustomerinvoice = mysqli_fetch_assoc($customerinvoicelist)){ 
          

          if($dtcustomerinvoice['invoiceid'] != NULL && 
              $dtcustomerinvoice['ammount'] != NULL && 
              $dtcustomerinvoice['date'] != NULL && 
              $dtcustomerinvoice['due_date'] != NULL){
                              
                $invoice = $dtcustomerinvoice['invoiceid'];
                $date = date('d F Y', strtotime($dtcustomerinvoice['date']));
                $due_date = date('d F Y', strtotime($dtcustomerinvoice['due_date']));
                $ammount = $dtcustomerinvoice['ammount'];

                if($dtcustomerinvoice['paydate'] != NULL){
                  $paydate = date('d F Y', strtotime($dtcustomerinvoice['paydate']));
                }else{
                  $paydate = '-';
                }

          }else{
            $invoice = '-';
            $date = '-';
            $due_date = '-';
            $ammount = '-';
          }

        ?>

        <tr>
          <td><?php echo $dtcustomerinvoice['name']; ?></td>
          <td><?php echo date('d F Y', strtotime($dtcustomerinvoice['register_date'])); ?></td>
          <td><?php echo $invoice; ?></td>
          <td><?php echo $date; ?></td>
          <td><?php echo $due_date; ?></td>
          <td><?php echo $paydate; ?></td>
          <td><?php echo $ammount; ?></td>
        </tr>
        
        <?php 
          } 
        ?>

      </tbody>
    </table>

    <button id="send" type="submit" onclick="location.href = 'customer_invoiceproses.php'">Create Ammount</button>
             
</body>
</html>

 <?php
          if(!empty($_FILES["file_csv"]['tmp_name']) && !empty($_FILES["file_excel"]['tmp_name'])){
              
              echo '<script language="javascript">alert("Pilih Salah satu")</script>';

          }else if(!empty($_FILES["file_csv"]['tmp_name'])) {
              $filename = $_FILES["file_csv"]["tmp_name"];
              $file_array = file($filename);

                  foreach ($file_array as $line_number =>&$line)
                  {
                      $row=explode(',"',$line);
                      $csv_tgl = preg_replace('/[^0-9\/]/','',$row[0]);
                      $tgl = date("Y").'-'.substr($csv_tgl,-2).'-'.substr($csv_tgl, 0, 2);
                      $bulan = substr($csv_tgl,-2);
                      $csv_ammount = explode(".", $row[3]);
                      $ammount = preg_replace('/([^0-9])/i', '', $csv_ammount[0]);

                      $check_ammountinvoice = mysqli_query($connectdb, "SELECT ammount 
                                                                        FROM ng_invoice
                                                                        WHERE DATE_FORMAT(date, '%m') = \"$bulan\" AND 
                                                                        paydate IS NULL AND status != 1");

                      while($dtammount = mysqli_fetch_array($check_ammountinvoice)){
                          $invoice_ammount = $dtammount['ammount'];
                          
                          if($invoice_ammount == $ammount){

                              $update_invoice = mysqli_query($connectdb, "UPDATE ng_invoice 
                                                                          SET paydate = \"$tgl\", status = 1 
                                                                          WHERE ammount = \"$ammount\"");
                            
                          }
                      }
                  }

                  echo '<script language="javascript">alert("Update Sukses")</script>';
                  echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 

          }else if(!empty($_FILES["file_excel"]['tmp_name'])) {

              require_once 'PHPExcel/PHPExcel.php';
              $excelreader = new PHPExcel_Reader_Excel5();

              $filename = $_FILES["file_excel"]['name'];
              
              $tmp_file = $_FILES['file_excel']['tmp_name'];
              
              $path = "$filename";
              
              move_uploaded_file($tmp_file, $path);

              sleep(5);
              
              $loadexcel = $excelreader->load($filename);
              
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
              
              
            foreach($sheet as $row){
                  
                  $csv_tgl = explode("/", $row['C']);
                  $tgl = $csv_tgl[0];
                  $bulan = $csv_tgl[1];
                  $year = $csv_tgl[2];
                  $format_tgl = $year.'-'.$bulan.'-'.$tgl;
                  $csv_ammount = explode(".", $row['I']);
                  $ammount = preg_replace('/([^0-9])/i', '', $csv_ammount[0]);

                  $check_ammountinvoice = mysqli_query($connectdb, "SELECT ammount 
                                                                    FROM ng_invoice
                                                                    WHERE DATE_FORMAT(date, '%m') = \"$bulan\" AND 
                                                                          paydate IS NULL AND status != 1");

                  while($dtammount = mysqli_fetch_array($check_ammountinvoice)){
                    
                    $invoice_ammount = $dtammount['ammount'];
                          
                    if($invoice_ammount == $ammount){

                        $update_invoice = mysqli_query($connectdb, "UPDATE ng_invoice 
                                                                          SET paydate = \"$format_tgl\", status = 1 
                                                                          WHERE ammount = \"$ammount\"");
                            
                    }
                  }
                  // echo $format_tgl.' '.$ammount.'<br/>';
              
            }

              unlink($path);

              echo '<script language="javascript">alert("Update Sukses")</script>';
              echo("<meta http-equiv='refresh' content='1'>"); //Refresh by HTTP META 
          }
          ?>

       