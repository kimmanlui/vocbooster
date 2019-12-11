<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
 $title="Full Proficiency Report (Flashcard Only)"; 
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
    
    echo "<div id=title>$title</div>";
    echo "已会: Number of Words Learned<br>";
    echo "新学: Number of Words Newly Learned<br>";
    echo "在学: Number of Words Being Learned<br>";
    
    echo " <div style='overflow-x:auto;'>";
    echo '<table id="card-table">';
    
    class TableRows extends RecursiveIteratorIterator { 
    	public $last="";
    	public $laststudent="";
    	public $nowstudent="";
    	public $counter=""; 
    	public $colorA="#33EE99";
        public $colorB="#33cc99";
    	public $color="";

        function __construct($it) { 
            parent::__construct($it, self::LEAVES_ONLY); 
        }
    
        function current() {
        	    
        	    $retV = "<td>" . parent::current(). "</td>";
        	
        		return $retV; 
       
        }
    
        function beginChildren() { 
        	
        	    //$this->counter=$this->counter +1; 
        	    //if (($this->counter % 5)==1)
        	    //{
        	    	$this->nowstudent=parent::current(); 
        	    	if (!(strcmp($this->nowstudent,$this->laststudent)==0))
        	    	{ 
        	    	  if ($this->color==$this->colorA)
        	    	  {
        	    		$this->color=$this->colorB;
        	    	  } else
        	    	  {
        	    		$this->color=$this->colorA;
        	    	  }
        	    	}
        	    	$this->laststudent=	$this->nowstudent; 
        	    //}
        	    $this->last = parent::current(); 
            echo "<tr bgcolor='$this->color'>"; 
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
       
            $tableCols = array("名字", "wk","已会","新学","在学");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";
            $sql = "
select   
name, 
wk, 
sum( if (learning=1, 1, 0)) learned , 
sum( if (learning!=1 and learning>=$learningThreshold, 1, 0)) Newly_learned , 
sum( if (learning<$learningThreshold, 1, 0)) learning from v_student_performance_wk , users 
where userid=users.id and 
userid in (select distinct userid from v_student_performance)
group by userid, wk order by name, wk desc
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
        
        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
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


