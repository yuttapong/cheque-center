<?php  include("../public_inc.php");?>

<?php 

	mustLoginAdmin();

	connectdb();

	charSetThai();

	

	$hidOfficerIDSelect = $requ["hidOfficerIDSelect"];

	

	$OfficerIDArr = explode("++", $hidOfficerIDSelect);

	

	for ($i = 1; $i < count($OfficerIDArr); $i++) {

		$OfficerID = $OfficerIDArr[$i];

		

		$sql = "Delete From cheque_officer Where OfficerID = $OfficerID;";

		$result = mysql_query($sql);

		if (!$result) {echo "Found problem."; exit;}

		@mysql_free_result($result);	

	} //for ($i = 1; $i < count($OfficerIDArr); $i++) {

		

	$sess["delOfficer"] = "del";

	echo "<meta http-equiv=\"refresh\" content=\"0; url=../rbac/officerlist.php\">";

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

