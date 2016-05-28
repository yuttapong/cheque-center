<?php
require '../../public_inc.php';
require '.././libs/Slim/PDO/Database.php';
$dsn = 'mysql:host='.CHEQUE_DB_HOST.';dbname='.CHEQUE_DB_NAME.';charset=utf8';
$pdo = new \Slim\PDO\Database($dsn, CHEQUE_DB_USER, CHEQUE_DB_PWD);


