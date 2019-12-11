<?php require("inc/cookiecheck.inc"); ?>
<!DOCTYPE html>
<html>
     <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<head>
<?php
 $maincssv=100;
 ?>  
    <title> Welcome to Flashcards!</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/main.css?v=<?php echo $maincssv;?>"/>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/common.js"></script>
</head>

<body>
    <?php require("header.php"); ?>
    
    <?php 
      $username=$_SESSION['name']; 
      echo " <p style='margin-left:1em; margin-right:1em' align='left'>Hello,&nbsp;<font color='red'>".$username."</font></p>"; 
    ?>
    <br>

<?php
   echo "<form   action='' method='post' name=''>";
   //echo "<div class='container'>";
   echo $_SESSION['passwordMessage'];
   echo "<label><b>Old Password</b></label> <br> ";
   echo "<input type='password' placeholder='Enter the old password' name='oldpassword' required><br><br>";
   echo "<label><b>New Password</b></label> <br> ";
   echo "<input type='password' placeholder='Enter a new password' name='newpassword1' required><br><br>";
   echo "<label><b>Password Confirmation</b></label> <br> ";
   echo "<input type='password' placeholder='Enter a new password again' name='newpassword2' required><br>";
   echo "<button type='submit' value='submit' name='submit' >Submit</button>";
   //echo "<input type='checkbox' checked='checked'> Remember me";
   //echo "</div>";
   echo "</form>";
   
   
if (isset($_POST['submit']))
{ 
    #$loginID=$_POST['loginID'];
    if (strcmp($_POST['newpassword1'],$_POST['newpassword2'])!=0) 
    {
    	header("location: chgpwd.php"); 
    	$_SESSION['passwordMessage']="<font color='blue'>The password confirmation doesn't match the new password.</font><br><br>";
  		die; 
    }
    if (strcmp($_SESSION['password'],$_POST['oldpassword'])!=0) 
    {
    	header("location: chgpwd.php"); 
    	$_SESSION['passwordMessage']="<font color='blue'>Doesn't match the existing password.</font><br><br>";
  		die; 
    }
    $_SESSION['passwordMessage']="";
    $_SESSION['oldpassword']=$_POST['oldpassword'];
    $_SESSION['newpassword']=$_POST['newpassword1'];
    echo "<p>processing ...</p>";
    echo "<script language='javascript' type='text/javascript'> location.href='chgpwdData.php' </script>";
    
}   
?>

<br>
    <button onclick="goBack()">Go Back</button>  <br><br> <br>
  


   
   <!-- 	 
        <li><a href="addcards_form.php">Add cards</a></li>
        <br>
        <li><a href="deckoptions.php">Your decks</a></li>
        <br>
        <li><a href="usecards.php">Use cards</a></li>
     -->
  </p> 
    <script>
function goBack() {
  window.history.back();
}
</script>
    <?php require("footer.php"); ?>
</body>