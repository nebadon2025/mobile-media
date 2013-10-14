<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- Mobile Media v5.0 by Michael Emory Cerquoni 2013 http://www.nebadon2025.com -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<link rel="shortcut icon" href="apple-touch-icon.png" />
<link rel="apple-touch-icon" href="apple-touch-icon.png" />
<title>Mobile Media</title>
<link rel="stylesheet" type="text/css" href="css/mobile.css">
<script src="js/jquery-1.4.4.js" type="text/javascript"></script>
<script src="js/animated-menu.js" type="text/javascript"></script>
</head>
<body>

<ol>
<?php
	$wildcard = $_GET["fetch"];
	$sort = $_GET["sort"];
	$offset = $_GET["offset"];
		if ($offset == null){
			$offset = "0";
		}
	$imgdir = "img/"; //relative path to cover/poster images
	$glob1 = "$imgdir" . "$wildcard" . "*";
	$movdir = "mov/"; //relative path to movie files
	$datadir = "/srv/www/htdocs/data/"; //literal path to flat file data storage
	$images = glob($glob1);
	$moviecount = count($images);
	if($wildcard == null){
		if(stripos($sort, "1") !== false){
			usort($images, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));
		}
		$idIterator = new ArrayIterator($images);
		$count = "96";
		$pages = ceil($filecount / $count);
		$limitIterator = new LimitIterator($idIterator, $offset, $count);	
	}
	if($wildcard != null){
		$limitIterator = $images;
	}
	$ignore = Array(".", "..");
	require_once('getid3/getid3.php');
	$getID3 = new getID3;
	$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$path2 = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
	
	foreach($limitIterator as $curimg){
        if(!in_array($curimg, $ignore)) {
	    $basenam = basename($curimg);
	    $movname = explode(".", $basenam);
	    $name2 = ($movname[0].".mp4");
	    $datatxt = ($movname[0].".php");
	    if (!file_exists($datadir.$datatxt)) {
		$file = $getID3->analyze($movdir.$name2);														
		$datacontent = "Length: ".$file['playtime_string']." | Resolution: ".(int)$file['video']['resolution_x']."x".(int)$file['video']['resolution_y'];	
		file_put_contents($datadir.$movname[0].".php", $datacontent);
	       }
	    echo "<div class='tile'>
	    <a href='".$movdir.$name2."'><img src='".$imgdir.$basenam."'/></a></br></br>
	    <center>$movname[0]</br></br></br>" .file_get_contents($datadir.$datatxt, true). "<hr></center>
	    </div>";
	 }
	}                 
?>
</ol>

<div class="paginate"> 
<?php
if($wildcard == null){
if($offset == "0"){
	echo "<a href='http://".$path2."?&offset=100'><img class='menuimg' src='buttons/next.jpg'></a>";
	}
if($offset > "0" AND $offset < floor($moviecount/100)*100){
	echo "<a href='http://".$path2."?&offset=".($offset-100)."'><img class='menuimg' src='buttons/prev.jpg'></a>   ";
	echo "<a href='http://".$path2."?&offset=".($offset+100)."'><img class='menuimg' src='buttons/next.jpg'></a>";
	}	
if($offset == floor($moviecount/100)*100){
	echo "<a href='http://".$path2."?&offset=".($offset-100)."'><img class='menuimg' src='buttons/prev.jpg'></a><br/><br/>";
	}
}
?>
</div>

<div class="count">
  <?php echo $moviecount; ?> Movies
</div>

<div class="menu">
<ul class="navigation">
<li class="toggle">Menu</li>
<li class="content"><center>
<a href="index.php">
<img class="menuimg" src="buttons/ALL.jpg"></a
  ><?php $arrList = array_merge(range('0','9'), range('A','Z')); 	
  foreach ($arrList as $value) { 
    echo '<a href="?fetch=' . $value . '"><img class="menuimg" src="buttons/' . 	$value . '.jpg"></a
    >';
  } ?><br><br>
 <a href="http://<?php echo $path; ?>?&sort=1"><img class="menuimg" src="buttons/sortnew.jpg"></a>
 </center>
 &nbsp;<br>&nbsp;<br>
</li>
</ul>
</div>

</body>