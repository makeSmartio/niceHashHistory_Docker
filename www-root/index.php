<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <script src="//code.jquery.com/jquery-1.11.0.js"></script>
    <link rel = "stylesheet2" href = "https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

    <script src="https://www.google.com/jsapi"></script>
  <meta name="viewport" content="initial-scale=1.0" />
<link rel="stylesheet" href="DarkModeCSS/style.css">
<link rel="apple-touch-icon" href="/favicon-180.png">

<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
$rowCount = 0;
?>
<?php
    $currencySymbol = "$";
    $chartTitle = "Average Dollars per Day";

    if( isset($_COOKIE["darkMode"]))
      $darkMode = $_COOKIE["darkMode"];
    else
      $darkMode=false;

    if( isset($_COOKIE["address"]))
      {
        $address = $_COOKIE["address"];
        //echo "Cookie address:$address";
      }
      else
      {
        $address = '';
      }

    if( isset($_COOKIE["currency"]))
      {$currency = $_COOKIE["currency"];}
    else
      {$currency = 'USD';}

    $address = isset($_GET['address']) ? $_GET['address'] : $address;
    $currency = isset($_GET['currency']) ? $_GET['currency'] : $currency;
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $DaysBack = isset($_GET['DaysBack']) ? $_GET['DaysBack'] : '7';
    $darkMode = isset($_GET['darkMode']) ? $_GET['darkMode'] : $darkMode;
    $type = isset($_GET['type']) ? $_GET['type'] : 'hourly';
    $queryString = $_SERVER['QUERY_STRING'];
    

    $timezone = -5;
    if (isset($_GET['timezone']))
      $timezone = $_GET['timezone'];
    elseif (isset($_COOKIE["timezone"]))
      $timezone = $_COOKIE["timezone"];
    //echo $timezone;

    setcookie('timezone', $timezone, [
      'expires' => time() + (10 * 365 * 24 * 60 * 60),
      'path' => '/',
      'secure' => true,
      'httponly' => true,
      'samesite' => 'None',
  ]);

    setcookie('currency', $currency, [
      'expires' => time() + (10 * 365 * 24 * 60 * 60),
      'path' => '/',
      'secure' => true,
      'httponly' => true,
      'samesite' => 'None',
  ]);

  {
      setcookie('address', $address, [
        'expires' => time() + (10 * 365 * 24 * 60 * 60),
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None',
    ]);
    }
    
    if ($darkMode == 'true')
    {
      setcookie('darkMode', 'true', [
        'expires' => time() + (10 * 365 * 24 * 60 * 60),
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None',
    ]);
      $bodyMode = 'dark';
      $darkModeSwitch = 'false';
      $loadDarkModeTxt =   "$('body').addClass( 'dark' );";
    }
    else
    {
      setcookie('darkMode', 'false', [
        'expires' => time() + (10 * 365 * 24 * 60 * 60),
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None',
    ]);
      $bodyMode='light';
      $darkModeSwitch = 'true';
      $loadDarkModeTxt =   "$('body').addClass( 'light' );";
    }

?>
<script src="DarkModeCSS/darkmode.js"></script>

