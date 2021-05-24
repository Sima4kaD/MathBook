<?
error_reporting(E_ALL);

$confirm ="";  
if(!isset($_GET['edit'])) $_GET['edit'] = 0;
 /* Export content & db */
if (isset($_GET['export'])){
$phar = new Phar('content.phar', FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,'content.phar');

$dir    = './doc';

$phar->buildFromIterator(
    new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    ),
    $dir
	);
	$phar->addFile("math.sqlite"); 	
	//header('Content-Description: File Transfer');
   // header('Content-Type: application/octet-stream');
   // header('Cache-Control: must-revalidate'); 
	// header("Content-Disposition: attachment; filename='content.phar'");
	// $file = "imgs/404.jpg"; 
	 $download_rate = 20.5;

	 file_force_download('content.phar' )	; 
}  // end export

 

function file_force_download($file) {
  if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью
   if (ob_get_level()) {
      ob_end_clean();
    }
	   
    // заставляем браузер показать окно сохранения файла
    header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename='". basename($file) ."'");
	header("Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: binary"); 
   header("Content-Length: ". filesize($file)." ");
    // читаем файл и отправляем его пользователю
	//echo "-54- ";
	
    if ($fd = fopen($file, 'rb')){  
		//echo "-56- ";
      while (!feof($fd)) {
       print fread($fd, 1024);
	  //echo $i++;
	    
      }
      fclose($fd);
	  
    }
	//readfile($file);
	//echo $i;
   exit;
  }
}

/* end of export */

/*  */ 
function rnd_str ($len=5){ // generate syntetic name for compressed content file
	$res ="";
	$str = "1qwert5yuiop2asdf6ghjkl3zxc7vbnm4890";
	for($i=0; $i<$len;$i++){
		$res .= substr($str,rand(0,35),1); 
	}
	return $res;
}

function compress($fn,$name){// compress content file 		
		
		$handle = fopen($fn, "rb"); 
		$a = lzf_compress (fread ($handle, filesize($fn))) ; 
		$b = fopen("doc/".$name , "w");
		if(fwrite($b, $a)) $r = true;
		else $r = false;
		fclose($handle);
		fclose($b);
		return $r;
		
	}

$db = new SQLite3('math.sqlite');

// write new content file to ./doc directory from form
 function upload_file($uf, $name){ // first key in POST array
     
   	if ($_FILES["$uf"]['error'] ===0){
   		 
   		$name  = (!is_file("doc/$name")) ? $name : str_replace(".","_.",$name);
		
   		 if(compress($_FILES[$uf]['tmp_name'],$name))
			return $name;
   		 else return false;
   	}
   	else return false;
   }

