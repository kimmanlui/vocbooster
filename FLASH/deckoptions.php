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
    <script src="js/deckoptions.js"></script>
    
</head>
<body>
    
<?php require("header.php"); ?>

Your decks:<br>

<table id="tbl-deck-select">
    <tr><th><input type="checkbox" name="select-all" id="select-all"/></th>
       
        <th>Deck Name</th><th>Cards</th><th># of Study</th><th>Active</th></tr>
    <?php 
    require("inc/dbinfo.inc");
    $userid = $_SESSION['userid'];
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("
            select v.deckid, v.name, v.cards, IFNULL(times, 0) times, if(enable=1,'yes','no') enable  from v_deckoptionrpt v
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
        	$showstatus=$rrow['enable'];
        	if (strcmp($rrow['enable'],'no')==0)
        	{
        		$showstatus="<font color='red'>".$rrow['enable']."</font>";
        	} 
            echo '
                <tr>
                    <td><input type="checkbox" name="deckid" value="'.$rrow['deckid'].'""/></td>
                    <td><a href="viewcards.php?deckid='.$rrow['deckid'].'">'.$rrow['name'].'</a></td>
                    <td>'.$rrow['cards'].'</td>
                    <td>'.$rrow['times'].'</td>
                    <td>'.$showstatus.'</td>
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


<input id="btn-active" type="button" name="active" value="active"/>
<input id="btn-inactive" type="button" name="inactive" value="inactive"/>
<input id="btn-delete" type="button" name="delete" value="Delete"/>
<input id="btn-combine" type="button" name="combine" value="Combine"/>


<input id="btn-copy" type="button" name="comp" value="Copy"/>
<br>


<?php require("footer.php"); ?>

</body>