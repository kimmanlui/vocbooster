<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>
<?php
 $maincssv=100;
 ?>  
    <title> Welcome to Flashcards!</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
</head>

<body>
    <?php require("header.php"); ?>
    
    <?php 
      $username=$_SESSION['name']; 
      echo " <p style='margin-left:1em; margin-right:1em' align='left'>Hello,&nbsp;<font color='red'>".$username."</font></p>"; 
    ?>
    <br>
    
    <p style='margin-left:1em;' align='left'>
     The following QR code with your encrypted login and password embedded will allow you to use FlashCard right away.<br><br>

     You may download it and add it to your WeChat Favorites.<br><br>
     
     Tag it as "VocBooster" for easy reference.<br><br>

     Do not share this code with others. <br><br>

    <button onclick="goBack()">Go Back</button>  <br><br> <br>
  

<?php
	include_once "LIB.php";
	include 'bicrypt.php'; 
    include 'global_domain.php';
		
	$fc_username=$_SESSION['username'];
    $fc_password=$_SESSION['password'];
	$fc_username=enbicrypt($fc_username);
    $fc_password=enbicrypt($fc_password);
    $fc_username=urlencode($fc_username);
    $fc_password=urlencode($fc_password);
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = str_replace("localhost",$global_domain,$actual_link);
    
    $pos         = strrpos($actual_link, "/", -1) ;
    $actual_link = substr($actual_link,0,$pos);
    $uData = $actual_link."/login.php?AL=AL&username=".$fc_username."&password=".$fc_password;

	// QR1 ********************************************
    $today = date("Ymd"); 
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmpQR'.DIRECTORY_SEPARATOR;
    $PNG_WEB_DIR = 'tmpQR/';
    include "phpqrcode/qrlib.php";    
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    $filenameQR1 = $PNG_TEMP_DIR.'QR1_survey.png';                                                        // <---Changed QR1-QR2
    $errorCorrectionLevel = 'M';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    
    $matrixPointSize = 6;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
    $filenameQR1 = $PNG_TEMP_DIR.'test'.md5($uData.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png'; // <---Changed QR1-QR2
    $marginSize=3;
    QRcode::png($uData, $filenameQR1, $errorCorrectionLevel, $matrixPointSize, $marginSize);                         // <---Changed QR1-QR2 *
     // END OF QR1   
    addColorTextOnImage($filenameQR1,  " ".$_SESSION['username'], $R=139, $G=0, $B=139, $xpos=5,$ypos=15);
   // addTextOnImage($filenameQR1, "  ".$_SESSION['loginID']);
	?>
	
	<?php   echo '<img src="'.$PNG_WEB_DIR.basename($filenameQR1).'"   width="100" height="100" />';  ?>
     
   
   <!-- 	 
        <li><a href="addcards_form.php">Add cards</a></li>
        <br>
        <li><a href="deckoptions.php">Your decks</a></li>
        <br>
        <li><a href="usecards.php">Use cards</a></li>
     -->
  </p> 
    <script>
function goBack() {
  window.history.back();
}
</script>
    <?php require("footer.php"); ?>
</body>