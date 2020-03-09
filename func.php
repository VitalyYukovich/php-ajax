<?php

	function WriteToDataBase($table, $nameDataBase){
		$mysqli= new mysqli('localhost', 'root', '', $nameDataBase);
		foreach($table as $row){
			$string = '(NUll, ';
			foreach($row as $col){
				$string.="'".$mysqli->real_escape_string($col)."',";
			}
			$string=trim($string, ",");
			$string.=')';
			$mysqli->query("INSERT INTO `pricelist` VALUES". $string);
		}
	}

	function ReadFromDataBase($nameDataBase){
		$mysqli = mysqli_connect('127.0.0.1', 'root', '', 'brainforce');
		$result = $mysqli->query("SELECT * FROM `pricelist`");
		while($string = $result->fetch_assoc()){
			$table[]= array($string['name'] , $string['price'], $string['tradePrice'], $string['amountOnFirstStorage'], $string['amountOnSecondStorage'], $string['countryOrigin']);
		}
		return $table;
	}

	function PrintTable($table){
		$maxPrice = QueryToDataBase('brainforce', "SELECT MAX(price) FROM `pricelist`");
	 	$minTradePrice = QueryToDataBase('brainforce', "SELECT MIN(tradePrice) FROM `pricelist`");
		echo '<table border="1" cellspacing="0" class = "table">';
		echo '<tr><th>Наименование товара</th><th>Стоимость</th><th>Стоимость Опт</th><th>Количество товара на 1 складе</th><th>Количество товара на 2 складе</th><th>Страна производитель</th><th>Примечание</th></tr>';
	 	for ($i=0; $i<count($table); $i++){
	 		echo '<tr>';
	 		for($j=0; $j<count($table[$i]); $j++){
	 			$color = 'black';
	     		if($j==0 && $table[$i][1] == $maxPrice)
	     			$color = 'red';
	     		if($j==0 && $table[$i][2] == $minTradePrice)
	     			$color = 'green';
	     		echo '<td><font color="'.$color.'">'.$table[$i][$j].'</font></td>';
	 		}
	 		$messege="";
	 		if($table[$i][3] + $table[$i][4] < 20){
	 			$messege="Осталось мало! Срочно докупите!";
	 		}
	 		echo '<td>'.$messege.'</td></tr>';
	 	}	
	 	echo '</table>';
	}

	function QueryToDataBase($nameDataBase, $query){
		$mysqli= new mysqli('localhost', 'root', '', $nameDataBase);
		$result= $mysqli->query($query);
		$result=$result->fetch_array();
		return number_format((double)$result[0],2,'.', '');
	}