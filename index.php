<?php
header('Content-Type: application/json');
$object = new stdClass();
$symbols = explode(",", $_GET['symbols']);
$base=$_GET['base'];
$object->base = $base;
$rates = array();
foreach ($symbols as $key=>$value) {
	if ($value) {
		$rates[$value] = getExchange($base, $value);
	}
}
$object->rates = $rates;
echo json_encode($object);
function getExchange($from, $to)
{
	$get = file_get_contents("https://www.google.com/finance/converter?a=1&from=$from&to=$to");
	$get = explode("<span class=bld>",$get);
	$get = explode("</span>",$get[1]);  
	$converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
	if ($converted_amount<1) {
		$get = file_get_contents("https://www.google.com/finance/converter?a=1&from=$to&to=$from");
		$get = explode("<span class=bld>",$get);
		$get = explode("</span>",$get[1]); 
		$result1 = preg_replace("/[^0-9\.]/", null, $get[0]);
		return 1/$result1;
	}else{
		return $converted_amount;
	}
}
