<?php
$managerid = $_SESSION['managerid'];
$dtfoto = mysqli_query($connectdb, "SELECT foto FROM `ng_manager` WHERE id = \"$managerid\"");
$foto = mysqli_fetch_assoc($dtfoto);
?>
    <img src="foto/<?php echo $foto['foto']; ?>" alt="...">

    <!-- sidebar menu -->
      <ul>
        <li><a> Home </a>
            <ul>
              <li><a href="index.php">Dashboard</a></li>
            </ul>
          </li>
        <li><a> Finance </a>
            <ul>
                <li><a href="billinglist.php">Billing</a></li>
                <li><a href="customer_invoice.php">Invoice</a></li>
            </ul>
        </li>
        <li><a> Invoice </a>
             <ul>
                <li><a href="imemofinance.php">Internal Memo</a></li>
             </ul> 
        </li>
      </ul>
    <!-- /sidebar menu -->