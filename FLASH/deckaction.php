<?php

/*
 * Action codes $_GET['action']:
 *  
 *  delete: 
 *      params: $_GET['deckids']=1,2,3,...
 *      delete deck(s)
 *  
 *  combine:
 *      Combines all passed decks into a new one, removing the old deck titles
 *      params: 
 *          deckids: comma separated. no spaces, empty spots, or duplicates
 *          newdecktitle: title of new deck created from combination
 *          userid: id of acting user
 */


if($_GET['action'] == 'delete'){
    deleteDecks(getDeckIDs());
}else if($_GET['action'] == 'active'){
    changeStatusDecks(getDeckIDs(),"1");
}else if($_GET['action'] == 'inactive'){
    changeStatusDecks(getDeckIDs(),"0");
}else if($_GET['action'] == 'combine'){
    combineDecks(getDeckIDs());
}else if($_GET['action'] == 'copy'){
    copyDeck(getDeckIDs());
}

function getDeckIDs(){
    if(!isset($_GET['deckids'])){
        die("ERROR: no deckids");
    }

    if(!preg_match('/^\d+(,\d+)*$/', $_GET['deckids'])){
        die("ERROR: \$_GET[deckids] improperly formatted");
    }
    
    $deckids = preg_split('/,/', $_GET['deckids']);

    if(count(array_unique($deckids))<count($deckids)){
        die("ERROR: No duplicate values");
    }
    
    return $deckids;
}



function changeStatusDecks($deckids, $status){
    require("inc/dbinfo.inc");
    $userid = $_SESSION['userid'];
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $dsql = "
            update decks set enable='$status'
            WHERE deckid=? "; 
            
        for($i=0; $i<sizeof($deckids)-1; $i++){
            $dsql .= "OR deckid=?";
        }
       // echo $dsql;
        $dstmt = $conn->prepare($dsql);
        $dstmt->execute( $deckids);

        $csql = "
            update flashcards set enable='$status'
            WHERE deckid=? "; 
            
        for($i=0; $i<sizeof($deckids)-1; $i++){
            $csql .= "OR deckid=?";
        }
      //  echo $csql;
        $cstmt = $conn->prepare($csql);
        $cstmt->execute( $deckids);

    
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
        exit;
    }
    $conn = null;
    
    header("Location: deckoptions.php");
}

function deleteDecks($deckids){

    
    require("inc/dbinfo.inc");
    $userid = $_SESSION['userid'];
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    //    $sql = "
    //        DELETE decks,flashcards 
   //         FROM decks LEFT JOIN flashcards ON flashcards.deckid = decks.deckid
   //         WHERE userid=?
   //             AND decks.deckid=? ";
        $sql = "
            DELETE decks,flashcards 
            FROM decks LEFT JOIN flashcards ON flashcards.deckid = decks.deckid
            WHERE 
                 decks.deckid=? "; 
            
        for($i=0; $i<sizeof($deckids)-1; $i++){
            $sql .= "OR decks.deckid=?";
        }
        
        $stmt = $conn->prepare($sql);
    //    $stmt->execute(array_merge(array($userid), $deckids));
         $stmt->execute( $deckids);
    
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
        exit;
    }
    $conn = null;
    
    header("Location: deckoptions.php");
}

function combineDecks($deckids){
    
    $currentUserID = $_SESSION['userid'];
    
    require("inc/dbinfo.inc");
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create new deck
        $newDeckTitle = $_GET['newdecktitle'];
        
        $stmt_newDeck = $conn->prepare(
            "INSERT INTO decks(userid,name) VALUES(?,?)"
        );
        if(!$stmt_newDeck->execute(array($currentUserID, $newDeckTitle))){
            die("No decks added");
        }
        
        $stmt_getDeckID = $conn->prepare("SELECT deckid FROM decks WHERE userid=? AND name=?");
        $stmt_getDeckID->execute(array($currentUserID, $newDeckTitle));

        // set the resulting array to associative
        $stmt_getDeckID->setFetchMode(PDO::FETCH_ASSOC); 
        $rrow = $stmt_getDeckID->fetch();
        
        $deckid = $rrow["deckid"];
    
        
        // Reassign to new deck
        $sql = "UPDATE flashcards SET deckid=? WHERE deckid=? ";
        for($i=0; $i<sizeof($deckids)-1; $i++){
            $sql .= "OR deckid=?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge(array($deckid),$deckids));
        
        // Delete now empty decks
        deleteDecks($deckids);
        
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
        exit;
    }
    $conn = null;
    
    header("Location: deckoptions.php");
    
}

function copyDeck($deckids){
    $currentUserID = $_SESSION['userid'];
    $deckid = array_shift($deckids);
    
    require("inc/dbinfo.inc");
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create new deck
        $newDeckTitle = $_GET['newdecktitle'];
        
        $stmt_newDeck = $conn->prepare(
            "INSERT INTO decks(userid,name) VALUES(?,?)"
        );
        $result = $stmt_newDeck->execute(array($currentUserID, $newDeckTitle));
        if(!$result){
            die("No decks added");
        }
        
        $stmt_getDeckID = $conn->prepare("SELECT deckid FROM decks WHERE userid=? AND name=?");
        $stmt_getDeckID->execute(array($currentUserID, $newDeckTitle));

        // set the resulting array to associative
        $stmt_getDeckID->setFetchMode(PDO::FETCH_ASSOC); 
        $rrow = $stmt_getDeckID->fetch();
        
        $newdeckid = $rrow["deckid"];

        // Copy records into new deck
        $sql = "
            INSERT INTO flashcards (deckid, front, back)
            SELECT ?, front, back
            FROM flashcards JOIN decks ON flashcards.deckid = decks.deckid
            WHERE userid=? 
                AND flashcards.deckid=?
        ";
        $stmt_insert = $conn->prepare($sql);
        $stmt_insert->execute(array($newdeckid,$currentUserID,$deckid));
        
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
        exit;
    }
    $conn = null;
    
    header("Location: deckoptions.php");
        
    
}
?>