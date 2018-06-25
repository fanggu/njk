<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="../datetime/jquery-ui-timepicker-addon.css">
<script src="../datetime/jquery-ui-timepicker-addon.js"></script>
<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// First, include Requests
include('../library/Requests.php');
include('../php-barcode-generator-master/generate-verified-files.php');

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
$response = $request->body;
$response_body = json_decode($response,1);

//date_default_timezone_set('UTC');


if(isset($_POST['submitButton'])){
	$from_date 		= $_POST['from_date']; 
	$to_date 		= $_POST['to_date'];	
} 

if($from_date != '' && $to_date != ''){
	$i = 1; 
	$product_sku 		= ""; 
	$product_name 		= ""; 

	$generator = new Picqer\Barcode\BarcodeGeneratorHTML();

	$html = '<table style="border-collapse: collapse;">';
	foreach ($response_body as $value) {

		$product_sku 		= $value['styleCode'];
		$product_name 		= $value['name']; 

		if(!empty($value['productOptions'])){
			$productOptions = $value['productOptions'];

			$product_modifiedDate 	= "";
			$product_name_english 	= "";
			$product_barcode 		= "";
			$product_retailPrice 	= "";
			$product_specialPrice 	= "";
			$pricing_data 			= "";

			foreach ($productOptions as $keyrow => $row) {
				$product_modifiedDate 	= $row['modifiedDate'];
				//$modifiedDate 	= strtotime($row['modifiedDate']);
				//$from_date 		= strtotime($from_date);
				

				//echo(date("Y-m-dTG:i:sz",$modifiedDate) . "<br />");
				// echo $modifiedDate_1 = date("Y-m-dTG:i:sz",$modifiedDate);

				date_default_timezone_set("UTC");

				// echo $modifiedDate = date("Y-m-dTG:i:sz", $modifiedDate);
				//echo $modifiedDate = date("Y-m-d H:i:s", $modifiedDate);

				$modifiedDate1 	= new DateTime($row['modifiedDate']);
				$from_date1 	= new DateTime($from_date);
				$to_date1 		= new DateTime($to_date);
				
							
				if($from_date1 <= $modifiedDate1 && $modifiedDate1 >= $to_date1){
					$product_name_english 	= $row['option1'];
					$product_barcode 		= $row['barcode'];
					$product_retailPrice 	= $row['retailPrice'];
					$product_specialPrice 	= $row['specialPrice'];

					if($product_specialPrice > 0){
						$pricing_data = '<div class="price_data" style="width:23%;float:left;padding:5px;">
										<span style="padding: 25px;text-decoration: line-through;">'.$product_retailPrice.'</span><br />
										<span style="padding: 25px;">'.$product_specialPrice.'</span>
									</div>';
					}else{
						$pricing_data = '<div class="price_data" style="width:23%;float:left;padding:5px;">
										<span style="padding: 25px;">'.$product_retailPrice.'</span><br />
									</div>';
					}

					if($product_barcode){
						$product_barcode_data = $generator->getBarcode($product_barcode, $generator::TYPE_EAN_13);
					}else{
						$product_barcode_data = $generator->getBarcode($product_sku, $generator::TYPE_CODE_128);
					}
					
					if ($i == 1) { 
						$html .= "<tr>"; 
					} 

					$html .= '<td style="border: 1px solid black;">
							<span>'.$product_sku.'</span><br />
							<span>'.$product_name.'</span><br />
							<span>'.$product_name_english.'</span><br /><br />
							<div class="content" style="width:250px;">
								<div class="barcode_data" style="width:69%;float:left;padding:5px;">
									<span style="width: 25px;">'.$product_barcode_data.'</span>
									<span style="padding: 25px;">'.$product_barcode.'</span>
								</div>
								'.$pricing_data.'
							</div>						
						</td>';

					if($i == 3) {
						$html .= "</tr>"; 
						$i = 1;
					}else {
						$i++;
					}

				}
			
			}
		}
	}
	echo $html .= '</table>';
}




$form_data = '<h3>Print Product labels</h3>
	<form action="" method="POST">
	From Date : <input class="datepicker" type="text" id="order_date_from" name="from_date">
	To Date : <input class="datepicker" type="text" id="order_date_to" name="to_date">
	<input type="submit" name="submitButton" value="Get Pricing Labels">
</form>';

echo $form_data;
?>

<script>
$( function() {
	$( ".datepicker" ).datetimepicker();
} );
</script>