/* update db from admin form */	
if(!empty($_POST)){ // 
	 foreach($_POST as $k=>$v) $$k = $v; // register globals ;-)
	 
	//echo "<pre>POST: \n";print_r($_POST);echo"</pre>"; 
	 
	function is_upload ($num){
		if($_FILES['a_fname0']['error'] === 0) {
			return "a_fname";
		} 
		 		 
	 }
	
	if($t_id > 0){ //date format: 2020-06-26 17:42:48
		$db->query("UPDATE `theme`
			SET `t_name` = '$t_name', 
				`t_date` = CURRENT_TIMESTAMP, 
				`t_view` = '1'
				WHERE `t_id` = '$_GET[edit]' "); 
		// lection
		if( $a_id0 > 0){
			$db->query("UPDATE `article` 
			SET `a_date` = CURRENT_TIMESTAMP, 
				`a_view` = '$a_view0', 
				`a_descr` = '$a_descr0' 
				WHERE `a_id` = '$a_id0' "); 
		}
		else {
		$db->query("INSERT INTO `article` 
			(`t_id`,  `a_descr`,  `a_type`, `a_date`, `a_view`) 
			VALUES
			('$_GET[edit]', '$a_descr0', '1', CURRENT_TIMESTAMP, '$a_view0' ) "); 
		
		}
		// homework
		if( $a_id1 > 0){
			$db->query("UPDATE `article`
			SET `a_date` = CURRENT_TIMESTAMP, 
				`a_view` = '$a_view1', 
				`a_descr` = '$a_descr1' 
				WHERE `a_id` = '$a_id1' "); 
			 
		}
		else {
		$db->query("INSERT INTO `article` 
			(`t_id`,  `a_descr`,  `a_type`, `a_date`, `a_view`) 
			VALUES
			('$_GET[edit]', '$a_descr1', '2', CURRENT_TIMESTAMP, '$a_view1' ) "); 
		
		}
		
		
		
	//echo "FILES is empty? "; echo( empty($_FILES))?"empty":"no empty";
 }
		
	else {
		// Main table
		$q = $db->query("INSERT INTO `theme`(`t_name`, `t_date`, `t_view`) 
			VALUES ('$t_name',CURRENT_TIMESTAMP,'1')	"); 
		 $_GET['edit'] = $t_ins = $db->lastInsertRowID () ;
		 
		 //echo "Last insert in `themes`: $t_ins<br>";
		// Lection
		$db->query("INSERT INTO `article` 
			(`t_id`,  `a_descr`,  `a_type`, `a_date`, `a_view`) VALUES
			('$t_ins', '$a_descr0', '1', CURRENT_TIMESTAMP, '$a_view0' ) "); 
		//HW
		$db->query("INSERT INTO `article` 
			(`t_id`,  `a_descr`,  `a_type`, `a_date`, `a_view`) VALUES
			('$t_ins', '$a_descr1', '2', CURRENT_TIMESTAMP, '$a_view1' ) "); 
		
		
		}
	 	
 }
/* generate global array for admin interface content */ 
$q = $db->query("SELECT * FROM `theme`, `article` 
		WHERE  `theme`.`t_id` = `article`.`t_id` 
		AND `theme`.`t_id` = '$_GET[edit]' 
		ORDER BY `article`.`a_type` LIMIT 2");

$rows = [];
$j = 0;
while($res = $q->fetchArray(SQLITE3_ASSOC)) { $rows[$j++] = $res;}
 $db->close(); 
if (count($rows) == 1) { // paterica
	$rows[] = $rows[0];
	$rows[1]['a_id']=0;
    $rows[1]['a_fname'] = ""; 
    $rows[1]['a_type'] = 2;     
	
	
}
elseif (count($rows) == 0) { // t_id	t_name	t_date	t_view
						 // a_id	a_fname	a_ftype	a_descr	a_type	a_date	a_view
	$fields[0] = ['t_id',	't_name',	't_date',	't_view'];
	$fields[1] = ['a_id', 'a_fname', 'a_descr',	'a_type', 'a_date', 'a_view'];
	
	for($i=0; $i<2; $i++){
		foreach( $fields[0] as $k=>$v){
			$rows[$i][$v] =0 ;
		}
		foreach( $fields[1] as $k=>$v){
			$rows[$i][$v] =0 ;
		}
	}
	 
    $rows[0]['a_type'] = 1;      
    $rows[1]['a_type'] = 2;     
	
	
} 
$fu = []; // flags is uploaded file

/* upload content files & update db from form*/
if(!empty($_FILES) && !isset($_GET['import'])){		
	//	echo "<pre>";print_r($_FILES); echo "</pre>";
		if($_FILES['a_fname0']['error'] === 0) {
			$name = rnd_str(10);
			$fu[] = upload_file('a_fname0',$name);
			if($fu[0]){
				$db->query("UPDATE `article` SET 
				`a_fname` = '$name', 
				`a_ftype`='".$_FILES['a_fname0']['type']."' 
				WHERE `a_id` = ".$rows[0]['a_id']);
			}
		}
		
		if( $_FILES['a_fname1']['error'] === 0){ 
			$name = rnd_str(10);
			$fu[] = upload_file('a_fname1',$name);
			if($fu[1]){
				$db->query("UPDATE `article` SET 
				`a_fname` = '$name', 
				`a_ftype`='".$_FILES['a_fname1']['type']."' 
				WHERE `a_id` = ".$rows[1]['a_id']);
			}
		}
		
	}
/* download (import) content files & db - update procedure */
elseif(isset($_GET['import'])){
	
	if($_FILES['phar']['error'] === 0) {
	if (move_uploaded_file($_FILES['phar']['tmp_name'], "./".$_FILES['phar']['name'])) {
		$confirm = "Успех!";
		 
		$files = scandir("./doc");
		foreach($files as $k=>$v)
			if($v != "." && $v != "..") unlink("./doc/$v");
		
		rmdir("./doc");
		$phar = new Phar('content.phar');
		$phar->extractTo("./doc");
		unlink("./math.sqlite");
		rename("./doc/math.sqlite","./math.sqlite");
		
	}
	else  return false; 
		
	}
	
}	

function getDB($field){ // find field in global array $rows
	global $rows;
 
	 	for($i=0; $i<count($rows); $i++){
			 foreach($rows[$i] as $k=>$v){
				 if($k == $field) return $v;
			}
		}
	 
	return " none ";
}

function getFile($type){
	global $rows;
	if(isset($rows[$type])){ return trim($rows[$type]['a_fname']);	}	 
	 
	return " file not found ";
}

function get_file($fs){
	if(!empty($fs)){
		
		if(is_file("doc/".$fs)) return "\"$fs\"";
		else return "<span>\"$fs\"</span>";
		
		}
	else return "";
}


function _getDate($type){
	global $rows;
	
	if(isset($rows[$type])){ 
			foreach($rows[$type] as $k=>$v){
				// echo " $k->$v \n";
					if($k == "a_date") return $v;
			}
	}	 
	 	 
	return " ------ ";
	
}
//echo "<small><pre> rows: ";print_r($rows); echo "</pre>";
 //$db->query("DELETE FROM `theme` WHERE `t_id`>13");
 //$db->query("DELETE FROM `article` WHERE `a_id`>36");
 
function getId(){ // generate select form 
	$str = "<select size='1' name='t_id' id='t_id' onchange=\"location.replace('99.php?edit='+this.value);\">
	<option value=0>new id</option>"; 
	
	$db = new SQLite3('math.sqlite');
	$q = $db->query("SELECT `t_id`, `t_name` FROM `theme`");
	while($res=$q->fetchArray(SQLITE3_ASSOC) ) 
	$str .=($res['t_id'] == $_GET['edit'])?
		"<option value='$res[t_id]' class='opt' selected>$res[t_id]</option>\n"
		:"<option value='$res[t_id]'>$res[t_id]: $res[t_name]</option>\n";
	return $str ."</select>\n";  
	 
	$db->close(); 
} 
 
  
?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<?
if(@$fu[0]) { 
echo "<script>alert('Uploaded is: ".implode(', \n',$fu)."\n count fu=".count($fu)."'); location.href='./99.php?edit=$_GET[edit]'</script>"; 
}?>
<title>Edit Math Book</title>
<link rel="stylesheet" href="css/style.css">
<style type="text/css">
body{padding-left:5%} 
hr{
	color:#FFF
}
#cont{
	overflow:auto;
	padding:3px;
	font-size:15px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	text-align:justify;
	color:#013756;
	width: 850px; height: 450px;
	top:0;
	text-align:center;
}
#cont p:first-child{cursor:pointer;}
#content img{
	text-align:center;
}
.style40 {font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: small;
}
table{min-width:600px;max-width:800px;width:600px;}
tr{text-align:left;vertical-align:bottom;} 
//td:hover {background: #999;}
td{border:solid 0px #333; border-collapse:collapse;vertical-align:middle;}
td:first-child{text-align:right;max-width:40%;}
table input , textarea{
	width:100%;
	margin-left:5px;
	border-collapse:collapse;
	border-width:0;
	padding:1px 5px;
}
tr:last-child{text-align:right}
td {margin:1px; padding:0;}
input[type="submit"]{margin-right:0px;margin-top:10px;}
input[type="checkbox"]{width:25px;vertical-align:bottom;}
table input[type="file"]{float:left; width:110px; margin:2px 0px;}
span{float:left; font-size:small;color:white;margin-left:5px;}
fieldset{overflow:hidden;border-color: white;margin-right:-2px;} 
fieldset legend{text-align:left;color:#013756}
span span{ -webkit-animation: blink 2s linear infinite;
  animation: blink 2s linear infinite;
 margin:2px 3px;padding:2px;font-size:100%}
#blink {
  -webkit-animation: blink 1s linear infinite;
  animation: blink 1s linear infinite;
}
@-webkit-keyframes blink {
  50% { color: rgb(34, 34, 34); }
  51% { color: rgba(34, 34, 34, 0); }
  100% { color: rgba(34, 34, 34, 0); }
}
@keyframes blink {
  50% { color: rgb(34, 34, 34); }
  51% { color: rgba(34, 34, 34, 0); }
  100% { color: rgba(34, 34, 34, 0); }
}
select {margin-left:5px;width:100%;margin-right:0px;}
select .opt{background:#369;color:#013756;}
.float {float:right;border:solid 0px gray;margin:0 10px;padding:5px; max-width:200px;}
.float input{ width:110px;}
</style>
</head>

<body link="#FFFFFF">

 <? echo (!empty($confirm)) ? "<script>alert(\"$confirm\");</script>":""; ?>
 
  <? include_once("rmenu.inc"); ?>
<div id="cont" >
 <p style="font-size:larger;cursor:pointer" onclick="parent.document.location='./'">Edit Book</p>
      <hr />
	  <div class="float">
	  <fieldset><legend>Export update</legend>
	  <input type="button" onclick="location.href='99.php?edit=0&export'" value="Start export" /> 
	  </fieldset>
	  <fieldset><legend>Import update</legend>
	  <form action="99.php?edit=0&import" enctype="multipart/form-data" method="POST"  target="main">
	  <input type="file" name="phar"  />
	  <input type="submit" value="Import update" /></form></fieldset>
	  </div>
	  <!--p>Work with id =<?=$_GET['edit']?> </p--> 
	  <form action="99.php?edit=<?=$_GET['edit']?>" enctype="multipart/form-data" method="POST"  target="main">
      <table align="center">
	    <tr>
          <td> id</td>
          <td >
		  <? echo getId(); ?>
            
          </td>
        </tr>
		<tr>
          <td>Lection shortname</td>
          <td>
              <input type="text" name="t_name" value="<?=getDB('t_name')?>" />
          </td>
        </tr>
		<tr>
          <td>Last update</td>
          <td>
              <input type="text" name="t_date" readonly value="<?=$rows[0]['t_date']?>" title="Last update of lection" />
          </td>
        </tr>
        <tr>
          <td>Detail name of lection</td>
          <td>
              <input type="text" name="a_descr" value="<?
			   echo $str = getDB('a_descr')."\" ";
			  if (strlen($str)>50) echo "title='$str'";
			  ?> />
         </td>
        </tr>
        <tr><td colspan="2">
		 
		<fieldset><legend>Lection file </legend>
          <input name="a_id0" type="hidden" value="<?=$rows[0]['a_id']?> "/>
		  <input type="checkbox" name="aview0" title="Enable a lection" onclick="a_view0.value=(this.checked)?1:0" <? echo($rows[0]['a_view']==1)?"checked":"";?> /> 
		  <input name="a_view0" type="hidden" value="<?=$rows[0]['a_view']?> "/>  
		 <? if ($_GET['edit']>0) { ?>
			<input type="file" name="a_fname0"  />
		 <?}?>
          <span title="time:<?=$rows[0]["a_date"]?>"> <?=get_file($rows[0]["a_fname"])?> </span>
		  <input type="text" name="a_descr0" value="<?=$rows[0]['a_descr']?>" title="Description of lection" />
        </fieldset>
		</td></tr>		
        <tr><td colspan="2" align="center">
		
		<fieldset><legend>Homework file</legend>
          <input name="a_id1" type="hidden" value="<?=$rows[1]['a_id']?> "/>
		  <input type="checkbox" name="aview1" title="Enable a homework"  onclick="a_view1.value=(this.checked)?1:0" <? echo($rows[1]['a_view']==1)?"checked":"";?> /> 
		  <input name="a_view1" type="hidden" value="<?=$rows[1]['a_view']?>" /> 
		  <? if ($_GET['edit']>0) { ?>
		   <input type="file" name="a_fname1"  />
		 <?}?>     
          <span title="time:<? echo $rows[1]["a_date"] ;?>"> <?=get_file($rows[1]["a_fname"])?> </span>
		  <input type="text" name="a_descr1" value="<?=$rows[1]['a_descr']?>" title="Description of homework" />   
              
           </fieldset> 
		</td></tr>
        
		<tr>
          <td >&nbsp;</td>
          <td > 
            <input type="submit" value="Submit" />
            
           </td>
        </tr>
      </table>
	  </form>
      <hr /> 

<br>

</div>

    <script  src="js/index.js"></script>
<? 

 //echo "<pre><small>"; print_r($rows);
	?>
</body>
</html>
