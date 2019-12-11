<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
  $title="Vocabulary By IELTS (Flashcard)"; 
  $userid=$_GET['ID'];
 ?>
<head>
    
    <title><?php echo $title; ?></title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
    
    <script src="../highchart/code/highcharts.js"></script>
<script src="../highcharttable/jquery.highchartTable.js" type="text/javascript"></script>

<style>
	th {border: 1px #555 solid; padding: 5px; cursor: pointer;}

	td {border: 1px #555 solid; padding: 5px; cursor: pointer;}
	
	#graphBS {
  margin-right: 5%;
  margin-left: 5%;
}
</style>
</head>
<body>
	
<?php
require("header.php");

	 echo "<div id=title>$title</div>";
echo "<center>";

    include_once "LIB.php";
    // $sql="select   date_format(created_d,'%Y%m') date,  sum(status ='Open') as Open_Case, sum(status ='Pending')  as Pending_Case , sum(status ='Closed')  as Closed_Case from acemis_remarks   group by date desc
    // " ;
    $sql="  
            select  ielts, round(sum( if (choice=1, 1, 0)) /sum(1)*100,0)  achievement_percentage , sum(1) num_of_different_words
            from log_v_student ,  flashcards_cefr_v, users, conv
            where word=front   and userid=users.id and conv.cefr=flashcards_cefr_v.cefr and userid=$userid
            group by  ielts order by conv.cefr
         ";
    echo " <div style='display:none'>";
    echo sql_to_html($sql , "AS" ,"data-graph-container='#graphBS'  data-graph-height='300' data-graph-margin-top='40' 
                    data-graph-datalabels-enabled='1'  data-graph-type='line' ");
    echo "</div>";                 
     //include "../AH/sql_to_table.php"
  ?>
   <br>
    <div id="graphBS">
    </div>
  
  <?php 
  echo "</center>";
  echo "<br><button onclick='goBack()'>Go Back</button>";;
  ?>
</div>
 
<?php
require("footer.php");
?>
<script>
 $(document).ready(function() { $('table.AS').highchartTable(); });
</script>
<script>
function goBack() {
  window.history.back();
}
</script>
</body>


