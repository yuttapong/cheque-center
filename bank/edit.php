<?php
  include("../public_inc.php");
connectdb();
 $id_bank = isset($_GET['id_bank']) && $_GET['id_bank']!='' ? $_GET['id_bank']:'';

if ( ! $id_bank ) {

		$act = "add";
		$formTitle = "เพิ่มธนาคารใหม่";
		$SubmitValue = "เพิ่ม";
		$code = "";
		$name = "";
		$branch = "";

		$number = "";
		$active = 1;
	}

	else

	{
  $act = 'edit';

  $formTitle = "แก้ไขข้อมูลธนาคาร";

  $SubmitValue = "แก้ไข";


  $id_bank = $_GET["id_bank"];
  $sql = "select * from cheque_bank  where id_bank = $id_bank ";
  $result = mysql_query($sql) or die(mysql_error());
  $rw = mysql_fetch_array($result);
  $code = $rw["code"];
  $name =$rw["name"];
  $branch = $rw["branch"];
  $number =$rw["number"];
  $active = $rw['active'];
	}



?>
<html>
<head>
<title>Edit Bank</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="../bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bower_components/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
<link href="../css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../css/fa/css/font-awesome.min.css" type="text/css" />
</head>

<body topmargin="0" leftmargin="0">
<div class="container">


	<div class="col-md-5">



<p>
<ul class="nav nav-pills">
<li>
 <a href="../index.html">หน้าแรก</a>
 </li>
 <li> <a href="index.php">กลับ</a></li>
 </ul>
</p>

            <?=$formTitle;?>


              <form action="save.php" name="form" method="post">
              <input type="hidden" name="act" value="<?=$act?>">
              <input type="hidden" name="id_bank" value="<?=$id_bank?>">
                <table border="0" cellpadding="" cellspacing="" class="table table-striped">
                  <tr>
                    <td width="65" align="left"><b> Code</b></td>
                    <td width="264"><input name="code" type="text" required class="form-control"  value="<?=$code;?>" maxlength="255"></td>
                  </tr>
                  <tr>
                    <td align="left"><b> ชื่อธนาคาร</b></td>
                    <td><input name="name" type="text" required class="form-control"  value="<?=$name;?>" maxlength="255"></td>
                  </tr>
                  <tr>
                    <td align="left"><b>สาขา</b></td>
                    <td><input name="branch" type="text" required class="form-control"  value="<?=$branch;?>" maxlength="255"></td>
                  </tr>
                  <tr>
                    <td align="left"><b>เลขที่บัญชี</b></td>
                    <td><input name="number" type="text" required class="form-control"  value="<?=$number;?>" maxlength="255"></td>
                  </tr>
               <tr>
                    <td align="left"><b>Active</b></td>
                    <td>
                    	<select class="form-control" name="active">
                    		<option value="1" <?=$active=="1"?'selected':''?>> Active</option>
                    		<option value="0" <?=$active=="0"?'selected':''?>> Inactive</option>
                    	</select>

                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>
                      <input type="submit" value="<?=$SubmitValue;?>" class="btn btn-primary">
                      <input type="reset" value="Reset"  class="btn btn-default"></td>
                  </tr>
                </table>
              </form>



	</div>
</div>

</body>


</html>
