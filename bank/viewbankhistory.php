<?php  include("../public_inc.php");?>

<?php 

	connectdb();

	charSetThai();

	mustLoginAdmin();

	loadControl();

	

	$sess["bankhistorysortkey"] = "";

	if (!isset($requ["page"])) {$page = 1;} else {$page=$requ["page"];}

	

	/*$sess["banksortkey"] = "";

	if (!isset($requ["page"]) && !$sess["bankpage"]) {

		$page = 1;

	} else {

		if (isset($requ["page"])) {

			$page=$requ["page"];

			$sess["bankpage"] = $page;

		}

		elseif ($sess["bankpage"]) {

			$page=$sess["bankpage"];

			$sess["bankpage"] = $page;

		}

	}*/

	

	$Search_Requ = @addslashes($requ["txtSearch"]);

	$BankID_Requ = $requ["bankid"];

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

.style2 {color: #999999}

-->

</style>

</head>

<script language="JavaScript" src="../lib_js/public_inc.js"></script>

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

					<!--<tr>

						<td style="border-right:solid;border-width:1px;border-color:#000000;" id="trHide"><a href="#" onClick="hideMenu();">��͹����</a></td>

					</tr>

					<tr>

						<td style="border-right:solid;border-width:1px;border-color:#000000; display:none;" id="trShow"><a href="#" onClick="showMenu();">�ʴ�����</a></td>

					</tr> -->

					<tr align="left" valign="top"> 

						<td width="160" style="border-right:solid;border-width:1px;border-color:#000000;" id="trLeft">

							<?php  include("../admin_include/left.php");?>

						</td>

						<td align="center">

							<table border="0" cellpadding="5" cellspacing="0" width="99%">

								<tr>

									<td>

										&nbsp;&nbsp;<b><a href="../admin_main/default.php">˹���á</a> > <a href="../bank/banklist.php">��¡�� ��Ҥ��</a> > �ٻ���ѵԡ�����</b>

									</td>

								</tr>

								<tr> 

									<td align="center" valign="top">

										<form name="form" action="#" method="get">

										<table border="0" cellpadding="0" cellspacing="0" width="99%">

											<tr> 

												<td height="20" bgcolor="#CFCFCF">

													<img src="../images/admin/icon.gif" width="13" height="13" hspace="5" align="absmiddle"><b> �ٻ���ѵԡ�����</b>

												</td>

											</tr>

											<tr><td><br></td></tr>

											<tr> 

												<td>

													<table border="0" cellpadding="0" cellspacing="0" width="100%">

														<tr>

															<td>

																<input name="button" type="button" value="&nbsp;��Ѻ˹����¡�ø�Ҥ��&nbsp;" onClick="javascript: location.href='banklist.php';" class="buttonadmin"> &nbsp;

															</td>

														</tr>

													</table>

												</td>

											</tr>

											<tr><td><br></td></tr>

											<tr> 

												<td>

													

													<table border="0" cellpadding="3" cellspacing="1" width="100%" class="border">

														<?php 

															$sqlSelect = "Select *";

															$sqlSelect .= " From cheque_bankhistoryedit";

															$sqlSelect .= " Where BankID = $BankID_Requ";

															manageSortKeyAdmin("bankhistorysortkey","DateRegister desc", $sqlSelect);

															$sortsql= " order by $sortkey";

															$limit = ($page == 1) ? 0 : ($page - 1) * $AdminPageList;

															$sqlLimit = " Limit $limit, $AdminPageList";

															$sql = "$sqlSelect $sortsql $sqlLimit;";

															

															echo "<tr>";

															echo "<td height=\"25\" $AlignCenter bgcolor=\"#C80000\"><span class=\"style1\"><b>#</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>���ʸ�Ҥ��</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>���ͺ���ѷ/��ҹ���/˹��§ҹ</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>�������</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>�������Ѿ��</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>���������</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>�����</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>���ͼ��Դ���</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>���͹䢡�ê����Թ</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>�����˵�</b></span></td>";

															echo "<td bgcolor=\"#C80000\" $AlignCenter><span class=\"style1\"><b>�ѹ������</b></span></td>";

															echo "</tr>";



															$result = mysql_query($sql);

															if ($result) {

																$numRows = mysql_num_rows($result);

																if ($numRows != 0) {

																	if ($page == 1) {$i = 1;} else {$i = ($page * $AdminPageList) - $AdminPageList + 1;}

																	while ($rw = mysql_fetch_array($result)) {

																		$BankHistoryEditID = $rw["BankHistoryEditID"];

																		$BankCode = convertToText(stripStr($rw["BankCode"]));

																		$BankCompany = convertToText(stripStr($rw["BankCompany"]));

																		$BankAddress = convertToText(stripStr($rw["BankAddress"]));

																		$BankTel = convertToText(stripStr($rw["BankTel"]));

																		$BankFax = convertToText(stripStr($rw["BankFax"]));

																		$BankEmail = convertToText(stripStr($rw["BankEmail"]));

																		$BankContactName = convertToText(stripStr($rw["BankContactName"]));

																		$BankTermPayment = convertToText(stripStr($rw["BankTermPayment"]));

																		$BankComment = convertToText(stripStr($rw["BankComment"]));

																		

																		$DateRegister = mySqlToFullDate($rw["DateRegister"]) . " " . mySqlToFullTime($rw["DateRegister"]);

																		

																		if (($i % 2) == 1) {

																			echo "<tr bgcolor=\"#EBE9E8\">";

																			echo "<td height=\"25\" $AlignCenter>$i</td>";

																			echo "<td $AlignCenter>$BankCode</td>";

																			echo "<td $AlignCenter>$BankCompany</td>";

																			echo "<td $AlignCenter>$BankAddress</td>";

																			echo "<td $AlignCenter>$BankTel</td>";

																			echo "<td $AlignCenter>$BankFax</td>";

																			echo "<td $AlignCenter>$BankEmail</td>";

																			echo "<td $AlignCenter>$BankContactName</td>";

																			echo "<td $AlignCenter>$BankTermPayment</td>";

																			echo "<td $AlignCenter>$BankComment</td>";

																			echo "<td $AlignCenter>$DateRegister</td>";

																			echo "</tr>";

																		}

																		else {

																			echo "<tr>";

																			echo "<td height=\"25\" $AlignCenter>$i</td>";

																			echo "<td $AlignCenter>$BankCode</td>";

																			echo "<td $AlignCenter>$BankCompany</td>";

																			echo "<td $AlignCenter>$BankAddress</td>";

																			echo "<td $AlignCenter>$BankTel</td>";

																			echo "<td $AlignCenter>$BankFax</td>";

																			echo "<td $AlignCenter>$BankEmail</td>";

																			echo "<td $AlignCenter>$BankContactName</td>";

																			echo "<td $AlignCenter>$BankTermPayment</td>";

																			echo "<td $AlignCenter>$BankComment</td>";

																			echo "<td $AlignCenter>$DateRegister</td>";

																			echo "</tr>";

																		}

																		

																		$i++;

																	} //while ($rw = mysql_fetch_array($result)) {

																} //if (mysql_num_rows($result) != 0) {

																else {

																	echo "<tr><td height=\"25\" colspan=\"12\" align=\"center\" bgcolor=\"#EBE9E8\"><br><b>��辺  ����ѵԡ����䢸�Ҥ��</b><br></td></tr>";

																}

															} //if ($result) {

															@mysql_free_result($result);

														?>

													</table>

												</td>

											</tr>

											<tr><td><br></td></tr>

											<tr>

												<td>

													<?php 

														if ($numRows != 0) {

															createPageListAdmin($sqlSelect, "sortkey", "txtSearch", "bankid", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", $sortkey, "$Search_Requ", "$BankID_Requ", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");

														}

													?>

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

	<?php  @mysql_close($cn);?>

</body>

</html>

