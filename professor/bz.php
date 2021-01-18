<? 
/*
echo (lzf_optimized_for (  ) == 1)?"скорость ":"сжатие";
$lzf = "My string <br>" ;
if($a = lzf_compress ($lzf ))  echo "compressed<br>";
else  echo "error <br>";
if($b = lzf_decompress($a))  echo "decompressed<br>";
else "error d";
$fname = "doc/1 - Polinomials HW.pdf";
$handle = fopen($fname, "rb");
$c = fread ($handle, filesize($fname));
$a = lzf_compress ($c);

$b =  fopen("doc/1.lzf", "w");
fwrite($b, $a);
fclose($handle);
fclose($b);

$a = fopen("doc/1.lzf", "rb");
$b = lzf_decompress(fread($a,filesize("doc/1.lzf")));
$c = fopen($fname."f", "w");
fwrite($c, $b);
fclose($a);
fclose($c);*/
/*
$i=0;
$db = new SQLite3('math.sqlite');
echo "<pre>";
$q = $db->query("SELECT * FROM  `article` ");

	while($row = $q->fetchArray(SQLITE3_ASSOC)){
		echo "i:$i, row:<br>";print_r($row); 
		if(is_file("doc/".$row['a_fname'])){
		//	if(compress("doc/".$row['a_fname']))		
			if (stristr($row['a_ftype'] , 'pdf')>0){
				$newname = str_replace(".pdf","",$row['a_fname']);
				$db->query("UPDATE `article` SET `a_fname`='$newname',
				`a_ftype`='application/pdf' where `a_id` = '".$row['a_id']."'");	
				
			}
			else $db->query("UPDATE `article` SET  
				`a_ftype`='application/pdf' where `a_id` = '".$row['a_id']."'");
			//echo "compressed & update \n";
			//else echo "not compressed! \n";
		}
		else echo "not file!!! \n";
		$i++;
		//echo "id: $row[a_id], ".substr($row['a_fname'],0,-4)." <br>";
	}	
		echo "$i файлов переименовано";
		
	*/	
	function compress($fn){
		
		
		$handle = fopen($fn, "rb");
		//$c = fread ($handle, filesize("doc/".$fname));
		$a = lzf_compress (fread ($handle, filesize($fn))) ;	
		//$n_name = substr($row['a_fname'],0,-4);
		$b = fopen(substr($fn,0,-4) , "w");
		if(fwrite($b, $a)) $r = true;
		else $r = false;
		fclose($handle);
		fclose($b);
		return $r;
		
	}	
	
	function decompress($fn){
		//$handle = fopen("doc/".$fn, "rb");
		//$c = fread ($handle, filesize("doc/".$fname));
		$a = fopen( "doc/".$fn, "rb");
		$c = fread($a,filesize("doc/".$fn));
		$b = lzf_decompress($c);
		$n_name =  "doc/".$fn."~.pdf";
		$d = fopen($n_name, "w");
		if(fwrite($d, $b)) $r = true;
		else $r = false;
		fclose($d);
		fclose($a);
		
		$e = fopen($n_name, "rb");
		$f = fread($e,filesize("doc/".$fn));
		fclose($e);
		header("Content-Type:'pdf'");
	//header('Content-Type: application/octet-stream');	
	//header("Content-Transfer-Encoding: binary"); 
	header('Accept-Ranges: bytes');
		echo $f;
		//return $d;
	}
	decompress($_GET['file']);
	
?>