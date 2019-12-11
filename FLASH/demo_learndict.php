<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       
  
       
<head>
<?php
 $maincssv=321;
 $title="Learn By Definition";
 ?>  
    <title><?php echo $title ?></title>
     <link rel="stylesheet" href="js-ui-1.12.1/jquery-ui.css?v=<?php echo $maincssv;?>"/>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
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
	width: 7em;  /* control the width*/
}


#fo{
  border-radius: 5px;
 text-align: left;
  padding: 20px;
}
	
</style>         
</head>
<body>

<?php 
require("header.php");
include("config.php");

$username=$_SESSION['name']; 

 
    try {
    	require("inc/dbinfo.inc");
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //$conditionV="(67, 68, 70, 74, 86)";
  
        $sql = "
            select deckid, q, ans, R1, R2, R3, R4, R5 from quiz_dictionary_back_v where q!='' and wordcount(ans)=1
              order by rand() limit $numLearnByDefinition
            ";
        $stmt = $conn->prepare("$sql");    
       //      echo 'ok';
       // $stmt->bindValue(1,  $conditionV );
       // echo $stmt;
         //  echo 'ok';
    //$stmt->bindValue(1, $userid);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    $qarray= $stmt->fetchAll() ;
    
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}

echo "<br>";
echo "$title";
echo "<div id=fo>";
echo "<form method='post' action='demo_learndictData.php' >";

$roll=2;
$rollsize= sizeof($qarray);
$roll=4;

include ("LIB_DICT.php"); 

for ($roll=0; $roll<$rollsize ; $roll++)
{
  $item=$qarray[$roll];
  $random=$item;
  unset($random[deckid]);
  unset($random[q]);
  $random=array_values($random);
  $random=array_unique($random);
  shuffle($random);

  $val=ucfirst($item[q]);
  echo "<font color='blue'>$val:&nbsp; &nbsp; <br></font>";
  $answer='a'.$roll;
  $d='d'.$roll;
  $q='q'.$roll;
  echo "<input type='hidden' name=$d value='$item[deckid]' >";
  echo "<input type='hidden' name=$q value='$item[q]' >";
  echo "<input type='hidden' name=$answer value='$item[ans]' >";
  $sel='s'.$roll;
  echo "<select required name = '$sel' id='$sel'>";
  echo "<option  value=\"\"> *** options *** </option>";
  foreach($random as $cc => $name) {
    echo "<option value=\"$name\" >  $name &nbsp; </option>";
  }
  echo '</select><br><br>';
  
  echo "
  <script>
    $( function() {
    $( '#$sel' ).selectmenu();
  } );
  </script>
  ";
}
   
echo "<input type='submit' /></form>";

echo "</div>";


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