<script type="text/javascript">
function loadDarkMode() {

  <?=$loadDarkModeTxt?>
}
</script>
<?php 

  $page = file_get_contents('https://bitpay.com/api/rates');
  $my_array = json_decode($page, true);
  $exchange_rate = $my_array[2]["rate"];

  $timeOffset=$timezone;

  if ($type == 'all')
  {
    $grouping = "CONCAT(year(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)),',',
    month(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR))-1,',',
    day(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)),',',
    hour(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)),',',
    minute(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)))";
  }
  elseif ($type == 'day')
  {
    $grouping = "CONCAT(year(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)),',',
    month(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR))-1,',',
    day(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)))";
  }
  else
  {
    $grouping="CONCAT(year(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)),',
    ',month(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR))-1,',
    ',day(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)),',
    ',hour(Date_Add(ts, INTERVAL ". $timeOffset ." HOUR)))";
  }
    
    include("connect.php");
    $link=Connection();

    if (mysqli_connect_errno())
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $query = "Select distinct address
    From niceHashKeys
    order by id desc;";

    $result = mysqli_query($link,$query);

    if (mysqli_num_rows($result)==0)
    {
      echo "<p><p>No data for this address yet. Did you <a href=addMe.php>add</a> yourself?";
      //exit();
      $address = '';
      $id=1;
    }
    else
    {
    $getByID = mysqli_fetch_assoc($result);
    $address = $getByID['address'];
    //echo "id:$id address:$address";
    }

    $query = "Select distinct rigName
    From niceHash
    Where address='".$address."';";

    $result = mysqli_query($link,$query);

    $rigs = "";
    $rigSelect = "";
    $numOfRigs = 0;
    while($row = mysqli_fetch_array($result))
    {
      $numOfRigs++;
      $rigArray[] = $row['rigName'];
      if (isset($_GET['id']))
      {$rigs .= "'rig$numOfRigs',";}
      else
      {$rigs .= "'".$row['rigName']."',";}


      if ($currency=="BTC")
        {
          $rigSelect .= ",avg(case when rigName='".$row['rigName']."' then (case when profitability> localProfitability*1.5 then localProfitability else profitability end)*1 end) as '".$row['rigName']."'";
          $chartTitle = "Average SatosBTC per Day";
          $currencySymbol = "";
        }
      elseif ($currency=="Satoshi")
      {
        $rigSelect .= ",avg(case when rigName='".$row['rigName']."' then (case when profitability> localProfitability*1.5 then localProfitability else profitability end)*100000 end) as '".$row['rigName']."'";
        $chartTitle = "Average Satoshi per Day";
        $currencySymbol = "";
      }
    else
      {
        $rigSelect .= ",avg(case when rigName='".$row['rigName']."' then (case when profitability> localProfitability*1.5 then localProfitability else profitability end)*".$exchange_rate." end) as '".$row['rigName']."'";
        $chartTitle = "Average Dollars per Day";
        $currencySymbol = "$";
      }
        
    }
    //echo $rigSelect;
    //echo "rigs: " . $rigs;

    $query = "Select  
    ".$grouping." as vdate
    ".$rigSelect." 
    From rigs2 
    Where address = '".$address."' and ts>=DATE_ADD(now(), INTERVAL ".($DaysBack*-1)." DAY) and ignoreReading='false'
    Group by ".$grouping."
    Order by ts;";
    
    //echo $query;

    $result = mysqli_query($link,$query);

    if (mysqli_num_rows($result)==0)
    {
      echo "<p><p>No data for this address yet. Did you <a href=addMe.php>add</a> yourself yet?";
      //exit();
    }
?>

<title>niceHash History</title>

<script type='text/javascript'>
  
  var viewColumns = [0,1];
  i = 0;
  var data;

google.load('visualization', '1', {packages: ['corechart', 'table']});
//google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
    <?php
      
      if ($numOfRigs>1){
        echo "['Date', ".$rigs."'Total'],";
      }
      else
      {
        echo "['Date', ".$rigs."],";
      }
      while($row = mysqli_fetch_array($result))
      {
        echo "[new Date(".$row['vdate']."),";
        $rowTotal=0;
        foreach ($rigArray as $rig)
        {
          echo number_format($row[$rig],6,'.','').",";
          $rowTotal=$rowTotal+$row[$rig];
        }
        if ($numOfRigs>1){
          echo number_format($rowTotal,6,'.','').",";
        }
        echo "]," . PHP_EOL;
      }
    ?> 
  ]);

  //var date_formatter = new google.visualization.DateFormat({pattern: "MMM dd, yyyy h:mm a"}); 
  var date_formatter = new google.visualization.DateFormat({pattern: "EEE M/d/y h:mm a"}); 
  date_formatter.format(data, 0);

  <?php
