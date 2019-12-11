<?php 



 $numupdateDict=10; 

    try {
    	require("inc/dbinfo.inc");
      $servername="182.239.53.21";
      $dbusername="kimmanlui";
      $dbpassword="13823756771123";
      
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //$conditionV="(67, 68, 70, 74, 86)";
        $where=" wordcount(front)=1 and (isnull(cefr) or isnull(dictionary) or isnull(numeg))";
        $sql = "
           select cardid, front  from flashcards where $where
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

$rollsize= sizeof($qarray);

include ("LIB_DICT.php"); 


for ($roll=0; $roll<$rollsize ; $roll++)
{
  $item=$qarray[$roll];
//cardid
   $cardid=$item["cardid"]; 
   $word=$item["front"]; 
   
   echo "<font color='red'>$word</font><br>";
                                       
   
   $tokenA='def';
   $tokenB='def-info';
   $tokenC='inf';
   $tokenD='eg';
   $tokenE='examp emphasized';
   $tokenArr=array($tokenA , $tokenB,$tokenC,$tokenD,$tokenE);
   $dictionaryType="English"; 
   $defarr=getGen($word, $tokenArr ,  $dictionaryType);
   
   $l_word=strtolower($word);
     $val =$defarr[0][0][$tokenA];
     $cefr=getcefr($defarr[1][0][$tokenB]);
     $infArr =$defarr[2];
     $egArr  =$defarr[3];
     $exampleArr  =$defarr[4];
     echo $val.'('.$cefr.')\n'; 
      
     //print_r($infArr);
     //echo "<br>";
     //print_r($egArr);
     //echo "<br>";
     //echo "<br>";
     //echo "<br>";
     $egAF=array();
     for ($i=0 ; $i<sizeof($egArr); $i++)
     {
     	$egtemp=$egArr[$i][$tokenD];
     	$l_egtemp=strtolower($egtemp);
     	if (strpos($l_egtemp, '.') !== false && strpos($l_egtemp, $l_word)!== false)
     	{
     	  $egtemp  =str_replace("From Cambridge English Corpus","",$egtemp);
     	  $l_egtemp=str_replace("From Cambridge English Corpus","",$l_egtemp);
     	  $egtemp  =str_replace("From Wikipedia","",$egtemp);
     	  $l_egtemp=str_replace("From Wikipedia","",$l_egtemp);
     	  $egtemp  =str_replace("\n","",$egtemp);
     	  $l_egtemp=str_replace("\n","",$l_egtemp);
     	  $egtemp=trim($egtemp);
     	  $l_egtemp=trim($l_egtemp);
     	  
     	  if (strpos($egtemp, $l_word)!== false)
     	  {
     	  	$egtemp=str_replace($l_word,"____",$egtemp);
     	  	array_push($egAF, $egtemp); 
     	  } else if (strpos($egtemp, ucfirst($l_word))!== false)
     	  {
     	  	 $egtemp=str_replace(ucfirst($l_word),"____",$egtemp);
     	  	 array_push($egAF, $egtemp); 
     	  } else
          {
          	$l_egtemp=str_replace($l_word,"____",$l_egtemp);
          	array_push($egAF, $l_egtemp);
          }
     	  //echo $egtemp."<br>"; 
     	}  
     }
     $numeg=sizeof($egAF);
     if ($numeg>5) $numeg=5; 
     echo "[$numeg]\n\n"; 

     if ($numeg==5) {$eg1=$egAF[0];$eg2=$egAF[1];$eg3=$egAF[2];$eg4=$egAF[3];$eg5=$egAF[4];} 
     if ($numeg==4) {$eg1=$egAF[0];$eg2=$egAF[1];$eg3=$egAF[2];$eg4=$egAF[3];$eg5=$egAF[0];} 
     if ($numeg==3) {$eg1=$egAF[0];$eg2=$egAF[1];$eg3=$egAF[2];$eg4=$egAF[0];$eg5=$egAF[1];} 
     if ($numeg==2) {$eg1=$egAF[0];$eg2=$egAF[1];$eg3=$egAF[0];$eg4=$egAF[1];$eg5=$egAF[0];} 
     if ($numeg==1) {$eg1=$egAF[0];$eg2=$egAF[0];$eg3=$egAF[0];$eg4=$egAF[0];$eg5=$egAF[0];} 
     if ($numeg==0) {$eg1="";$eg2="";$eg3="";$eg4="";$eg5="";} 
     

     $val = str_replace("'","\'",$val);
     $eg1 = str_replace("'","\'",$eg1);
     $eg2 = str_replace("'","\'",$eg2);
     $eg3 = str_replace("'","\'",$eg3);
     $eg4 = str_replace("'","\'",$eg4);
     $eg5 = str_replace("'","\'",$eg5);

     try {
     	$updatesql = "
           update flashcards set  dictionary='$val' , cefr='$cefr'  , numeg='$numeg', 
                                  eg1='$eg1',eg2='$eg2',eg3='$eg3',eg4='$eg4',eg5='$eg5'
           where cardid=$cardid
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
   

try {
    $sql = "
           select count(cardid) cnt from flashcards where wordcount(front)=1 and !isnull(numeg)
             order by rand() 
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
           select count(cardid) cnt from flashcards where wordcount(front)=1 and isnull(numeg)
             order by rand() 
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
