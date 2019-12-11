<?php 

include "global_session_name.php";
session_name($global_session_name);
session_start();

if(!isset($_SESSION['name']) && !isset($_SESSION['userid'])){
   if ( isset($_SESSION[login]))
   {
     header("Location: $_SESSION[login]?msg=nocookie");
   }  else
   {
        header("Location: login.php?msg=nocookie");
   }
   exit;
}
?>