if ($currency=="Satoshi") 
{
  echo "var formatter = new google.visualization.NumberFormat({fractionDigits: 2, decimalSymbol: '.',groupingSymbol: ',', negativeColor: 'red', negativeParens: true, prefix: '$currencySymbol'});";
}
elseif ($currency=="BTC") 
{
  echo "var formatter = new google.visualization.NumberFormat({fractionDigits: 6, decimalSymbol: '.',groupingSymbol: ',', negativeColor: 'red', negativeParens: true, prefix: '$currencySymbol'});";
}
else
{
  echo "var formatter = new google.visualization.NumberFormat({fractionDigits: 2, decimalSymbol: '.',groupingSymbol: ',', negativeColor: 'red', negativeParens: true, prefix: '$currencySymbol'});";
}

if ($numOfRigs>1)
{
  for ($i = 1; $i <= $numOfRigs+1; $i++)
  {
    echo "formatter.format(data, $i);";
  }
}
else
{
  echo "formatter.format(data, 1);";
}
  ?>

  //showEvery = parseInt(data.getNumberOfRows() / 6);
  
  //parseInt(data.getNumberOfRows() / 6);
  showEvery = 1;
  var seriesColors = ['green', '#0000FF', 'red', 'orange'];
  var options = {
    strictFirstColumnType: true
    ,colors: seriesColors
    ,chartArea:{left:40,top:40,bottom:45,width:'100%'}
    ,height: 300
    ,legend: { position : 'bottom', textStyle: {color: 'gray'}}
    ,'title':'<?=$chartTitle?>'
    ,interpolateNulls: false
    ,vAxes: {0: {format: '<?=$currencySymbol?>#,###'}, 1: {format: '#,###',ticks: [0,1]},}
    ,hAxis: {
          gridlines: {
            count: -1,
            units: {
              days: {format: ['E MMM dd']},
              hours: {format: ['h a', 'ha']},
            }
          }
        }
    ,seriesType: 'line'
    //,series: {[barColumnNum]: {type: 'bars', targetAxisIndex: 1, color: "orange"}}
    ,backgroundColor: 'transparent'
  };

  var view = new google.visualization.DataView(data);
  //view.setColumns([0,1,2]);

  //var chart = new google.visualization.LineChart($('#chart_div')[0]);
  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  data.sort({column: 0, desc: true});
  view.setRows(data.getSortedRows({column: 0, desc: true}));
  //attach the error handler here, before draw()
  google.visualization.events.addListener(chart, 'error', errorHandler);

  chart.draw(view, options);

  var table = new google.visualization.Table(document.getElementById('table_div'));
  //table.setColumns([0,1,2,4]);
  //table.setColumns(viewColumns);
  
  var tableOptions = 
    {
      allowHtml: true, 
      showRowNumber: false, 
      cssClassNames: { 
        headerRow: 'headerRow',
        tableRow: '#DCDCDC',
        oddTableRow: 'oddTableRow',
        selectedTableRow: 'selectedTableRow',
        hoverTableRow: 'hoverTableRow',
        headerCell: 'headerCell',
        tableCell: 'tableCell',
        rowNumberCell: 'rowNumberCell'
        ,backgroundColor: 'transparent'
      }
    }
  table.draw(data, tableOptions);
  //table.draw(data);

  google.visualization.events.addListener(chart, 'select', function() {
    var selectedItem = chart.getSelection()[0];   
    if (selectedItem) {
      table.setSelection([{'row': selectedItem.row}]);
    }
 
  });
  // When the table is selected, update the graph.
  google.visualization.events.addListener(table, 'select', function() {
    var selectedItem = table.getSelection()[0];   
    if (selectedItem)  {
      //alert(selectedItem.row);
      chart.setSelection(table.getSelection());
      //chart.setSelection([{'row': data.getNumberOfRows() - selectedItem.row - 1}]);
    }
  });

