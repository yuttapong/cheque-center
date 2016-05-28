<?php
//////////////////////////////// Cheque name ///////////////////////////////

$app->get('/autocomplete/cheque-name/:q', function($q) use ($pdo,$app){ 
   $data = array();
   	try{
   	    if($q !=''){
			$rs = $pdo->prepare("SELECT * FROM (SELECT cheque_name
			FROM cheque_cheque
			WHERE cheque_name LIKE ?
			ORDER BY cheque_name ASC) a
			GROUP BY a.cheque_name");
			$rs->execute(array("%$q%"));
			while ($row = $rs->fetchObject()) {
				$data['items'][] = $row->cheque_name;
			}
	     }
		echo json_encode($data);
	} catch(PDOException $e){
            echo $e->getMessage();
    }
   
});
//////////////////////////////// Cheque Items  ///////////////////////////////
$app->get('/autocomplete/cheque-item/:q', function($q) use ($pdo,$app){ 
	$data = array();

      	try{
      		if($q !=''){
				$rs = $pdo->prepare("SELECT * FROM (SELECT title
				FROM cheque_cheque_items 
				WHERE title LIKE ?
				ORDER BY title ASC) a
				GROUP BY a.title");
				$rs->execute(array("%$q%"));
				while ($row = $rs->fetchObject()) {
					$data['items'][] = $row->title;
				}
	     		 }
		echo json_encode($data);
	} catch(PDOException $e){
            echo $e->getMessage();
    }
});

