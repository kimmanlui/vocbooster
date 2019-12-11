<?php

foreach($_GET as $k=>$v){
    echo $k."=>".$v."<br>";
}

?>
<form action="<?php echo basename(__FILE__);?>" method="get">
<?php
foreach($_GET as $k=>$v){
    echo '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
}

?>
<input type="submit"/>

</form>
