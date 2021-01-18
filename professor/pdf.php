<?

function file_read($fname,$ftype) {
		$file = "doc/".$fname;
  if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
      ob_end_clean();
    }
	
    
	//header('Content-Disposition: attachment; filename=materials for lection');//. basename( $file));
    //header('Content-Transfer-Encoding: binary'); 
	if(stristr($ftype, 'pdf'))
		header("Content-Type:'pdf'");
	else 
		header('Content-Type: application/octet-stream');	
	//header("Content-Transfer-Encoding: binary");
    header('Cache-Control: must-revalidate'); 
	header('Accept-Ranges: bytes');
   // header('Content-Length: ' . filesize($file));
    // читаем файл и отправляем его пользователю
    //readfile( $file);
    
	$a = fopen($file, "rb");
	$c = fread($a,filesize($file));
	$b = lzf_decompress($c);
	//echo ($b)?$b:"Ошибка распаковки";
	//fpassthru( $b);
	//echo $b;
	// header('Content-Length: ' . strlen($b));
	//echo $b;
	$d = fopen("doc/pdf.pdf", "w");
	fwrite($d, $b);
	
	fclose($d);
	fclose($a);
	 @header('Location: ./doc/pdf.pdf'); 
	}
  else echo "file not found!";
}

$db = new SQLite3('math.sqlite');

$q = $db->query("SELECT a_fname, a_ftype FROM  `article` 
				WHERE  `a_id` = '$_GET[f]' ");
$row = $q->fetchArray(SQLITE3_ASSOC);
file_read($row['a_fname'],$row['a_ftype']);

?>