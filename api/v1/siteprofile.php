<?php
//////////////////////////////// Site Profile ///////////////////////////////
$app->get('/siteprofile/list', function() use ($pdo){
    $rs = $pdo->query("select a.*,b.site_name
    	from cheque_site_profile a
    	inner join org_site b on b.site_id=a.id_site");
    $rs->execute();
    $rows = $rs->fetchAll();
    echo json_encode($rows);
});

$app->get('/siteprofile/:id', function($id) use ($pdo){
    $rs = $pdo->query("select a.*,b.site_name from cheque_site_profile a
    inner join org_site b on b.site_id = a.id_site
    where a.id='$id'
     ");
    $rs->execute();
    $rows = $rs->fetch();
    echo json_encode($rows);
});
