<?php

require 'vendor/autoload.php';
require 'TpReport.php';
require 'HttpErrorException.php';
require_once 'Config.php';

// IDs of projects
$projects = array(1, 2, 4);

// bugs states
$states = array(
  'New', 'In progress'
);
$report = new TpReport(Config::API_base_url, Config::username, Config::password);
$q = $report->collection('Bugs')
        ->where(array(
            'and', 
                "Priority.Name eq 'Unspecified'", 
                array('in', 'EntityState.Name', $states), 
                array('in', 'Project.Id', $projects)
                ));

try {
    $result = $q->query();
} catch (HttpErrorException $e) {
    echo $e->getCode().': '.$e->getMessage().PHP_EOL;
}

//counting bugs
echo 'Ilość: ' . count($result->Items);
echo PHP_EOL;