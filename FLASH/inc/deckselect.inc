<?php 
 /* 
Caller variables recognized:
 * bool newDeckOption:  assert default first "create new deck" option of value -1 
 * bool allDecksOption: assert "All decks" option of value "all"
*/?>
<?php 
//echo         "Select deck:";
echo "<select id='deckid' name='deckid'>";

if(isset($newDeckOption) && $newDeckOption == true){
    ?>
            <option value="-1">Create new deck</option>
    <?php
}

if(isset($allDecksOption) && $allDecksOption == true){
    ?>
            
    <?php
}

function newScope(){
    require("inc/dbinfo.inc");
    $userid = $_COOKIE['userid'];
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //  $stmt = $conn->prepare("SELECT deckid,name FROM decks WHERE userid=?"); 
      //    $stmt = $conn->prepare("SELECT deckid,name FROM decks "); 
          $stmt = $conn->prepare("SELECT deckid,name FROM decks_v where deckid in (select distinct deckid from flashcards_v )  ORDER BY RAND() "); 
          
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
newScope();
?>
        </select><br/>
