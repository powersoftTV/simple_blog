<?PHP
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

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
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
      <title>An Access Controlled Page</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">
      
<link rel="stylesheet"	type="text/css"	 href="bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet"	type="text/css"	media="screen"	href="style/karen.css" />
<link rel="stylesheet"	type="text/css"	media="screen"	href="style/fineuploader.css" />

<link rel="stylesheet" type="text/css" media="screen" 	href="style/css/overcast/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" media="screen"	href="style/css/imgareaselect-default.css" />

<script type="text/javascript"	src="scripts/jquery-1.9.1.min.js"></script>
<script type="text/javascript" 	src="bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript"	src="scripts/pwdwidget.js"></script>

<script type="text/javascript"	src="scripts/jquery.form.js"></script>
<script type="text/javascript"	src="scripts/jquery.fineuploader-3.3.1.js" ></script>
<script type="text/javascript"	src="scripts/i18n/grid.locale-ru.js" ></script>

<script type="text/javascript" 	src="scripts/jquery.imgareaselect.pack.js"></script>

<script type="text/javascript"	src="scripts/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript"	src="scripts/js_karen.js"></script>
<style type="text/css">


table.type10{
	display:block;
	padding:5px;
	color:#fff;
	background:#555;
	text-decoration:none;
	text-shadow:1px 1px 1px rgba(0,0,0,0.75); /* Тень текста, чтобы приподнять его на немного */
	-moz-border-radius:20px;
	-webkit-border-radius:2px;
	border-radius:20px;
}

</style>





</head>
<body>
<div align="center" id='fg_membersite_content'>
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
<h2>Welcome back <?= $fgmembersite->UserFullName(); ?>!</h2>
<a href='change-pwd.php'>Change password</a><br />
<a href='logout.php'>Logout</a><br /><br />

<? 
require_once("include/php_functions.php"); 


if (isset($_SESSION['name_of_user']))
			{				
				$path="uploader/uploads/".$_SESSION['name_of_user'];
					
			 	if(!is_dir($path))   @mkdir($path); 
					
			
			}
			
?>
<form  action="uploader/ajaxcreate.php" id="aptform" method="post" >  
<div >
	<article class="row">	
		<div class="wrapper"> 
			<div  id="filelimit-fine-uploader" style="visibility: visible;"></div>
			<div  id="messages" style="visibility: visible;"></div>
		</div>
		<div  id="edit" style="visibility: visible;"></div>
        <br />
       <select  style="width:200px" >
	<option selected="selected" value="age">Сортировать по дате</option>
	<option value="pl">Сортировать по размеру</option>
	<option value="ph">Сортировать по названиям</option>
</select><br />
		<div  id="111" style="visibility: visible;"></div>	
		
        
 
<script>



  $(document).ready(function() {
	 
    fulltable(); 			  
	
    $fub = $('#fine-uploader');
    $messages = $('#messages');
	
 
     var uploader = new qq.FineUploader({
      element: $('#filelimit-fine-uploader')[0],
      request: {
        endpoint: 'uploader/uploaderserv.php'
      }, 
      validation: {
        allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
        sizeLimit: 5120000, // 500 kB = 500 * 1024 bytes
        //itemLimit: numfile
      },
      callbacks: {
        onSubmit: function(id, fileName) {
          $messages.append('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>');
        },
        onUpload: function(id, fileName) {
          $('#file-' + id).addClass('alert-info')
                          .html('<img src="images/ajax-loader.gif" alt="Initializing. Please hold."> ' +
                                'Initializing ' +
                                '“' + fileName + '”');
        },
        onProgress: function(id, fileName, loaded, total) {
          if (loaded < total) {
            progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
            $('#file-' + id).removeClass('alert-info')
                            .html('<img src="images/ajax-loader.gif" alt="In progress. Please hold."> ' +
                                  'Uploading ' +
                                  '“' + fileName + '” ' +
                                  progress);
          } else {
            $('#file-' + id).addClass('alert-info')
                            .html('<img src="images/ajax-loader.gif" alt="Saving. Please hold."> ' +
                                  'Saving ' +
                                  '“' + fileName + '”');
          }
        },
        onComplete: function(id, fileName, responseJSON) {
          if (responseJSON.success) {
			 		
           $('#file-' + id).remove();
		   fulltable();
		
								
			
					  
          } else {alert (responseJSON);
            $('#file-' + id).removeClass('alert-info') 
                            .addClass('alert-error')
                            .html('<i class="icon-exclamation-sign"></i> ' +
                                  'Error with ' +
                                  '“' + fileName + '”: ' +
                                  responseJSON.error);
          }
        }
      }
    });
  });
</script>  

<input type="hidden" id="hidden" name="hidden" />
    
  
</form>
 
</article>

</div>
</div>
<br /><br />
</body>
</html>
