<?php
$act = isset($_GET['act'])?$_GET['act']:'';
$id = $_GET['id'];



if(  $act == 'add' || $act == 'edit'):


$cm = $siteprofile->getOne($id);
$id_company = $cm->id_company;
$name = $cm->name;
$id_site = $cm->id_site;

?>
<script type="text/javascript">
	
$(function(){
	$("#c-site").on('change',function(){
		var el = $("#c-site option:selected");
		var name = el.data('name');
		var company = el.data('company');
		console.log(name);
		console.log(company);
		$("#c-name").val(name);
		$("#c-company").val(company);
	})
})	
</script>

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12">
<div class="well">



<form action="action.php" method="post" name="form-company" class="form-horizontal">
<input type="hidden" name="act" value="<?=$_GET['act']?>">
<div class="row">
<div class="col-xs-12 col-md-6">

 <label   class="control-label">ชื่อโปรไฟร์</label>
<input 
name="name"
type="text" 
class="form-control" 
id="c-name"
placeholder="ชื่อ"
value="<?=$cm->name?>"
required
>

<!-- site -->
<label for="c-site"   class="control-label">ไซต์/สาขา</label>
<?php
//if( $id_site !='' ) : ?>
<input type="hidden" name="old_id_site" value="<?=$cm->id_site?>" readonly class="form-control">
<?php // else: ?>
<select name="id_site"  class="form-control" id="c-site" required>
<option value="">--ไซต์งาน--</option>
<?php
foreach ($org->getSiteAll() as $site) {
     $txt = '';
    if( ! in_array($site['site_id'],$siteprofile->getAll(true))){
         $txt = ' (ว่าง)';
     }elseif($site['site_id'] == $cm->id_site){
        $txt = '  *** ';
     }
	echo'<option value="'.$site['site_id'].'" 
	'.($cm->id_site==$site['site_id']?'selected':'').' 
	data-name="'.$site['site_name'].$txt.'"
	data-company="'.$site['company_id'].'"
	>';
	echo $site['site_name'].$txt.'</option>';
}
?>
 </select>

<?php  //endif;?>

  </div>
  <div class="col-xs-12 col-md-6">
    <!-- company -->
    <label for="c-company"   class="control-label">บริษัท</label>
    <select name="id_company"  class="form-control" id="c-company" > 
    <option value="">---บริษัท---</option>
<?php
foreach ($org->getCompanyAll() as $company) {
	echo'<option value="'.$company['id'].'" '.($id_company==$company['id']?'selected':'').'>';
	echo $company['name'];
	echo '</option>';
}
?>
    </select>



    <!-- Bank -->

    <label for="c-company"  class="control-label">ธนาคาร</label>
    <select name="id_bank"  class="form-control" id="c-company" required>
    <option value="">---ธนาคาร---</option>
<?php
foreach ($org->getBankAll() as $bank) {
	echo'<option value="'.$bank['id_bank'].'" '.($cm->id_bank==$bank['id_bank']?'selected':'').'>';
	echo $bank['name'].' - '.$bank['branch'] .' เลขที่ '. $bank['number'];
	echo '</option>';
}
?>
  </select>
  

    <!-- active -->

    <label for="c-active" class="control-label">Active</label>
    <input name="active" type="checkbox"  class="checkbox-inline" id="c-active" value="1" <?=$cm->active==1?'checked':''?>>

</div>
</div>

  <button type="submit" class="btn btn-primary">บันทึก</button>
  <a href="index.php" class="btn btn-default">ยกเลิก</a>

</form>
</div>


</div>
</div>

<?php
endif;
?>