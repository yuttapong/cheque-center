<?php
include("../public_inc.php");
connectdb();

 $act = $_POST["act"];
 $id_bank = $_POST["id_bank"];
$name = mysql_real_escape_string($_POST['name']);
$branch = mysql_real_escape_string($_POST['branch']);
$code = mysql_real_escape_string($_POST['code']);
$number = mysql_real_escape_string($_POST['number']);
$active = isset($_POST['active'])?$_POST['active']:0;


	if ($act == "add") {
		$sql = "insert into cheque_bank set
	       name='$name',
	       branch='$branch',
	       number='$number',
	       code='$code',
	       active=$active
	       ";
	      $rs =  mysql_query($sql);
	      if($rs){
	      	header("location:index.php");
	      }
		$result = mysql_query($sql);
	}

	if($act == 'edit' ) {
		$sql = "update cheque_bank set
	       name='$name',
	       branch='$branch',
	       number='$number',
	       code='$code',
	       active=$active
	       where id_bank=$id_bank
	       ";
	      // echo $sql; exit();
	      $rs =  mysql_query($sql);
	      if($rs){
	      	header("location:index.php");
	      }
	}


?>
