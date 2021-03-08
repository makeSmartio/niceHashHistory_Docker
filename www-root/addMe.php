<!DOCTYPE HTML>
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
$rowCount = 0;
    
include("connect.php");
$link=Connection();

?>
<html>
<head>
	<meta charset="UTF-8">
	<title>addMe</title>
	<link rel="stylesheet" href="../DarkModeCSS/style.css">


</head>
<body>
	<p>
<h1>Add me:</h1>
<p>
	Firstly: is it safe? Please see: <br><a href="https://www.nicehash.com/support/general-help/security/can-i-display-my-nicehash-wallet-address-publicly">https://www.nicehash.com/support/general-help/security/can-i-display-my-nicehash-wallet-address-publicly</a>
	<p>
	And: <a href="https://www.nicehash.com/blog/post/how-nicehash-secures-your-wallet">ttps://www.nicehash.com/blog/post/how-nicehash-secures-your-wallet</a>  - this one is just good advice in general.
</p>
<p>
After you add your address we start polling the Nicehash API every 5 minutes, so it will take up to 5 minutes for the first poll. Save your address in your favorites for easier access. 
<p>
This service polls the Nicehash API which can be spotty at times, especially lately (as of March 6, 2021) and some data points will be missing and/or inaurate (very high). I will work on removing data that is too high. Please use Reddit or the contactUs page to message me. 

<?php 

$errors = '';
$address = isset($_POST['address']) ? (trim($_POST['address'])) : '';
$email = isset($_POST['email']) ? ($_POST['email']) : '';
$message = isset($_POST['email']) ? ($_POST['email']) : 'email';
$publicIp=$_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT']??'null';
$referer = $_SERVER['HTTP_REFERER']??'?';


if($address=='')
	{
		$errors .= "\n Wallet address required";
	}
else
	{
		$query = "Select address from niceHashKeys where address='$address';";
		
		//echo $query;
		//exit();
		$result = mysqli_query($link,$query);
	
		if (mysqli_num_rows($result)>0)
		{
			echo "<p><b>Already addded! $address you can view your graphs here (in up to 5 minutes): <a href='/?address=$address'>https://nicehashHistory/?$address</a></b><p>";
			exit();
		}
	
		//$dbh = new PDO('mysql:host=localhost;dbname=niceHash', 'nginx', 'asdf');
		//$mysqli = new mysqli('localhost', 'nginx', 'asdf', 'niceHash');
		$statement = $link->prepare("INSERT INTO niceHashKeys (address, email, ip) VALUES(?,?,?)");
		echo $link->error;
		$statement->bind_param("sss", $address, $email, $publicIp);
		$statement->execute();

		setcookie('address', $address, [
			'expires' => time() + (10 * 365 * 24 * 60 * 60),
			'path' => '/',
			'domain' => 'nicehashhistory.com',
			'secure' => true,
			'httponly' => true,
			'samesite' => 'None',
		]);
	
		echo "<p><b>Addded! $address you can view your graphs here (in up to 5 minutes): <a href='/?address=$address'>https://nicehashHistory/?$address</a></b><p>";
		//$page = file_get_contents('https://nicehashhistory.com/email.php?address='.$address);
		//echo $page;
		exit();
	}
?>

<br><br>
To find your address, go here: <a href="https://www.nicehash.com/my/wallets/">https://www.nicehash.com/my/wallets/</a>, click "Deposit", and then click "Show Address" - copy that "BTC Deposit Address" and paste it here.
	<form action="?" method="post" name="form">
	Nicehash Wallet Address: <input type="text" placeholder="Nicehash address" name="address" value="<?=$address?>" onFocus="this.select();" onMouseOut="javascript:return false;"/>
	<br>Email address (optional): <input type="text" placeholder="email" name="email" value="<?=$email?>" onFocus="this.select();" onMouseOut="javascript:return false;"/>
      <br><input type="submit" value="Submit">
    </form>
<p>
	Note, if you have more than one mining rig, make sure they have unique names. 
	<img src="images/rigName.png" width="50%" intrinsicsize="738 × 332">
<?php
	
	//echo "<p>$referer";

	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $start), 4);
	//echo 'Page generated in '.$total_time.' seconds.';

	//echo $agent;
	$query = "INSERT INTO WebLog (Page,UserAgent,IP,What,RowCount,FreeSpace, Referrer) 
	VALUES ('addMe.php',
	'".$agent."',
	'".$publicIp."',
	'".$total_time."', 
	'".$rowCount."',
	'".round(disk_free_space("/var/") / 1024 / 1024)."',
	'".$referer."'  );"; 

	//echo $query;

	$link->query($query)
	
?>
</body>
</html>
