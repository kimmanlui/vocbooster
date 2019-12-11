<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
 $title="CEFR/IELTS Report (Activity Type)"; 
 ?>
<head>
    
     <title><?php echo $title; ?></title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
</head>
<body>
<?php
require("header.php");
include_once("config.php");


    if(isset($_GET['status'])){
        $msg = '';
        switch($_GET['status']){
            case 'added':
                $msg = "Records successfully added!";
                break;
        }
        echo $msg."<br/>";
    }

 //   require("inc/getdeckname.inc");
    echo "<div id=title>$title</div>";
        echo "Activity Type: ".str_replace("'","",$cfg_cefr_dictationType)."<br>";
   // echo "已会: Numbr of Words Learned<br>";
   // echo "新学: Numbr of Words Newly Learned<br>";
   // echo "在学: Numbr of Words Being Learned<br>";
    echo " <div style='overflow-x:auto;'>";
    echo '<table id="card-table">';
    
    class TableRows extends RecursiveIteratorIterator { 
    	public $last="";
    	public $laststudent="";
    	public $nowstudent="";
    	public $counter=""; 
    	public $colorA="#fff7e6"; 
        public $colorB="#ffe6b3";
    	public $color="";
    	public $curID="";
    

        function __construct($it) { 
            parent::__construct($it, self::LEAVES_ONLY); 
        }
    
        function current() {
        	    $this->counter = $this->counter+1; 
        	    
        	    if ( ($this->counter % 6) ==1 ) 
        	    {
        	    	$this->curID=parent::current(); 
        	    	return ""; 
        	    }
        	    
        	    $retV = "<td>" . parent::current(). "</td>";
        	    
        	    if ( ($this->counter % 6) ==2 ) //<a href="url">link text</a>
        	    {
          	      $retV = "<td><a href='g_IELTS_dictation.php?ID=$this->curID'>" . parent::current(). "</a></td>";
        	    }
        	
        		return $retV; 
       
        }
    
        function beginChildren() { 
        	
        	    //$this->counter=$this->counter +1; 
        	    //if (($this->counter % 5)==1)
        	    //{
        	    	$this->nowstudent=parent::current(); 
        	    	if (!(strcmp($this->nowstudent,$this->laststudent)==0))
        	    	{ 
        	    	  if ($this->color==$this->colorA)
        	    	  {
        	    		$this->color=$this->colorB;
        	    	  } else
        	    	  {
        	    		$this->color=$this->colorA;
        	    	  }
        	    	}
        	    	$this->laststudent=	$this->nowstudent; 
        	    //}
        	    $this->last = parent::current(); 
            echo "<tr bgcolor='$this->color'>"; 
        } 
    
        function endChildren() { 
            echo "</tr>" . "\n";
        } 
    } 
   
    require("inc/dbinfo.inc");
    $userid = $_SESSION['userid']; 

    
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tableCols = array();
       
            $tableCols = array("名字","CEFR","IELTS", "成功率" , "单子数");
            //$sql = "
            //    SELECT decks.name,front,back 
            //    FROM flashcards JOIN decks ON decks.deckid = flashcards.deckid
            //    WHERE userid=?
            //    ORDER BY decks.deckid, cardid ";
  
            $sql = "
            select userid, users.name, flashcards_cefr_v.cefr , ielts, round(sum( if (choice=1, 1, 0)) /sum(1)*100,0)  achieve , sum(1) total  
            from all_log_v_student qv ,  flashcards_cefr_v, users, conv
            where qv.type in ($cfg_cefr_dictationType) and a=front   and userid=users.id and conv.cefr=flashcards_cefr_v.cefr
            group by userid, cefr order by users.name, cefr
            ";       
            $stmt = $conn->prepare($sql); 
            //$stmt->bindValue(1, $userid);
        
        $stmt->execute();
    
        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    
        echo "<tr>";
        foreach($tableCols as $colName){
            echo "<th>".$colName."</th>";
        }
        echo "</tr>";
        
        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
            echo $v;
            
        }
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        exit;
        }
    $conn = null;
    
    echo "</table>";
    echo " </div>";

require("footer.php");
?>

</body>


