<?
/*
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('math.db');
    }
}
*/
 
if(!empty($_FILES)){
	if($_FILES['phar']['name'] == "content.phar" && $_FILES['phar']['error'] == 0){
			
			include_once("update.php");
			//echo $_FILES['phar']['name'];
		}
	else $confirm = "File is bad or corrupted!";
	}
 

$db = new SQLite3('math.sqlite');


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
<script>
<? if (isset($confirm)){
	echo "alert('$confirm');
	 location.href='./';";	
}
	?> 
	
	</script>  
</head>

<body>
 
<? include_once("rmenu.inc"); ?>
  
<div id='txt' style='background:#fff;font-size:small'></div>



<div class="content" style="width:90%" onclick="styl = document.getElementById('content').style;styl.top = 0+'px'; styl.height=100+'%'">
<?

if(empty($_GET)){ // lections
    
    $q = $db->query("SELECT * FROM `theme`, `article` WHERE `theme`.`t_view` = 1 AND `article`.`a_type` = 1 AND `article`.`a_view` = 1 AND `theme`.`t_id` = `article`.`t_id` ORDER BY `theme`.`t_id`");
 $i=1;
 $arr = [];
while($res=$q->fetchArray(SQLITE3_ASSOC)){
        
         if (file_exists("./doc/".$res['a_fname']) && is_file("./doc/".$res['a_fname']))
         echo "<a href='./pdf.php?f=".$res['a_id']."' target='main'><div id='btn".($i++)."' class='btn' title='$res[a_descr]' > <p>$res[t_name] </p> <span>$res[a_descr]</span></div> </a>\n";
         else echo "<a href='./404.html' target='main' ><div id='btn".($i++)."' class='btn' title='$res[a_descr] (not found)'> <p style='opacity:0.1'>$res[t_name] </p> </div> </a>\n";
        
        
     $arr[] = $res;
        }         
}
elseif(isset($_GET['homework'])){ // homework
    
    $q = $db->query("SELECT * FROM `theme`, `article` WHERE `theme`.`t_view` = 1 AND `article`.`a_type` = 2 AND `article`.`a_view` = 1 AND `theme`.`t_id` = `article`.`t_id` ORDER BY `theme`.`t_id`");
 $i=1;
 $arr = [];
while($res=$q->fetchArray(SQLITE3_ASSOC)){
        
         if (file_exists("./doc/".$res['a_fname']) && is_file("./doc/".$res['a_fname']))
         echo "<a href='./pdf.php?f=".$res['a_id']."' target='main'><div id='btn".($i++)."' class='btn' title='$res[a_descr]' > <p>$res[t_name] </p> <span>$res[a_descr]</span></div> </a>\n";
         else echo "<a href='./imgs/404.jpg' target='main' ><div id='btn".($i++)."' class='btn' title='$res[a_descr] (not found)'> <p style='opacity:0.1'>$res[t_name] </p> </div> </a>\n";
        
        
     $arr[] = $res;
        }         
}



?>
</div> 
<div id="content"> <iframe name="main" frameborder="0"  style="margin-bottom:0;"></iframe></div>
 <script src='js/classList.min.js'></script>  

    <script  src="js/index.js"></script>
<script type="text/javascript">
edit = 0;
 
document.addEventListener('keydown', helper);

function helper(e){ 
	if(	 e.code == "F1") document.getElementById("help").click(); 
	//alert( e.code);
	}

onload = function(){
	
	document.getElementsByTagName ('iframe')[0].style.height = (window.innerHeight ) + 'px';
	 
};
   
  <?  
  function getFname($id,$type){
	$db1 = new SQLite3('math.sqlite');
	$q = $db1->query("SELECT `a_fname`,`a_id` FROM `article` WHERE `a_type` = $type
				AND `t_id`=$id LIMIT 1");
	$res=$q->fetchArray(SQLITE3_ASSOC) ;
	$db1->close();
	return $res['a_id'];//"./doc/".trim($res['a_fname']);
	
  }
  
  for($i=0; $i<count($arr); $i++){    
      
      $fname2=getFname($arr[$i]['t_id'],2);	
  } 
  
?>
  
  
</script>   
<? $db->close(); 
 // echo "<pre><small> Arr: "; print_r($arr);
?> 
</body>

</html>
