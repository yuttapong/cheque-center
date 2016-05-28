<?php
$app->get('/cheques',function(){
    global $pdo;
    global $mysql;
   //pls validate that are numbers
    $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
    $start = isset($_GET['start'])&&$_GET['start']>0 ? $_GET['start'] : 0;
    $end = isset($_GET['end'])&&$_GET['end']>0 ? $_GET['end'] : 20;
    $id_site = isset($_GET['id_site'])&&$_GET['id_site']>0?$_GET['id_site']:0;
    $year = isset($_GET['year'])&&$_GET['year']>0?$_GET['year']:'2016';
    $q = isset($_GET['q'])&&$_GET['q'] != '' ?$_GET['q']:'';
    $is_cancel = isset($_GET['is_cancel'])&&$_GET['is_cancel'] != '' ?$_GET['is_cancel']:0;
    $id_site_profile = isset($_GET['profile_id'])&&$_GET['profile_id']>0?$_GET['profile_id']:0;

    $cheque_name = isset($_GET['cheque_name'])&&$_GET['cheque_name'] != '' ?$_GET['cheque_name']:'';
    $offset = (--$page) * 20; //calculate what data you want
        //page 1 -> 0 * 10 -> get data from row 0 (first entry) to row 9
        //page 2 -> 1 * 10 -> get data from row 10 to row 19
    $condition_q = '';
    if($q !='' || $q != 'underfined') {
        $condition_q .= " AND c.cheque_no LIKE  :q  ";
    }
    $qry_q = '%' . $q . '%';
    $qry_name = '%' . $cheque_name . '%';
    $sql = "SELECT c.id,c.cheque_no,c.cheque_name,c.cheque_date,cheque_ac,cheque_status,
    c.cheque_price,c.amount,c.created_at, c.id_site,c.created_name,c.created_by,
    c.bank_code,c.bank_name,c.id_site_profile,c.is_cancel,c.cancel_note,
    sp.name AS site_name,
    (select i.title from cheque_cheque_items i where id_cheque=c.id  order by i._id asc limit 1)cheque_for
    FROM cheque_cheque c
    left join cheque_site_profile sp ON sp.id_site = c.id_site
    WHERE
     c.id_site_profile=:id_site_profile
    AND c.is_cancel=:is_cancel
    AND ( c.cheque_no LIKE  :q
    AND c.cheque_name LIKE :cheque_name)
    order by c.created_at DESC
     ";

    try{
    $cc = $pdo->prepare($sql);
    $cc->execute(array(
        'id_site_profile' =>$id_site_profile ,
        'is_cancel' => $is_cancel,
        'q'=>$qry_q,
        'cheque_name'=>$qry_name,
        ));
     $totalCount = $cc->rowCount();

    $sql .= "limit :start,:end";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('id_site_profile', $id_site_profile, PDO::PARAM_INT);
    $stmt->bindParam('is_cancel', $is_cancel, PDO::PARAM_INT);
    $stmt->bindParam('start', $start, PDO::PARAM_INT);
    $stmt->bindParam('end', $end, PDO::PARAM_INT);
    $stmt->bindParam('q', $qry_q, PDO::PARAM_STR);
    $stmt->bindParam('cheque_name', $qry_name, PDO::PARAM_STR);
    $stmt->execute();
    $data  = array(
                    'msg' => "limit {$start}, {$end}",
                    'page' => $page,
                    'year' => $year,
                    'total' => $totalCount,
                    'data' => $stmt->fetchAll(),
    );
     echo json_encode($data);
    }
    catch(PDOException $e){
            echo $e->getMessage();
    }

  });



///////////////////////////// Cheque   today ////////////////////////////////////
$app->get('/cheques/today',function() use ($pdo,$app){
    $id_site_profile = $app->request()->params('profile_id');
    try{
    $sql = "SELECT c.id,c.cheque_no,c.cheque_name,c.cheque_date,cheque_ac,cheque_status,
    c.cheque_price,c.amount,c.created_at, c.id_site,
    c.bank_code,c.bank_name,
    sp.name AS site_name
    FROM cheque_cheque c
    LEFT JOIN  cheque_site_profile sp ON sp.id_site = c.id_site
    WHERE 1 AND
    c.is_cancel=0
    AND c.id_site_profile=:id_site_profile
    AND DATE(NOW()) = cheque_date
    ORDER BY c.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('id_site_profile', $id_site_profile, PDO::PARAM_INT);
    $stmt->execute();
    $data  = array(
                    'data' => $stmt->fetchAll(),
                    'id_site_profile' => $id_site_profile,
    );
     echo json_encode($data);
    }
    catch(PDOException $e){
            echo $e->getMessage();
    }

  });


$app->get('/cheque/:id', function($id) use($pdo) {
    $sql = "SELECT c.*,
    sp.name site_name
    FROM cheque_cheque c
    left join cheque_site_profile sp ON sp.id_site = c.id_site
    WHERE c.id = :id
    limit 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetch();
    echoResponse(200, $rows);
});

