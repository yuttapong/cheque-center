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
 	('$name','$data[id_site]','$data[id_company]','$data[id_bank]','$active');";	
 	//echo '<br>'.$sql;exit();
	$rs = mysql_query($sql) or die(mysql_error());
}


function update($data = array(),$id){
	$name = trim(mysql_real_escape_string($data['name']));
	$active = $data['active'];
    $sql = "update cheque_site_profile set 
	    name = '$name' ,
	    id_company = '$data[id_company]',
	    id_bank = '$data[id_bank]',
	    id_site = '$data[id_site]',
	    active=$active
	     where id=$id;";	
	$rs = mysql_query($sql) or die(mysql_error());
}

function updateCheque($data = array(),$id){
	$id_site = $data['id_site'];
     mysql_query("update cheque_cheque set  id_site = '$id_site'  where id_site_profile=$id;");	
     mysql_query("update cheque_cheque_items set  id_site = '$id_site'  where id_site_profile=$id;");	
}

function getOne($id){
	if($id){
		$sql = "select a.*,b.name company,c.site_name ,d.name bank 
		 from cheque_site_profile a 
		left join org_company b on b.id=a.id_company
		left join org_site c on c.site_id=a.id_site
		left join cheque_bank d on d.id_bank=a.id_bank
		where a.id=$id
		order by a.name
		limit 1
		";
		$rs = mysql_query($sql);
		$row = mysql_fetch_object($rs);	
		return $row;
	}
}


   # สาขาหรือไซต์งาน
	 function getAll($onlyId=false){
	$sql = "select a.*,b.name company,c.site_name ,d.name bank 
	 from cheque_site_profile a 
	left join org_company b on b.id=a.id_company
	left join org_site c on c.site_id=a.id_site
	left join cheque_bank d on d.id_bank=a.id_bank
	order by a.id_company,a.id DESC
	";
	    $rs = mysql_query($sql) or die(mysql_error());
	    while($row = mysql_fetch_object($rs)):
	    	if($onlyId===true){
	    		$data[] = $row->id_site;
	    	}
	    	else{
	      $data[]  = array(
	        'id' => $row->id,
	        'id_site' => $row->id_site ,
	        'id_company' => $row->id_company,
	        'name'=> $row->name,
	        'id_bank' => $row->id_bank,
	        'company' => $row->company,
	        'site' => $row->site_name,
	        'bank' => $row->bank,
	      );
	    }
	    endwhile;
	    return $data;
	  }


}


