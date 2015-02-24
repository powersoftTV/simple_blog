<?
function mythumbnails($x=100,$y=100,$path,$picture,$destin, $prefix){
	$im = @imagecreatefromjpeg($path.$picture);
	$ext="jpg";
	if ($im === false) {
		$im = @imagecreatefromgif($path.$picture);
		$ext="gif";
		if ($im === false) {
			$im = @imagecreatefrompng($path.$picture);
			$ext="png";
			}
		else {
			$ext=false;
			return false;
			}
		}
	if ($im){
	$size=array();
	$size=@getimagesize($path.$picture);
	$srcx= $size[0];
	$srcy= $size[1];
	if($srcx>$srcy){ 
		$dstx= $x;
		$dsty= $srcy/$srcx*$x;
		}
	else {
		$dsty= $y;
		$dstx= $srcx/$srcy*$y;
		}
	$dest = imagecreatetruecolor($dstx,$dsty);
	
	imagecopyresampled($dest, $im, 0, 0, 0 ,0, $dstx, $dsty, $srcx, $srcy);
	$verch=imagejpeg($dest,$destin.$prefix.$picture);
	if($verch) return $destin.$prefix.$picture;
	else return false;}
}
function mypagesnew($num,$page,$pages, $dest){?>
<table align="center">
<tr> <?
	echo "Страница ".$page." из "." ".$pages." ";
		 if($page==1){?>
         	 <td>
<ul id="nav">	
<li ><a><<<</a></li>
</ul>
</td>
         <? }
		 else {
			 ?>
             <td>
<ul id="nav">	
<li ><a href="<?=$dest?>p=<?=$page-1?>"><<<</a></li>
</ul>
</td>
         	
         <? }
		
		 for($i=1;$i<$pages+1;$i++){
			 
			 if($pages<12){
				 if($i==$page){ ?>
                 	<td>
					<ul id="nav">	
					 <a style="background-color:#FFF"><h2><?=$i ?></h2></a>
                     </ul>
					</td>
                 <? ; }
				 else {?>
                 <td>
<ul id="nav">	
<li ><a href="<?=$dest?>p=<?=$i?>"><?=$i ?></a></li>
</ul>
</td>
				 	
				 <? }
			  }
			  else {				  
				  if($page<7){
					if($i==$page){ ?>
                 	<td>
					<ul id="nav">	
					 <a style="background-color:#FFF"><h2><?=$i ?></h2></a>
                     </ul>
					</td>
                 <? ; }
					if(($i==1 && $i!=$page) || ($i==$pages && $page!=$pages) || ($i<10 && $i!=$page)){?>
                    <td>
<ul id="nav">	
<li ><a href="<?=$dest?>p=<?=$i?>"><?=$i ?></a></li>
</ul>
</td>
				 	 	<?	}
					if(($i==$page+4 && ($page+4 < $pages) && $page>5) || ($pages>10 && $i==10)){ ?>
                 	<td>
					<ul id="nav">	
					 <a>...</a>
                     </ul>
					</td>
                 <? ; }				 					 	
				 	}
				  elseif($page>$pages-6){
					 if($i==$page){ ?>
                 	<td>
					<ul id="nav">	
					 <a style="background-color:#FFF"><h2><?=$i ?></h2></a>
                     </ul>
					</td>
                 <? ; }
					if(($i==1 && $i!=$page) || ($i==$pages && $page!=$pages) || ($i>$pages-9 && $i!=$page)){?>
                       <td>
<ul id="nav">	
<li ><a href="<?=$dest?>p=<?=$i?>"><?=$i ?></a></li>
</ul>
</td>
				 	 	<?	}
					if(($i==$page-4 && ($page-4 < $pages) && $page<$pages-9) || ($pages>10 && $i==1)){ ?>
                 	<td>
					<ul id="nav">	
					 <a>...</a>
                     </ul>
					</td>
                 <? ; }				 	
				 	}	
				  else {
					if($i==$page){ ?>
                 	<td>
					<ul id="nav">	
					 <a style="background-color:#FFF"><h2><?=$i ?></h2></a>
                     </ul>
					</td>
                 <? ; }		
					if(($i==1 && $i!=$page) || ($i==$pages && $page!=$pages) || ($i>$page-4 && $i<$page+4 && $i!=$page)){?>
                     <td>
<ul id="nav">	
<li ><a href="<?=$dest?>p=<?=$i?>"><?=$i ?></a></li>
</ul>
</td>
				 		 	<? }				 
					if(($i==$page+3 && ($page+4 < $pages)) || ($i==$page-4) && ($page-4 > 1)){ ?>
                 	<td>
					<ul id="nav">	
					 <a>...</a>
                     </ul>
					</td>
                 <? ; }				 	
				 	  }
			  	}
		 }
		 if($page==$pages ||$page>$pages-1){?>
           <td>
<ul id="nav">	
<li ><a > >>></a></li>
</ul>
</td>
         <? ;}
		 else { 
		  ?>
          <td>
<ul id="nav">	
<li ><a href="<?=$dest?>p=<?=$page+1 ?>"> >>></a></li>
</ul>
</td>
        
         <? } ?>
         </tr>
</table> <?
}?>


<?

?>