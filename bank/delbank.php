<?php  include("../public_inc.php");?>

<?php 

	connectdb();

	charSetThai();

	mustLoginAdmin();

	

	$hidBankIDSelect = $requ["hidBankIDSelect"];

	

	$BankIDArr = explode("++", $hidBankIDSelect);

	

	for ($i = 1; $i < count($BankIDArr); $i++) {

		$BankID = $BankIDArr[$i];

		

		$sql = "Delete From  Where BankID = $BankID;";

		$result = mysql_query($sql);

		if (!$result) {echo "���ѭ��"; exit;}

		@mysql_free_result($result);	

		

	} //for ($i = 1; $i < count($BankBankIDArr); $i++) {

		

	$sess["delBank"] = "del";

	echo "<meta http-equiv=\"refresh\" content=\"0; url=banklist.php\">";

?>

<html>

<head>

<title>Administrator</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>



<body>



</body>

<?php 

	@mysql_free_result($result);

	@mysql_close($cn);

?>

</html>

