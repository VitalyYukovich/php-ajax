<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Brainforce</title>
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>

	<script>
		$('document').ready(function(){
			$('.submit').click(function() {
				var minPrice = $('.minPrice').val();
				var maxPrice = $('.maxPrice').val();
				var amount = $('.amount').val();

				if(minPrice.match(/^\d+$/) && maxPrice.match(/^\d+$/) && amount.match(/^\d+$/)){

					var data=$('.filter').serialize();
					$.ajax({
						url: 'insert.php',
						type: 'GET',
						data: data,
						error: function(){
							alert('ошибка');
						},
						success: function(data){
							var table = jQuery.parseJSON(data);

							$('.table tr:not(:first)').detach();
							$.each(table, function(i, itemsStrTable) {
								strTable = '';
								$.each(itemsStrTable, function(j, item) {
									strTable = strTable + '<td>' + item + '</td>';
								});
								$('.table').append('<tr>'+ strTable +'<td></td></tr>');
							});
							
						}
					})
				}
				else alert("Должны быть заполнены все поля. Вводить можно только цифры.");	
			});
		});

	</script>
</head>
<body>
	<div>
		<form class='filter'>
			Показать товары  у которых
			<select class='typePrice' name='typePrice'>
				<option>Розничная цена</option>
				<option>Оптовая цена</option>
			</select>
			от
			<input class='minPrice' type="text" name="minPrice" value="1000">
			до
			<input class='maxPrice' type="text" name="maxPrice"value="3000">
			рублей и на складе
			<select сlass='typeComparison' name=typeComparison>
				<option>Более</option>
				<option>Менее</option>
			</select>
			<input class='amount' type="text" name='amount' value="20">
			штук.
			<input class='submit' type="button" value="Показать товары">
		</form>
	</div>
	<?php

	require_once 'Classes/PHPExcel.php';
	require_once 'func.php';

	//$excel = PHPExcel_IOFactory::load('xls/pricelist.xls');
	//$list=$excel->getActiveSheet()->toArray();

	//WriteToDataBase($list, 'brainforce');
	$table = ReadFromDataBase('brainforce');

	PrintTable($table);

	echo '<br> Общее количество товаров на Складе1 и Складе2: ' . QueryToDataBase('brainforce', "SELECT SUM(amountOnFirstStorage) + SUM(amountOnSecondStorage) FROM `pricelist`");

	echo '<br><br> Средняя стоимость розничной цены товара: ' . QueryToDataBase('brainforce', "SELECT AVG(price) FROM `pricelist`");

	echo '<br><br> Средняя стоимость оптовой цены товара: ' . QueryToDataBase('brainforce', "SELECT AVG(tradePrice) FROM `pricelist`");
	?>

</body>
</html>