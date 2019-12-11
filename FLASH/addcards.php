<?php require("inc/cookiecheck.inc"); ?>
<?php

$currentUserID = $_SESSION['userid'];

$values = array();
if($_POST['input-method'] == 'upload'){

    // upload the file, foil the hackers
    $uploadDir = 'tmp';
    try {
        
        var_dump($_FILES)."<br>\n";
        
        
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($_FILES['input-file']['error']) ||
            is_array($_FILES['input-file']['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }
    
        // Check $_FILES['input-file']['error'] value.
        switch ($_FILES['input-file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
                break;
            default:
                throw new RuntimeException('Unknown errors.');
        }
    
        // Should also check filesize here. 
        if ($_FILES['input-file']['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }
    
        // DO NOT TRUST $_FILES['input-file']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['input-file']['tmp_name']),
            array(
                'txt' => 'text/plain',
            ),
            true
        )) {
            //throw new RuntimeException('Invalid file format.');
        }
    
        // You should name it uniquely.
        // DO NOT USE $_FILES['input-file']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $inputfile = sprintf($uploadDir.'/%s.%s',
                sha1_file($_FILES['input-file']['tmp_name']),
                $ext );
        if (!move_uploaded_file(
            $_FILES['input-file']['tmp_name'],
            $inputfile
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    
        //echo 'File is uploaded successfully<br>';
    
        include_once("LIB_ReadChiFile.php");
        $values=read_chi_file($inputfile,$values);
       
        
         $test=read_chi_file($inputfile, array());
       
        foreach ($test as $oneitem) {
          $v = trim($oneitem[1]);
          if (!empty(trim($oneitem[0])) and empty($v))
          {
          	    echo "<br>Error at the line of $oneitem[0]<br>"; 
          	
            throw new RuntimeException('File Format Error');
          }
        }
        unlink($inputfile);
        
    } catch (RuntimeException $e) {
    
        echo $e->getMessage();
        exit;
    }
   
   
     
    // now open and play
    //$fh = fopen($inputfile, "r") or die("Unable to open file!");
    //while( !feof( $fh) ){
    //    $readline = fgets($fh);
    //    if(preg_match('/,/', $readline)){
    //        $tokens = preg_split('/\s*,\s*/', trim($readline));
    //        array_push( $values, $tokens);
    //    }
    //}
    //fclose($fh);
    
    
   // include_once("LIB_ReadChiFile.php");
   // $values=read_chi_file($inputfile,$values);
  //  unlink($inputfile);
    
     
}elseif($_POST['input-method'] == 'manual'){
    
    $cardInput = $_POST['card-input'];
    $lines = preg_split('/\n/', $cardInput);
    
    foreach( $lines as $line ){
        $matches = array();
        if( preg_match('/^\s*(.*\S+.*)\s*,\s*\s*(.*\S+.*)\s*$/', $line, $matches) ){
            array_push( $values, array($matches[1],$matches[2]));
        }
        
    }
}

require("inc/dbinfo.inc");

//print_r($values); echo "<br>";


$e=null;
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // get/create deck
    $deckid = -1;
    if($_POST['deckid'] == -1){
            
        $newDeckTitle = $_POST['new-deck-title-input'];
        
        $stmt_newDeck = $conn->prepare(
            "INSERT INTO decks(userid,name) VALUES(?,?)"
        );
        $result = $stmt_newDeck->execute(array($currentUserID, $newDeckTitle));
        
        $stmt_getDeckID = $conn->prepare("SELECT deckid FROM decks WHERE userid=? AND name=? order by deckid desc LIMIT 0,1");
        $stmt_getDeckID->execute(array($currentUserID, $newDeckTitle));

        // set the resulting array to associative
        $stmt_getDeckID->setFetchMode(PDO::FETCH_ASSOC); 
        $rrow = $stmt_getDeckID->fetch();
        
        $deckid = $rrow["deckid"];
    }else{
        $deckid = $_POST['deckid'];
    }

    // begin the transaction
    $conn->beginTransaction();
    // SQL statements
    
    $stmt = $conn->prepare("INSERT INTO flashcards (deckid, front, back) VALUES (?,?,?)");
    foreach( $values as $rr ){
  //  	echo $deckid."___".$rr[0]."___".$rr[1];
       $stmt->execute(array($deckid, $rr[0], $rr[1]));
    }
    
    $conn->commit();
 
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$conn = null;

if($e == null){
    header("Location: viewcards.php?status=added&deckid=".$deckid);
    exit;
}

?>