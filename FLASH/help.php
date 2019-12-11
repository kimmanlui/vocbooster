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
      echo " <p style='margin-left:1em;' align='left'>Hello,&nbsp;<font color='red'>".$username."</font></p>"; 
    ?>
    <br>
    
    <p style='margin-left:1em;' align='left'>
    Click the word to flip the card and look at the explanation<br><br>
    You may click the skip button. The word will then appear at the end.<br><br>
    Click "Don't know." The word will be reviewed at the next round.<br><br>
    Play the flashcard twice a day to help you memorize new vocabulary.<br><br>
    <button onclick="goBack()">Go Back</button>   
    </p> 


     
   
   <!-- 	 
        <li><a href="addcards_form.php">Add cards</a></li>
        <br>
        <li><a href="deckoptions.php">Your decks</a></li>
        <br>
        <li><a href="usecards.php">Use cards</a></li>
     -->
    </ul>
    <script>
function goBack() {
  window.history.back();
}
</script>
    <?php require("footer.php"); ?>
</body>