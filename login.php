<?PHP
require_once("lang.inc");
if($_GET[lang]==ru){
	   $mylang=$lang_ru;
	   $lg='ru';
   }
elseif($_GET[lang]==arm){
	   $mylang=$lang_arm;
	   $lg='arm';
   }
elseif($_GET[lang]==eng){
	   $mylang=$lang_eng;
	   $lg='eng';
   }
else {
	 if($_COOKIE['mypreflang']!=''){
		   	if($_COOKIE['mypreflang']==ru){
				$mylang=$lang_ru;
	   			$lg='ru';
			}
   			elseif($_COOKIE['mypreflang']==arm){
				$mylang=$lang_arm;
	   			$lg='arm';
			}
			else{
				$mylang=$lang_eng;
	   			$lg='eng';
			}
	   }
	   else {
		   		$mylang=$lang_eng;
				$lg='eng';
	   		}
   }
require_once("./include/membersite_config.php");
if(isset($_POST['submitted']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("index.php");
   }
}
if($lg==ru){
	setcookie('mypreflang','ru',time() + (86400 * 7)); // 86400 = 1 day
	$_SESSION['lang']='ru';
	}
elseif($lg==arm){
	setcookie('mypreflang','arm',time() + (86400 * 7)); // 86400 = 1 day
	$_SESSION['lang']='arm';	
	}
else{
	setcookie('mypreflang','eng',time() + (86400 * 7));
	$_SESSION['lang']='eng';	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title><?=$mylang[login]?></title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
<div class="centraldiv">
<?	if($_GET[st]==reg)$st="reg";
	else $st="ent";
	if($lg==ru){?>
		<a href='<?=$_SERVER['PHP_SELF']?>?lang=eng'><img src='png/eng.png'> <?=$mylang[lng_eng]?></a>
		<a href='<?=$_SERVER['PHP_SELF']?>?lang=arm'><img src='png/arm.png'> <?=$mylang[lng_arm]?></a>
	<? }
   	elseif($lg==arm){?>
    	<a href='<?=$_SERVER['PHP_SELF']?>?lang=ru'><img src='png/ru.png'> <?=$mylang[lng_ru]?></a>
		<a href='<?=$_SERVER['PHP_SELF']?>?lang=eng'><img src='png/eng.png'> <?=$mylang[lng_eng]?></a>
	<? }
   	else{?>
    	<a href='<?=$_SERVER['PHP_SELF']?>?lang=ru'><img src='png/ru.png'> <?=$mylang[lng_ru]?></a>
		<a href='<?=$_SERVER['PHP_SELF']?>?lang=arm'><img src='png/arm.png'> <?=$mylang[lng_arm]?></a>
	<? }
?>
<!-- Form Code Start -->

<div id='fg_membersite'>
<form id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend><?=$mylang[login]?></legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class='short_explanation'>* <?=$mylang[req]?></div>

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='username' ><?=$mylang[user]?>*:</label><br/>
    <input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
    <span id='login_username_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='password' ><?=$mylang[pass]?>*:</label><br/>
    <input type='password' name='password' id='password' maxlength="50" /><br/>
    <span id='login_password_errorloc' class='error'></span>
</div>

<div class='container'>
    <input type='submit' name='Submit' value='<?=$mylang[submit]?>' />
</div>
<div class='short_explanation'>
	<a style="float:left" href='reset-pwd-req.php'><?=$mylang[lost_pass]?></a>
    <a style="float:right" href='register.php'><?=$mylang[registr]?></a>
</div>

</fieldset>
</form>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("login");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("username","req","<?=$mylang[error2]?>");
    
    frmvalidator.addValidation("password","req","<?=$mylang[error3]?>");

// ]]>
</script>
</div>
</div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>