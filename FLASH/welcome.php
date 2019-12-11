<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>
<?php
 $maincssv=323;
 ?>  
    <title> Welcome to VocBooster 词汇宝!</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
    
<style>
li {
   margin:0 0 8px 0;   
}
</style>    
</head>
<body>
	
	
	
    <?php require("header.php"); ?>
    
    <?php 
    $username=$_SESSION['name']; 
    $role=$_SESSION['role']; 
    echo "<font color='red'>".$username."</font>"; ?>
    Options:<br><br>
    <ul>
    	<?php 
    $new="<img src='img/new.jpg'  style='height:40px;' alt='new'>";
    	      //if (strpos($username,"teacher")!==false)
    	   
    	      if ($role=='m' or $role=='t' or $role=='a')
    	      {
    	      	//echo "<font color='blue'>Demo version: Deck maintenance is disabled.</font><br><br>";
    	      	if ($role=='m')
    	      	{
    	      	  echo "System Administration<br>";	
    	      	  echo "<li>";
    	      	  echo "<a href='update_learnaudio.php'>AUDIO</a>&nbsp;&nbsp; ";
    	      	  echo "<a href='update_learndict_eg.php'>DICT</a>&nbsp;&nbsp;";
    	      	  echo "<a href='update_learndict_chinese.php'>BACKSIDE</a>";
    	      	  echo "<br></li>";
    	      	  echo "<li>";
    	      	  echo "<a href='configmaintenance.php'>SETUP</a>&nbsp;&nbsp; ";
    	      	  echo "<a href='usermaintenance.php'>USER EDIT</a><br>";
    	      	   echo "</li>";
    	      	    echo "<li>";
    	      	  echo "<a href='deckmaintenance.php'>DECK EDIT</a>&nbsp;&nbsp;";
    	      	  echo "<a href='cardmaintenance.php'>CARD EDIT</a>";
    	      	  echo "</li><br>";
    	      	}
    	      	
    	      	if ($role=='m' or $role=='t')
    	      	{
    	      	  echo "Card Maintenance<br>";	
    	      	  echo "<li>";
    	      	  echo "<a href='addcards_form.php'>Add cards</a>&nbsp;&nbsp; ";
    	      	  echo "<a href='deckoptions.php'>Enable decks</a>";
    	      	  echo "<br></li>";
    	      	}
    	      	
    	      	echo "<li><a href='deckoptionsRpt.php'>Deck (Flashcard Studied)</a></li><br>";
    	        echo "Report<br>";
    	        echo "<li><a href='rpt_general_user.php'>Word Proficiency</a>&nbsp;&nbsp; <a href='rpt_cefr_user_general.php'>CEFR/IELTS</a><br><br>";


    	      	echo "Progress / Log<br>";
    	      	echo "<li>";
                echo "<a href='rpt_student_performance_dictation.php'>Rank(Dic)</a>&nbsp;&nbsp;";
    	      	echo "<a href='rpt_student_performance_learnby.php'>Rank(Learnby)</a>&nbsp;&nbsp;<br>";
    	      	echo "</li>";
    	      		echo "<li>";
    	      	echo "<a href='g_quiz_user.php'>Activity Chart</a>&nbsp;&nbsp;";
    	      	echo "<a href='rpt_activity_week.php'>Activity Log</a><br>";
    	      	echo "</li>";
    	   //   	echo "<li><a href='rpt_user.php'>Student Activity Report</a></li><br>";
    	      //	echo "<li><a href='newuser.php'>Add users</a></li><br>";
    	      echo " <br>";
    	      	echo "<li><a href='usecards.php'>Student Mode</a></li><br>";
    	      }
    	      else
    	      {
    	    
    	      	echo "<li><a href='usecards.php'>Student Mode</a></li><br>";
    	      	header("Location: usecards.php"); /* Redirect browser */
                exit();
    	      }
    	 ?>     
   <!-- 	 
        <li><a href="addcards_form.php">Add cards</a></li>
        <br>
        <li><a href="deckoptions.php">Your decks</a></li>
        <br>
        <li><a href="usecards.php">Use cards</a></li>
     -->
    </ul>
    
    <?php require("footer.php"); ?>
</body>