<?php
require '../public_inc.php';
connectdb();

$org  = new Org;
$siteprofile = new siteprofile;
?>
<html>
<head>
<title>ไซต์งาน/สาขา</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bower_components/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
<link href="../css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../css/fa/css/font-awesome.min.css" type="text/css" />
</head>
<body>

<div class="row-fluid">
<div class="col-md-12">
<h1>ข้อมูลบริษัท/ไซต์งาน</h1>
<p>
   <a href="../#home"><i class="fa fa-home"></i> หน้แรก</a>
   </p>
<?php include '_form.php';?>

<?php
$q = $_GET['q'];
$where_q = isset($_GET['q'])?" AND a.name like'%$q%' ":"";
$sql = "select a.*,b.name company,c.site_name , d.code bank_code,
d.name bank_name ,d.branch bank_branch,d.number bank_number ";
$sql .= " from cheque_site_profile a 
left join org_company b on b.id=a.id_company
left join org_site c on c.site_id=a.id_site
left join cheque_bank d on d.id_bank=a.id_bank
";

$sql .= " where 1 $where_q ";
$sql .= " order by  a.active desc,a.id_site desc";
$result = mysql_query($sql) or die(mysql_error());
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">

<tr>

<td  class="btn-group">

<?php  if ((@$_SESSION["activeloginadmin"] == 'admin') || ($PermissionCheque21 == "Y")) {?>

<a href="?act=add" type="button"  class="btn btn-default btn-sm"><i class="fa fa-plus"></i> เพิ่ม</a>

<?php  }?>


</td>

<td align="right">
<form name="form" action="" method="get" role="form" class="form-inline">
<input type="text" name="q" size="30" class="form-control input-sm" value="<?=$q?>"> 
<input type="button" value="Search" class="btn btn-default btn-sm" onClick="javascript: document.form.submit();">
</form>
</td>

</tr>

</table>





<table class="table table-striped" border="0">
<tr>
<th>ID</th>
<th>ชื่อ</th>
<th>ไซต์งาน/โครงการ</th>
<th>บริษัท</th>
<th>ธนาคาร</th>
<th>สาขาธนาคาร</th>
<th>หมายเลขบัญชี</th>
<th>Active</th>
<th>Action</th>
</tr>
<?php




while ($rw = mysql_fetch_object($result)) {


echo "<tr>";
echo '<td>'.$rw->id.'</td>';
echo '<td>'.$rw->name.'</td>';
echo '<td>'.$rw->site_name.'</td>';
echo '<td>'.$rw->company.'</td>';
echo '<td>'.$rw->bank_code. ' - '.$rw->bank_name;;
echo'</td>';
echo'<td>'.$rw->bank_branch.'</td>';
echo'<td>'.$rw->bank_number.'</td>';
echo '<td>'.($rw->active==1?'<span class="">Yes</span>':'<span>No</span</span>').'</td>';
echo'<td>';
echo'<a href="index.php?act=edit&id='.$rw->id.'" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> แก้ไข</a>';
echo'</td>';

echo "</tr>";


$i++;

} 

?>
</table>
</div>
</div>
<body>
</html>

