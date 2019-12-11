<?php 

$message="Demo Version:<br> 
          Student Account/Password: <font color='blue'>guest/guest</font><br>
          Teacher Account/Password: <font color='brown'>teacher/513</font><br><br>
          Review your flashcards once or twice everyday.";   
$message="Review Flashcards Once or Twice Everyday.<br><br>Achieve Dictation 80% of New Decks Each Week.";

  require("inc/dbinfo.inc");
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM setup");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetchAll();
        
        for ($i=0 ; $i<sizeof($result) ; $i++)
        {
        	if ($result[$i]["type"]=="loginMessage")               $message=$result[$i]["value"]; 
        }
    }catch(PDOException $e){
    
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

?>