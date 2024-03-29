<?php
    // default values
    $learningThreshold=0.5; 
    $numQuizQuestion=35; 
    $numDictationQuestion_text=10; 
    $numDictationQuestion_voice=10;
    $numDictationQuestion_fill=5;
    $numLearnByDefinition=5;
    $numLearnByExample=5; 
    $cfg_dictationType="'d' , 'v' , 'f'"; 
    $cfg_cefr_dictationType="'d' , 'v' , 'f'"; 
    
    $numupdateDict=5; 
    $numupdateDict_voice=10; 
    $showDictationTopPercentage=1; 

    //$numDictQuizQuestion=5;  
    //if (!isset($servername) and !isset($dbname) and !isset($dbusername) and !isset($dbpassword)) {  require("inc/dbinfo.inc"); }
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
        	if ($result[$i]["type"]=="learningThreshold")          $learningThreshold=$result[$i]["value"]; 
        	if ($result[$i]["type"]=="numQuizQuestion")            $numQuizQuestion=$result[$i]["value"]; 
        	if ($result[$i]["type"]=="numDictationQuestion_text")  $numDictationQuestion_text=$result[$i]["value"]; 
        	if ($result[$i]["type"]=="numDictationQuestion_voice") $numDictationQuestion_voice=$result[$i]["value"]; 
        	if ($result[$i]["type"]=="numDictationQuestion_fill")  $numDictationQuestion_fill=$result[$i]["value"]; 
        	if ($result[$i]["type"]=="numLearnByDefinition")       $numLearnByDefinition=$result[$i]["value"]; 
        	if ($result[$i]["type"]=="numLearnByExample")          $numLearnByExample=$result[$i]["value"];
            if ($result[$i]["type"]=="cfg_dictationType")          
            {
               $cfg_dictationType=$result[$i]["value"]; //$cfg_dictationType="c,f,d";
               $array = explode(",", $cfg_dictationType);
               array_walk($array, function(&$item) { $item="'".$item."'"; }); 
               $cfg_dictationType=implode(",",$array);
               $cfg_dictationType=strtolower($cfg_dictationType);
            }
            if ($result[$i]["type"]=="cfg_cefr_dictationType")          
            {
               $cfg_cefr_dictationType=$result[$i]["value"]; //$cfg_dictationType="c,f,d";
               $array = explode(",", $cfg_cefr_dictationType);
               array_walk($array, function(&$item) { $item="'".$item."'"; }); 
               $cfg_cefr_dictationType=implode(",",$array);
               $cfg_cefr_dictationType=strtolower($cfg_cefr_dictationType);
            }
            if ($result[$i]["type"]=="showDictationTopPercentage")  $showDictationTopPercentage=$result[$i]["value"];
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