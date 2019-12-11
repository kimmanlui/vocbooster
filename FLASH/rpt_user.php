<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
 $title="Student Activity Report"; 
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
    echo " <div style='overflow-x:auto;'>";
    echo '<table id="card-table">';
    
    class TableRows extends RecursiveIteratorIterator { 
        function __construct($it) { 
            parent::__construct($it, self::LEAVES_ONLY); 
        }
    
        function current() {
            return "<td>" . parent::current(). "</td>";
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
       
            $tableCols = array("Student","Date","Day", "Activity");
      
            $sql = "
            (select name, max(created_d) date ,  SUBSTRING(DayName( max(created_d)),1,3) day,  'card' activity from log_v_student , users where userid=users.id group by username )
            union 
            (select name, max(created_d) date ,  SUBSTRING(DayName( max(created_d)),1,3) day,   if( type='d', 'dict', 'quiz' ) activity from quizlog_v_student , users where userid=users.id and type!='c' group by username )
            union
            (select name, max(created_d) date ,  SUBSTRING(DayName( max(created_d)),1,3) day,   'cbg' activity from quizlog_v_student , users where userid=users.id and type='c' group by username )
            order by date desc
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


