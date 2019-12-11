<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
 $title="CEFR/IELTS Report (Activity)"; 
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
require("inc/dbinfo.inc");

if(isset($_REQUEST['type'])) {
 $temparr=array();
 if (isset($_REQUEST['flash'])) array_push($temparr,'flash'); 
 if (isset($_REQUEST['q'])) array_push($temparr,'q'); 
 if (isset($_REQUEST['d'])) array_push($temparr,'d'); 
 if (isset($_REQUEST['v'])) array_push($temparr,'v'); 
 if (isset($_REQUEST['f'])) array_push($temparr,'f'); 
 if (isset($_REQUEST['c'])) array_push($temparr,'c'); 
 if (isset($_REQUEST['e'])) array_push($temparr,'e'); 
 array_walk($temparr, function(&$item) { $item="'".$item."'"; }); 
 $temparr=implode(",",$temparr);
 $cfg_cefr_dictationType_user=strtolower($temparr);	
 
 $value_update=str_replace("'", "", $cfg_cefr_dictationType_user); 
 try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("select value from setup_user  where type='cfg_cefr_dictationType_user' and userid=$_SESSION[userid]");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetchAll();
        if (sizeof($result)==1)
        {
          $stmt = $conn->prepare("update  setup_user  set value='$value_update' 
                                 where type='cfg_cefr_dictationType_user' and userid=$_SESSION[userid]
                                 ");
        } else
        {
          $stmt = $conn->prepare("insert into setup_user (type,value,userid) values 
                                 ('cfg_cefr_dictationType_user','$value_update','$_SESSION[userid]')
                                 ");
        }
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    }catch(PDOException $e){
    
        echo "Error: " . $e->getMessage();
    }
     $conn = null;
} else
{
  require("config_user.php");
}

$cfg_temp_value=$cfg_cefr_dictationType_user;
if (strpos($cfg_temp_value, 'flash') !== false) $checked_flash="checked";
$cfg_temp_value=str_replace('flash','', $cfg_temp_value);
if (strpos($cfg_temp_value,'q') !== false) $checked_q="checked";
if (strpos($cfg_temp_value,'d') !== false) $checked_d="checked";
if (strpos($cfg_temp_value,'v') !== false) $checked_v="checked";
if (strpos($cfg_temp_value,'f') !== false) $checked_f="checked";
if (strpos($cfg_temp_value,'c') !== false) $checked_c="checked";
if (strpos($cfg_temp_value,'e') !== false) $checked_e="checked";


 //   require("inc/getdeckname.inc");
    echo "<div id=title>$title</div>";
    
    echo " <font color='blue'>";
    echo "<form action=''>";
    echo " <input type='checkbox' name='flash' value='flash' $checked_flash><font color='red'>Flash</font>card";
    echo " <input type='checkbox' name='q' value='q' $checked_q><font color='red'>Q</font>uiz<br>";
    echo " <input type='checkbox' name='d' value='d' $checked_d><font color='red'>D</font>ic. Text";
    echo " <input type='checkbox' name='v' value='v' $checked_v><font color='red'>V</font>oice";
    echo " <input type='checkbox' name='f' value='f' $checked_f><font color='red'>F</font>ill-in-blank<br>";
    echo " <input type='checkbox' name='c' value='c' $checked_c><font color='red'>C</font>AMB Definition";
    echo " <input type='checkbox' name='e' value='e' $checked_e><font color='red'>E</font>xample<br>";
    echo " <input type='hidden' name='type' value='1'>";
    echo "<input type='submit' value='Submit'>";
    echo "</form>"; 
    echo "</font><br>";
    
    
    
    echo "Activity: ".str_replace("'","",$cfg_cefr_dictationType_user)."<br>";
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
          	      $retV = "<td><a href='g_IELTS_general.php?ID=$this->curID'>" . parent::current(). "</a></td>";
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
            where qv.type in ($cfg_cefr_dictationType_user) and a=front   and userid=users.id and conv.cefr=flashcards_cefr_v.cefr
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


