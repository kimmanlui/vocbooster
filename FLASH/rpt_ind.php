<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
 $title="Word Proficiency Report"; 
 ?>
<head>
    
     <title><?php echo $title; ?></title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
</head>
<body>
<?php
require("header.php");
include_once("config.php");
$ID=$_GET['ID'];
$created_d=$_GET['created_d'];
$type=$_GET['type'];



 //   require("inc/getdeckname.inc");
    echo "<div id=title>$ID</div>";

    echo " <div style='overflow-x:auto;'>";
    echo '<table id="card-table">';
    
    class TableRows extends RecursiveIteratorIterator { 
    	public  $cnt=0; 
    	public  $currentwk=0; 
    	public  $cid=0;
        //function __construct($it , $pid) { 
        //    parent::__construct($it,  self::LEAVES_ONLY); 
        //    $this->cid = $pid; 
        //}
        function __construct($it) { 
            parent::__construct($it, self::LEAVES_ONLY); 
        }
    
        function current() {
        	//$this->cnt = $this->cnt +1;  
        	//if ($this->cnt % 4 ==1) $this->currentwk=parent::current(); 
        	$tmpV=parent::current();
        	//if ($this->cnt % 4 ==2) $retV="<td><a href='rpt_learnword_user_word.php?type=2&id=$this->cid&wk="  .$this->currentwk. "'>" . parent::current()."</td>"; 
        	//if ($this->cnt % 4 ==3) $retV="<td><a href='rpt_learnword_user_word.php?type=3&id=$this->cid&wk="  .$this->currentwk. "'>" . parent::current()."</td>"; 
        	//if ($this->cnt % 4 ==0) $retV="<td><a href='rpt_learnword_user_word.php?type=0&id=$this->cid&wk="  .$this->currentwk. "'>" . parent::current()."</td>"; 
        	if (is_numeric($tmpV) && $tmpV<0.6 ) 
        	{
        		return "<td><font color='red'>" . parent::current(). "</font></td>";
        	} else if (is_numeric($tmpV) && $tmpV<1 )  
        	{
        		return "<td><font color='blue'>" . parent::current(). "</font></td>";
        	} else
        	{
                return "<td>" . parent::current(). "</td>";
        	}
        	
        	return  "<td>" . parent::current(). "</td>";
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
        
        if ($type=='d' or $type=='q' or $type=='c' or $type=='v' or $type=='e' or $type=='f')
        {
       
            $tableCols = array("学生答","答案","正确率");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";
            $sql = "
                   select s student_ans,  a ans, choice  accuracy from quizlog_v_student 
                    where sid in (select distinct sid from quizlog_v_student where created_d='$created_d') order by choice 
                   ";    
        } // end of      if ($type=='d')    
        if ($type=='flash')
        {
       
            $tableCols = array("DeckID", "学习单词","评估");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";
            $sql = "
                  select deckid, word, choice from log_v_student where userid=$ID and 
                  created_d between subtime('$created_d' ,'00:02:00')   and addtime('$created_d',  '00:02:00')   
                  order by choice
                   ";    
        }

  //      echo $sql;            
                   
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
        
        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll()) , $id ) as $k=>$v) { 
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
//	window.location.href = 'rpt_activity_week.php';
  window.history.back();
}
</script>

</body>


