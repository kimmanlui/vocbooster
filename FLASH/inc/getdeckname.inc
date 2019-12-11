<?php

function getDeckName($deckid){
    
    require("inc/dbinfo.inc");
    $userid = $_COOKIE['userid'];
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
//        $stmt = $conn->prepare("SELECT name FROM decks WHERE userid=? AND deckid=?"); 
        $stmt = $conn->prepare("SELECT name FROM decks WHERE  deckid=?"); 
        
//        $stmt->execute(array($userid, $deckid));
            $stmt->execute(array($deckid));
        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    
        $result = $stmt->fetch();
        
        if( $stmt->rowCount() < 1 ){
            return -1;
        }
        
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $conn = null;
        
        return $result['name'];
    }

?>