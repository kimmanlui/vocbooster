<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
  $title="Activity/Performance Report"; 
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


    $where_extra=""; 
    $and_extra=""; 
    if(isset($_GET['week'])){
        $where_extra=" where week=$_GET[week] ";
         $and_extra=" and week=$_GET[week] ";
    }

 //   require("inc/getdeckname.inc");
    echo "<div id=title>$title</div>";
    echo "Type: flash / <font color='red'>d</font>ictation / <font color='blue'>q</font>uiz / <font color='brown'>c</font>ambridge 
                      / <font color='magenta'>v</font>oice / <font color='cyan'>f</font>ill-in-blank / <font color='purple'>e</font>xample <br><br>";
    echo " <div style='overflow-x:auto;'>";
    echo '<table id="card-table">';
    
    class TableRows extends RecursiveIteratorIterator { 
    	public $lastV="";
        function __construct($it) { 
            parent::__construct($it, self::LEAVES_ONLY); 
        }
    
        function current() {
        	$value=parent::current(); 
        	$action=0; 
        	
        	if ( strpos( $value, 'ID=' ) !== false) 
        	{
        		$this->lastV=$value;
        		$this->action=1; 
        		return "";
        	}
        	
        	if ( $this->action==1 )
        	{
        		$linkV="<td><a href='rpt_ind.php?$this->lastV'>$value</a></td>";
        		$this->lastV="";
        		$this->action=0;
        		return $linkV;
        	}
        	
        	if ($value=='q') $value="<font color='blue'>$value</font>"; 
        	if ($value=='d') $value="<font color='red'>$value</font>"; 
            if ($value=='c') $value="<font color='brown'>$value</font>"; 
            if ($value=='v') $value="<font color='magenta'>$value</font>"; 
            if ($value=='f') $value="<font color='cyan'>$value</font>"; 
            if ($value=='e') $value="<font color='purple'>$value</font>"; 
            
            return "<td>" . $value. "</td>";
        }
    
        function beginChildren() { 
            echo "<tr>"; 
        } 
    
        function endChildren() { 
            echo "</tr>" . "\n";
        } 
    } 
   
    require("inc/dbinfo.inc");
    session_name( 'FLASH' );
    session_start();
    $userid = $_SESSION['userid'];
    $role = $_SESSION['role'];
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tableCols = array();
       
            $tableCols = array("Name","Mark","Date","Day", "Type");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";
            if ($role !='s')
            {
              $sql = "
              select hideP, name, mark, Date, day, type from activity_v  $where_extra   order by date desc
              ";
            } else
            { //role is 's'
              $sql = "
               select hideP, name, mark, Date, day, type from activity_v where userid='$userid' $and_extra  order by date desc
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
     echo "<br><button onclick='goBack()'>Go Back</button>";
    echo " </div>";
    

require("footer.php");
?>
<script>
function goBack() {
	window.location.href = 'rpt_activity_week.php';
 // window.history.back();
}
</script>
</body>


