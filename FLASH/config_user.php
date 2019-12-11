<?php
    // default values
    $cfg_dictationType_user="'d' , 'v','f' "; 
    $cfg_cefr_dictationType_user="'d' , 'v' , 'f'"; 
 

    //$numDictQuizQuestion=5;  
    require("inc/dbinfo.inc");
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM setup_user where userid=$_SESSION[userid]");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetchAll();
        
        for ($i=0 ; $i<sizeof($result) ; $i++)
        {
            if ($result[$i]["type"]=="cfg_dictationType_user")          
            {
               $cfg_dictationType_user=$result[$i]["value"]; //$cfg_dictationType="c,f,d";
               $array = explode(",", $cfg_dictationType_user);
               array_walk($array, function(&$item) { $item="'".$item."'"; }); 
               $cfg_dictationType_user=implode(",",$array);
               $cfg_dictationType_user=strtolower($cfg_dictationType_user);
            }
            if ($result[$i]["type"]=="cfg_cefr_dictationType_user")          
            {
               $cfg_cefr_dictationType_user=$result[$i]["value"]; //$cfg_dictationType="c,f,d";
               $array = explode(",", $cfg_cefr_dictationType_user);
               array_walk($array, function(&$item) { $item="'".$item."'"; }); 
               $cfg_cefr_dictationType_user=implode(",",$array);
               $cfg_cefr_dictationType_user=strtolower($cfg_cefr_dictationType_user);
            }
        }
    }catch(PDOException $e){
    
        echo "Error: " . $e->getMessage();
    }
    $conn = null;



    //echo $numDictationQuestion_text; 

//   $numDictationQuestion=8; 
//   $numQuizQuestion=10; 
//   $numDictQuizQuestion=5; 
//   $numDictationQuestion_voice=5;




?>                                                                                                                                