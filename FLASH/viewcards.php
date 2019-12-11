<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=323;
 ?>
<head>

    <title> View your flashcards</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
</head>
<body>
<?php
require("header.php");

if(isset($_GET['deckid'])){

    if(isset($_GET['status'])){
        $msg = '';
        switch($_GET['status']){
            case 'added':
                $msg = "Records successfully added!";
                break;
        }
        echo $msg."<br/>";
    }

    require("inc/getdeckname.inc");
    echo '<div id=title>'.
        ($_GET['deckid'] == 'all' ? "All Decks" : getDeckName($_GET['deckid'])) . "</div>";
     echo 'CEFR is automatically generated';
    echo '<table id="card-table">';
    
    class TableRows extends RecursiveIteratorIterator { 
        function __construct($it) { 
            parent::__construct($it, self::LEAVES_ONLY); 
        }
    
        function current() {
        	if (parent::current()=='N')  return "<td><font color='red'>" . parent::current(). "</font></td>";
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
        if( $_GET['deckid'] == 'all' ){
            $tableCols = array("Deck name","front","back","DIC","AU");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";
            $sql = "
                SELECT decks.name,front,back,cefr, 
                if(length(dictionary)>10, 'Y', 'N') DIC,
                if(length(audiob64)>10, 'Y', 'N') AU
                FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
                
                ORDER BY decks.deckid, cardid ";    
            $stmt = $conn->prepare($sql); 
            $stmt->bindValue(1, $userid);
        }else{
            $tableCols =  array("front","back","CEFR", "DIC","AU");
            //$sql = "
            //    SELECT front,back 
            //    FROM flashcards JOIN decks ON flashcards.deckid=decks.deckid 
            //    WHERE userid=? 
            //        AND flashcards.deckid=?";
            $sql = "
                SELECT front,back ,cefr,
                if(length(dictionary)>10, 'Y', 'N') DIC,
                if(length(audiob64)>10, 'Y', 'N') AU
                FROM flashcards JOIN decks ON flashcards.deckid=decks.deckid 
                WHERE  flashcards.deckid=?";

            $stmt = $conn->prepare($sql); 
           // $stmt->bindValue(1, $userid);
           // $stmt->bindValue(2, $_GET['deckid']);
           $stmt->bindValue(1, $_GET['deckid']);
        }

        
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
}else{
    ?>
    Choose a deck:
    <form method="get" action="<?php echo basename(__FILE__); ?>" >
        <?php 
        $allDecksOption = true;
        require("inc/deckselect.inc"); 
        foreach($_GET as $k=>$v){
            echo '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
        }
        ?>
        <input type="submit" />
    </form>
    <?php    
}

require("footer.php");
?>

</body>


