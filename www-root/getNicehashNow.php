<?php

include("connect.php");
$link=Connection();

if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

  $page = file_get_contents('https://bitpay.com/api/rates');
  $my_array = json_decode($page, true);

  $btcPrice = $my_array[2]["rate"];

  $id = isset($_GET['id']) ? $_GET['id'] : 0;
  $currency = isset($_GET['currency']) ? $_GET['currency'] : "USD";

  $query = "Select address
  From niceHashKeys
	Where id=$id;";
  //echo $query;
  $result = mysqli_query($link,$query);
  $row = mysqli_fetch_assoc($result);
  $address = $row['address'];

  $query = "Select sum(profitability) as profitability, TIMESTAMPDIFF(minute,ts,now()) ts
  From rigs2
	Where address='$address' and batchId in (select max(batchId) from rigs2 Where address='$address') 
  order by ts desc limit 1;";
  //echo $query;
  $result = mysqli_query($link,$query);
  $row = mysqli_fetch_assoc($result);
  $profitability = $row['profitability'];
  $ts = $row['ts'];

  if ($currency=="USD")
   {
     echo "$".number_format($profitability*$btcPrice,2);
   }
   else
   {
    echo "".number_format($profitability*1,6); 
   }
		
 ?>
