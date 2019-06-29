<?php
include "koneksi.php";
include "check.php";

  $jmlinvoice = mysqli_query($connectdb, "SELECT SUM(invoiceid) AS jmlinvoice 
                                          FROM ng_invoice 
                                          GROUP BY invoiceid 
                                          ORDER BY invoiceid DESC LIMIT 1");
  $dtjmlinvoice = mysqli_fetch_array($jmlinvoice);

  $customerlist = mysqli_query($connectdb, "SELECT ng_customer.id AS customerid, 
                                                  PERIOD_DIFF(DATE_FORMAT(CURDATE(), '%Y%m'), 
                                                  DATE_FORMAT(ng_customer.register_date, '%Y%m')) AS jmlbulan, 
                                                  ng_paket.*, ng_invoice.date 
                                            FROM ng_customer 
                                            INNER JOIN ng_paket ON ng_paket.id = ng_customer.paket 
                                            LEFT JOIN ng_invoice ON ng_invoice.customerid = ng_customer.id
                                            WHERE ng_customer.id NOT IN (SELECT customerid FROM ng_invoice) OR DATE_FORMAT(ng_invoice.date, '%Y%m') NOT IN (DATE_FORMAT(CURDATE(), '%Y%m'))");

  while($dtcustomer = mysqli_fetch_assoc($customerlist)){
            $dtjmlinvoice['jmlinvoice']++;
            $invoiceid = $dtjmlinvoice['jmlinvoice'].'/INV/'.date('m').'/'.date('Y');

            $customerid = $dtcustomer['customerid'];
            $jmlbulan = $dtcustomer['jmlbulan'] + 1;
            $skema = $dtcustomer['skema'];

            $digits = 3;
            $randomNumber = rand(pow(10, $digits-1), pow(10, $digits)-1); 

            if($skema == 1){
              if($jmlbulan >= 1 && $jmlbulan <= 3){
                $ammount = $dtcustomer['price1'] + $randomNumber;
              }else if($jmlbulan > 3 && $jmlbulan <= 12){
                $ammount = $dtcustomer['price2'] + $randomNumber;
              }else if($jmlbulan > 12){
                $ammount = $dtcustomer['price3'] + $randomNumber;
              }

            }else if($skema == 2){
              $ammount = $dtcustomer['price4'] + $randomNumber;
            
            }else if($skema == 3){
              if($jmlbulan <= 12){
                $ammount = $dtcustomer['price3'] + $randomNumber;
              }else if($jmlbulan > 12){
                $ammount = $dtcustomer['price4'] + $randomNumber;
              }
            }

            $ng_invoice = mysqli_query($connectdb, "INSERT INTO ng_invoice (invoiceid, customerid, date, due_date, ammount) VALUES (\"$invoiceid\", \"$customerid\", DATE(NOW()), DATE_ADD(DATE(NOW()), INTERVAL 5 DAY), \"$ammount\")"); 
            
        }
            header("location: customer_invoice.php");
        ?>