//save cheque
$app->post('/cheques', function() use ($app,$pdo) {

    $data = json_decode($app->request->getBody());


    $sql = "INSERT INTO cheque_cheque ";
    $sql .= " (
    cheque_no,
    cheque_date,
    cheque_name,
    cheque_price,
    amount,
    cheque_ac,
    cheque_status,
    bank_id,
    bank_code,
    bank_name,
    bank_branch,
    bank_no,
    id_site,
    id_site_profile,
    created_by,
    created_name,
    updated_name,
    created_at,
    updated_at
    ) ";
    $sql .= " VALUES  ";
    $sql .= "(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
    $params = array(
        $data->cheque_no,
        $data->cheque_date,
        $data->cheque_name,
        $data->cheque_price,
        $data->amount,
        $data->cheque_ac,
        $data->cheque_status,
        $data->bank_id,
        $data->bank_code,
        $data->bank_name,
        $data->bank_branch,
        $data->bank_no,
        $data->id_site,
        $data->id_site_profile,
        $data->username,
        $data->created_name,
        $data->created_name
        );
    try {
      $rs = $pdo->prepare($sql);
      $rs->execute($params);
      $lastId = $pdo->lastInsertId();
      $qry = array(
         'id' => $lastId,
        );

      if($lastId){
         if(count($data->records)>0){
             foreach ($data->records as $item) {
                if($item->_act=='add') {
                $rsItem = $pdo->prepare("insert into cheque_cheque_items
                    (table_id,created_at,active,ref_id,id_cheque,title,price,id_site,id_site_profile)
                    values
                    (8,UNIX_TIMESTAMP(),1,NULL,?,?,?,?,?)
                    ");
                $rsItem->execute(array(
                        $lastId,
                        $item->title,
                        $item->price,
                        $data->id_site,
                        $data->id_site_profile,
                    ));
                }
          }
         }
      }
      echoResponse(200,$qry);
   } catch(PDOException $ex){
     echo $ex->getMessage();
   }
});


