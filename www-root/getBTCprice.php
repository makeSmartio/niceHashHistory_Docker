<?php
	
	$page = file_get_contents('https://bitpay.com/api/rates');
	$my_array = json_decode($page, true);

	$exchange_rate = $my_array[2]["rate"];
  	
	echo '$'.number_format($exchange_rate,0,'',',');
 ?>
