<?php  include("../public_inc.php");?>

<?php 

	connectdb();

	charSetThai();

	mustLoginAdmin();

	loadControl();

	

	$BankID = $requ["bankid"];

	



	

	$sql = "Select * From cheque_bank  Where BankID = $BankID;";

	$result = mysql_query($sql);

	if ($result) {

		$rw = mysql_fetch_array($result);

		$BankID = $rw["BankID"];

		$BankCode = convertToText(stripStr($rw["BankCode"]));

		$BankName = convertToText(stripStr($rw["BankName"]));

		$BankBranch = convertToText(stripStr($rw["BankBranch"]));

		$BankNumber = convertToText(stripStr($rw["BankNumber"]));

		

		$DateRegister = mySqlToFullDate($rw["DateRegister"]) . " " . mySqlToFullTime($rw["DateRegister"]);

		$DateUpdate = mySqlToFullDate($rw["DateUpdate"]) . " " . mySqlToFullTime($rw["DateUpdate"]);

	} //if ($result) {

	@mysql_free_result($result);

?>

<html>

<head>

<title>Administrator</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta http-equiv="refresh" content="<?=($ReturnHome * 60);?>; url=../admin_main/default.php">

<style type="text/css">

<!--

.style1 {color: #FFFFFF}

-->

</style>

<link rel="stylesheet" type="text/css" href="../layout/stylesheet.css">

<style type="text/css">

<!--

.style4 {color: #FF0000}

-->

</style>

</head>

<script language="JavaScript" src="../lib_js/public_inc.js"></script>

<script language="JavaScript">

	function canReset() {

		var f = document.form;

		f.method = 'get';

		f.action = 'banklist.php';

		f.submit();

	}

	

	function editBank() {

		var f = document.form;

		f.action = "editbank.php";

		f.submit();

	}

	

	

</script>

<body topmargin="0" leftmargin="0">

	<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"> 

		<tr>

			<td align="left" valign="top">

				<?php  include("../layout/top.php");?>

			</td>

		</tr>

		<tr> 

			<td align="left" valign="top">

				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr align="left" valign="top"> 

				
						<td align="center">

							<table border="0" cellpadding="5" cellspacing="0" width="100%">
								<tr> 

									<td align="center" valign="top">

										<form name="form">

										<table border="0" cellpadding="0" cellspacing="0" width="99%">

											<tr> 

												<td height="20" bgcolor="#CFCFCF">

													<img src="../images/admin/icon.gif" width="13" height="13" hspace="5" align="absmiddle"> รายละเอียด

												</td>

											</tr>

											<tr> 

												<td>
                                                <table border="0" cellpadding="5" cellspacing="5">

																	<tr>

																	  	<td align="left"><b>รหัสธนาคาร</b></td>

																	  	<td align="left"><b>:</b></td>

																	  	<td>

																			<?=$BankCode;?>

																		</td>

																	</tr>

																	<tr>

																	  	<td align="left"><b>ชื่อธนาคาร</b></td>

																	  	<td align="left"><b>:</b></td>

																	  	<td>

																			<?=$BankName;?>

																		</td>

																	</tr>

																	<tr>

																	  	<td align="left"><b>สาขา</b></td>

																	  	<td align="left"><b>:</b></td>

																	  	<td>

																		  	<?=$BankBranch;?>

																		</td>

																	</tr>

																	<tr>

																	  	<td align="left"><b> เลขที่บัญชี</b></td>

																	  	<td align="left"><b>:</b></td>

																	  	<td>

																			<?=$BankNumber;?>

																		</td>

																	</tr>

																	<tr> 

																		<td><b>วันที่เพิ่มข้อมูล</b></td>

																		<td><b>:</b></td>

																		<td>

																			<?=$DateRegister;?>

																		</td>

																	</tr>

																	<tr> 

																		<td align="center" colspan="3">

																			<input  type="hidden" name="hidProcess" value="edit">

																			<input type="hidden" name="hidBankID" value="<?=$BankID;?>">

																			<input type="hidden" name="bankid" value="<?=$BankID;?>">

																			<input type="button" value="Edit" onClick="editBank()">

																			<input type="button" value="Reset" onClick="canReset()" >

																		</td>

																	</tr>

																</table>
                                                </td>

											</tr>

										</table>

										</form>

									</td>

								</tr>

							</table>

						</td>

					</tr>

				</table>

			</td>

		</tr>

		<?php  include("../layout/copyright.php");?>

	</table>

</body>

<?php  @mysql_close($cn);?>

</html>

