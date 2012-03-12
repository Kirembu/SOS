<?php 
// BOLLIX THIS IS A BLOODY TEST :) :-)

if(isset($_POST['username'])||isset($_POST['password'])){
	if (!isset($_SESSION)) { session_start();}
   if($_SESSION['security_code'] == $_POST['CaptchaTxt'] && !empty($_SESSION['security_code'])) {
		//code for processing the form.To clear session 
		unset($_SESSION['security_code']);
   } else {

		//code for showing an error when invalid security code!!!.
		$redirectpage="login.php?attempt=1?#msg";
		header("Location: " . $redirectpage);
		exit;
   }
}
?>
<?php require_once('Connections/system_database_connection.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "user_id";
  $MM_redirectLoginSuccess = "user_logs.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_system_database_connection, $system_database_connection);
  	
  $LoginRS__query=sprintf("SELECT username, password, user_id, department_id, user_level FROM users WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $system_database_connection) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'user_id');
	$loginStrUserName  = mysql_result($LoginRS,0,'username');
	$loginStrDepartment  = mysql_result($LoginRS,0,'department_id');
	$loginStrUserLevel  = mysql_result($LoginRS,0,'user_level');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
	$_SESSION['MM_UserGroup'] = $loginStrGroup;	 
    $_SESSION['MM_Username'] = $loginStrUserName;
    $_SESSION['MM_UserDepartment'] = $loginStrDepartment;	 
	$_SESSION['MM_UserLevel'] = $loginStrUserLevel;     

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
  <title>Store Order System &raquo; Login :: This is a simple, efficient and minimalistic Inventory order system that can be used by small agency to manage their orders and inventory.</title>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
  <link href="layoutdesign.css" rel="stylesheet" type="text/css" />
  <script>
  <!--
		function captcha_img(box)
		{
			// loads new freeCap image
			if(document.getElementById)
			{
				// extract image name from image source (i.e. cut off ?randomness)
				thesrc = document.getElementById(box).src;
				thesrc = thesrc.substring(0,thesrc.lastIndexOf(".")+4);
				// add ?(random) to prevent browser/isp caching
				document.getElementById(box).src = thesrc+"?"+Math.round(Math.random()*100000);
			} else {
				alert("Sorry, cannot load image\nSubmit the form and a new image will be loaded");
			}
		}
		//-->
  </script>
  <?php include('google.php');?>
</head>
 <body>
 <div id="container">
   <div id="header">
     <div>
       <div style=" float:right width:100px; text-align:right;">
         <table width="100%" border="0">
           <tr>
             <td width="79%" height="52">&nbsp;</td>
             <td width="21%">&nbsp;</td>
           </tr>
         </table>
       </div>
     </div>
     <div style="background-image:url(topnav2.gif); background-repeat:repeat-x; border-color:#666; border-style:solid; border-width:1px; height:10px;"></div>
     <div style="border-bottom-color:#333;border-bottom-style:solid;border-bottom-width:1px; background-color:#DDD; height:20px;"></div>
     <!-- end #header -->
   </div>
   
   <div id="mainContent" style="padding-bottom:10px; float: left;">
       <div style="padding-top:50px; padding-left:10px; float: left;">
       
         <div style="float:left; width:300px;">
         <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
         <div style="width:350px; float:left;">
           <fieldset id="mainfieldset" style="width:300px; height:140px;">
             <legend>LOGIN</legend>
                         <table border="0" align="left" cellpadding="0" cellspacing="0">
                           <tr>
                             <td><div align="left" style="padding-bottom:10px; margin:0;">Enter your username and password.</div></td>
                           </tr>
                           <tr>
                             <td><div align="left">Username </div><input name="username" type="text" id="username"  value="a" />
                             <div align="left">Password </div><input name="password" type="password" id="password" value="a" /></td>
                           </tr>
                           <tr>
                             <td height="29">
                               <div style="text-align:left; padding:5px 0 0 0;">
                                 <?php if(isset($_GET['attempt'])&&($_GET['attempt']>0)){?>
                                 <div style="color:#FF0000">The characters you entered didn't match the word verification. Please try again.<a name="msg" id="msg2"></a></div>
                                 <?php }else{?>
                                 Type the characters you see below.
                                 <?php }?>
                                 <div style=" padding:0;"><img title="Enter this characters" src="captcha-security-image.php?width=100&amp;height=35&amp;characters=4" alt="image" name="cap_img" id="cap_img" />
                                   <input name="CaptchaTxt" type="text" id="CaptchaTxt" style="width:100px;height:30px; padding:0; margin:0; vertical-align:top;" maxlength="10" />
                                 <a href="#" onclick="this.blur();captcha_img('cap_img');return false;"><img src="images/Green_Ball.png" alt="refresh image" width="32" height="32" border="0" /></a></div>
                               </div>                             </td>
                           </tr>
                           <tr>
                             <td><div align="left" style="padding:5px 0 0 0;">
                               <input type="submit" name="button" id="button" value="   LOGIN   " />
                             </div></td>
                           </tr>
                         </table>
                         <!-- end #mainContent -->
                       </fieldset>
           </div>
           </form>  
         </div>
     </div>
   </div>
     
   <!-- end #container -->
 </div>
<!-- test -->
</body>
</html>
