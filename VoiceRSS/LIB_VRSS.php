<?php

//http://www.voicerss.org/sdk/php.aspx



require_once('voicerss_tts.php');

function genMP3($word) {
  $tts = new VoiceRSS;
  $file=str_replace(array("'"), "", $word).".mp3";
  $api="dcd9887aaab54fda85aa19f11f1bd89d";
  $voice = $tts->speech([
    'key' => $api,
    'hl' => 'en-us',
    'src' => $word,
    'r' => '0',
    'c' => 'mp3',
    'f' => '44khz_16bit_stereo',
    'ssml' => 'false',
    'b64' => 'false'
  ]);
  $dir="../VoiceRSS/audio/";
  file_put_contents($dir.$file, $voice, FILE_APPEND | LOCK_EX);
  return ($dir.$file); 
}

function genMP3B64($word, $dir="../VoiceRSS/audiob64/") {
  $tts = new VoiceRSS;
  $file=str_replace(array("'"), "", $word).".txt";
  $api="dcd9887aaab54fda85aa19f11f1bd89d";
  $voice = $tts->speech([
    'key' => $api,
    'hl' => 'en-us',
    'src' => $word,
    'r' => '0',
    'c' => 'mp3',
    'f' => '44khz_16bit_stereo',
    'ssml' => 'false',
    'b64' => 'true'
  ]);
  
  if (strpos($voice, 'data:audio/mpeg;base64') !== false) {
      file_put_contents($dir.$file, $voice, LOCK_EX);
      return ($dir.$file); 
  }
  return ('ERROR'); 
}

//$ptr = fopen("audio/".$file, 'a');
//fwrite($ptr, $voice);
//fclose($ptr);
//genMP3B64("university");
?>

