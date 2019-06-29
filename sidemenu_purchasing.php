<?php
$managerid = $_SESSION['managerid'];
$dtfoto = mysqli_query($connectdb, "SELECT foto FROM `ng_manager` WHERE id = \"$managerid\"");
$foto = mysqli_fetch_assoc($dtfoto);
?>

    <img src="foto/<?php echo $foto['foto']; ?>" alt="...">

    <!-- sidebar menu -->
      <ul class="nav side-menu">
        <li><a> Home </a>
            <ul>
              <li><a href="index.php">Dashboard</a></li>
            </ul>
        </li>
        <li><a> Vendor </a>
             <ul>
                <li><a href="vendorlist.php">Vendor list</a></li>
                <li><a href="vendorreg.php">Vendor</a></li>
             </ul> 
        </li>
        <li><a> Master Equipment </a>
             <ul>
                <li><a href="masterbrglist.php">Master Equipment list</a></li>
                <li><a href="masterbrg.php">Master Equipment</a></li>
             </ul> 
        </li>
        <li><a> Inventory </a>
             <ul>
                <li><a href="imemo.php">Internal Memo</a></li>
                <li><a href="imemolist.php">Internal Memo list</a></li>
             </ul> 
        </li>
      </ul>
    <!-- /sidebar menu -->