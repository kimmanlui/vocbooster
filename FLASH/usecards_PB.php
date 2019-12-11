<?php require_once("inc/cookiecheck.inc"); ?>

<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>

<?php
 $maincssv=324;
 ?>  
	<title>Flashcards</title>
	<link rel="stylesheet" href="js-ui-1.12.1/jquery-ui.css?v=<?php echo $maincssv;?>"/>

	<link type="text/css" rel="stylesheet" href="css/reset.css"/>
	<link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
	<script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
	<script src="js/usecards.js?v=<?php echo $maincssv;?>"></script>
    <script src="js-ui-1.12.1/jquery-ui.js"></script>
 
 
<style>

.ui-widget {
	font-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif;
	font-size: 0.9em;
}	
.ui-selectmenu-menu .ui-menu .ui-selectmenu-optgroup {
	font-size: 0.5em;
	font-weight: bold;
	line-height: 1;
	padding: 2px 0.4em;
	margin: 0.5em 0 0 0;
	height: auto;
	border: 0;
}
.ui-selectmenu-button.ui-button {
	text-align: left;
	white-space: nowrap;
	width: 12em;  /* control the width*/
}



</style> 
 
<style>


#center-area{
	border-radius: 10px;
	background-color:  #cccc99;               /* #00cc99; */
	margin-right: auto;
	margin-left: auto;
	margin-top: 10px;
	margin-bottom: 10px;
	padding-top: 20px;
	padding-bottom: 30px;
	min-height: 500px;
	width: 90%;
	text-align: center;
	overflow: auto;
}

    #flashcard {

	background-image: url("img/paper.gif");
	margin-left: auto;
	margin-right:auto;
	margin-top: 15px;
	margin-bottom: 15px;
	padding: 10px;
	height: 230px;
	width: 82%;
	vertical-align: middle;
     }

    .button-container form,
    .button-container form div {
      display: inline;
    }

    .button-container button {
      display: inline;
      vertical-align: middle;
    }
</style>


  <script>
  $( function() {
    $( "#deckid" ).selectmenu();
    $( "#deckiddic" ).selectmenu();
  } );
  </script>


<script>
<?php

require("inc/dbinfo.inc");
require("config.php");

$userid = $_SESSION['userid'];

echo "var gbcontent = '';";
echo "var sid=".date("YmdHis").";" ;
echo "var jvuserid = ".$userid.";";

