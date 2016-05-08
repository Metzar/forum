<?php
require_once 'vendor/autoload.php';
require_once 'Class/Database.php';

$queryDatabase = new \Forum\Database();
$numberOfEntries = $queryDatabase->getcount();


$dataResponse = array ("count" => $numberOfEntries);

header('Content-Type: application/json');
echo json_encode($dataResponse);


