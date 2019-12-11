<?php
error_reporting(E_ERROR | E_PARSE);
if( 
    isset($_POST['username']) and 
    isset($_POST['password']) and 
    isset($_POST['name']) 
){
    require("inc/dbinfo.inc");
    
    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test for existing user
        $stmt_finduser = $conn->prepare("SELECT id FROM users WHERE username=?");
        $stmt_finduser->execute(array($_POST['username']));
        if($stmt_finduser->rowCount() >= 1){
            $code = "userexists";
            $urlsuffix = "?".join("&",array(
                "msg=".$code,
                "username=".$_POST['username'],
                "name=".$_POST['name']
            ));
            header("Location: ".basename(__FILE__).$urlsuffix);
            exit;
        }
        
        // Create new user
        $stmt_makeuser = $conn->prepare("
            INSERT INTO users(username, password, name, comment)
            VALUES( ?, ?, ?,? )
        ");
        $stmt_makeuser->execute(array(
            $_POST['username'],
            $_POST['password'],
            $_POST['name'],
            $_POST['comment']
        ));
       // $stmt_makeuser->execute(array(
       //     $_POST['username'],
       //     hash("sha256", $_POST['password']),
       //     $_POST['name']
       // ));
        
        
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
        exit;
    }
    
    header("Location: login.php?msg=newuser");
    exit;
    
}else{
    
?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>

    <title>Log-in</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/newuser.js"></script>
    

</head>
<body>        
<?php require("header.php"); 

if(isset($_GET['msg'])){
    switch($_GET['msg']){
        case "userexists":
            ?><span class="form-msg">Username already exists!</span><br><?php
            break;
    }
}
?>

<div id="title">Your info:</div>
<form method="post" id="newuser-form" action="<?php echo basename(__FILE__); ?>">
    Username:           <input type="text"      id="username"       name="username" 
        <?php echo isset($_GET['username']) ? 'value='.$_GET['username'] : ""; ?> /><br>
    Password:           <input type="password"  id="password"       name="password"  autocomplete="off" /><br>
    Re-enter Password:  <input type="password"  id="password-clone" name="password"  autocomplete="off" /><br>
    Name:               <input type="text"      id="name"           name="name" autocomplete="off"     
        <?php echo isset($_GET['name']) ? 'value='.$_GET['name']: ""; ?> /><br>
    Comment:           <input type="text"         name="comment"   /><br>    
                        <input type="submit" value="Submit"/>
</form> 

<?php require("footer.php"); ?>      
    
</body>

</html>
<?php 
}

?>