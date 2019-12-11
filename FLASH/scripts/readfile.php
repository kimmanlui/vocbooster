<?php

//nl2br(var_dump($_FILES));


if(count($_POST) == 0){
    ?>
    <form action="<?php basename(__FILE__) ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
        <input type="file" name="userfile" />
        <input type="submit"/>
    
    </form>
    <?php
}else{

    //echo "ini_set=".ini_set("upload_tmp_dir", "/uploads")."\n";
    echo "upload_tmp_dir=".ini_get("upload_tmp_dir")."<br>\n";
    
    //nl2br(var_dump($_FILES));
    $uploadDir = '/uploads';
    try {
        
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($_FILES['userfile']['error']) ||
            is_array($_FILES['userfile']['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }
    
        // Check $_FILES['userfile']['error'] value.
        switch ($_FILES['userfile']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
                break;
            default:
                throw new RuntimeException('Unknown errors.');
        }
    
        // Should also check filesize here. 
        if ($_FILES['userfile']['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }
    
        // DO NOT TRUST $_FILES['userfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['userfile']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'txt' => 'text/plain',
            ),
            true
        )) {
            //throw new RuntimeException('Invalid file format.');
        }
    
        // You should name it uniquely.
        // DO NOT USE $_FILES['userfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $new_filename = sprintf($uploadDir.'/%s.%s',
                sha1_file($_FILES['userfile']['tmp_name']),
                $ext );
        if (!move_uploaded_file(
            $_FILES['userfile']['tmp_name'],
            $new_filename
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    
        echo 'File is uploaded successfully<br>';
        echo 'saved as '.$new_filename;
    
    } catch (RuntimeException $e) {
    
        echo $e->getMessage();
        exit;
    }

}
?>