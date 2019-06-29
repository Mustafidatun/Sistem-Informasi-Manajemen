<?php
session_start();
$managerid = $_SESSION['managerid'];
$dtfoto = mysqli_query($connectdb, "SELECT foto FROM `ng_submanager` WHERE id = \"$managerid\"");
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
        <li><a> Customer </a>
            <ul>
                <li><a href="userlist.php">User List</a></li>
                <li><a href="userlist_notverified.php">User List Not Verified</a></li>
                <li><a href="regristration.php">User Registration</a></li>
            </ul>
        </li>
      </ul>
    <!-- /sidebar menu -->