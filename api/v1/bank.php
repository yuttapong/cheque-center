<?php
$app->get('/bank/list', function() { 
    global $pdo;
    $st = $pdo->prepare("select * from cheque_bank ");
    $st->execute();
    $rows = $st->fetchAll();
    echoResponse(200, $rows);
});

$app->get('/bank/:id', function($id) { 
    global $pdo;
    $st = $pdo->prepare("select * from cheque_bank where id_bank=?");
    $st->execute(array($id));
    $rows = $st->fetch();
    echoResponse(200, $rows);
});
