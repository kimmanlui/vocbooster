<?php
date_default_timezone_set('PRC');


function my_get_contents($url)
{
  $html=""; 	
  $array = file($url);
  foreach($array as $line){
    $html=$html.$line;
  }
  return($html);
}

function genMP3Button($filevoice) {
   $filevoiceID=str_replace(array("/"," ","'","..","."), "", $filevoice);
   $data=my_get_contents($filevoice);
   $retV="
         <audio id='$filevoiceID'>
         <source src='$data' type='audio/mpeg'>
         Your browser does not support the audio element.
         </audio>
         <button onclick=\"playAudio('$filevoiceID')\" type=\"button\">Play</button>
   ";
   return ($retV); 
}


function myIP()
{
  $host= gethostname();
  $ip = gethostbyname($host);
  return $ip;
}

function vid()
{
	// ref https://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string
	return date('mdHi').md5(microtime(true).mt_Rand());
}

function countRow($configureFile, $table, $primaryNameArr, $primaryValueArr)
{
	$ini_array = parse_ini_file($configureFile) ;
    $dbserver=$ini_array["mysql_host"];
    $dbuser=$ini_array["mysql_login"];
    $dbpwd=$ini_array["mysql_password"];
    $dbname=$ini_array["mysql_db"];
    date_default_timezone_set('PRC');
    $where="";
    for ($dx=0; $dx<sizeof($primaryNameArr); $dx++)
    {
    	$where=$where." ".$primaryNameArr[$dx]."='".$primaryValueArr[$dx]."'";
    	if ($dx!=(sizeof($primaryNameArr)-1)) $where=$where." and";
    }
    $conn = new mysqli($dbserver, $dbuser, $dbpwd, $dbname);
    // Check connection
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
    $conn->set_charset("utf8");
    
    $sql = "SELECT count(*) cnt FROM $table where ".$where;
    //echo $sql; 
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
       $r = $result->fetch_assoc();
       $conn->close(); 
       return $r['cnt'];
    } else {
	  echo "Error:<br>"; 
      echo "Please check the sql: ".$sql;
      $conn->close(); 
      die;
    }
}

function logSys($text, $file = 'C:\Bitnami\log\log.txt')
{
  date_default_timezone_set('Asia/Hong_Kong');
  $curDate=date("Y-m-d");
  $curTime=date("h:i:sa");  
  $current = $_SESSION['loginID'].",".$curDate.",".$curTime.",".$text."\r\n";
  file_put_contents($file, $current , FILE_APPEND | LOCK_EX);
}

function str_replace_infile ( $arrayOld, $arrayNew, $templateFile , $newFile)
{
	if (sizeof( $arrayOld )!= sizeof( $arrayNew )) return -1;
	if (file_exists($newFile)) return -2;
    
    $contents=file_get_contents($templateFile);
    for ($dx=0; $dx<sizeof($arrayOld) ; $dx++)
    {
    	$contents=str_replace($arrayOld[$dx], $arrayNew[$dx], $contents);
    }
    file_put_contents($newFile, $contents , LOCK_EX);
    return 1; 
}

function my_str_replace($var)
{
	$retV=str_replace("/","",$var);
    $retV=str_replace("+","",$retV);
    $retV=str_replace(":","",$retV);
    $retV=str_replace("&","",$retV);
    $retV=str_replace("?","",$retV);
    $retV=str_replace("_","",$retV);
    $retV=str_replace("=","",$retV);
    $retV=str_replace("+","",$retV);
    $retV=str_replace("*","",$retV);
    $retV=str_replace("^","",$retV);
    $retV=str_replace("%","",$retV);
    $retV=str_replace("#","",$retV);
    $retV=str_replace("@","",$retV);
    $retV=str_replace("!","",$retV);
    $retV=str_replace("~","",$retV);
    return $retV; 
}

function addTextOnImage($outputfilename, $QR_desc)
{
    $size = getimagesize($outputfilename);
    $imgHeight=$size[1];
    $imgX=10;
    $imgPng = imageCreateFromPng($outputfilename);
    $text_color = imagecolorallocate($imgPng, 91, 91, 200);
    imagestring($imgPng, 5, $imgX, $imgHeight-30,  $QR_desc, $text_color);
    imagepng($imgPng, $outputfilename);
    imagedestroy($imgPng);
}

function addColorTextOnImage($outputfilename, $QR_desc, $R=91, $G=91, $B=200, $xpos=5,$ypos=15)
{
    $size = getimagesize($outputfilename);
    $imgHeight=$size[1];
    $imgX=$xpos;
    $imgPng = imageCreateFromPng($outputfilename);
    $text_color = imagecolorallocate($imgPng, $R, $G, $B);
    imagestring($imgPng, 5, $imgX, $imgHeight-$ypos,  $QR_desc, $text_color);
    imagepng($imgPng, $outputfilename);
    imagedestroy($imgPng);
}

