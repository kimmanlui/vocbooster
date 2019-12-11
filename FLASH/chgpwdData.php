<?php

include "global_session_name.php";
session_name($global_session_name);
session_start();


if (!(isset($_SESSION['userid']) && isset($_SESSION['oldpassword']) && $_SESSION['newpassword'] ))
{
   echo "<p>logging in ...</p>";
   echo "<script language='javascript' type='text/javascript'> location.href='login.php' </script>";
} 

    require("inc/dbinfo.inc");
    try 
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
       // $conn->set_charset("utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "
        update users_data set password='$_SESSION[newpassword]' where id='$_SESSION[userid]'
                   ";  
          //echo $sql;          
            $stmt = $conn->prepare($sql); 
        $stmt->execute();
       $_SESSION['password']=$_SESSION['newpassword'];
       header("location: welcome.php"); 
       die;

    }
    catch(PDOException $e)
    {
    	echo "Password Change Failed<br>";
    	echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='welcome.php'>Back to Main</a>";
        //echo "Error: " . $e->getMessage();
        //exit;
    }
    $conn = null;



?>
