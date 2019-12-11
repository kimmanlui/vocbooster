<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       
<style>
#fo{
  border-radius: 5px;
 text-align: left;
  padding: 20px;
}
	
</style>       
       
<head>
<?php
 $maincssv=100;
 ?>  
    <title>Result</title>
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
    echo "<br>";
    echo "Hi <font color='red'>".$username."</font>"; ?>
   
 
<?php 

function sim($var_1, $var_2)
{
  $var_1=strtolower($var_1);
  $var_2=strtolower($var_2);
  similar_text($var_1, $var_2, $percent); 
  $RANK=round($percent/100,2); 
  $srcmin=0;
  $srcmax=1;
  $destmin=-1;
  $destmax=1;
  $pos = (($RANK - $srcmin) / ($srcmax-$srcmin)) ;
  $rescaled = ($pos * ($destmax-$destmin)) + $destmin;
  return( $rescaled);
}


$p=$_REQUEST; 

date_default_timezone_set('PRC');
$sid=date("YmdHis") ;


    try {
    	require("inc/dbinfo.inc");
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conditionV="(67, 68, 70, 74)";
 
      $anyupdate=0;
 
      for ($i=0; $i<sizeof($p); $i++)
      {
      	    $anyupdate=$anyupdate+1; 
      		$qst='q'.$i;
      		$ans='a'.$i;
	        $sel='s'.$i;
	        $did='d'.$i;
	        if (isset($p[$ans]) and isset($p[$sel])) 
	        {
              $sql = "
              insert into quizlog (userid, q, a, s, choice, d, sid, type) values ( ?, ?, ?, ?, ?, ?, ?, ?)
              ";
              $stmt = $conn->prepare("$sql");    
       
              $stmt->bindValue(1, $_SESSION['userid']);
              $stmt->bindValue(2,  $p[$qst]);
              $stmt->bindValue(3,  $p[$ans]);
              $stmt->bindValue(4,  $p[$sel]);

              $choice=sim($p[$ans], $p[$sel]);

              $stmt->bindValue(5,  $choice);
              $stmt->bindValue(6,  $p[$did]);	      
              $stmt->bindValue(7,  $sid);
              $stmt->bindValue(8,  $p["type"]);
    	      $stmt->execute();
	        }
      }
      
      if ($anyupdate>0)
      {
            $stmt_top = $conn->prepare("SELECT top_dictation($_SESSION[userid]) as top_dictation ");
            $stmt_top->execute();
            $result_top = $stmt_top->fetchAll();
            if(sizeof($result_top) == 1)
            {
            	 $_SESSION['top_dictation']=$result_top[0]["top_dictation"];
            }
      }
      

    // set the resulting array to associative
    //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
   // $qarray= $stmt->fetchAll() ;
    
}catch(PDOException $e){
   // echo "Error: " . $e->getMessage();
}

$threshold=100; 
$correct=0;
$total=0;
$feedback="";
for ($i=0; $i<sizeof($p); $i++)
{
	$ans='a'.$i;
	$sel='s'.$i;
    $qst='q'.$i;
	if (isset($p[$ans]) and isset($p[$sel])) 
	{
		$total=$total+1; 
	  	if (strcmp(strtolower($p[$ans]), strtolower($p[$sel]))==0 )
	  	{
	  		$correct=$correct+1;
	  	} else
	  	{
	  		$sim=similar_text ( strtolower($p[$ans]) , strtolower($p[$sel]), $perc);
	  		if ( $sim>60)
	  		{
	  		  $feedback=$feedback."$p[$qst] ::: <font color='brown'>$p[$sel]</font>  <font color='DarkBlue'>$p[$ans]</font><br>";
	  		} else
	  		{
	  		  $feedback=$feedback."$p[$qst] ::: <font color='brown'>$p[$sel]</font> <br>";
	  		}
	  	}
	}
}

$marks=round($correct/$total * 100);
echo "<br>";
echo "<br>";
echo "<h1>Your Score is </h1><br>";
echo "<font size='8vw'> $marks (%) </font>";
echo "<br><br>";
if ($threshold<=$marks) {
	echo "Congratulations!!! ";
} else
{
	  echo "<font color='blue'>Review the FlashCard and Retake the Dictation</font><br><br>";
  echo "$feedback";
  echo "<br>";
  echo "Review <a href='usecards.php'>FlashCard</a>";
}

//print_r($p);
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