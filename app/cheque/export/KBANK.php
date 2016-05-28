<?php 
header("content-type:text/html; charset=utf8");




include("../../../public_inc.php");
include("../../../bin/numbathtostrbath.php");
require('../../../bin/pdf/thaipdfclass.php');

connectdb();




$id = $_GET['id'];
$sql = "select * from cheque_cheque where id = '$id' ";

$result = mysql_query($sql) or die(mysql_error());
$rw = mysql_fetch_object($result);
$cheque_ac =  $rw->cheque_ac;
$cheque_act_show = ($cheque_ac == "Y") ? 'A/C PAYEE ONLY' : '';



define('FPDF_FONTPATH','../include/pdf/font/');
$pdf=new ThaiPDF('P', 'pt', 'A4');
$pdf->SetThaiFont();
$pdf->AddPage();

//cheque date
$pdf->SetFont('CordiaNew','',17); 
$pdf->text(350, 16, cheque_formatdate($rw->cheque_date)); //350/16

//A/C Payee Only
$pdf->SetFont('CordiaNew','',13); 
if ($cheque_ac == "Y") {
  $pdf->text(75, 46, $cheque_act_show); 
}
else {
  $pdf->text(75, 46, ""); 
}	

//cheque name
$pdf->SetFont('CordiaNew','',16); 
$pdf->text(75, 61,iconv("utf-8", "tis-620", $rw->cheque_name)); //105, 61
$pdf->text(110, 85, Cnv_TH_Money($rw->amount)); //115, 85
$pdf->text(320, 114, number_format($rw->amount, 2)); 
$pdf->Output();
?>

