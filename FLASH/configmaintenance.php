<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>
<?php
 $maincssv=106;
 ?>  
    <title> Welcome to VocBooster 词汇宝!</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <link type="text/css" rel="stylesheet" href="css/style02.css?v=<?php echo $maincssv;?>"/>
    
    
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
</head>
<body>
	
	
	
    <?php require("header.php"); ?>
    
    <?php 
    $username=$_SESSION['name']; 
    $role=$_SESSION['role']; 
    
    
    function setServerForRedmine()
    {
      include "global_domain.php";
	  $host= gethostname();
      $ip = gethostbyname($host);
      if (strcmp($host,'redmine')==0) return $global_domain;
      else return $_SERVER['HTTP_HOST']; 
    }
    $_SERVER['HTTP_HOST']=setServerForRedmine();
    
    echo "<font color='red'>".$username."</font>"; 
    echo "<div align='left'><br><font color='gray'>";
  //  echo "&nbsp;cfg_dictationType & cfg_cefr_dictationType:<br>"; 
  //  echo "&nbsp;<font color='blue'>d</font>ictation, <font color='blue'>v</font>oice, <font color='blue'>f</font>ill-in-blank), <font color='blue'>flash</font>card<br>";
  //  echo "&nbsp;<font color='blue'>q</font>uiz,learn-by-<font color='blue'>c</font>ambridge-definition,learn-by-<font color='blue'>e</font>xample<br>";
  //  echo "<font color='brown'>&nbsp;eg: d,v,f<br>";
 //   echo "&nbsp;eg: flash</font><br><br>";
    echo "showDictationTopPercentage: 1 or 0";
    echo "</font></div><br>";
   ?>
       
   <?php include "configmaintenanceContent.php"?>

 

    
    <?php require("footer.php"); ?>
</body>