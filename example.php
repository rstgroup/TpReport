<?php

require 'vendor/autoload.php';

// IDs of projects
$projects = array(1, 2, 4);

// bugs states
$states = array(
    'New', 'In progress'
);
$report = new \TpReport\Request('http://localhost/TargetProcess/api/v1/', 'YOUR_USERNAME', 'YOUR_PASSWORD');
$q = $report->collection('Bugs')
        ->where(array(
    'and',
    "Priority.Name eq 'Unspecified'",
    array('in', 'EntityState.Name', $states),
    array('in', 'Project.Id', $projects)
        ));

$result = $q->query();

echo 'Count: ' . count($result->Items);
echo PHP_EOL;

