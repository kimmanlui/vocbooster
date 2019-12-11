<?php

$vocab="home";

function getDef($vocab) {

  $dictionary="https://dictionary.cambridge.org/zht/%E8%A9%9E%E5%85%B8/%E8%8B%B1%E8%AA%9E-%E6%BC%A2%E8%AA%9E-%E7%B0%A1%E9%AB%94/";
  $array = file($dictionary.$vocab);

  foreach($array as $line){
    $html=$html.$line;
  }

  $dom = new DOMDocument();
  @$dom->loadHtml($html);

  $xpath = new DOMXPath($dom);

  $allClassDEF = $xpath->query("//*[@class='def']");

  $defs = array();
  foreach ($allClassDEF as $node) {
        $defone = array("def" => $node->nodeValue);
        $defs[] = $defone;
  }
  return ($defs);
}

function getGen($vocab, $class=array("def","def"), $dictionaryType="Chinese") {
  $html="";
  if (strcmp($dictionaryType,"Chinese")==0)
  {
    $dictionary="https://dictionary.cambridge.org/zht/%E8%A9%9E%E5%85%B8/%E8%8B%B1%E8%AA%9E-%E6%BC%A2%E8%AA%9E-%E7%B0%A1%E9%AB%94/";
  } else if  (strcmp($dictionaryType,"English")==0)
  {
  	$dictionary="https://dictionary.cambridge.org/dictionary/english/";
  } else
  {
  	echo "Wrong dictionary type";
  	die; 
  }
  $array = file($dictionary.$vocab);

  foreach($array as $line){
    $html=$html.$line;
  }

  $dom = new DOMDocument();
  @$dom->loadHtml($html);

$retVarray= array(); 
  $xpath = new DOMXPath($dom);

//  echo "//*[@class='def']"; 
//echo "//*[@class='$class']"; 
//echo "<br>";
   
  for ($i=0; $i < sizeof($class) ; $i++)
  {
  	 $tempclass= $class[$i];
  	 $allClassDEF = $xpath->query("//*[@class='$tempclass']");
  	 
  	 $defs = array();
     foreach ($allClassDEF as $node) {
        $defone = array($tempclass => $node->nodeValue);
        $defs[] = $defone;
     }
     array_push($retVarray, $defs); 
  }
  return ($retVarray);
}

function getcefr($txt)
{
  $retV="N";
  if (strpos( $txt, 'A1' ) !== false) $retV='A1';
  if (strpos( $txt, 'A2' ) !== false) $retV='A2';
  if (strpos( $txt, 'B1' ) !== false) $retV='B1';
  if (strpos( $txt, 'B2' ) !== false) $retV='B2';
  if (strpos( $txt, 'C1' ) !== false) $retV='C1';
  if (strpos( $txt, 'C2' ) !== false) $retV='C2';
  return($retV);
}
?>
