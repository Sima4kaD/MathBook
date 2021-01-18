<?
$confirm ="";  

if($_FILES['phar']['error'] === 0  ) {
	if (move_uploaded_file($_FILES['phar']['tmp_name'], "./".$_FILES['phar']['name'])) {	
		 
		$files = scandir("./doc");
		foreach($files as $k=>$v)
			if($v != "." && $v != "..") unlink("./doc/$v");
		
		rmdir("./doc");
		$phar = new Phar('content.phar');
		$phar->extractTo("./doc");
		unlink("./math.sqlite");
		rename("./doc/math.sqlite","./math.sqlite");
		$confirm = "Успех!";
	}
	else  {
		$confirm = "Грешка!";
		return false; 
	}
		
	}
	

?><!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>MathBook Student</title>
  
    <link rel="stylesheet" href="css/normalize.css">
	
  <link rel='stylesheet prefetch' href='css/font-awesome.css'>

      <link rel="stylesheet" href="css/style.css">

<link rel="shortcut icon" href="imgs/favicon.png" type="image/png">  
<style> 
 
</style>
</head>
<body><? echo (!empty($confirm)) ? "<script>alert(\"$confirm\"); location.replace('./')</script>":""; ?>
  <div class="nav">
    <a href="#" class="nav__trigger">
        <div class="bars"></div>
    </a> 
    <div class="nav__content" >
        <nav class="nav__list">
            <ul>
                <li class="nav__item"><a href="./">Lections</a></li>
				<li class="nav__item"><a href="./?homework">Homework</a></li>
                <li class="nav__item"><a href="update.php">Update</a></li>
                <!--li class="nav__item"><a href="#">Team</a></li-->
                <li class="nav__item"><a href="about.php">About</a></li>
            </ul>
        </nav>
    </div>    
</div>
<div class="content">
<fieldset><legend>Import update</legend>
	  <form action="update.php" enctype="multipart/form-data" method="POST" >
	  <input type="file" name="phar"  />
	  <input type="submit" value="Import update" /></form></fieldset>
</div>	  
<script src='js/classList.min.js'></script>  
<script  src="js/index.js"></script>	  
	  </body>
	  </html>