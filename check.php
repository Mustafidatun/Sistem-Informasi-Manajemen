<?php
 	session_start();
	$user_check = $_SESSION['login_user'];
	
	$ses_sql = mysqli_query($connectdb, "select username from ng_userlogin where username = '$user_check' ");
	$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
	$login_session = $row['username'];
	if(!isset($_SESSION['login_user'])){
      header("location:login.php");
	}
	
	$expire_time = 30*60 ;
	if( $_SESSION['last_activity'] < ( time() - $expire_time ) ) {
		header("location:logout.php");
	}
	else {
		$_SESSION['last_activity'] = time();
	}
?>	