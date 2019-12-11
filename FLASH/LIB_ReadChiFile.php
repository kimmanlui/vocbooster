<?php




function read_chi_file($fname, $arrayvalues)
{
  $contents = file_get_contents($fname);
  $encoding = mb_detect_encoding($contents, array('GB2312','GBK','UTF-16','UCS-2','UTF-8','BIG5','ASCII'));
  $fp=fopen($fname,"r");//以只读的方式打开文件
  $text = "";
  $num = 0;

if(!(feof($fp))) {
    $num++;
    $str = trim(fgets($fp));
    if ($encoding != false) {
                  $str = iconv($encoding, 'UTF-8', $str);
                  if ($str != "" and $str != NULL) {
                      $text = $str;
                      $tokens = preg_split('/\s*,\s*/', $str);
                      array_push( $arrayvalues, $tokens);	
                  }
     }
     else {
                  $str = mb_convert_encoding ( $str, 'UTF-8','Unicode');
                  if ($str != "" and $str != NULL) {
                      $text = $str;
                      $tokens = preg_split('/\s*,\s*/', $str);
                      array_push( $arrayvalues, $tokens);	
                  }
     }
}


while(!(feof($fp))) {
    $str = '';
    $str = trim(fgets($fp));
    if ($encoding != false) {
        $str = iconv($encoding, 'UTF-8', $str);
        if ($str != "" and $str != NULL) {
           //$text = $text.";".$str;
           
           $tokens = preg_split('/\s*,\s*/', $str);
           array_push( $arrayvalues, $tokens);	
          
        }
    }
    else 
    {
        $str = mb_convert_encoding ( $str, 'UTF-8','Unicode');
        if ($str != "" and $str != NULL) {
           // $text = $text.";".$str;
            
           $tokens = preg_split('/\s*,\s*/', $str);
           array_push( $arrayvalues, $tokens);	
        }
    }
}
return($arrayvalues);
}

//$fname="tmp/sample.txt";
//$values=array(); 
//$values=read_chi_file($fname,$values );
//print_r($values); 

?>
