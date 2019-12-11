<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $title_detail="Word Proficiency"; 
 $usedView_detail="v_student_performance_wk";
 $callingphp_detail="rpt_learnword_user_word.php";
 $maincssv=100;

 ?>
<head>
    
     <title><?php echo $title_detail; ?></title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
        <script src="../highchart/code/highcharts.js"></script>
    <script src="../highcharttable/jquery.highchartTable.js" type="text/javascript"></script>

<style>
	#graphBS {
  margin-right: 5%;
  margin-left: 5%;
}
</style>

    
</head>
<body>
<?php
require("header.php");
include_once("config.php");
$id=$_GET['id'];
$name=$_GET['name'];

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
    echo "<div id=title>$title_detail : $name</div>";
    echo "已会: Number of Words Learned<br>";
    echo "新学: Number of Words Newly Learned<br>";
    echo "在学: Number of Words Being Learned<br>";
    echo " <div style='overflow-x:auto;'>";
    echo '<table id="card-table" class="card-table" data-graph-container="#graphBS"  data-graph-height="200" data-graph-margin-top="40"
                    data-graph-datalabels-enabled="1"  data-graph-type="line">';
    
    class TableRows extends RecursiveIteratorIterator { 
    	public  $cnt=0; 
    	public  $currentwk=0; 
    	public  $cid=0;
        public $varcall="";
        function __construct($it , $pid, $call) { 
            parent::__construct($it,  self::LEAVES_ONLY); 
            $this->cid = $pid; 
            $this->varcall = $call;
        }
    
        function current() {
        	$this->cnt = $this->cnt +1;  
        	if ($this->cnt % 4 ==1) $this->currentwk=parent::current(); 
        	$retV="<td>" . parent::current()."</td>"; 
        	if ($this->cnt % 4 ==2) $retV="<td><a href='$this->varcall?type=2&id=$this->cid&wk="  .$this->currentwk. "'>" . parent::current()."</td>"; 
        	if ($this->cnt % 4 ==3) $retV="<td><a href='$this->varcall?type=3&id=$this->cid&wk="  .$this->currentwk. "'>" . parent::current()."</td>"; 
        	if ($this->cnt % 4 ==0) $retV="<td><a href='$this->varcall?type=0&id=$this->cid&wk="  .$this->currentwk. "'>" . parent::current()."</td>"; 
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
       
            $tableCols = array("周","已会","新学","在学");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";
            $sql = "
select   wk, 
sum( if (learning=1, 1, 0)) learned , 
sum( if (learning!=1 and learning>=$learningThreshold, 1, 0)) Newly_learned , 
sum( if (learning<$learningThreshold, 1, 0)) learning from $usedView_detail , users 
where userid=users.id and userid='$id' group by wk order by wk 
                   ";    
            $stmt = $conn->prepare($sql); 
            //$stmt->bindValue(1, $userid);
        
        $stmt->execute();
    
        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    
        echo "<thead><tr>";
        foreach($tableCols as $colName){
            echo "<th>".$colName."</th>";
        }
        echo "</tr></thead><tbody>";
        
        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll()) , $id, $callingphp_detail ) as $k=>$v) { 
            echo $v;
            
        }
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        exit;
        }
    $conn = null;
    
    echo "</tbody></table>";
    
    echo "<div id='graphBS'></div>";
    
    echo "<br><button onclick='goBack()'>Go Back</button>";

    echo " </div>";

require("footer.php");
?>

                   

<script>
 $(document).ready(function() { $('table.card-table').highchartTable(); });
</script>

<script>
function goBack() {
  window.history.back();
}
</script>

</body>