function generateNormalQR($url, $outputfilename)
{
    //$uData =  'http://'.'uic.test.healthywin.com'.$_SERVER['REQUEST_URI'];
    //echo "<br>$uData<br>"; 
     include_once "../phpqrcode/qrlib.php";    
    //ofcourse we need rights to create temp dir
    //echo $url; 
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'M';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    
    $matrixPointSize = 6;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
    $marginSize=2; //before is 2
     QRcode::png($url, $outputfilename, $errorCorrectionLevel, $matrixPointSize, $marginSize);                         // <---Changed QR1-QR2 *
     
     // END OF QR1   
}

function generateQR($url, $outputfilename)
{
    //$uData =  'http://'.'uic.test.healthywin.com'.$_SERVER['REQUEST_URI'];
    //echo "<br>$uData<br>"; 
     include_once "../phpqrcode/qrlib.php";    
    //ofcourse we need rights to create temp dir
    //echo $url; 
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'M';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    
    $matrixPointSize = 6;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
    $marginSize=6; //before is 2
     QRcode::png($url, $outputfilename, $errorCorrectionLevel, $matrixPointSize, $marginSize);                         // <---Changed QR1-QR2 *
     
     // END OF QR1   
}
    

//Used in AR/selectionboe_2017S1_IFPcontent.php   etc
//HOW TO USE
// // $url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vRZNk-_far9VUw2TpA4urUsNq9GXprL39KT9HxRyJcQAkvZ_urTWUGQQuZp_IVOG5So9Fe9mh54_8ZQ/pub?output=csv';
// // file_put_contents("IFP_BOE_2017.csv", fopen($url, 'r'));
// $selectedCol=array(0,1,2,4,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21);
// $f = fopen("../AR/csv/BOE_2017S1_IFP.csv", "r");  
// $tableProperty= "<table id='table' class='lm_pagination'>";
// 
// echo csvToHTMLTable($f,$selectedCol,$tableProperty); 
// fclose($f);
function csvToHTMLTable($file, $selectedCol, $tableProperty,$delimiter=",")
{
  $html=$tableProperty;	
  $startDx=0;
  while (($line = fgetcsv($file,0,$delimiter)) !== false) {
	    if ($startDx==0) $html=$html." <thead>";
	    if ($startDx==1) $html=$html." <tbody>";
	    $html=$html."<tr>";
        $columnDex=0;
        foreach ($line as $cell) {
        	    if (in_array($columnDex, $selectedCol)) {
        	       if ($startDx==0) $html=$html."<th>";	
        	       if ($startDx!=0) $html=$html."<td>";
                   //echo "<td>" . htmlspecialchars($cell) . "</td>";
                   $html=$html.htmlspecialchars($cell) ;
                   if ($startDx==0) $html=$html."</th>";	
        	       if ($startDx!=0) $html=$html."</td>";
        	    }
        	    $columnDex++;
        }
        $html=$html."</tr>\n";
        if ($startDx==0) $html=$html." <thead>";
        $startDx=$startDx+1; 
  }
  fclose($file);
  if ($startDx!=1) $html=$html." <tbody>";
  $html=$html."\n</table>";
  return $html; 
}


// How to USe
// echo oneSubmitButton("importMasterupdate.php", array("setup"), array($setupFile), "post"); 
function oneSubmitButton($action, $name, $value , $method="post")
{
	$html=$html."<form action='$action' method='$method'>";
	if (sizeof($name)!=sizeof($value)) 
	{
		echo "Serious Error on oneSubmitButton";
		die; 
	}
	for ($dx=0; $dx<sizeof($name); $dx++)
	{
		$html=$html."<input type='hidden' name='$name[$dx]' value='$value[$dx]'>";
	}
	$html=$html."<input type='submit'></form>"; 
	return $html; 
}

function csvToTable($data, $delimit)
{
	$retV="<table border='1'>";
	for ($i = 0; $i < sizeof($data); $i++) {
	    $ele=explode($delimit, $data[$i]);
	    $retV=$retV." <tr>";
	    for ($k = 0; $k < sizeof($ele); $k++) {
          $retV=$retV."<td>".$ele[$k]."</td>";
      
	    }
	    $retV=$retV."</tr>";
    }
    $retV=$retV."</table>";
    return $retV; 
} //eg. CM/tsvToTable.php

function linkToName($downFile)
{
    $downFile=str_replace("/","",$downFile);
	$downFile=str_replace(":","",$downFile);
	$downFile=str_replace("&","",$downFile);
	$downFile=str_replace("?","",$downFile);
	$downFile=str_replace("=","",$downFile);
    $downFile=str_replace(" ","",$downFile);
    $downFile=str_replace("\t","",$downFile);
    $downFile=str_replace("\n","",$downFile);
    $downFile=str_replace("\r","",$downFile);

	return $downFile;
}

