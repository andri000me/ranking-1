<?php 
  include_once('../connection.php');
  include_once('../config.php');

  if(isset($_POST['password'])){
	  $pwd = 	mysqli_real_escape_string($link, $_POST['password']);
	  $pwd_db = '$2y$10$cqSQfgOg3.hwp37aWUd90eqmcqndl0WCxMDIc0gF2tq884F.sSr3W'; // byruddy

	  if (password_verify($pwd, $pwd_db)){
	  	$_SESSION['is_logged_in'] = true;
	  	$_SESSION['administrator'] = true;
	  	$_SESSION['guest_name'] 	 = NULL;
	  	echo "success";
	  } else {
	  	echo "fail";
	  }
  } else {
  	header('Location: '.BASE_URL); exit;
  }