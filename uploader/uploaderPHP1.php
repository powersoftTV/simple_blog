<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>image uploader</title>

<link rel="stylesheet"	type="text/css"	 href="bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet"	type="text/css"	media="screen"	href="style/karen.css" />
<link rel="stylesheet"	type="text/css" media="screen"	href="style/fg_membersite.css" />
<link rel="stylesheet"	type="text/css" media="screen"	href="style/pwdwidget.css" />
<link rel="stylesheet"	type="text/css"	media="screen"	href="style/fineuploader.css" />

<link rel="stylesheet" type="text/css" media="screen" 	href="css/overcast/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" 	href="css/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen"	href="css/jquery.arcticmodal-0.3.css" />
<link rel="stylesheet" type="text/css" media="screen"	href="style/css/imgareaselect-default.css" />


<script type="text/javascript"	src="scripts/jquery-1.9.1.min.js"></script>
<script type="text/javascript" 	src="bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript"	src="scripts/gen_validatorv4.js" ></script>
<script type="text/javascript"	src="scripts/pwdwidget.js"></script>
<script type="text/javascript"	src="scripts/jquery.form.js"></script>
<script type="text/javascript"	src="scripts/jquery.fineuploader-3.3.1.js" ></script>
<script type="text/javascript"	src="scripts/i18n/grid.locale-ru.js" ></script>
<script type="text/javascript"	src="scripts/jquery.jqGrid.min.js" ></script>
<script type="text/javascript"	src="scripts/jquery.arcticmodal-0.3.min.js" ></script>
<script type="text/javascript" 	src="scripts/jquery.imgareaselect.pack.js"></script>
<script type="text/javascript"	src="ckeditor/ckeditor.js"></script>
<script type="text/javascript"	src="ckeditor/adapters/jquery.js"></script>
<script type="text/javascript"	src="scripts/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript"	src="scripts/js_functions.js"></script>

</head>

<body> 
<?
session_start(); 


require_once("include/php_functions.php"); 
?>
<form action="ajaxcreate.php" id="aptform" method="post" > 

		
		<div class="wrapper"> 
			<div  id="filelimit-fine-uploader" style="visibility: visible;"></div>
			<div  id="messages" style="visibility: visible;"></div>
		</div>
		<div  id="edit" style="visibility: visible;"></div>
		<div  id="111" style="visibility: visible;"></div>	
		<div id="messages" ></div>
 
<script>



  $(document).ready(function() {
	 
    fulltable(); 			  
	
    $fub = $('#fine-uploader');
    $messages = $('#messages');
	
 
     var uploader = new qq.FineUploader({
      element: $('#filelimit-fine-uploader')[0],
      request: {
        endpoint: 'uploaderserv.php'
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
		
								
			
					  
          } else {
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
 



</body>
</html>