<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $title_user="Word Proficiency Report (Activity Type)"; 
 //$usedView_user="v_dictation_performance";
 $callingphp_user="rpt_dictation_user_detail.php";
 $maincssv=100;
?>

<head>
    
     <title><?php echo $title_user; ?></title>
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
    echo "<div id=title>$title_user</div>";
    echo "类型: ".str_replace("'","",$cfg_dictationType)."<br>";
    echo "已会: Number of Words Learned<br>";
    echo "新学: Number of Words Newly Learned<br>";
    echo "在学: Number of Words Being Learned<br>";
    echo " <div style='overflow-x:auto;'>";
    echo '<table id="card-table">';
    
    class TableRows extends RecursiveIteratorIterator { 
    	public $last=""; 
    	public $varcall="";
        function __construct($it, $call) { 
            parent::__construct($it, self::LEAVES_ONLY); 
            $this->varcall=$call; 
        }
    
        function current() {
        	if (is_numeric(parent::current())) 
        	{
        		$retV = "<td>" . parent::current(). "</td>";
        		$this->last = parent::current(); 
        		return $retV; 
        	}
        	$retV="<td><a href='$this->varcall?id=$this->last&name=" . parent::current()."'>" . parent::current()."</a></td>";
        	$this->last = parent::current(); 
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
       
            $tableCols = array("ID", "名字","已会","新学","在学");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";

                  
            $sql = "
            select em.userid, u.name,  
sum( if (em.learning=1, 1, 0)) LNT , 
sum( if (em.learning!=1 and em.learning>=$learningThreshold, 1, 0)) NewLNT  , 
sum( if (em.learning< $learningThreshold, 1, 0)) LNG from  
(
  select  userid, a word, sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning
        , type
 from all_log_v_student where type in ($cfg_dictationType) group by userid, word
) em
, users u
where em.userid=u.id group by em.userid order by NewLNT desc
                   ";       
                   
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
        
        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll()), $callingphp_user) as $k=>$v) { 
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
    echo " </div>";

require("footer.php");
?>

</body>


