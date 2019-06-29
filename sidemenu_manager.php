<?php
session_start();
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
        <li><a> Sub Manager </a>
            <ul>
                <li><a href="submanagerlist.php">Sub Manager List</a></li>
                <li><a href="submanagerreg.php">Sub Manager Registration</a></li>
            </ul>
          </li>
        <li><a> Customer </a>
            <ul>
                <li><a href="customerlist.php">Customer List</a></li>
                <li><a href="cutomerlist_notverified.php">User List Not Verified</a></li>
                <li><a href="customerreg.php">User Registration</a></li>
            </ul>
        </li>
	      <li><a> Node </a>
            <ul >
                <li><a href="nodelist.php">Node List</a></li>
				<li><a href="nodereg.php">Node Registration</a></li>
            </ul>
        </li>
			  <li><a> Pool </span></a>
            <ul>
                <li><a href="poollist.php">Pool List</a></li>
			          <li><a href="poolreg.php">Pool Registration</a></li>
					      <li><a href="chpoollist.php">Childpool List</a></li>
					      <!-- <li><a href="#">Used Address</a></li> -->
            </ul>
        </li>
		    <li><a> Paket </a>
           <ul>
                <li><a href="paketlist.php">Paket List</a></li>
		            <li><a href="paketreg.php">Paket Registration</a></li>
           </ul>
       </li>
     </ul>
    <!-- /sidebar menu -->
