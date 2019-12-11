<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
 $title_word="Word Proficiency Details"; 
// $usedView_word="v_dictation_performance_wk";
 ?>
<head>
    
     <title><?php echo $title_word; ?></title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
</head>
<body>
<?php
require("header.php");
require("config.php");
require("config_user.php");

$id=$_GET['id'];
$wk=$_GET['wk'];
$type=$_GET['type'];

    if(isset($_GET['status'])){
        $msg = '';
        switch($_GET['status']){
            case 'added':
                $msg = "Records successfully added!";
                break;
        }
        echo $msg."<br/>";
    }

 //   require("inc/getdeckname.inc");
    echo "<div id=title>Week: $wk (ID:$id)</div>";
    echo "类型: ".str_replace("'","",$cfg_dictationType_user)."<br>";
    echo " <div style='overflow-x:auto;'>";
    echo '<table id="card-table">';
    
    class TableRows extends RecursiveIteratorIterator { 
    	public  $cnt=0; 
    	public  $currentwk=0; 
    	public  $cid=0;
        function __construct($it) { 
            parent::__construct($it,  self::LEAVES_ONLY); 
        }
    
        function current() {
        	$retV="<td>" . parent::current()."</td>"; 
            return $retV;
        }
    
        function beginChildren() { 
            echo "<tr>"; 
        } 
    
        function endChildren() { 
            echo "</tr>" . "\n";
        } 
    } 
   
    require("inc/dbinfo.inc");
    $userid = $_SESSION['userid'];
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tableCols = array();
       
            $tableCols = array("序号","Word","正确率");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";
            $sql = "";
            if ($type==2)
            {
              $sql = "
                     select @rownum:=@rownum+1 No, word, learning from 
                     (
                     select  userid, a word , sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning, week(created_d) wk
                     from all_log_v_student where type in ($cfg_dictationType_user) group by userid, word, wk
                     ) em , (SELECT @rownum:=0) r  where  em.learning>=$learningThreshold and em.learning>=1 and em.userid=$id and em.wk=$wk 
                     order by learning, word; 
                     ";    
            }
            
            if ($type==3)
            {
              $sql = "
                     select @rownum:=@rownum+1 No, word, learning from 
                     (
                     select  userid, a word , sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning, week(created_d) wk
                     from all_log_v_student where type in ($cfg_dictationType_user) group by userid, word, wk
                     ) em , (SELECT @rownum:=0) r  where  em.learning>=$learningThreshold and em.learning<1 and em.userid=$id and em.wk=$wk 
                     order by learning, word;             
                     ";    
            }
            
            if ($type==0)
            {
              $sql = "
                     select @rownum:=@rownum+1 No, word, learning from 
                     (
                     select  userid, a word , sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning, week(created_d) wk
                     from all_log_v_student where type in ($cfg_dictationType_user) group by userid, word, wk
                     ) em , (SELECT @rownum:=0) r  where  em.learning<$learningThreshold and em.userid=$id and em.wk=$wk 
                     order by learning, word; 
                     ";    
            }       

            $stmt = $conn->prepare($sql); 
            //$stmt->bindValue(1, $userid);
        
        $stmt->execute();
    
        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    
        echo "<tr>";
        foreach($tableCols as $colName){
            echo "<th>".$colName."</th>";
        }
        echo "</tr>";
        
        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())  ) as $k=>$v) { 
            echo $v;
            
        }
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        exit;
        }
    $conn = null;
    
    echo "</table>";
    echo "<br><button onclick='goBack()'>Go Back</button>";

    echo " </div>";

require("footer.php");
?>


<script>
function goBack() {
  window.history.back();
}
</script>

</body>


