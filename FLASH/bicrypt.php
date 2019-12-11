<?php
// String EnCrypt + DeCrypt function
// Author: halojoy, July 2006 https://board.phpbuilder.com/d/10326721-simple-string-encrypt-decrypt-function
function bicrypt($str,$ky='mysecretkey'){
if($ky=='') return $str;
$ky=str_replace(chr(32),'',$ky);
if(strlen($ky)<8)exit('key error');
$kl=strlen($ky)<32?strlen($ky):32;
$k=array();for($i=0;$i<$kl;$i++){
$k[$i]=ord($ky{$i})&0x1F;}
$j=0;for($i=0;$i<strlen($str);$i++){
$e=ord($str{$i});
$str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);
$j++;$j=$j==$kl?0:$j;}
return $str;
}

function enbicrypt($str,$ky='mysecretkey') 
{
	return(rawurlencode(bicrypt($str,$ky)));
}

function debicrypt($str,$ky='mysecretkey') 
{
	return((bicrypt(rawurldecode($str),$ky)));
}

//echo enbicrypt('select * from ');
//echo '<br>';
//echo debicrypt('12345');


//echo '<br>';
//echo debicrypt(enbicrypt('kimman'));
?>