if(isset($_GET['deckid'])){

echo "var flashCards = ";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($_GET['deckid'] == 'all'){
        //$stmt = $conn->prepare("
        //    SELECT front,  back 
        //    FROM flashcards 
        //    JOIN decks ON flashcards.deckid=decks.deckid 
        //    WHERE userid=?
        //"); 
        $stmt = $conn->prepare("
              SELECT front,  back  ,audiob64 au
            FROM flashcards_v fv
            JOIN decks_v dv ON fv.deckid=dv.deckid ORDER BY RAND()
        "); 

    }else{
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (strpos($actual_link, 'codiad/workspace') !== false)
        {
          $stmt = $conn->prepare("
              SELECT front, back , audiob64 au, 
                (select  ifnull(round(avg(k.choice),2) , 0.5 ) from log_v_student k where fv.deckid=k.deckid and k.word=fv.front) score
              FROM flashcards_v fv
              JOIN decks dv ON fv.deckid=dv.deckid 
              WHERE  fv.deckid=?  ORDER BY RAND()
              ");
        } else
        {
          $stmt = $conn->prepare("
              SELECT front, back , REPLACE(audiob64,'../','../codiad/workspace/')  au, 
                (select  ifnull(round(avg(k.choice),2) , 0.5 ) from log_v_student k where fv.deckid=k.deckid and k.word=fv.front) score
              FROM flashcards_v fv
              JOIN decks dv ON fv.deckid=dv.deckid 
              WHERE  fv.deckid=?  ORDER BY RAND()
              ");
        }
        $stmt->bindValue(1, $_GET['deckid']);
    }
  
    //$stmt->bindValue(1, $userid);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    echo json_encode( $stmt->fetchAll() );
    
    
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}
$conn = null;

echo ";";

}    
?>

<?php
echo "var jvdeckid = ".$_GET['deckid'].";";
?>
        
    </script>
</head>

<body>

	<!--
	<div id="debug">
		DEBUG MENU
		<button style="button">TEST</button>
	</div>
	-->
	
<?php 
function topDictation($rank) 
{
	
  if (isset($rank) and $rank<=10) return("You're in top 10% <font color='blue'>&#9733;&#9733;&#9733;&#9733;&#9733;</font>");
  if (isset($rank) and $rank<=20) return("You're in top 20% <font color='blue'>&#9733;&#9733;&#9733;&#9733;</font>");
  if (isset($rank) and $rank<=30) return("You're in top 30% <font color='blue'>&#9733;&#9733;&#9733;</font>");
  if (isset($rank) and $rank<=40) return("You're in top 40% <font color='blue'>&#9733;&#9733;</font>");
  if (isset($rank) and $rank<=50) return("You're in top 50% <font color='blue'>&#9733;</font>");
  if (isset($rank) and $rank<=60) return("You're in top 60%");
  if (isset($rank) and $rank<=70) return("You're in top 70%");
  if (isset($rank) and $rank<=80) return("You're in top 80%");

  return ("Get Prepared for a Hard Challenge"); 
}

require("header.php");



if(isset($_GET['deckid'])){
?>			    
			    
	<div id="title"><?php 
	   if($_GET['deckid'] == 'all'){
	       echo "All Decks";
	   }else{
	       require("inc/getdeckname.inc"); 
	       echo getDeckName($_GET['deckid']); 
       }?></div>
	
	<div id="header-stats">
		<div id="round-counter">Round 0</div>
		::
		<div id="card-counter">1 of a billion</div>
	</div>
	
	<div id="flashcard"><p>SOME TEXT</p></div>

	<div id="control-panel">
		
		Do you know the above word? 
		<a href="help.php">help</a>
		<br><br>
		<div class="inline-wrapper">
			
		    <button type="button" id="btn-correct">		Yes,I know	</button>
		    <div class="card-counter" id="correct-counter">0</div>
        </div>
		<div class="inline-wrapper">
		    <button type="button" id="btn-incorrect">	Don't know	</button>
		    <div class="card-counter" id="incorrect-counter">0</div>
		</div>
		<button type="button" id="btn-skip">		Skip		</button>
	    <button type="button" id="btn-play">		Play		</button>
	</div>
<!--				
    <div id="key-info">
        <h1>Keyboard controls:</h1>
        <table>
            <tr><th>Skip</th><th>Correct</th><th>Incorrect</th><th>Flip card</th></tr>
            <tr><td>a</td><td>s</td><td>d</td><td>f</td></tr>
        </table>
    </div>
-->
<?php 
}else{
    $allDecksOption = true;
?>
<font color='brown' STYLE="font-weight:600;">1 FLASHCARD</font><br>
<form method="get" action="<?php echo basename(__FILE__); ?>" >
    <?php 
    require("inc/deckselect.inc"); 
    foreach($_GET as $k=>$v){
        echo '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
    }
    ?>
     <input type="image" src="img/submit.gif" alt="Submit" width="45" height="45">
    
</form>
<br>
<br>
	<font color='brown' STYLE="font-weight:600;">2 QUIZ</font><br>
	<a href="rpt_user_suggestion_after_quiz.php">Advised To Learn</a>
<form method="post" action="quiz.php" >
	 <input type="image" src="img/submit2.gif"   alt="Submit" width="45" height="45" align="middle">
 
</form>
<br>

<br>
<font color='brown' STYLE="font-weight:600;">3 DICTATION</font><br>
<?php
   if ($showDictationTopPercentage==1)
   {
       echo topDictation($_SESSION['top_dictation']);
   }
?>
<form method="post" action="dictation.php" >
    <?php 
    
    require("inc/deckselect_dictation.inc"); 
    foreach($_GET as $k=>$v){
        echo '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
    }
    ?>

   
    <input type="radio" name="type" id="radio-1" value="d"  checked>
    <label for="radio-1">text</label>
    <input type="radio" name="type" id="radio-2" value="v">
    <label for="radio-2">voice</label>
    <input type="radio" name="type" id="radio-3" value="f">
    <label for="radio-3">fill-in-blank</label>
 
 <!--   
    <input type="radio" name="type" value="d"  checked> text
    <input type="radio" name="type" value="v" > voice
     <input type="radio" name="type" value="f" > fill-in-blank
    -->
    <br>
    <input type="image" src="img/submit3.gif"   alt="Submit" width="45" height="45" align="middle">
</form>

<!--
  <script>
  $( function() {
    $( "#radio-1" ).checkboxradio();
    $( "#radio-2" ).checkboxradio();
     $( "#radio-3" ).checkboxradio();
  } );
  </script>
-->  
  
  
<br>
<br>
<font color='brown' STYLE="font-weight:600;">4 LEARN BY</font><br>
<form method="post" action="selection.php" >
	
	<input type="radio" name="type" id="radio-11" value="d"  checked>
    <label for="radio-11">Definition</label>
    <input type="radio" name="type" id="radio-12" value="e">
    <label for="radio-12">Example</label>
	<!--
	<input type="radio" name="type" value="d"  checked> Definition
    <input type="radio" name="type" value="e" > Example
    -->
     <br>
   <input type="image" src="img/submit4.gif"   alt="Submit" width="45" height="45" align="middle">

</form>

<!--
<br>
<br>
<font color='blue'>5 LEARN By EXAMPLE:</font><br>
<form method="post" action="learndict_eg.php" >
    <input type="submit" value="Example" />
</form>
-->

<br>
<br>
<font color='brown' STYLE="font-weight:600;">5 Your Progress</font><br>
<div class="button-container">
<form method="post" action="g_quiz_user.php" >
	<input type="image" src="img/cake2.png"   alt="Submit" width="50" height="50" >

     &nbsp; 
</form>
<form method="post" action="rpt_activity_week.php" >
  	<input type="image" src="img/report3.png"   alt="Submit" width="50" height="50" >
</form>
</div>


<br>
<br>
<?php    
    
}
require("footer.php");		 
?>		
	
</body>

</html>