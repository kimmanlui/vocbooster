<?php



//generate a QR Code dynamically
	include_once "LIB.php";
	include_once "bicrypt.php"; 
	include 'global_domain.php';
	
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = str_replace("localhost",$global_domain,$actual_link);
   
    $pos         = strrpos($actual_link, "/", -1) ;
    $actual_link = substr($actual_link,0,$pos);
    $uData = $actual_link."/login.php";


	// QR1 ********************************************
    $today = date("Ymd"); 
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmpQR'.DIRECTORY_SEPARATOR;
    $PNG_WEB_DIR = 'tmpQR/';
    include "phpqrcode/qrlib.php";    
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    $filenameQR1 = $PNG_TEMP_DIR.'SystemFlashLogin.png';                                                        // <---Changed QR1-QR2
    $errorCorrectionLevel = 'M';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    
    $matrixPointSize = 6;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
    //$filenameQR1 = $PNG_TEMP_DIR.'test'.md5($uData.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png'; // <---Changed QR1-QR2
    $marginSize=3;
    //echo $filenameQR1; 
    QRcode::png($uData, $filenameQR1, $errorCorrectionLevel, $matrixPointSize, $marginSize);                         // <---Changed QR1-QR2 *
     // END OF QR1   
    addColorTextOnImage($filenameQR1,  " "."WeChat", $R=139, $G=0, $B=139, $xpos=5,$ypos=15);
   // addTextOnImage($filenameQR1, "  ".$_SESSION['loginID']);
	

function isWechat()
{
  if( !preg_match('/micromessenger/i', strtolower($_SERVER['HTTP_USER_AGENT'])) )
  {
  	return 0;
  } else
  {
  	return 1; 
  }
}

if (isWeChat()==0)
{
	echo "<body><br><br><center>Please login via wechat<br><br>
	<img src='tmpQR/SystemFlashLogin.png' alt='QR Code'>
	      <center></body></html>
	      ";
	die; 
}

include "global_session_name.php";
session_name($global_session_name);
session_start();
$_SESSION['login']="login.php";
?>