function webToFile($output_filename, $host)
{
	$host=trim($host);
//    $output_filename = "abc.tsv";
//    $host = "https://docs.google.com/spreadsheets/d/e/2PACX-1vQLVmKtRHrb435iLUnjeGk9JQ_A0fysiO94jU-98XR0b3XIh-GoAtUwsSIgCxw1kKILGzZ3KjOhXZvu/pub?output=tsv";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $host);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);
    curl_setopt($ch, CURLOPT_REFERER, "http://www.xcontest.org");
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);

    //print_r($result); // prints the contents of the collected file before writing..


    // the following lines write the contents to a file in the same directory (provided permissions etc)
    $fp = fopen($output_filename, 'w');
    fwrite($fp, $result);
    fclose($fp);
}

function echoHTMLDateTime($color='red') {
             date_default_timezone_set("Asia/Hong_Kong");
      	     echo "<br><font color='$color'>Print date " . date("Y-m-d , h:i"). "</font><br><br>"; 
         };
         


function sql_to_html($sql, $tableID='AH', $extra='') {         
	 require("inc/dbinfo.inc");

  //$sql="select eDate Date, attendance_status(status)  Status  from acemis_attendance where active=1 and status!='P' and sid=1830801013";
  if (!isset($sql)) 	return ""; 

  $dbserver=$servername;
  $dbuser=$dbusername;
  $dbpwd=$dbpassword;
  $dbname=$dbname;
  date_default_timezone_set('PRC');
         
  $conn = new mysqli($dbserver, $dbuser, $dbpwd, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $conn->set_charset("utf8");
  $result = $conn->query($sql);

  $colname = array();
  $htmlEcho="<table id='$tableID' class='$tableID' $extra ><thead><tr>";
  while ($property = mysqli_fetch_field($result)) {
    array_push($colname, $property->name); 
    $htmlEcho=$htmlEcho."<th>$property->name</th>";
  }
  $htmlEcho=$htmlEcho."</tr></thead><tbody>";

  $myData=array( 1 => $colname); 

  $rethtmlEcho="";
  $sql_to_table_nrow=0;         
  while ( $row =$result->fetch_assoc())
  {
	if ($sql_to_table_nrow==0) $rethtmlEcho=$htmlEcho;
	$sql_to_table_nrow = $sql_to_table_nrow+1; 
	$tmpArr = array();
	$rethtmlEcho=$rethtmlEcho."<tr>";
	for ($dx = 0; $dx < sizeOf($colname); $dx++)
	{
		$value=$row[$colname[$dx]]; 
		array_push($tmpArr, $value);
		$rethtmlEcho=$rethtmlEcho."<td>$value</td>";
	}
	$rethtmlEcho=$rethtmlEcho."</tr>";
	array_push($myData, $tmpArr); 
  }

  if ($sql_to_table_nrow!=0)  $rethtmlEcho=$rethtmlEcho."</tbody></table>";
  $conn->close(); 
  return $rethtmlEcho=$rethtmlEcho;
}  


function sql_to_html_pie($sql, $tableID='AH',  $extra='', $tdextra_at=-1) {         
	 require("inc/dbinfo.inc");

  //$sql="select eDate Date, attendance_status(status)  Status  from acemis_attendance where active=1 and status!='P' and sid=1830801013";
  if (!isset($sql)) 	return ""; 

  $dbserver=$servername;
  $dbuser=$dbusername;
  $dbpwd=$dbpassword;
  $dbname=$dbname;
  date_default_timezone_set('PRC');
         
  $conn = new mysqli($dbserver, $dbuser, $dbpwd, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $conn->set_charset("utf8");
  $result = $conn->query($sql);

  $colname = array();
  $htmlEcho="<table id='$tableID' class='$tableID' $extra ><thead><tr>";
  while ($property = mysqli_fetch_field($result)) {
    array_push($colname, $property->name); 
    $htmlEcho=$htmlEcho."<th>$property->name</th>";
  }
  $htmlEcho=$htmlEcho."</tr></thead><tbody>";

  $myData=array( 1 => $colname); 

  $rethtmlEcho="";
  $sql_to_table_nrow=0;         
  while ( $row =$result->fetch_assoc())
  {
	if ($sql_to_table_nrow==0) $rethtmlEcho=$htmlEcho;
	$sql_to_table_nrow = $sql_to_table_nrow+1; 
	$tmpArr = array();
	$rethtmlEcho=$rethtmlEcho."<tr>";
	for ($dx = 0; $dx < sizeOf($colname); $dx++)
	{
		$name=$row[$colname[0]]; 
		$value=$row[$colname[$dx]]; 
		array_push($tmpArr, $value);
		if ($dx==$tdextra_at)
		{
		  $rethtmlEcho=$rethtmlEcho."<td data-graph-name='$name'>$value</td>";
		} else
		{
		  $rethtmlEcho=$rethtmlEcho."<td>$value</td>";
		}
	}
	$rethtmlEcho=$rethtmlEcho."</tr>";
	array_push($myData, $tmpArr); 
  }

  if ($sql_to_table_nrow!=0)  $rethtmlEcho=$rethtmlEcho."</tbody></table>";
  $conn->close(); 
  return $rethtmlEcho=$rethtmlEcho;
} 


