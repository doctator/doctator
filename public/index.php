<?php
require __DIR__ . '/../vendor/autoload.php';

$app = new \Doctator\Doctator\App();
$app->config(array(
    'debug' => TRUE,
    'templates.path' => '../templates',
	'resources.path' => '../res',
	'mongo.server' => 'mongodb://localhost:27017',
	'mongo.options' => array("connect" => TRUE)
));

$app->initialize();
$app->run();
