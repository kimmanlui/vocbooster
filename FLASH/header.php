<?php 
  include "global_session_name.php";
  session_name($global_session_name);
  session_start();
  
  $mystring= $_SERVER['REQUEST_URI']; 
  $findme="codiad/workspace/";
  $pos = strpos($mystring, $findme);
  $SystemName='VocBooster 词汇宝';
  $titleText=$SystemName;
  if (!($pos === false)) $titleText="$SystemName <font color='blue'>(Demo)</font>";
  
  
  //$titleText="$SystemName <font color='blue'>(Demo)</font>";
  //echo $_SESSION['login'];
  if (!isset($_SESSION['login'])) $_SESSION['login']="login.php";
?>
  
    <div id="wrap">
        <div id="main">
        
            
            <div id="header">
                <div id="page-title">
                    <a href="welcome.php" ><?php echo $titleText; ?></a>
                    <br>
                     
                </div>
                    <?php 

                    if(isset($_SESSION['name']) && isset($_SESSION['userid'])){ ?>
                    <div id="user-corner">
                         <p><span id="name"><?php echo $_SESSION['name']; ?></span>!</p> 
                    <a href="<?php echo $_SESSION['login']; ?>?action=logout" id="logout" style="font-size: calc(12px + 1vw)">Log out</a>
                    </div>
                    <?php } ?>
            </div>
                        
            <div id="content">
                
                <div id="center-area">
