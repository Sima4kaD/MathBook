<?

function up_link($file = "update.lst"){
	//$a = @fopen($file, "r");
	//return fread($a,filesize($file));
	$a = @fopen($file, "rb");
if ($a) {
    while (($buffer = fgets($a, 1024)) !== false) {
        echo "?update=".$buffer;
    }
    if (!feof($a)) {
        echo "javascript:alert(\"Грешка: няма връзка със сървър\")";
    }
    fclose($a);
}
}
if(isset($_GET['update'])){
	
	$a = @fopen($file, "rb");
	
	
	
	//$lines = file($_GET['update']);

// Осуществим проход массива и выведем содержимое в виде HTML-кода вместе с номерами строк.
//foreach ($lines as $line_num => $line) {
  //  echo "Строка #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
//}

	
}


?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HELP</title>
<style type="text/css">
body {
	margin:0;
	background-color:#369;
}
hr{
	color:#FFF
}
#content{
	overflow:auto;
	padding:10px;
	font-size:15px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	text-align:justify;
	color:#FFF;
}
#content img{
	text-align:center;
}
td img{
	float:left; padding:20px;padding-left:0;
	width:430px;height:300px;
}
</style>
</head>

<body>
<div id="content" style="width: 730px; !important;">
<table width="100%" border="0" cellpadding="0">
  <tr>
    <td align="center"> <h1>Ръководство на потребителя</h1> </td>
  </tr>
  <tr><td><p>Извлечете от архив проекта в удобна за Вас
папка, влезте в папката и намерете програмата с името  "mathbook.exe".  </p>
<p>Отворете го с двойно кликване по него.
Изчакайте програмата да се зареди. </p>
  </td></tr>
  <tr><td><img id="Picture 3" src="imgs/image001.png"> <p>Програмата се зареди. Наведете курсора на урока, на който искате да отидете. </p>
  </td></tr>
  <tr><td><img id="Picture 4" src="imgs/image002.jpg"><p>При навеждането на бутона, се появява наименованието на урока. </p> 
 

  </td></tr>
  <tr><td><img id="Picture 5"src="imgs/image003.jpg"> <p> За да изберете първата лекция, натиснете бутона <em>Lection 1.</em>  </p> 
	
  </td></tr>
  <tr><td><img id="Picture 6" src="imgs/image004.jpg"> <p>При натискане на бутона, се появява прозорец с първата лекция. </p>
  </td></tr>
  <tr><td><img id="Picture 7" src="imgs/image005.jpg"><p>Горе в ляво се появя бутон за домашно задание. </p>
  </td></tr>
  <tr><td><img id="Picture 8" src="imgs/image006.jpg"> 
<p>За да можете да изберете друга лекция, трябва да се върнете обратно. Бутона за връщане назад се намира горе вдясно. </p>

  </td></tr>
  <tr><td> <img id="Picture 9" src="imgs/image007.jpg"> 

<p>Натиснете го и лекцията(домашното задание) ще
се скрие.  Готово. Отново сте на
начален екан. </p>
  </td></tr>
  <tr><td><img id="Picture 10" src="imgs/image008.png"> 

<p>По същия начин става отварянето на всички
уроци. </p>
  </td></tr> 
  <tr><td> <h2>Update</h2>

<p><A HREF="<?=up_link()?>" target="main">Натиснете този линк, изчакайте.</a>  Готово. </p>
  </td></tr>
  <tr><td>

<pre><? print_r($_GET);?></pre>
  </td></tr>  
</table>

</div>
</body>
</html>
