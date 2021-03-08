<?php
$address = "";
$response = "";
$records = 0;
$errors = 0;

echo date("Y-m-d H:i:s")."<pre>";

include("connect.php");
$link=Connection();

function customError($errno, $errstr, $address, $response) {
	$link=Connection();
	echo "<b>Error:</b> [$errno] $errstr  <p>";
	$sql="Insert into niceHashErrors (address, description, data) values ('".$address."','".$errstr."','get2.php');";
		echo $sql;
		$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));
  }
  
//set error handler
//set_error_handler("customError");	

$query = "select max(batchId)+1 as batchId from rigs2_polling;";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_assoc($result);
$batchId = isset($row['batchId']) ? $row['batchId'] : 0;

$query="select address from niceHashKeys where address not in (select address from rigs2_polling where ts>Date_Add(now(), INTERVAL -4.1 MINUTE));";
//echo $query . "<br>";

echo date("Y-m-d H:i:s");

$results = mysqli_query($link,$query) or die('Could not look up user information; ' . mysqli_error($link));
while($row = mysqli_fetch_array($results))
{
	$records++;
	$address = trim($row["address"]);
	//echo "nhAddress: $address";
	$url="https://api2.nicehash.com/main/api/v2/mining/external/$address/rigs2";
	//$url = "https://api2.nicehash.com/main/api/v2/mining/external/$address/rigs/activeWorkers";
	echo $url."<br>";

	$response = file_get_contents($url);

	$status_line = $http_response_header[0];
    preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);

    $status = $match[1];

    if ($status !== "200" || $status == "429 Too Many Requests") {
        echo ("Unexpected response status: {$status_line}\n" . $response);
		$sql="Insert into niceHashErrors (address, description, data) values ('".$address."','".$status_line."','get_rig2.php');";
		echo $sql;
		$result = mysqli_query( $link,$sql);
		
		$sql="Update niceHashKeys set lastPollResult='".substr($status_line, 0, 255)."' where address='$address';";
		echo "<p>".$sql;
		$link->query($sql);

		$errors++;
		sleep(1);
		continue; //next row
    }

	$json = json_decode($response, TRUE);

	var_dump($response);
    //exit();
	echo "<p>";
	
	$workerId = 0;
	if (isset($json['miningRigs'])) 
	{
		foreach($json['miningRigs'] as $miningRigs) 
		{ //foreach element in $arr
			if (isset($miningRigs['devices'])) 
			{
				foreach($miningRigs['devices'] as $devices)
				{
				
				$sql="Insert into devices (
					batchId,
					address,
					rigId,
					rigName,
					deviceId,
					deviceName,
					type,
					status,
					powerUsage,
					temperature,
					deviceLoad,
					revolutionsPerMinute,
					revolutionsPerMinutePercentage,
					intensity
					)
					values (
					$batchId,
					'".$address."',
					'".$miningRigs['rigId']."',
					'".$miningRigs['name']."',
					'".$devices['id']."',
					'".$devices['name']."',
					'".$devices['deviceType']['description']."',
					'".$devices['status']['description']."',
					'".$devices['powerUsage']."',
					'".$devices['temperature']."',
					'".$devices['load']."',
					'".$devices['revolutionsPerMinute']."',
					'".$devices['revolutionsPerMinutePercentage']."',
					'".$devices['intensity']['description']."'	
					);";
					echo "<p>".$sql;
					//exit();
					$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));

				}//devices foreach
			}//if isset devices

				if (isset($miningRigs['stats'])) {
					foreach($miningRigs['stats'] as $stats)
					{			
						$sql = 	"Insert into stats (
						batchId,
						address,
						rigId,
						rigName,
						profitability,
						market,
						algorithm,
						deviceDifficulty,
						speedAccepted,
						speedRejectedR1Target,
						speedRejectedR2Stale,
						speedRejectedR3Duplicate,
						speedRejectedR4NTime,
						speedRejectedR5Other,
						speedRejectedTotal
						) values (	
							$batchId,
							'".$address."',
							'".$miningRigs['rigId']."',
							'".$miningRigs['name']."',
							'".$stats['profitability']."',
							'".$stats['market']."',
							'".$stats['algorithm']['description']."',
							'".$stats['difficulty']."',
							'".$stats['speedAccepted']."',
							'".$stats['speedRejectedR1Target']."',
							'".$stats['speedRejectedR2Stale']."',
							'".$stats['speedRejectedR3Duplicate']."',
							'".$stats['speedRejectedR4NTime']."',
							'".$stats['speedRejectedR5Other']."',
							'".$stats['speedRejectedTotal']."'
						);";
						echo "<p>".$sql;
						//exit();
						$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));
					}//foreach stats
				}//if stats

				$cpuMiningEnabled = isset($miningRigs['cpuMiningEnabled']) ? $miningRigs['cpuMiningEnabled'] : 0;
				//$cpuMiningEnabled = $miningRigs['cpuMiningEnabled'];
				$rigPowerMode = isset($miningRigs['rigPowerMode']) ? $miningRigs['rigPowerMode'] : 0;
				$sql="Insert into rigs2 (
				batchId,
				address,
				rigId,
				rigName,
				profitability,
				localProfitability,
				cpuMiningEnabled,
				minerStatus,
				rigPowerMode
				)
				values (
				$batchId,
				'".$address."',
				'".$miningRigs['rigId']."',
				'".$miningRigs['name']."',
				'".$miningRigs['profitability']."',
				'".$miningRigs['localProfitability']."',
				'".$cpuMiningEnabled."',
				'".$miningRigs['minerStatus']."',
				'".$rigPowerMode."'
				);";
				echo "<p>".$sql;
				//exit();
				$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));
				//echo $result;

			}//foreach miningRigs
	}//if miningRigs

	$sql = "Insert into rigs2_polling (
		batchId,
		address,
		totalProfitability,
		totalProfitabilityLocal,
		unpaidAmount,
		nextPayoutTimestamp,
		lastPayoutTimestamp
		)
		values (
			$batchId,
			'$address',
			'".$json['totalProfitability']."',
			'".$json['totalProfitabilityLocal']."',
			'".$json['unpaidAmount']."',
			'".$json['nextPayoutTimestamp']."',
			'".$json['lastPayoutTimestamp']."'
		);";
		echo "<p>".$sql;
		//exit();
		$result = mysqli_query( $link,$sql) or die('Error; ' . mysqli_error($link));
		//insert polling record
		//exit();


	$sql="Update niceHashKeys set lastPollResult='".substr($response, 0, 4000)."', lastUpdate=now() where address='$address';";
	echo "<p>".$sql;
	//$link->query($sql);
	//sleep(1);
}//while
$sql="update rigs2 set ignoreReading='true' where localProfitability>0 and profitability*50000-localProfitability*50000 > 10 and ignoreReading='false';"; //hide rows where profitiably is out of whack
//echo "<p>".$sql;
//$link->query($sql);

$sql = "Insert into pollingLog (
	page,
	batchId,
	records,
	errors
  )
  values (
	  'get_rigs2.php',
	  $batchId,
	  $records,
	  $errors);";
	  echo $sql;
	  $result = mysqli_query( $link,$sql);
?>