//errorHandler("test");
function errorHandler(errorMessage) {
    //curisosity, check out the error in the console
    //console.log(errorMessage);
    //window.alert(viewColumns);
    var req = new XMLHttpRequest();
    var params = "msg=" + (JSON.stringify(errorMessage)) + '&amp;<?php echo $address ?>;viewColumns=' + viewColumns + 'url=' + "?<?=$queryString?>";
    req.open("POST", "/error.php?<?=$queryString?>");
    req.send(params);

    //simply remove the error, the user never see it
    google.visualization.errors.removeError(errorMessage.id);
  }
}

</script>
<style>

.grid-container {
  display: grid;
  grid-template-columns: 2fr .25fr 1fr;
  grid-template-rows: 1fr;
  gap: 0px 0px;
  grid-template-areas:
    "TopLeft Middle TopRight";
}
.TopLeft { grid-area: TopLeft; }
.TopRight { grid-area: TopRight; }
.Middle { grid-area: Middle; }


.timezone-select{
 width:150px;}

 .currency-select{
 width:100px;}

</style>

<script type='text/javascript'>
  
  $(document).ready(function(){    

    $(".id-select").change(function(){      
     var page_url = "?id="+$(".id-select").val()+"&type=<?=$type?>&DaysBack=<?=$DaysBack?>&darkMode=<?=$darkMode?>";    
     $(location).attr('href',page_url);
    });

    $(".currency-select").change(function(){      
     var page_url = "?currency="+$(".currency-select").val()+"&type=<?=$type?>&DaysBack=<?=$DaysBack?>&darkMode=<?=$darkMode?>";    
     $(location).attr('href',page_url);
    });

    $(".timezone-select").change(function(){      
     var page_url = "?timezone="+$(".timezone-select").val()+"&";    
     $(location).attr('href',page_url);
    });

    var refreshit = setInterval(getLatestMining, 60000);
    getLatestMining();

  function getLatestMining(){

    var url = "getNicehashNow.php?id=<?=$id?>&currency=<?=$currency?>";   
    $.post( url, function(data) {
        //console.log("running");
        console.log(data);
        document.title = data+' Nicehash History';
        var d = new Date();
        //$('#current_div').html("<p>Real time:"+d.toLocaleTimeString() + " " +data);
      });
    }
  
});

</script>

<body bgcolor="black" onload="loadDarkMode()">
<p>
<div class="grid-container">
<div class="TopLeft">
<form method="GET">
  <?php 
  //View live api data on <a href=https://api2.nicehash.com/main/api/v2/mining/external/$address/rigs/activeWorkers>Nicehash</a>
  ?>
<p>
  Days:  <a href="?type=<?=$type?>&id=<?=$id?>&DaysBack=1&darkMode=<?=$darkMode?>">1 Day</a>
  <a href="?type=<?=$type?>&id=<?=$id?>&DaysBack=7&darkMode=<?=$darkMode?>">7 Days</a>
  <a href="?type=<?=$type?>&id=<?=$id?>&DaysBack=<?=$DaysBack?>&darkMode=<?=$darkMode?>">All</a>

  <p>
Show: 
<?php if ($type=="hourly")
  {
    echo "<a href=?type=all&id=$id&DaysBack=$DaysBack&darkMode=$darkMode>All Readings</a> | ";
    echo "<a href=?type=day&id=$id&DaysBack=$DaysBack&darkMode=$darkMode>Daily Averages</a>";
  }
  elseif ($type=="day")
  {
    echo "<a href=?type=all&id=$id&DaysBack=$DaysBack&darkMode=$darkMode>All Readings</a> | ";
    echo "<a href=?type=hourly&id=$id&DaysBack=$DaysBack&darkMode=$darkMode>Hourly Averages</a>";
  }
  else
  {
    echo "<a href=?type=day&id=$id&DaysBack=$DaysBack&darkMode=$darkMode>Daily Averages</a> | ";
    echo "<a href=?type=hourly&id=$id&DaysBack=$DaysBack&darkMode=$darkMode>Hourly Averages</a>";
  }
