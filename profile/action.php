<?php
require '../public_inc.php';
connectdb();

$siteprofile = new siteprofile;

$act = $_REQUEST['act'];



switch($act){
	case 'add' :
		
		$data = array(
			'name'=> $_POST['name'],
			'active'=> isset($_POST['active'])?1:0,
			'id_site' => $_POST['id_site'],
			'id_company' => $_POST['id_company'],
			'id_bank' => $_POST['id_bank'],
			);

		if( ! $siteprofile->checkExist($data['name']) ){
			$siteprofile->save($data);
			header("location:index.php");
		}else{
			echo 'ชื่อบริษัท ['.$data['name'] .'] มีแล้วในระบบ';	
		}

		break;
	case 'edit' :
		$id = $_POST['id'];
		$data = array(
			'name'=> $_POST['name'],
			'active'=> isset($_POST['active'])?1:0,
			'id_company' => $_POST['id_company'],
			'id_bank' => $_POST['id_bank'],
			'id_site' => $_POST['id_site'],
			);
		$siteprofile->update($data,$id);
		$siteprofile->updateCheque($data,$id);
		header("location:index.php");
		break;
}