// get cheque item
$app->get('/cheque-items/:id', function($id) use ($pdo){
   $rs = $pdo->prepare("select
    _id,title,price,active,created_at,id_site,id_site_profile,'edit' as _act
    from cheque_cheque_items
    where id_cheque = ?
    order by created_at asc");
   $rs->execute(array($id));
   $rows = $rs->fetchAll();
   echoResponse(200,$rows);
});

// update Cheque
$app->post('/cheques/:id', function($id) use ($app,$pdo) {
    $data = json_decode($app->request->getBody());
    $sql = "update  cheque_cheque set  ";
    $sql .= "
    cheque_no=?,
    cheque_date=?,
    cheque_name=?,
    cheque_price=?,
    amount=?,
    cheque_ac=?,
    bank_id=?,
    bank_code=?,
    bank_name=?,
    bank_branch=?,
    bank_no=?,
    id_site_profile=?,
    updated_name=?,
    updated_at=NOW()
    where id = ? ";
    $params = array(
        $data->cheque_no,
        $data->cheque_date,
        $data->cheque_name,
        $data->cheque_price,
        $data->amount,
        $data->cheque_ac,
        $data->bank_id,
        $data->bank_code,
        $data->bank_name,
        $data->bank_branch,
        $data->bank_no,
        $data->id_site_profile,
        $data->updated_name,
        $id
        );
    try {
      $rs = $pdo->prepare($sql);
      $rs->execute($params);
         if(count($data->records)>0){
            $ok = '';
             foreach ($data->records as $item) {
                    if($item->_act=='add'){
                        $rsItem = $pdo->prepare("insert into cheque_cheque_items
                            (table_id,created_at,active,ref_id,
                            id_cheque,title,price,id_site,id_site_profile)
                            values (8,UNIX_TIMESTAMP(),1,NULL,?,?,?,?,?)
                            ");
                         $rsItem->execute(array(
                                $id,
                                $item->title,
                                $item->price,
                                $data->id_site,
                                $data->id_site_profile
                            ));
                     }else{
                        $rsUpdate = $pdo->prepare("UPDATE cheque_cheque_items
                          SET title=?, price=?,id_site=?,id_site_profile=? WHERE _id=? ");
                        $rsUpdate->execute(array(
                          $item->title,
                          $item->price,
                          $data->id_site,
                          $data->id_site_profile,
                          $item->_id,
                        ));

                    }
                }
         }
      echoResponse(200,true);
   } catch(PDOException $ex){
       echo $ex->getMessage();
   }
});

// change status
$app->post('/cheque/set-status',function() use ($pdo,$app){
    $data = json_decode($app->request->getBody());
    $data->cheque_status;

   if($data->cheque_status =='Y')
       $t = 'N';
   if($data->cheque_status =='N')
        $t = 'Y';

    $sql = "update cheque_cheque set cheque_status = ?  where id =? ";
    $rs = $pdo->prepare($sql);
     $rs->execute(array($t,$data->id));
     $log_detail = "เปลี่ยนสถานะเช็ค ID#$data->id   ($data->cheque_status => $t)  ";
     saveLog("CHEQUE","เปลี่ยนสถานะ",$log_detail,$data->id);
     echo json_encode(array('cheque_status'=>$t));

});


//////////////////////////////// search cheuqe  //////////////////////////////
$app->get('/cheques/search', function() use ($app,$pdo){
     $params = array();
     $q = $app->request()->params('q');
     $cheque_date = $app->request()->params('cheque_date');
        $sql = "select  *
        from cheque_cheque
        where 1 ";
        $sql .=" AND (cheque_no LIKE ? OR cheque_name LIKE ? ) ";
       $params[] = "%$q%";
       $params[] = "%$q%";

        $sql .= "  OR (cheque_date = ? ) ";
        $params[] = $cheque_date;
        $sql .= " order by id DESC ";

        $rs =$pdo->prepare($sql);
        $rs->execute($params);
        $rows = $rs->fetchAll();
        echoResponse(200, $rows);
});

//////////////////////////////// Group  ///////////////////////////////
$app->get('/cheque-group/list', function() use ($pdo){
    $st = $pdo->prepare("select id,name from cheque_cheque_group group by name");
    $st->execute();
    $rows = $st->fetchAll();
    echoResponse(200, $rows);
});


//////////////////// Delete Cheque Item ////////////////////////
$app->delete('/cheque/delete/:item_id',function($item_id) use ($app,$pdo){

    try{
        if($item_id > 0 ){
            $st = $pdo->prepare("DELETE FROM cheque_cheque_items where _id=? ");
            $st->execute(array($item_id));
            $data = array(
                'status' =>1 ,
                'result'=> "Record deleted successfully : ".$item_id,
                );
        }
    }catch(PDOException $e){
        $data = array('status' => 0 ,'result'=> "unsuccessfully");
    }
    echoResponse(200,$data);

});

$app->get('/report',function() use ($pdo,$app){
    $id_site_profile = $app->request()->params('profile_id');
    $d1 = $app->request()->params('start');
    $d2 = $app->request()->params('end');
    $condition = " AND id_site_profile='$id_site_profile' AND is_cancel=0 ";
    $condition .= ($d1!=''&&$d2!='')?" AND cheque_date BETWEEN '$d1' AND '$d2' ":"";
     $sql = "SELECT CURDATE() today,
    (SELECT count(cheque_no)  FROM cheque_cheque WHERE 1 $condition  LIMIT 1) all_qty,
    (SELECT sum(cheque_price) FROM cheque_cheque WHERE 1 $condition LIMIT 1) all_price,
    (SELECT count(cheque_no)  FROM cheque_cheque WHERE  cheque_status='Y'  $condition LIMIT 1) success_qty,
    (SELECT sum(cheque_price) FROM cheque_cheque WHERE  cheque_status='Y' $condition LIMIT 1) success_price,
    (SELECT count(cheque_no)  FROM cheque_cheque WHERE cheque_status='N' AND CURDATE()>= DATE(cheque_date) $condition  LIMIT 1) unsuccess_qty,
    (SELECT sum(cheque_price) FROM cheque_cheque WHERE cheque_status='N' AND CURDATE()>= DATE(cheque_date)  $condition  LIMIT 1) unsuccess_price,
    (SELECT count(cheque_no)  FROM cheque_cheque WHERE cheque_status='N' AND CURDATE()< DATE(cheque_date)  $condition  LIMIT 1) pending_qty,
    (SELECT sum(cheque_price) FROM cheque_cheque WHERE cheque_status='N' AND CURDATE()< DATE(cheque_date) $condition  LIMIT 1) pending_price,
    (SELECT count(cheque_no)  FROM cheque_cheque WHERE cheque_status='N' AND CURDATE() = DATE(cheque_date)  $condition  LIMIT 1) today_qty,
    (SELECT sum(cheque_price) FROM cheque_cheque WHERE cheque_status='N' AND CURDATE() = DATE(cheque_date) $condition   LIMIT 1) tody_price
    FROM cheque_cheque
    LIMIT 1";
    $rs = $pdo->prepare($sql);
    $rs->execute();
    $rows = $rs->fetch();
	$data['record'] = $rows;
	$data['start']  =$d1;
	$data['end'] = $d2;
            $data['id_site_profile'] = $id_site_profile;
    echoResponse(200,$data);
});

//////////////////// cancel Cheque  ////////////////////////
$app->post('/cheques/cancel/:id',function($id) use ($app,$pdo){
    $data = json_decode($app->request->getBody());
    try{
        if($id > 0 ){
            $st = $pdo->prepare("UPDATE  cheque_cheque
              SET is_cancel=1,cancel_note=?
              where id=? ");
            $st->execute(array($data->cancel_note,$id));
            $data = array(
                'result' =>1 ,
                'msg'=> "cancel successfully   : ".$id,
                );
        }
    }catch(PDOException $e){
        $data = array('result' => 0 ,'result'=> "unsuccessfully");
    }
    echoResponse(200,$data);

});
