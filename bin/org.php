<?php
class Org{

 # ข้อมูลบริษัท
  function getCompanyAll(){
    $sql = "select * from org_company order by name asc ";
  	$rs = mysql_query($sql) or die(mysql_error());
  	while($row = mysql_fetch_object($rs)):
  		$data[]  = array(
  			'id' => $row->id ,
  			'code' => $row->code,
  			'name' => $row->name,
  			'address'=> $row->address_full,
  			'contact' => $row->contact,
  			'img'=> $row->img,
  		);
  	endwhile;
  	return $data;
  }

 # สาขาหรือไซต์งาน
  function getSiteAll(){
    $sql = "select * from org_site order by site_name asc ";
    $rs = mysql_query($sql) or die(mysql_error());
    while($row = mysql_fetch_object($rs)):
      $data[]  = array(
        'site_id' => $row->site_id ,
        'site_name' => $row->site_name,
        'site_description'=> $row->site_description,
        'company_id' => $row->company_id,
      );
    endwhile;
    return $data;
  }

  # ข้อมูลธนาคาร
  function getBankAll(){
    $sql = "select * from cheque_bank  where active=1 order by name asc ";
    $rs = mysql_query($sql) or die(mysql_error());
    while($row = mysql_fetch_object($rs)):
      $data[]  = array(
        'id_bank' => $row->id_bank ,
        'code' => $row->code,
        'name'=> $row->name,
        'branch' => $row->branch,
        'number' => $row->number,
      );
    endwhile;
    return $data;
  }

   # สาขาหรือไซต์งาน
  function getSiteProfileAll(){
$sql = "select a.*,b.name company,c.site_name ,d.name bank 
 from cheque_site_profile a 
left join org_company b on b.id=a.id_company
left join org_site c on c.site_id=a.id_site
left join cheque_bank d on d.id_bank=a.id_bank
where a.active=1
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
