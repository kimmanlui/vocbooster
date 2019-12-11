<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
	     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
	
<?php
 $maincssv=100;
  $title="Number of Activity By Week"; 
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

#graphBS2 {
  margin-right: 5%;
  margin-left: 5%;
}
</style>
</head>
<body>
	
<?php
require("header.php");

$role=$_SESSION['role'];
$id=$_SESSION['userid'];
	 echo "<div id=title>$title</div>";
echo "<center>";

    include_once "LIB.php";
    // $sql="select   date_format(created_d,'%Y%m') date,  sum(status ='Open') as Open_Case, sum(status ='Pending')  as Pending_Case , sum(status ='Closed')  as Closed_Case from acemis_remarks   group by date desc
    // " ;
    if ($role!='s')
    {
      $sql="select week(Date) week, count(*) num_of_student_activity from v_activity_with_id group by week ";
      $cakesql="select typename(type) type, count(*) cnt from v_activity_with_ID group by type ";
    } else
    {
      $sql="select week(Date) week, count(*) num_of_student_activity from v_activity_with_id where id='$id' group by week ";
      $cakesql="select typename(type) type, count(*) cnt from v_activity_with_ID where id='$id' group by type ";
    }
    echo " <div style='display:none'>";
    echo sql_to_html($sql , "AS" ,"data-graph-container='#graphBS'  data-graph-height='300' data-graph-margin-top='40' data-graph-datalabels-enabled='1'  data-graph-type='column'");
     //include "../AH/sql_to_table.php"
     echo "</div>";
     echo "<br>";
     echo " <div style='display:none'>";
     echo sql_to_html_pie($cakesql , "BS" ,"data-graph-container='#graphBS2'  data-graph-height='300' data-graph-margin-top='40'  data-graph-datalabels-enabled='1'  data-graph-type='pie'", 1);
     echo "</div>";
     
     
     
  ?>
   <br>
    <div id="graphBS"></div>
    <br>
    <div id="graphBS2"></div>
  <br>
  <br>
  <?php 
  echo "</center>";
  ?>
</div>
 
<?php
require("footer.php");
?>
<script>
 $(document).ready(function() { $('table.AS').highchartTable(); });
 $(document).ready(function() { $('table.BS').highchartTable(); });
</script>

</body>