?>
<p>
<?php
  $query = "select count(id) as apiErrors from niceHashErrors where ts>date_add(now(), INTERVAL -60 MINUTE);";
  $result = mysqli_query($link,$query);
  $getByID = mysqli_fetch_assoc($result);
  $apiErrors = $getByID['apiErrors'];
?>
<?php if ($apiErrors<5) echo "Please be patient, Nicehash API errors in the past hour: $apiErrors"; ?>
This is the newer API. If you want to see the old API, it is here: 
<a href="activeWorkers.php?type=<?=$type?>&id=<?=$id?>&DaysBack=<?=$DaysBack?>&darkMode=<?=$darkMode?>">activeWorkers</a>
</div>
  <div class="TopRight"  text-align: right;>
  <a href=addMe.php>Add Me!</a>
  <br>
    Dark mode: <a href="?type=<?=$type?>&id=<?=$id?>&DaysBack=3&darkMode=<?=$darkModeSwitch?>">Switch</a>
    <br>
    <a href=contactUs.php>Contact Us/Feedback</a>
    <br>

Timezone: 
     <select class="timezone-select" name="timezone-select">
  <?php
     $query = "Select value, label
    From timezones;";

    $result = mysqli_query($link,$query);

    while($row = mysqli_fetch_array($result))
    {
      //echo $id;
      if ($timezone==$row['value'])  
        {echo "<option value=".$row['value']." selected>".$row['label']."</option>";}
      else
        {echo "<option value=".$row['value'].">".$row['label']."</option>";}
    }
?>
     </select>
     <br>
     Other Miners: 
     <select class="id-select">
  <?php
     $query = "Select distinct min(id) as id, address
    From niceHashKeys
    Group by address
    Order by id;";

    $result = mysqli_query($link,$query);

    while($row = mysqli_fetch_array($result))
    {
      //echo $id;
      if ($id==$row['id'])  
        {echo "<option value=".$row['id']." selected>".$row['id']."</option>";}
      else
        {echo "<option value=".$row['id'].">".$row['id']."</option>";}

    }
?>
     </select>


    </div>

  </div>
  
 <ul id="series" style="list-style: none">
 <?php
 $i=1;
  //foreach ($rigArray as $rig)
      {
        //echo "<input class=box type=checkbox id=$rig name=series value=".$i." />$rig</span>";
        $i++;
      }
?>
</ul>

<div id="chart_div"></div>
<div>
<?php 
echo "Bitcoin price: USD $".number_format($exchange_rate)." <p>Show values in: ";
echo "<select class=currency-select>";
if ($currency=="Satoshi")
{
  echo "<option selected>Satoshi</option>";
  echo "<option>BTC</option>";
  echo "<option>USD</option>";
}
elseif ($currency=="BTC")
{
  echo "<option>Satoshi</option>";
  echo "<option selected>BTC</option>";
  echo "<option>USD</option>";
}
else
{
  echo "<option>Satoshi</option>";
  echo "<option>BTC</option>";
  echo "<option selected>USD</option>";
}
echo "</select>"
?>
</div>
<div id="current_div"></div>

<div id="table_div"></div>
</form>
<?php
//echo $query;

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';
$publicIp=$_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT']??'';
$referer = $_SERVER['HTTP_REFERER']??'';

//echo $agent;
$query = "INSERT INTO WebLog (Page,UserAgent,IP,What,RowCount,FreeSpace, Referrer) 
VALUES ('index.php?type=".$type."&address=".$address."&DaysBack=".$DaysBack."&darkMode=".$darkMode."',
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