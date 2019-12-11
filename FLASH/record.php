<?php 

$deckid=$_REQUEST['deckid'];
$userid=$_REQUEST['userid'];
$data  =$_REQUEST['data'];
$sid  =$_REQUEST['sid'];

if (!isset($data))
{
	die; 
}

$dataarray  =explode("^$", $data);
if (!isset($sid)) $sid=date("YmdHis");
require("inc/dbinfo.inc");

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("insert into log (deckid, userid,word,choice,sid) values (?,?,?,?,?)");
        //$stmt->execute(array($deckid,$userid,'test','yes'));
        foreach( $dataarray as $group ){
          $atomV  =explode("`", $group);
          $stmt->execute(array($deckid,$userid, $atomV[0],$atomV[1],$sid));
        }
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }

?>