<?php
include_once "login_message.php";

include "global_session_name.php";
if (isset($_REQUEST['action']) and strcmp($_REQUEST['action'],'logout')==0)
{
	session_name($global_session_name);
	session_start();

	unset($_SESSION['name']);
     unset($_SESSION['userid']);
    session_destroy();
}
session_name($global_session_name);
session_start();

include 'bicrypt.php';
$autologin = trim($_REQUEST['AL']);
$fc_username = trim($_REQUEST['username']);
$fc_password = trim($_REQUEST['password']);

if (isset($autologin) && $autologin=="AL")
{
    	//$fc_username=urldecode($fc_username);
    	//$fc_password=urldecode($fc_password);
    	$fc_username=debicrypt($fc_username);
    	$fc_password=debicrypt($fc_password);
}


if(isset($_SESSION['name']) && isset($_SESSION['userid'])){
    header("Location: welcome.php");
    exit;
}else if( !isset($_REQUEST['username']) && !isset($_REQUEST['password']) ){
?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>
<?php
 $maincssv=323;
 ?>  
    <title>VocBooster</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
     <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/login.js"></script>

<style>
#title{
	background-color: #2ae769;

	margin-left: auto;
	margin-right: auto;
	font-size: calc(12px + 1.5vw);;
}
</style>
</head>

<?php
  $_SESSION['login']="loginpc.php";
  $phpfile=basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
  if (strcmp($phpfile, "loginpc.php")!=0) include("wechat_control.php");
?>

<body>        
<?php require("header.php"); 

if(isset($_GET['msg'])){
    echo '<span class="form-msg">';
    switch($_GET['msg']){
        case "newuser":
            echo "Your account has been added!  Please log in to continue";
            break;
        case "badlogin":
            echo "Invalid username or password, please try again";
            break;
        case "nocookie":
            echo "Session Time Out. Please log in again.";
            break;
    }
    echo '</span><br>';
}

?>

<?php
$mystring= $_SERVER['REQUEST_URI']; 
$findme="codiad/workspace/";
$pos = strpos($mystring, $findme);
$version="v3.0";
if ($pos === false) $version="v3.0 (kimman:12356)";

?>

<h1></h1>
<br>
<div id="title2">  <?php echo $titleText." ".$version; ?></div>
<font color="green"><center> Demo Account: student/student admin/admin</center></font>
<br>
<?php
  if (file_exists("aceFlashSystemT.png"))
  {
    echo "<img src='aceFlashSystemT.png' alt='' width=40% class='avatar'>";
  } else if (file_exists("English.png"))
  {
    echo "<img src='English.png' alt='' width=40% class='avatar'>";	
  }  
?>
<br>
<form method="post" action="<?php echo basename(__FILE__); ?>">
    Username: <input type="text" id="username" name="username" autocomplete="on" /><br>
    Password: <input type="password" id="password" name="password" autocomplete="on" /><br>
    <input type="submit" value="Submit"/>
<!--

    <input type="button" id="btn-newuser" value="New user?"/>
-->
</form> 
<br>
<br>
<center><?php echo $message; ?> </center>


<?php require("footer.php"); ?>      
    
</body>

</html>
<?php 
}else{
    require("inc/dbinfo.inc");

    
   
    
    //$hashedPass = hash( "sha256", $fc_password );
    $hashedPass = $fc_password;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM users_data WHERE enable='1' AND username=? AND password=?");
        $stmt->execute(array($fc_username,$hashedPass));
    
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetchAll();
        
        if(sizeof($result) == 1){
            // Login success!

            // Bake up a cookie
            //$cookie_expire = time()+86400; // 24 hr life
            //time() - 3600; // overdue expiration date. deletes cookie
            //$cookie_domain = $_SERVER['HTTP_HOST'];
            
            //setcookie($cookie_name, $cookie_value, $cookie_expire, "/" , $cookie_domain, 0);
           // setcookie("userid", $result[0]["id"], $cookie_expire, $cookie_domain);
           // setcookie("name", $result[0]["name"], $cookie_expire, $cookie_domain);
          
            $_SESSION['userid']=$result[0]["id"];
            $_SESSION['name']=$result[0]["name"];
            $_SESSION['role']=$result[0]["role"];            
            $_SESSION['username']=$fc_username;
            $_SESSION['password']=$fc_password;
            
            $stmt_top = $conn->prepare("SELECT top_dictation($_SESSION[userid]) as top_dictation ");
            $stmt_top->execute();
            $result_top = $stmt_top->fetchAll();
            if(sizeof($result_top) == 1)
            {
            	 $_SESSION['top_dictation']=$result_top[0]["top_dictation"];
            }
            
            $stmt_top = $conn->prepare("SELECT top_learnby($_SESSION[userid]) as top_learnby ");
            $stmt_top->execute();
            $result_top = $stmt_top->fetchAll();
            if(sizeof($result_top) == 1)
            {
            	 $_SESSION['top_learnby']=$result_top[0]["top_learnby"];
            }
            
            header("Location: welcome.php");
            exit;
        }else{
            // Login failure
            header("Location: $_SESSION[login]?msg=badlogin");
            exit;
        }
        
    }catch(PDOException $e){
    
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    
}
?>