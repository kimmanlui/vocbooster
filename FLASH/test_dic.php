<?php
 
include ("LIB_DICT.php"); 



$item="university"; 
$item='Microeconomics';

   
$tokenA='def';
$tokenB='def-info';
$tokenC='inf';
$tokenD='eg';
$tokenE='trans';

$tokenArr=array($tokenA , $tokenB, $tokenC,$tokenD, $tokenE);
$dictionaryType="Chinese";   
$defarr=getGen($item, $tokenArr, $dictionaryType);
print_r($defarr);

   
echo "<font color='red'>done</font>";

