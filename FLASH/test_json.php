<?php

$array = array("one" => 1, "two" => 2, "three" => 3);
foreach ( $array as $k => $v ){
    echo "key: $k, value: $v\n";
}
 echo json_encode($array);

?>