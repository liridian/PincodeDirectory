## PincodeDirectory

A PHP wrapper for the [All India Pincode directory](https://data.gov.in/resources/all-india-pincode-directory-along-contact-details/api) API provided by the [Ministry of Electronics & Information Technology,Government of India](https://data.gov.in/)


## Build Status

[![Build Status](https://travis-ci.org/liridian/PincodeDirectory.svg?branch=master)](https://travis-ci.org/liridian/PincodeDirectory)

## Usage

Here's an example of using the PHP wrapper -
```php
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
```