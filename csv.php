<?php
require_once 'vendor/autoload.php';
require_once 'Class/Database.php';

header('Content-Type: application/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="data-'.time().'.csv";');

$queryDatabase = new \Forum\Database();
$results = $queryDatabase->getAllResults();
// open the "output" stream
$f = fopen('php://output', 'w');
$line =array('timestamp', 'title', 'imageName');
fputcsv($f, $line);
foreach ($results as $result) {
    $timestamp = $result->__get('timestamp');    
    $datotitle = $result->__get('title');
    $datoimageName = $result->__get('imageName');
    $line = array($timestamp, $datotitle, $datoimageName);
    fputcsv($f, $line);    
}