<?php
include "koneksi.php";

if (isset($_POST['kota'])) {
   $kota = $_POST['kota'];

   $ng_node = mysqli_query($connectdb, "SELECT * FROM ng_node WHERE kota='".$kota."'");

   $html = "<option value=''>Pilih</option>";

   while($data = mysqli_fetch_array($ng_node)){ 
   	$html .= "<option value='".$data['nodeid']."'>".$data['node']."</option>"; 
   }

   $callback = array('data_node'=>$html); 
   echo json_encode($callback); 

} else if(isset($_POST['node'])) {
   $node = $_POST['node'];

   $sql = mysqli_query($connectdb, "SELECT ng_paket.id, 
                                          ng_paket.paket 
                                    FROM ng_paket,ng_childpool,ng_node,ng_pool 
                                    WHERE ng_node.nodeid=ng_pool.nodeid AND 
                                          ng_childpool.poolid=ng_pool.id AND 
                                          ng_childpool.kd_prod=ng_paket.kd_prod AND 
                                          ng_node.nodeid='".$node."' GROUP BY ng_paket.paket");

   $html = "<option value=''>Pilih</option>";

   while($data = mysqli_fetch_array($sql)){ 
   	$html .= "<option value='".$data['id']."'>".$data['paket']."</option>"; 
   }

   $callback = array('data_paket'=>$html); 
   echo json_encode($callback); 
}
?>
