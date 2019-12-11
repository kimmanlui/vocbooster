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
$p=$_REQUEST; 

date_default_timezone_set('PRC');
$sid=date("YmdHis") ;


    try {
    	require("inc/dbinfo.inc");
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conditionV="(67, 68, 70, 74)";
 
 
      for ($i=0; $i<sizeof($p); $i++)
      {
      		$qst='q'.$i;
      		$ans='a'.$i;
	        $sel='s'.$i;
	        $did='d'.$i;
	        if (isset($p[$ans]) and isset($p[$sel])) 
	        {
              $sql = "
              insert into quizlog (userid, q, a, s, choice, d, sid) values ( ?, ?, ?, ?, ?, ?, ?)
              ";
              $stmt = $conn->prepare("$sql");    
       
              $stmt->bindValue(1, $_SESSION['userid']);
              $stmt->bindValue(2,  $p[$qst]);
              $stmt->bindValue(3,  $p[$ans]);
              $stmt->bindValue(4,  $p[$sel]);
              $choice=-1; 
              if (strcmp($p[$ans], $p[$sel])==0 ) { $choice=1; }
              $stmt->bindValue(5,  $choice);
              $stmt->bindValue(6,  $p[$did]);	      
              $stmt->bindValue(7,  $sid);
    	      $stmt->execute();
	        }
      }
       //$stmt->execute();

    // set the resulting array to associative
    //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

   // $qarray= $stmt->fetchAll() ;
    
}catch(PDOException $e){
   // echo "Error: " . $e->getMessage();
}

$threshold=100; 
$correct=0;
$total=0;
$d_arr=array(); 
for ($i=0; $i<sizeof($p); $i++)
{
    $qst='q'.$i;
    $ans='a'.$i;
	$sel='s'.$i;
	$did='d'.$i;	
	if (isset($p[$ans]) and isset($p[$sel])) 
	{
		$total=$total+1; 
	  	if (strcmp($p[$ans], $p[$sel])==0 )
	  	{
	  		$correct=$correct+1;
	  	} else
	  	{
	  		array_push($d_arr, $p[$did]); 
	  		
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
  echo "<font color='blue'>Review the FlashCard and Retake The Quiz</font>";
}

$d_arr=array_unique($d_arr);
$wherecond="(".implode(",", $d_arr).")";


if ($marks<100)
{
  echo "<br><br>You are advised to review the card decks below";
  echo "<br><br><table id='tbl-deck-select'>";
  echo " <tr><th>Deck Name</th></tr>";
  try {
  $sql = "
         select deckid, name from decks where deckid in $wherecond order by deckid desc
         ";
  $stmt = $conn->prepare("$sql");    
  $stmt->execute();
   $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        
        foreach( $stmt->fetchall() as $rrow ){
            echo '
                <tr>
              
                    <td><a href=" usecards.php?deckid='.$rrow['deckid'].'">'.$rrow['name'].'</a></td>
                </tr>
            ';
        }
    } catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }
    $conn=NULL; 
     foreach($_GET as $k=>$v){
        echo '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
    }
    echo "</table><br>";
}


//print_r($p); //select name from decks where deckid in (93,74,86,104,91,67,70,87) order by deckid desc



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