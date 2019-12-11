
<!DOCTYPE html>
<html>
		     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<body>

<?php

function my_get_contents($url)
{
  $html=""; 	
  $array = file($url);
  foreach($array as $line){
    $html=$html.$line;
  }
  return($html);
}


$url="audiob64/university.txt";
$audiob64=my_get_contents($url);
?>


<audio id="myAudio">
  <source src="<?php echo $audiob64; ?>" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>


<p>Click the buttons to play or pause the audio.</p>
<button onclick="playAudio('myAudio')" type="button">Play</button>
<script>


function playAudio(sound) { 
  var x = document.getElementById(sound); 
  x.play(); 
} 

function pauseAudio() { 
  x.pause(); 
} 
</script>

</body>
</html>


