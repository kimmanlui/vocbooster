<?php
$type=$_REQUEST['type'];

if ($type=="d")
{ 
  header("Location: demo_learndict.php");
  die();	
}
if ($type=="e")
{ 
  header("Location: learndict_eg.php");
  die();	
} 
header("Location: usercards.php");
die();	

?>                                                                                                                                