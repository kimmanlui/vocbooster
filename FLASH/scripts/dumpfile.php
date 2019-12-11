<?php

echo "HELLO WORLD!<br>";

$myfile = fopen("flashcardinput.txt", "r") or die("Unable to open file!");

while( !feof( $myfile) ){
    echo fgets($myfile)."<br>\n";
    
}

fclose($myfile);


?>