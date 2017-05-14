<?php

require_once "vendor/autoload.php";

use Liridian\PincodeDirectory;

$client = new Liridian\PincodeDirectory();

$available_filters = $client->getAvailableFilters();

$available_fields = $client->getAvailableSelectFields();

$locale = $client
			->withApiKey($api_key)
			->withFilter([['pincode' => '400089']])
			->withSort([['pincode' => 'asc'], ['officename' => 'desc']])
			->get();

echo '<h1>PincodeDirectory Test</h1>';

echo 'Available filters:';
echo '<pre>'.json_encode($available_filters, JSON_PRETTY_PRINT).'</pre>';

echo 'Available fields:';
echo '<pre>'.json_encode($available_fields, JSON_PRETTY_PRINT).'</pre>';

echo 'Sample response:';
echo '<pre>'.json_encode($locale, JSON_PRETTY_PRINT).'</pre>';