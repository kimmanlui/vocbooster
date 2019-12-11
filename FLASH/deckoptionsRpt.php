<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>
<?php
 $maincssv=101;
 ?>  
    <title>Your Decks</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
      <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
    <script src="js/deckoptionsRpt.js"></script>
    
</head>
<body>
    
<?php require("header.php"); ?>

Your decks:<br>

<table id="tbl-deck-select">
    <tr>
        <th>Deck Name</th><th>Date</th><th>Cards</th><th># of Study</th></tr>
    <?php 
    require("inc/dbinfo.inc");
    $userid = $_SESSION['userid'];
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $updateStmt= $conn->prepare("
          update log t 
          set deckid = (select max(f.deckid) from flashcards f where f.front=t.word)
          where t.deckid=0 and t.word!='[No cards]'
        "); 
        $updateStmt->execute();


          // SELECT decks.deckid,name,count(*) AS cards
          //  FROM decks JOIN flashcards ON decks.deckid = flashcards.deckid
          //  GROUP BY decks.deckid order by decks.deckid desc
        $stmt = $conn->prepare("
            select v.deckid, v.name, v.cards, IFNULL(times, 0) times, REPLACE(date(v.created_d),'-','.') date from v_deckoptionrpt v
            left join 
            (
              select deckid, count(userid) times from (select distinct deckid, userid 
              from log_v_student where deckid!=0) k group by k.deckid 
            ) j on j.deckid=v.deckid
        "); 
        $stmt->execute(array($userid));
    
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        
        foreach( $stmt->fetchall() as $rrow ){
            echo '
                <tr>
                    
                    <td><a href="rpt_deck.php?deckid='.$rrow['deckid'].'">'.$rrow['name'].'</a></td>
                    <td>'.$rrow['date'].'</td>
                    <td>'.$rrow['cards'].'</td>
                    <td>'.$rrow['times'].'</td>
                </tr>
            ';
        }
        
        
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    
    
    foreach($_GET as $k=>$v){
        echo '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
    }
    ?>
</table><br>
<br>


<?php require("footer.php"); ?>

</body>