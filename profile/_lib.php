<?php

class SiteProfile {

function checkExist($name){
	 $sql = "select count(*) as num from cheque_site_profile where name='$name' ";
	$rs = mysql_query($sql);
	$row = mysql_fetch_assoc($rs);
	if($row['num']  > 0 )
		return true;
	else
		return false;	
}

function save($data = array()){
	$name = trim(mysql_real_escape_string($data['name']));
	$active = $data['active'];
 	$sql = "insert into cheque_site_profile(name,id_site,id_company,id_bank,active)
 	values
 	('$name','$id_site','$id_company','$id_bank','$active');";	
	$rs = mysql_query($sql) or die(mysql_error());
}


function update($data = array(),$id){
	$name = trim(mysql_real_escape_string($data['name']));
	$active = $data['active'];
    $sql = "update cheque_site_profile set 
	    name = '$name' ,
	    id_company = '$data[id_company]',
	    id_bank = '$data[id_bank]',
	    active=$active
	     where id_site=$id;";	
     //echo $sql; exit();
	$rs = mysql_query($sql) or die(mysql_error());
}

function getOne($id){
	$sql = "select a.*,b.name company,c.site_name ,d.BankName bank 
	 from cheque_site_profile a 
	left join org_company b on b.id=a.id_company
	left join org_site c on c.site_id=a.id_site
	left join cheque_bank d on d.BankID=a.id_bank
	order by a.name
	limit 1
	";
	$rs = mysql_query($sql);
	$row = mysql_fetch_object($rs);	
	return $row;
}


   # สาขาหรือไซต์งาน
	 function getAll(){
	$sql = "select a.*,b.name company,c.site_name ,d.BankName bank 
	 from cheque_site_profile a 
	left join org_company b on b.id=a.id_company
	left join org_site c on c.site_id=a.id_site
	left join cheque_bank d on d.BankID=a.id_bank
	order by a.name
	";
	    $rs = mysql_query($sql) or die(mysql_error());
	    while($row = mysql_fetch_object($rs)):
	      $data[]  = array(
	        'id_site' => $row->id_site ,
	        'id_company' => $row->id_company,
	        'name'=> $row->name,
	        'id_bank' => $row->id_bank,
	        'company' => $row->company,
	        'site' => $row->site_name,
	        'bank' => $row->bank,
	      );
	    endwhile;
	    return $data;
	  }
	}
}


