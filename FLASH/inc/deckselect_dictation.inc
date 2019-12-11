<?php 
 /* 
Caller variables recognized:
 * bool newDeckOption:  assert default first "create new deck" option of value -1 
 * bool allDecksOption: assert "All decks" option of value "all"
*/?>

<?php 
//echo         "Select deck:";
   echo "<select id='deckiddic' name='deckiddic'>";
   if(isset($newDeckOption) && $newDeckOption == true){
?>
            <option value="-1">Create new deck</option>
    <?php
}

if(isset($allDecksOption) && $allDecksOption == true){
    ?>
           
    <?php
}

function newScope2(){
    require("inc/dbinfo.inc");
    $userid = $_COOKIE['userid'];
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //  $stmt = $conn->prepare("SELECT deckid,name FROM decks WHERE userid=?"); 
         // $stmt = $conn->prepare("SELECT deckid,name FROM decks "); 
         // $stmt = $conn->prepare("SELECT deckid,name FROM decks where deckid in (select distinct deckid from flashcards ) order by deckid desc "); 
          $stmt = $conn->prepare("
    SELECT distinct d.deckid as deckid, d.name as name
    FROM flashcards_v f, decks_v d
    WHERE  f.deckid=d.deckid and
    HEX(f.back) REGEXP '^(..)*(E[2-9F]|F0A)' ORDER BY RAND()
          ");
          
        //$stmt->execute(array($userid));
          $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        
        foreach( $stmt->fetchall() as $rrow ){
            echo '<option value="'.$rrow["deckid"].'">'.$rrow["name"].'</option>';
        }
        
        
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $conn = null;
}
newScope2();
?>
        </select><br/>
