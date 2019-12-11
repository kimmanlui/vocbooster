<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
 $title="Advised To Learn"; 
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

    
    class TableRows extends RecursiveIteratorIterator { 
        function __construct($it) { 
            parent::__construct($it, self::LEAVES_ONLY); 
        }
        public $counter=0;
        public $did=0; 
    
        function current() {
        	$this->counter = $this->counter +1;
        	
        	$retV = parent::current();
            if ( $this->counter % 2 ==1) $this->did=parent::current(); 
        	if ( $this->counter % 2 ==0) 
        	{
        		$retV= "<a href='usecards.php?deckid=$this->did'>".parent::current()."</a>";
        	}
            return "<td>" . $retV . "</td>";
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
    //$userid = 111; 
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tableCols = array();
       
            $tableCols = array("Deck ID", "Deck Name");
            //$sql = "
            //select name, max(created_d) date from log_v_student , users where userid=users.id group by username order by date desc
            //       ";
            $sql = "
select  deckid, name from decks_v where
deckid in ( select  d from quizlog where a!=s and 
sid in (select max(sid) from quizlog where a!=s  and userid=$userid) ) order by deckid desc
                  "; 
            $stmt = $conn->prepare($sql); 
            //$stmt->bindValue(1, $userid);
        
        $stmt->execute();
        $count = $stmt->rowCount();
       //echo $count; 
        if ($count>0)
        {
           echo "You are advised to learn the following decks and then to retake the quiz";
           echo " <div style='overflow-x:auto;'>";
           echo '<table id="card-table">';
           
           $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    
           echo "<tr>";
           foreach($tableCols as $colName){
            echo "<th>".$colName."</th>";
           }
           echo "</tr>";
        
           foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
            echo $v;
           }
           
        } else
        {
           echo "Please take the <a href='quiz.php'>quiz</a> and we will advise you which decks to learn.";
           echo " <div style='overflow-x:auto;'>";
           echo '<table id="card-table">';
        }
        // set the resulting array to associative
       
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


