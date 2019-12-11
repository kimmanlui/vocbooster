<?php 
//http://uic.healthywin.com/cuteflow/test_620.php?date=2019-04-22

$linebreak="<br>";

try {

    $servername="localhost:33307";
    $dbusername="root";
    $dbpassword="root123";
    $dbname="vtigercrm600";
    
    $dservername="localhost:3306";
    $ddbusername="root";
    $ddbpassword ="techteam01";    
    $ddbname    ="flashcard";



      
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
           select Student_mobile coming_login, CAST( DATE_FORMAT(birthday,'%m%d' ) AS char)  pwd, English_Name 
           from student_master_all where vocbooster='Y'
           ";
    $stmt = $conn->prepare("$sql");    
    $stmt->execute();
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $redarray= $stmt->fetchAll() ;




    $dconn = new PDO("mysql:host=$dservername;dbname=$ddbname", $ddbusername, $ddbpassword);
    $dconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dsql = "
           select username, password, name from users_data 
           ";

    $dstmt = $dconn->prepare("$dsql");    
    $dstmt->execute();
    $dresult = $dstmt->setFetchMode(PDO::FETCH_ASSOC); 
    $dellarray= $dstmt->fetchAll() ;


    
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}


$rollsize= sizeof($redarray);
$dellsize= sizeof($dellarray);

echo "from vtiger : ".$rollsize;
echo "<br>";
echo "from flashcard : ".$dellsize;
echo "<br>";
echo "<br>";


for ($roll=0; $roll<$rollsize ; $roll++)
{
	$already=0;
	$item=$redarray[$roll];
    $batch=$item["batch"]; 
    
    $coming_login   =$item["coming_login"];
    $pwd            =$item["pwd"];
    $English_name   =$item["English_Name"];

    
    for ($dellroll=0; $dellroll<$dellsize ; $dellroll++)
    {
        $dellitem=$dellarray[$dellroll];
        $username=$dellitem["username"]; 

        
        if ($username==$coming_login)
        {
        	$already=1; 
        	break;
        }
    }

    if ($already==0)
    {
        $English_name=str_replace("'","","$English_name");
        $English_name=str_replace("\"","","$English_name");

    	$insertesql = "
           insert into users_data 
           (username, password, name) 
           values
           ('$coming_login', '$pwd', '$English_name') 
            ";
       // echo $insertesql.$linebreak; 
        try 
        {
            $updatestmt = $dconn->prepare("$insertesql");   
            $updatestmt->execute();
            echo "<font color='brown'>$coming_login inserted</font>".$linebreak;
        }catch(PDOException $e){
            echo "Update Error: " . $e->getMessage();
        }    
    }
    
}
echo "Done";

?>
