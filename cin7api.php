<?php

// First, include Requests
include('../library/Requests.php');

define("API_BASE_URL", 'https://api.cin7.com/api/v1/');
define("BASIC_AUTH", 'Basic bmprZ3JvdXBOWjo4N2I1MDczODllNTc0OGZmYjJjZGE1YzBlMmJlZGM1Ng==');

// gather url params

function getOptions(){
	$_validParams = array('fields'=>null, 'where'=>'', 'order'=>'', 'page'=>1, 'rows'=>10);

	foreach($_GET as $key=>$value){

		if(!array_key_exists($key, $_validParams) || empty($value)){
			// echo "------------<br/>unset key: ".$key."<br/>";
			unset($_validParams[$key]);
			continue;
		}

		// echo "------------<br/>key: ".$key."<br/>";
		// echo "value: ".$value."<br/>";
		// echo "filter_input: " . filter_input(INPUT_GET, $key , FILTER_SANITIZE_URL) . "<br/>";

		$_validParams[$key] = filter_input(INPUT_GET, $key , FILTER_SANITIZE_URL);
	}

	// echo "------------<br/>valid params: <br/>" ;
	// var_dump($_validParams);
	return $_validParams;
}

// Next, make sure Requests can load internal classes
Requests::register_autoloader();

$header = array(
	'Authorization' => BASIC_AUTH,
	'Accept' => 'application/json',
);

// build http url query
$requestUrl = API_BASE_URL . 'Products?' . http_build_query(getOptions(), '', '&', PHP_QUERY_RFC3986);
// print('<br/><br/> url : ---------->>> <br/>' . $requestUrl . '<br/><br/>');

$request = Requests::get($requestUrl, $header);


// Check what we received
// var_dump(json_decode($request->body));
// echo "<br/><br/>------------<br/>request respond: <br/>" ;
// var_dump($request);

// output
print $request->body;
