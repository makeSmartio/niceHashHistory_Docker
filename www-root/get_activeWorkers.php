<?php
$address = "empty";
$response = "empty";
$records = 0;
$errors = 0;

echo date("Y-m-d H:i:s");

include("connect.php");
$link=Connection();

function customError($errno, $errstr, $address, $response) {
	$link=Connection();
	echo "<b>Error:</b> [$errno] $errstr  <p>";
	$sql="Insert into niceHashErrors (address, description, data) values ('".$address."','.$errstr.','".$response."');";
		//echo $sql;
		$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));
  }
  
//set error handler
set_error_handler("customError");	

$query = "select max(id)+1 as batchId from pollingLog;";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_assoc($result);
$batchId = isset($row['batchId']) ? $row['batchId'] : 0;



$query="SELECT distinct address
FROM niceHashKeys 
where Date_Add(lastUpdate, INTERVAL 4.8 MINUTE)<now() or lastUpdate =''
order by lastUpdate;";
//echo $query . "<br>";

$results = mysqli_query($link,$query) or die('Could not look up user information; ' . mysqli_error($link));
while($row = mysqli_fetch_array($results))
{
	$records++;
	$address = $row["address"];
	//echo "nhAddress: $address";
	
	$url = "https://api2.nicehash.com/main/api/v2/mining/external/".$address."/rigs/activeWorkers";
	echo $url;

	$response = file_get_contents($url);

	$status_line = $http_response_header[0];
    preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);

    $status = $match[1];

    if ($status !== "200") {
        echo ("Unexpected response status: {$status_line}\n" . $response);
		$sql="Insert into niceHashErrors (address, description, data) values ('".$address."','".$status_line."','get.php');";
		echo $sql;
		$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));

		$sql="Update niceHashKeys set lastPollResult='".substr($status_line, 0, 255)."' where address='$address';";
		echo "<p>".$sql;
		$link->query($sql);
		$errors++;
		sleep(2);
		continue; //next row
    }

	$json = json_decode($response, TRUE);

	$workerId = 0;

	if (isset($json['workers'])) {
		foreach($json['workers'] as $item) { //foreach element in $arr

			$difficulty = isset($item['difficulty']) ? $item['difficulty'] : 0;
			$proxyId = isset($item['proxyId']) ? $item['proxyId'] : 0;
			$timeConnected = isset($item['timeConnected']) ? $item['timeConnected'] : 0;
			$xnsub = 0;//isset($item['xnsub']) ? $item['xnsub'] : 0;//getting errors on this one
			$speedAccepted = isset($item['speedAccepted']) ? $item['speedAccepted'] : 0;
			$speedRejectedR1Target = isset($item['speedRejectedR1Target']) ? $item['speedRejectedR1Target'] : 0;
			$speedRejectedR2Stale = isset($item['speedRejectedR2Stale']) ? $item['speedRejectedR2Stale'] : 0;
			$speedRejectedR3Duplicate = isset($item['speedRejectedR3Duplicate']) ? $item['speedRejectedR3Duplicate'] : 0;
			$speedRejectedR4NTime = isset($item['speedRejectedR4NTime']) ? $item['speedRejectedR4NTime'] : 0;
			$speedRejectedR5Other = isset($item['speedRejectedR5Other']) ? $item['speedRejectedR5Other'] : 0;
			$speedRejectedTotal = isset($item['speedRejectedTotal']) ? $item['speedRejectedTotal'] : 0;
			$profitability = isset($item['profitability']) ? $item['profitability'] : 0;
			$rigName = isset($item['rigName']) ? $item['rigName'] : 'Blank';
			$statsTime = isset($item['statsTime']) ? $item['statsTime'] : 0;
			$market = isset($item['market']) ? $item['market'] : 'Blank';
			$algorithm = isset($item['algorithm']['description']) ? $item['algorithm']['description'] : 'Blank';
			$unpaidAmount = isset($item['unpaidAmount']) ? $item['unpaidAmount'] : 0;

			$sql="Insert into niceHash (
			address,
			batchId,
			statsTime,
			market,
			algorithm,
			unpaidAmount,
			difficulty,
			proxyId,
			timeConnected,
			xnsub,
			speedAccepted,
			speedRejectedR1Target,
			speedRejectedR2Stale,
			speedRejectedR3Duplicate,
			speedRejectedR4NTime,
			speedRejectedR5Other,
			speedRejectedTotal,
			profitability,
			rigName,
			workerId) 
			values (
			'".$address."',
			$batchId,
			$statsTime,
			'$market',
			'$algorithm',
			$unpaidAmount,
			$difficulty,
			$proxyId,
			$timeConnected,
			$xnsub,
			$speedAccepted,
			$speedRejectedR1Target,
			$speedRejectedR2Stale,
			$speedRejectedR3Duplicate,
			$speedRejectedR4NTime,
			$speedRejectedR5Other,
			$speedRejectedTotal,
			$profitability,
			'$rigName',
			$workerId);";
			echo "<p>".$sql;
			$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));
		
			$workerId++;
			
		}

		$sql="Update niceHashKeys set lastPollResult='".substr($response, 0, 4000)."', lastUpdate=now() where address='$address';";
		echo "<p>".$sql;
		$link->query($sql);

		//sleep(2);
	}
	else //no workers in json
	{
		$sql="Insert into niceHashErrors (address, description, data) values ('".$address."','API Error','".substr($response, 0, 255)."');";
		echo $sql;
		$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));
		$sql="Update niceHashKeys set lastPollResult='".substr($response, 0, 255)."' where address='$address';";
		echo "<p>".$sql;
		$link->query($sql);
	}
}

	$sql="update niceHash set ignoreReading='true' where profitability*45000>10 and ignoreReading='false' and profitability*45000/IF(speedAccepted = 0, 1, speedAccepted)>1 and algorithm='DaggerHashimoto';";
	echo $sql;
	$result = mysqli_query( $link,$sql);

	$sql = "Insert into pollingLog (
		page,
		batchId,
		records,
		errors
	  )
	  values (
		  'get.php',
		  $batchId,
		  $records,
		  $errors);";
		  echo $sql;
		  $result = mysqli_query( $link,$sql);
	  
 ?>
