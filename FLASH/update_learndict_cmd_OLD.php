<?php 



 $numupdateDict=10; 

try {
	require("inc/dbinfo.inc");
    $servername="182.239.53.21";
    $dbusername="kimmanlui";
    $dbpassword="13823756771123";
      
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
           select cardid, front  from flashcards where wordcount(front)=1 and (isnull(cefr) or isnull(dictionary))
             order by rand() limit $numupdateDict
            ";
    $stmt = $conn->prepare("$sql");    
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $qarray= $stmt->fetchAll() ;
    
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}


$rollsize= sizeof($qarray);

include ("LIB_DICT.php"); 

for ($roll=0; $roll<$rollsize ; $roll++)
{
	$item=$qarray[$roll];
    $cardid=$item["cardid"]; 
    echo "FRONT : ".$item["front"].'\n';
   
    $tokenA='def';
    $tokenB='def-info';
    $tokenArr=array($tokenA , $tokenB);
   
    $defarr=getGen($item["front"], $tokenArr);
    $val=$defarr[0][0][$tokenA];
    $cefr=getcefr($defarr[1][0][$tokenB]);
    //echo $val; 
    $val = str_replace("'","\'",$val);
    try 
    {
        $updatesql = "
           update flashcards set  dictionary='$val' , cefr='$cefr'  where cardid=$cardid
            ";
        $stmt = $conn->prepare("$updatesql");   
        $stmt->execute();
     }catch(PDOException $e){
        echo "Update Error: " . $e->getMessage();
    }
    echo "\n"; 
    sleep(5);//second
}
   

try {
    $sql = "
           select count(cardid) cnt from flashcards where wordcount(front)=1 and !isnull(dictionary)
             order by rand() limit $numupdateDict
            ";
    $stmt = $conn->prepare("$sql");    
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    $qarray= $stmt->fetchAll() ;
    $item=$qarray[0];
    echo "Number of Available Dictionary Definition:".$item['cnt']."\n";
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}

try {
    $sql = "
           select count(cardid) cnt from flashcards where wordcount(front)=1 and isnull(dictionary)
             order by rand() limit $numupdateDict
            ";
    $stmt = $conn->prepare("$sql");    
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    $qarray= $stmt->fetchAll() ;
    $item=$qarray[0];
    echo "Number of Not Yet Updated:".$item['cnt']."\n";
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}
?>
