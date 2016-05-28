<?php
require '.././libs/Slim/Slim.php';



\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app = \Slim\Slim::getInstance();

require 'pdo.php';



function echoResponse($status_code, $response) {
    global $app;
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response,JSON_NUMERIC_CHECK);
}

function saveLog($log_type,$title,$detail="",$refer=""){
    global $pdo;
    $sql = "INSERT INTO cheque_log (created,log_type,title,detail,user,refer) VALUES (UNIX_TIMESTAMP(),?,?,?,?,?);";
    $rs = $pdo->prepare($sql);
    $rs->execute(array($log_type,$title,$detail,$refer));
}
///////////////////////////// Cheque  ////////////////////////////////////
require 'cheque.php';
//////////////////////////////// Bank ///////////////////////////////
require 'bank.php';
//////////////////////////////// Site Profile ///////////////////////////////
require 'siteprofile.php';
///////////////////////////// User ////////////////////////////////////

$app->get('/user/session', function() use ($pdo) { 
    $user =  isset($_SESSION['USERNAME'])?$_SESSION['USERNAME']:'';
    $profile =  isset($_SESSION['sc_user_profile'])?$_SESSION['sc_user_profile']:array();
    $data = array();
    if( ! empty($user)){
        $data = array('session' => true ,'user' => $user,'userprofile'=>$profile);
    }else{
        $data = array('session' => false,'user' => null,'userprofile'=>null );
    }
    echo json_encode($data);
});
////////////// cheque items //////////////////////////
require 'autocomplete.php';


$app->run();

?>