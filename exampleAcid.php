<?php

require 'vendor/autoload.php';

// Context acid 
$acid = 'XSVEYXVBKSPONKASKWEK217492';

$report = new \TpReport\Request('http://localhost/TargetProcess/api/v1/', 'YOUR_USERNAME', 'YOUR_PASSWORD');

// Querying for list of new (state: 'New') bugs in context defined by acid parameter.
$q = $report->collection('Bugs')
        ->setAcid($acid)
        ->where("EntityState.Name eq 'New'");
       
$result = $q->query();

echo 'Count: ' . count($result->Items);
echo PHP_EOL;
