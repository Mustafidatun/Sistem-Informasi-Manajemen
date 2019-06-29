<?php
include "koneksi.php";
include "check.php";

$memoid = $_GET['memoid'];

$vendorlist = mysqli_query($connectdb, "SELECT ng_vendor.*
                                        FROM ng_vendor 
                                        INNER JOIN ng_equipmaster ON ng_equipmaster.vendorid = ng_vendor.id
                                        INNER JOIN ng_internalmemo ON ng_internalmemo.equipmasterid = ng_equipmaster.id
                                        WHERE ng_internalmemo.memoid = \"$memoid\" lIMIT 1");
$dtvendor = mysqli_fetch_assoc($vendorlist); 

$internalmemolist = mysqli_query($connectdb, "SELECT ng_internalmemo.quantity, 
                                                    ng_equipmaster.vol,
                                                    CONCAT(ng_equipmaster.type, ' - ', ng_equipmaster.merk) AS item, 
                                                    ng_internalmemo.price AS itemprice, 
                                                    (ng_internalmemo.price*ng_internalmemo.quantity) AS total
                                            FROM ng_internalmemo 
                                            INNER JOIN ng_equipmaster ON ng_equipmaster.id = ng_internalmemo.equipmasterid
                                            WHERE ng_internalmemo.memoid = \"$memoid\" AND
                                            ng_internalmemo.status = 2");

$user = mysqli_query($connectdb, "SELECT ng_userlogin.username AS usersupermanager, 
                                        ng_managerpurchase.username AS userpurchasing, 
                                        ng_managerfinance.username AS userfinance,
                                        ng_purchaseorder.poid 
                                  FROM ng_internalmemo
                                  INNER JOIN ng_userlogin ON ng_userlogin.id = ng_internalmemo.userid
                                  INNER JOIN ng_manager ng_managerpurchase ON ng_managerpurchase.id = ng_internalmemo.purchasingid
                                  INNER JOIN ng_manager ng_managerfinance ON ng_managerfinance.id = ng_internalmemo.financeid
                                  INNER JOIN ng_purchaseorder ON ng_purchaseorder.internalmemoid = ng_internalmemo.id 
                                  WHERE ng_internalmemo.memoid = \"$memoid\" AND
                                        ng_internalmemo.status = 2");
$dtuser = mysqli_fetch_assoc($user); 

$taxes= 0;
$shipping_handling1 = 0;
$shipping_handling2 = 0;
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
      }else if($_SESSION['level'] == 5){
        include 'sidemenu_fieldtec.php';
      }else if($_SESSION['level'] == 10){
        include 'sidemenu_finance.php';
      }else if($_SESSION['level'] == 11){
        include 'sidemenu_purchasing.php';
      }else if($_SESSION['level'] == ""){
        include 'page_404.html'; 
      }
    ?>

    <h2>Purchase Order<small id="poid"><?php echo $dtuser['poid']; ?></small></h2>

    Vendor
    <table>
      <tr>
        <td>Name</td>
        <td> : </td>
        <td id="vendor"><?php echo $dtvendor['vendor']; ?></td>
      </tr>
      <tr>
        <td>Attn</td>
        <td> : </td>
        <td id="attn"></td>
      </tr>
      <tr>
        <td>Address</td>
        <td> : </td>
        <td id="alamat"><?php echo $dtvendor['alamat']; ?></td>
      </tr>
      <tr>
        <td>Telp</td>
        <td> : </td>
        <td id="no_telp"><?php echo $dtvendor['no_telp']; ?></td>
      </tr>
    </table>
                            
    Ship to
    <table>
      <tr>
        <td>Name </td>
        <td> : </td>
        <td>PT Bentang Selaras Teknologi</td>
      </tr>
      <tr>
        <td>Attn</td>
        <td> : </td>
        <td>Nisa / 0812 3500 9122</td>
      </tr>
      <tr>
        <td>Address</td>
        <td> : </td>
        <td>Ruko Sukarno Hatta Indah Blok E8, Lowokwaru Malang - Jawa Timur</td>
      </tr>
      <tr>
        <td>Telp</td>
        <td> : </td>
        <td>0341 - 404286</td>
      </tr>
    </table>

    <table id="table1">
      <thead>
        <tr>
          <th>Qty</th>
          <th>Vol</th>
          <th>Item</th>
          <th>Item Price</th>
          <th>TOTAL</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $total = 0;
          $index = 1;
          while ($dtinternalmemo = mysqli_fetch_assoc($internalmemolist)){
            $subtotal += $dtinternalmemo['total'];
          ?>
          <tr>
            <td id="qty<?php echo $index; ?>"><?php echo $dtinternalmemo['quantity']; ?></td>
            <td id="vol<?php echo $index; ?>"><?php echo $dtinternalmemo['vol']; ?></td>
            <td id="item<?php echo $index; ?>"><?php echo $dtinternalmemo['item']; ?></td>
            <td id="itemprice<?php echo $index; ?>"><?php echo $dtinternalmemo['itemprice']; ?></td>
            <td id="total<?php echo $index; ?>"><?php echo $dtinternalmemo['total']; ?></td>
          </tr>
          <?php 
            $index++;
          } 
          ?>
          
          <input type="hidden" id="jumlah-row" value="<?php echo $index-1; ?>">
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4">Sub Total</td>
            <td id="subtotal"><?php echo $subtotal; ?></td>
          </tr>
          <tr>
            <td colspan="4">Taxes 10%</td>
            <td id="taxes"><?php echo $taxes; ?></td>
          </tr>
          <tr>
            <td colspan="4">Shipping & Handling</td>
            <td id="shipping_handling1"><?php echo $shipping_handling1; ?></td>
          </tr>
          <tr>
            <td colspan="4"> </td>
            <td id="shipping_handling2"><?php echo $shipping_handling2; ?></td>
          </tr>

          <?php 
          $total = $subtotal + $texas + $shipping_handling1 + $shipping_handling2; 
          ?>

          <tr>
            <td colspan="4">Total</td>
            <td id="total"><?php echo $total; ?></td>
          </tr>
        </tfoot>
      </table>
                       
      Payment Methods
      <label>
        <input type="radio" name="pm1" value="Cash"> Cash
      </label>
      <label>
        <input type="radio" checked name="pm1" value="Transfer"> Transfer
      </label>
      <label>
        <input type="radio" name="pm1" vaLue="Check"> Check
      </label>
      <label>
        <input type="radio" checked name="pm2" vaLue="IDR"> IDR
      </label>
      <label>
        <input type="radio" name="pm2" value="USD"> USD
      </label>
      <label>
        <input type="radio" name="pm3" value="Full"> Full
      </label>
      <label>
        <input type="radio" checked name="pm3" vaLue="Term"> Term
      </label>
      <label>
        <input type="radio" name="pm3" value="Monthly"> Monthly
      </label>

      <table>
        <thead>
          <tr>
            <th>Administrator</th>
            <th>Project Manager</th>
            <th>Finance</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="userpurchasing"><?php echo $dtuser['userpurchasing']; ?></td>
            <td id="usersupermanager"><?php echo $dtuser['usersupermanager']; ?></td>
            <td id="userfinance"><?php echo $dtuser['userfinance']; ?></td>
          </tr>
        </tbody>
      </table>
      
      <hr>

      Notes/Remark
      <textarea id="notes" name="notes" style="height: 120;"></textarea>
                      
      Received By
      <textarea id="received" name="received_by" style="height: 120;"></textarea>
       
      <button id="print"> Print</button>
</body>
</html>

        <?php
          // if($_SERVER["REQUEST_METHOD"] == "POST") {
          //   $poid = $dtuser['poid'];

          //   $update_internalmemo = mysqli_query($connectdb, "UPDATE ng_internalmemo SET status = \"3\" WHERE memoid = \"$memoid\"  AND ng_internalmemo.status = 2 ");
          //   $update_datepo = mysqli_query($connectdb, "UPDATE ng_purchaseorder SET date = DATE(NOW()) WHERE poid = \"$poid\"");
          //   echo("<meta http-equiv='refresh' content='0; url=http://10.10.10.222/ng4dm1n/production/imemolist.php'>"); //Refresh by HTTP META 
          // }
        ?>
        <!-- this row will not appear when printing -->
        
  
    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
      $("#print").click(function(){
            var poid = document.getElementById("poid").innerHTML;
            var notes = document.getElementById("notes").value;
            var received = document.getElementById("received").value;
            var pm1 = document.querySelector('input[name="pm1"]:checked').value;
            var pm2 = document.querySelector('input[name="pm2"]:checked').value;
            var pm3 = document.querySelector('input[name="pm3"]:checked').value;
            var vendor = document.getElementById("vendor").innerHTML;
            var attn = document.getElementById("attn").innerHTML;
            var alamat = document.getElementById("alamat").innerHTML;
            var no_telp = document.getElementById("no_telp").innerHTML;
            var userpurchasing = document.getElementById("userpurchasing").innerHTML;
            var usersupermanager = document.getElementById("usersupermanager").innerHTML;
            var userfinance = document.getElementById("userfinance").innerHTML;
            
            function formatRupiah(bilangan){
              var	number_string = bilangan.toString(),
                  sisa 	= number_string.length % 3,
                  rupiah 	= number_string.substr(0, sisa),
                  ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                  
              if (ribuan) {
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
              }
 
			        return rupiah;
		        }

            var jumlah = parseInt($("#jumlah-row").val());
            var content = [];
            content.push([{text: 'Qty', style: 'tableHeader'}, {text: 'Vol', style: 'tableHeader'}, {text: 'Item', style: 'tableHeader'}, {text: 'Item Price', style: 'tableHeader'}, {text: 'Total', style: 'tableHeader'}],);
              for (var i = 1; i <= jumlah; i++) {
                var row = [document.getElementById("qty"+i).innerHTML,
                          document.getElementById("vol"+i).innerHTML,
                          document.getElementById("item"+i).innerHTML,
                          {text :formatRupiah(document.getElementById("itemprice"+i).innerHTML), alignment:'right'},
                          {text :formatRupiah(document.getElementById("total"+i).innerHTML), alignment:'right'}];
                content.push(row);
              }

            var subtotal = document.getElementById("subtotal").innerHTML;
            var taxes = document.getElementById("taxes").innerHTML;
            var shipping_handling1 = document.getElementById("shipping_handling1").innerHTML;
            var shipping_handling2 = document.getElementById("shipping_handling2").innerHTML;
            var total = document.getElementById("total").innerHTML;

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = mm + '-' + dd + '-' + yyyy;

            var docDefinition = {
                  content: [
                    {
                    columns: [
                      {
                        width: 60,
                        // text:'logo',
                        image: 'images/pen_signing.png',
                        // image: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAQAAABKfvVzAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAHdElNRQfjBRUHHjbtl1eSAAABkElEQVQ4y43UT4iMcRgH8M+uqbUNti2X1SI2Bwc5oFykzYHY7MFJNITl4OLqPMVVLeFIsleNWikrkiK5OBDK7gG1Sc3asbZdO4/DvPPHmLHv9/b7/nl+3/d96kd6DBrRnUlhXGHchJJrGEwz+YBw3RchzHSmCJzAtHVgcvlAj2EfbUlOd5aff1a4bFYIS/rrN/TaqadFIKds3irwyOcKmZE3LxQdabIPKJswLoRwrEqPCjMKFv2qta1gqx+GZR33UFG2Qh4Upg3gonClRamcLFZXiefCYdAnvG6yd5gUZt1KvsNm4b2ORH7mXlNgb9L/Q8WTcQj3RSLvaVGogttVz5gw1HYL3YpCKNtUpd4KvW0DR5NCT+pUybf/7PlBEjhVp376CjrlFOT/svdZFMKcNXXykyUb7fIykRpxPpl/t5G8JJI574Q3NuivaTeTwP7GQJerprxy2m6hpOy3c9aDx0J4WttSE7KmhO9CWHABL4QF29v/k7X26XLSDXPCNgVlI1Jhh7yVxpxJZ69iyD+vyh/rs4179+o5DAAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxOS0wNS0yMVQwNTozMDo1NCswMjowMAKnpg0AAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTktMDUtMjFUMDU6MzA6NTQrMDI6MDBz+h6xAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAABJRU5ErkJggg==',
                        fit: [70, 70],
                      },
                      {
                        width: 170,
                        fontSize: 10,
                        style: 'tableExample',
                                      table: {
                                              widths: [50, 120],
                                          body: [
                                              [{text: 'PT. Bentang Selaras Teknologi', colSpan: 2, bold: true}, {}],
                                            [{text: 'Ruko Sukarno Hatta Indah Blok E-8 Malang', colSpan: 2, bold: true}, {}],
                                            [{text:'Telp.', bold: true}, {text: '0341 404286', bold: true}],
                                            [{text:'Fax.', bold: true}, {text: '0341 4345448', bold: true}],
                                            ]
                                            },
                                            layout: 'noBorders'
                                      
                      },
                      {
                        width: '*',
                        fontSize: 10,
                        style: 'tableExample',
                                      table: {
                                              widths: [110, 5, 120],
                                          body: [
                                              ['Date', ' : ', today],
                                            ['Purchase Order No.', ' : ', poid],
                                            ['Quotation Ref No.', ' : ', '-'],
                                            ['Project', ' : ', '']
                                            ]
                                            },
                                            layout: 'noBorders'
                                    
                      },
                    ]
                  },
                  {text: '\nPURCHASE ORDER                                              \n\n', style: 'header', fontSize: 26, decoration: 'underline'},
                    {
                      columns: [
                        {
                          text: 'Vendor'
                        },
                        {
                          text: 'Ship to'
                        }
                      ]
                    },
                    {
                      alignment: 'justify',
                      columns: [
                        {
                          columns: [
                              {
                                  style: 'tableExample',
                                        table: {
                                                widths: [50, 5, 170],
                                            body: [
                                                ['Nama', ' : ', vendor],
                                              ['Attn', ' : ', attn],
                                              [{text: 'Address', rowSpan: 2}, ' : ', {text: alamat, rowSpan: 2}],
                                              [' ',' ',' '],
                                              [' ',' ',' '],
                                              ['No. Telp', ' : ', no_telp]
                                              ]
                                              },
                                              layout: 'noBorders'
                                        }
                            ]
                        },
                        {
                          columns: [
                              {
                                  style: 'tableExample',
                                        table: {
                                                widths: [50, 5, 170],
                                            body: [
                                                ['Nama', ' : ', 'PT Bentang Selaras Teknologi'],
                                              ['Attn', ' : ', 'Nisa / 0812 3500 9122'],
                                              ['Address', ' : ', 'Ruko Sukarno Hatta Indah Blok E8, Lowokwaru Malang - Jawa Timur'],
                                              ['','',''],
                                              ['','',''],
                                              ['No. Telp', ' : ', '0341 - 404286']
                                              ]
                                              },
                                              layout: 'noBorders'
                                        }
                                    
                            ]
                        }
                      ]
                    },
                    '\n',
                    {
                    style: 'tableExample',
                    table: {
                            widths: ['*', 50, 240, 85,85],
                            headerRows: 1,
                        body:
                            content,
                        
                      }
                    },
                    '\n\n\n',
                    {
                      columns: [
                        {
                          text: {}
                        },
                        {
                                style: 'tableExample',
                                table: {
                                    widths: [120, 115],
                                    headerRows: 1,
                                body: [
                                    [{text: '', colSpan: 2}, {}],
                                  ['Sub Total', {text: formatRupiah(subtotal), alignment:'right'}],
                                  ['Taxes 10%', {text: formatRupiah(taxes), alignment:'right'}],
                                  ['Shipping & Handling', {text: formatRupiah(shipping_handling1), alignment:'right'}],
                                  [' ', {text: formatRupiah(shipping_handling2), alignment:'right'}],
                                  ['Total', {text: formatRupiah(total), alignment:'right'}],
                                    ]
                              },
                              layout: 'lightHorizontalLines'
                            },
                      ]
                    },
                    {text: '\n\nPayment Methods\n', fontSize: 15},
                    {
                      columns: [
                            {
                                width: 170,
                              ul: [
                                pm1,
                                pm2,
                                pm3
                              ]
                            },
                            {
                            style: 'tableExample',
                                  table: {
                                      widths: [100, 100, 100],
                                      body: [
                                              ['Administrator', 'Project Manager', 'Finance'],
                                              // [
                                              //   { 
                                              //     image: 'images/pen_signing.png',
                                              //         width: 50,
                                              //         height: 50
                                              //   }, 
                                              //   { 
                                              //     image: 'images/pen_signing.png',
                                              //         width: 50,
                                              //         height: 50
                                              //   },
                                              //   { 
                                              //     image: 'images/pen_signing.png',
                                              //         width: 50,
                                              //         height: 50
                                              //   }
                                              //   ],
                                                [{text: userpurchasing}, {text: usersupermanager}, {text: userfinance}],
                                              ]
                                          }
                                        
                            }
                      ]
                    },
                    '\n\n',
                    {
                      columns: [
                        {
                            width: 250,
                          text: 'Notes/Remark',
                          fontSize: 15
                        },
                        {
                          width: 250,
                          text: 'Received By',
                          fontSize: 15
                        }
                      ]
                    },
                    {
                      alignment: 'justify',
                      columns: [
                        {
                          width: 250,
                          text: notes
                        },
                        {
                          width: 250,
                          text: received
                        },
                      ]
                    }
                  ],
                  styles: {
                    header: {
                      fontSize: 18,
                      bold: true
                    },
                    bigger: {
                      fontSize: 15,
                      italics: true
                    }
                  },
                  defaultStyle: {
                    columnGap: 20
                  }
                  
                }
                
          pdfMake.createPdf(docDefinition).download(poid+'.pdf');

              });
            });
    </script>