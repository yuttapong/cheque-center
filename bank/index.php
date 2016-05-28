<?php
include("../public_inc.php");
connectdb();

?>
<html>
<head>
<title>Bank</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bower_components/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
<link href="../css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../css/fa/css/font-awesome.min.css" type="text/css" />
</head>
<body>
<div class="row-fluid">
<div class="col-md-12">
<?php
$sql = "select * from cheque_bank where 1";
$result = mysql_query($sql) or die(mysql_error());
$numrows = mysql_num_rows($result);
?>
<h1>ข้อมูลธนาคาร (<?=$numrows;?> รายการ)</h1>

<ul class="nav nav-pills">
<li>
   <a href="../#home"><i class="fa fa-home"></i> หน้แรก</a>
   </li>
   <li>   <a href="edit.php"><i class="fa fa-plus"></i> เพิ่มธนาคาร</a>
   </li>
   </ul>


<div class="table-responsive">
<table class="table table-striped">
<tr>
<th>ID</th>
<th>Code</th>
<th>ธนาคาร</th>
<th>สาขา</th>
<th>เลขที่บัญชี</th>
<th>Active</th>
<th>Action</th>
</tr>
<?php
while ($row = mysql_fetch_object($result)) {
?>
<tr>
     <td><?=$row->id_bank?></td>
     <td><?=$row->code?></td>
     <td><?=$row->name?></td>
     <td><?=$row->branch?></td>
     <td><?=$row->number?></td>
      <td><?=$row->active?></td>
     <td><a href="edit.php?id_bank=<?=$row->id_bank?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> แก้ไข</a>
     </td>
   </tr>
<?php
}


?>

</table>
</div>


</div>
</div>

</body>

</html>
