<?php
$managerid = $_SESSION['managerid'];
$dtfoto = mysqli_query($connectdb, "SELECT foto FROM `ng_manager` WHERE id = \"$managerid\"");
$foto = mysqli_fetch_assoc($dtfoto);
?>

    <img src="foto/<?php echo $foto['foto']; ?>" alt="..." class="img-circle profile_img">
      
    <!-- sidebar menu -->
        <ul>
          <li><a> Home</a>
            <ul>
              <li><a href="index.php">Dashboard</a></li>
            </ul>
          </li>
          <li><a> Queue </span></a>
            <ul>
                <!-- <li><a href="custqueue.php">Queue List</a></li> -->
            </ul>
          </li>
          <li><a> Customer </a>
              <ul>
                  <li><a href="userlist.php">User List</a></li>
              </ul>
          </li>
          <li><a> Node </a>
              <ul>
                  <li><a href="nodelist.php">Node List</a></li>
              </ul>
          </li>
		      <li><a> Pool </a>
            <ul>
                <li><a href="poollist.php">Pool List</a></li>
            </ul>
          </li>
		      <li><a> Tools </a>
             <ul>
                  <!-- <li><a href="paketlist.php">LatLong Calc</a></li> -->
             </ul>
          </li>
	    </ul>
            <!-- /sidebar menu -->

            