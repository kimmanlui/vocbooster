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
    <title>Dictation</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
</head>
<body>


<?php 
require("header.php");
include("config.php");    
include_once("LIB.php");



$username=$_SESSION['name']; 



    $deckid=$_REQUEST['deckid'];
    try {
    	require("inc/dbinfo.inc");
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //$conditionV="(67, 68, 70, 74, 86)";
 
        $sql = "
    SELECT  d.deckid deckid, back q, trim(lower(front)) ans, audiob64 au, d.name name
    FROM flashcards f, decks d
    WHERE !isnull(audiob64) and d.deckid=f.deckid and 
    HEX(f.back) REGEXP '^(..)*(E[2-9F]|F0A)'  order by rand() limit $numDictationQuestion_voice
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
echo "Dictation(Voice): Fill in the blank<br>";
//echo "<font size='3vw'>click the c button to clear the field</font>";
echo "<div id=fo>";
echo "<form autocomplete='off' method='get' action='dictationData.php' >";

$roll=1;
$rollsize= sizeof($qarray);


for ($roll=0; $roll<$rollsize ; $roll++)
{
  $item=$qarray[$roll];
  $answer='a'.$roll;
  $d='d'.$roll;
  $pv='p'.$roll;
  $q='q'.$roll;
  $sel='s'.$roll;
  $chk='c'.$roll;
  $hint="Hint: ".substr($item[ans], 0, 1)."...";

  echo "Deck: $item[name]<br>";
  echo "<font color='blue'>$item[q]</font>&nbsp;";
  echo genMP3Button($item["au"]); 
  echo " <input type=\"button\" onclick=\"myClear('$sel','$pv')\" value=\"clear\">";
 //   echo " <input type=\"button\" onclick=\"myShow('$sel')\" value=\"show\">&nbsp; ";
  echo "&nbsp;&nbsp;<p style=\"display:inline;\" id='$pv'>&nbsp;&nbsp;&nbsp;</p>";
  echo "<input type='hidden' name=$d value='$item[deckid]' >";
  echo "<input type='hidden' name=$q value='$item[q]' >";
  echo "<input type='hidden' name=$answer value='$item[ans]' >";

  echo "<br><input type='password' name=$sel value='' placeholder='$hint' id='$sel'  onkeyup=\"myDisplay('$sel', '$pv')\" required autocomplete='new-password'>";
 
 // echo "<input type='checkbox' id='$chk' onclick=\"myFunction('$sel', '$chk')\"><font size='0.7vw'>show</font>";
  echo '<br><br>';
}
echo "<input type='hidden' name=type value='v' >";     
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
 
<script>


function playAudio(sound) { 
  var x = document.getElementById(sound); 
  x.play(); 
} 

function pauseAudio() { 
  x.pause(); 
} 


const sleep = delay => new Promise(resolve => setTimeout(resolve, delay));

function myClear(id,id2) {
  document.getElementById(id).value='';
   document.getElementById(id2).innerHTML='';
}

function myShow(id) {
  if (  document.getElementById(id).value !== "")
  {
    alert(document.getElementById(id).value);
  }
}

function myDisplay(id, id2) {
  var k=document.getElementById(id).value; 
  document.getElementById(id2).innerHTML =k; 
}

 function myFunctionold(myid, chkid) {
  var x = document.getElementById(myid);
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
 // await sleep(3 * 1000);
//  x.type = "password";
//  document.getElementById(chkid).checked = false;
}
</script>
 
    
    <?php require("footer.php"); ?>
</body>