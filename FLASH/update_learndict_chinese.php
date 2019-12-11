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
 $title="update dictionary";
 ?>  
    <title><?php echo $title ?></title>
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


$username=$_SESSION['name']; 

 
    try {
      require("inc/dbinfo.inc");
      
      
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //$conditionV="(67, 68, 70, 74, 86)";
  
        $sql = "
           select cardid, front,back from flashcards where wordcount(front)=1 and trim(back)='NA'
             order by rand() limit $numupdateDict
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


$rollsize= sizeof($qarray);


include ("LIB_DICT.php"); 

for ($roll=0; $roll<$rollsize ; $roll++)
{
  $item=$qarray[$roll];
//cardid
   $cardid=$item[cardid]; 
   echo $item[front].'<br>';
   
   $tokenA='trans';
   $tokenArr=array($tokenA);
   
   $defarr=getGen($item[front], $tokenArr, 'Chinese');
     $val=$defarr[0][0][$tokenA];
     echo $val.'<br>'; 
     $val = str_replace("'","\'",$val);
     try {
     	$updatesql = "
           update flashcards set  back='$val'   where cardid=$cardid
            ";
          //  echo $updatesql; 
         $stmt = $conn->prepare("$updatesql");   
         $stmt->execute();
     }catch(PDOException $e){
        echo "Update Error: " . $e->getMessage();
    }
    echo "<br>"; 
    sleep(5);//second
}
   
echo "<font color='red'>done</font>";

echo "</div>";


    	 ?>     
   <!-- 	 
        <li><a href="addcards_form.php">Add cards</a></li>
        <br>
        <li><a href="deckoptions.php">Your decks</a></li>
        <br>
        <li><a href="usecards.php">Use cards</a></li>
     -->

<?php
  try {
    
   
      //$conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
      //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
  
        $sql = "
           select count(cardid) cnt from flashcards where wordcount(front)=1 and  trim(back)!='NA'
             order by rand() limit $numupdateDict
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
    $item=$qarray[0];
    echo "<br>Number of Available Backside:".$item['cnt']."<br>";
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}
?>
    
<?php
  try {
    
   
      //$conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
      //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
  
        $sql = "
           select count(cardid) cnt from flashcards where wordcount(front)=1 and trim(back)='NA'
             order by rand() limit $numupdateDict
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
    $item=$qarray[0];
    echo "<br>Number of Not Yet Updated Backside:".$item['cnt']."<br>";
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}
?>
    
    <?php require("footer.php"); ?>
</body>