<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="../datetime/jquery-ui-timepicker-addon.css">
<script src="../datetime/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">

<!-- <script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.0.3.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="dist/simplePagination.css" />
<script src="dist/jquery.simplePagination.js"></script> -->

<?php
session_start();
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// First, include Requests
include('../library/Requests.php');
include('../php-barcode-generator-master/generate-verified-files.php');

define("API_BASE_URL", 'https://api.cin7.com/api/v1/');
define("BASIC_AUTH", 'Basic bmprZ3JvdXBOWjo4N2I1MDczODllNTc0OGZmYjJjZGE1YzBlMmJlZGM1Ng==');

// date_default_timezone_set("NZ");

$where = '';
if(isset($_POST['submitButton'])){
	if($_POST['from_date'] != '' && $_POST['to_date'] != ''){
		$_SESSION['from_date'] 	= $_POST['from_date'];
		$_SESSION['to_date'] 	= $_POST['to_date'];
	}
}

if($_SESSION['from_date'] != '' && $_SESSION['to_date'] != ''){
	$from_date 		= strtotime($_SESSION['from_date']); 
	$to_date 		= strtotime($_SESSION['to_date']); 

	$from_date 		= str_replace('+00:00', 'Z', gmdate('c', strtotime($_SESSION['from_date'])));
	$to_date 		= str_replace('+00:00', 'Z', gmdate('c', strtotime($_SESSION['to_date'])));

	$where = "modifieddate>='$from_date'&modifieddate<='$to_date'";
}

$currentPage = 1;
if(!empty($_GET['page'])){
	$currentPage = $_GET['page'] ? $_GET['page'] : 1;
}


function getOptions($where, $currentPage){

	$_validParams = array('fields'=>null,'where'=>$where,'order'=>'desc','page'=>$currentPage,'rows'=>'');

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


if($where){

	//echo $requestUrl = API_BASE_URL . 'Products?' . http_build_query(getOptions($where,$currentPage), '', '&', PHP_QUERY_RFC3986);

	$query_result = getOptions($where,$currentPage);

	$requestUrl = API_BASE_URL . 'Products?where='.$query_result['where'].'&page='.$currentPage.'&rows="'.$query_result['rows'].'"';
	//echo '<br>';
	//echo '<br>'. $requestUrl = "https://api.cin7.com/api/v1/Products?where=modifieddate>='2018-06-19T11:58:00Z'&modifieddate<='2018-06-26T11:58:00Z'&page=".$currentPage."&rows=''";
	//print('<br/><br/> url : ---------->>> <br/>' . $requestUrl . '<br/><br/>');
	$request = Requests::get($requestUrl, $header);
	// Check what we received
	// var_dump(json_decode($request->body));
	// echo "<br/><br/>------------<br/>request respond: <br/>" ;
	// var_dump($request);
	$response = $request->body;
	$response_body = json_decode($response,1);
	//echo '<pre>'; print_r($response_body); exit;
	
}



$total_records 	= 250;  
$limit 			= 50;
$total_pages = ceil($total_records / $limit);
$total_pages11 = $total_records - $limit;
$offset = ($currentPage - 1) * $limit + 1;


if(!empty($response_body)){
	$i = 1; 
	$product_sku 		= ""; 
	$product_name 		= ""; 

	$generator = new Picqer\Barcode\BarcodeGeneratorHTML();

	$html = '<table style="border-collapse: collapse;" class="table table-bordered">';

	if($currentPage == $total_pages){
		$length = $total_pages11;
	}else{
		$length = $limit;
	}

	//for($k=$offset; $k<=$length; $k++){

	//}
		//$value = $response_body[$k];

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
				$modifiedDate 	= strtotime($row['modifiedDate']);
				$from_date 		= strtotime($from_date);

				//$modifiedDate = date("Y-m-dTG:i:sz", $modifiedDate);
				$modifiedDate = date("Y-m-d H:i:s", $modifiedDate);
				$modifiedDate1 	= strtotime($modifiedDate);

				//echo $modifiedDate1 	= new DateTime($row['modifiedDate']); exit;
				//$from_date1 	= new DateTime($from_date);
				//$to_date1 		= new DateTime($to_date);
							
				$product_name_english 	= $row['option1'];
				$product_barcode 		= $row['barcode'];

				if($product_barcode == 'vege007' || $product_barcode == 'fruit057' || $product_barcode == 'vege008' || $product_barcode =='vege013' || $product_barcode =='vege002' || $product_barcode =='vege011' || $product_barcode =='fruit014' || $product_barcode =='FROZEN1074' || $product_barcode == 'KITCHEN0188' || $product_barcode =='KITCHEN0189' || $product_barcode =='KITCHEN0190'){
					continue;
				}

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

				$product_barcode_data = '';

				if($product_barcode ){
					$product_barcode_data = $generator->getBarcode($product_barcode, $generator::TYPE_EAN_13);
				}else if($product_sku){
					$product_barcode_data = $generator->getBarcode($product_sku, $generator::TYPE_CODE_128);
				}
				
				if ($i == 1) { 
					$html .= "<tr>"; 
				} 

				$html .= '<td style="border: 1px solid black;">
						<span>'.$product_modifiedDate.'</span><br />
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
	echo $html .= '</table>';
}



$pagLink = "<nav><ul class='pagination'>";
for ($i=1; $i<=$currentPage + 1; $i++) {  
         $pagLink .= "<li><a href='cin7api.php?page=$i'>".$i."</a></li>";
};  
echo $pagLink . "</ul></nav>";  


if(!isset($_POST['submitButton'])){
	$form_data = '<h3>Print Product labels</h3>
	<form action="" method="POST">
	From Date : <input class="datepicker" type="text" id="order_date_from" name="from_date">
	To Date : <input class="datepicker" type="text" id="order_date_to" name="to_date">
	<input type="submit" name="submitButton" value="Get Pricing Labels">
	</form>';
	echo $form_data;
}
?>

<script type="text/javascript">
$( function() {
	$( ".datepicker" ).datetimepicker();
});
</script>

