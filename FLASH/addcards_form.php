<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>
<?php
 $maincssv=101;
 ?>  
    <title> Welcome to Flashcards!</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
     <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
    <script src="js/addcards_form.js"></script>
</head>
<body>

    <?php require("header.php"); ?>
    
    <div id="add-form-div">
        Add flashcards:<br>
        <form id="add-card-form" method="post" action="addcards.php" enctype="multipart/form-data">
            <input type="radio" name="input-method" id="radio-upload" value="upload"/>
            <span>File Upload</span>, 
            <input type="radio" name="input-method" id="radio-manual" value="manual" checked="checked"/>
            <span>Manual entry</span><br>
            
            <div id="file-upload">
                Filename: <input type="file" name="input-file"/><br/>
            </div>
            

            <textarea id="card-input" name="card-input" cols="40" rows="10">
card 1 front, card 1 back
card 2 front, card 2 back
card 3 front, card 3 back
( and so on... )</textarea>
            
            <?php $newDeckOption = true; require("inc/deckselect.inc"); ?>
            <div id="new-deck-title">
                New deck title: <input type="text" name="new-deck-title-input" />
            </div>
            <center><input type="submit" /></center>
        </form>
        <br>
        To upload a deck, you may follow the format of <a href="img/sample.txt" download>sample</a><br><br>
        You may set the backside as NA (e.g. computer, NA). The system will do the Chinese translation <a href="img/samplena.txt" download>sample</a>.
    </div>



    <?php require("footer.php"); ?>

</body>

</html>