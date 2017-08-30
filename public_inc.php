<?php
session_start();
//@session_cache_expire(1800);
//@set_time_limit(1800);

date_default_timezone_set('Asia/Bangkok') ;

/*
define('CHEQUE_DB_HOST', 'localhost');
define('CHEQUE_DB_USER', 'user');
define('CHEQUE_DB_PWD', 'password');
define('CHEQUE_DB_NAME', 'db');
*/



define('CHEQUE_DB_HOST', 'localhost');
define('CHEQUE_DB_USER', 'root');
define('CHEQUE_DB_PWD', '');
define('CHEQUE_DB_NAME', 'siricenter_cheque');


//-----------------------------------------------------
$monthNum = array(
	"1", "2", "3", "4", "5", "6",
	"7", "8", "9", "10", "11", "12");

$monthNames=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

//---------------------------------------------------------------
function connectdb() {
	mysql_connect(CHEQUE_DB_HOST, CHEQUE_DB_USER, CHEQUE_DB_PWD) ;
	mysql_select_db(CHEQUE_DB_NAME);
	mysql_query( "SET NAMES utf8");
}


// format date for export print dpf
function cheque_formatdate($date) {
	$monthNum = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
	$parts = explode("-", $date);
	if (count($parts) < 3) return "";
	$parts[2] = (int)$parts[2];
	$parts[1] = (int)$parts[1];
	$parts[0] = (int)$parts[0];
	/*
	if ($parts[0] < $MIN_YEAR || $parts[0] > date("Y")+543) return "";
	if ($parts[1] < 1 || $parts[1] > 12) return "";
	if ($parts[2] < 1 || $parts[2] > 31) return "";
	*/
	$result = str_pad(((int)$parts[2]), 2, "0", STR_PAD_LEFT) . "" . str_pad($monthNum[((int)$parts[1]) - 1], 2, "0", STR_PAD_LEFT) . "" . ((int)$parts[0] + 543);
	$nresult = strlen($result);
	for ($nr = 0; $nr < $nresult; $nr++) {
		$n_str .= substr($result, $nr, 1)."    ";
	}
	$result = $n_str;
	return $result;
}
require dirname(__FILE__).'/bin/sessionstore.php';
require dirname(__FILE__). '/bin/siteprofile.php';
require dirname(__FILE__). '/bin/org.php';


?>
