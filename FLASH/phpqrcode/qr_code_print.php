<?php    
    //How to Use
    //http://172.16.34.13/codiad/workspace/tle/qr_code.php?data=www.google.com&size=10
   // size 1-10

    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "../phpqrcode/qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'qr_online.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'M';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 10;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

   //eg. http://182.239.53.21/codiad/workspace/SR/qr_code_print.php?data=www.baidu.com?x=1ampersandy=2

    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
        
        $uData=$_REQUEST['data'];
        $uData= str_replace("ampersand","&",$uData);
        
        // user data
        $filename = $PNG_TEMP_DIR.'test'.md5($uData.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($uData, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
       
    } else {    
    
        //default data
        echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    }    
    	
       

    
     $fp = fopen($filename, 'rb');

// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($name));

// dump the picture and stop the script
fpassthru($fp);
exit;
     
         

?> 
