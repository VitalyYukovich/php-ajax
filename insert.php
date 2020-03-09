<?php
	require_once 'func.php';

	

	$typePrice = $_GET['typePrice'];
	$minPrice = $_GET['minPrice'];
	$maxPrice = $_GET['maxPrice'];
	$typeComparison = $_GET['typeComparison'];
	$amount = $_GET['amount'];

	$price = !strcmp($typePrice, "Розничная цена") ? 'price' : 'tradePrice';
	$queryStr  = "SELECT * FROM `pricelist` WHERE " . $price . "> ? AND " . $price . "< ? AND amountOnFirstStorage + amountOnSecondStorage " .  (!strcmp($typeComparison, 'Более')? '>': '<') . '?'; 

	$mysqli = new mysqli('127.0.0.1', 'root', '', 'brainforce');
	$result = $mysqli->prepare($queryStr);
	$result->bind_param('iii', $minPrice, $maxPrice, $amount);
	$result->execute();
	$result = $result->get_result();

	while($string = $result->fetch_assoc()){
		$table[]= array($string['name'] , $string['price'], $string['tradePrice'], $string['amountOnFirstStorage'], $string['amountOnSecondStorage'], $string['countryOrigin']);
	 }
	
	
	echo json_encode($table);