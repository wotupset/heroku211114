<?php

//print_r( $_SERVER['QUERY_STRING'] );
if( $_GET['inputurl'] ){
	$url=$_GET['inputurl'];
}else{
header('content-Type: text/html; charset=utf-8 '); //語言強制
$str=<<<EOF
<!DOCTYPE html>
<html>
<head>
<title>title</title>
<script>
document.addEventListener("DOMContentLoaded", function(event){
	poi();
});//DOMContentLoaded
function poi(){
	var aa = document.querySelector("input[name='sendbtn']");
	var bb = document.querySelector("input[name='inputurl']");
	bb.value='';
	aa.addEventListener("click", function( event ){
		console.log( bb.value );
		window.location.href = 'curl_getinfo_usage.php?inputurl='+bb.value;
	});	
	

}
</script>

</head>
<body>
<input type="text" name="inputurl" size="20" value="">
<input type="submit" name="sendbtn" value="送出">
curl_getinfo_usage.php?inputurl=http://ram.komica2.net/00/src/1546866116535.webm
</body>
</html>
EOF;
	die($str);
}


//print_r( $url );

$FFF=get_headers($url, 0);
//print_r($FFF);
if( count($FFF) >0 ){
	//繼續
}else{
	header('content-Type: text/plain; charset=utf-8 '); //語言強制
	header("Access-Control-Allow-Origin: *");
	header("HTTP/1.0 404 Not Found");
	die('get_headers???');;
}

require_once('curl_getinfo.php');

if(1){
  $x=curl_FFF($url);
  //echo print_r($x,true);exit;
  $getdata =$x_0 =$x[0];//資料
  $getinfo =$x_1 =$x[1];//訊息
  $geterror=$x_2 =$x[2];//錯誤
  //simple_html_dom
  //if(!$getdata){echo print_r($getinfo,true);exit;}
  //echo print_r($getinfo,true);//檢查點
  $content=$getdata;
}

if($getinfo['http_code'] != 200 ){
	header('content-Type: text/plain; charset=utf-8 '); //語言強制
	header("Access-Control-Allow-Origin: *");
	header("HTTP/1.0 404 Not Found");
	die('http_code='.$getinfo['http_code']);
}
if($geterror > 0 ){
	header('content-Type: text/plain; charset=utf-8 '); //語言強制
	header("Access-Control-Allow-Origin: *");
	header("HTTP/1.0 404 Not Found");
	die('geterror');
}
if( strlen($getdata) == 0 ){
	header('content-Type: text/plain; charset=utf-8 '); //語言強制
	header("Access-Control-Allow-Origin: *");
	header("HTTP/1.0 404 Not Found");
	die('0size');
}

//print_r( $getinfo );
//print_r( $geterror );
//exit;

//header('content-Type: text/plain; charset=utf-8 '); //語言強制
//header("Access-Control-Allow-Origin: *");
//echo $content;
//echo mime_content_type( $content );
//$file = tmpfile();

$file = tempnam(sys_get_temp_dir(), 'poi');
//print_r( $file );
file_put_contents($file,$content);
$poi_filesize=filesize($file);
//echo $poi_filesize;





//file_get_contents() 
//echo mime_content_type($file);

//$finfo=new finfo(FILEINFO_MIME_TYPE);
//$mime=$finfo->file($file);


$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file );
$mime2 = explode("/", $mime);
$uid = uniqid();
$fname = $mime2[0].'_'.$uid.'.'.$mime2[1];

//echo $mime2;
//exit;


header("Access-Control-Allow-Origin: *");//cross domin
header('content-Type:'.$mime); //語言強制
header('Content-Length:'.$poi_filesize);
header('Accept-Ranges: bytes');
header("Access-Control-Expose-Headers: Content-Length,Accept-Ranges,Access-Control-Allow-Origin");//cross domin

header("Cache-Control: public,only-if-cached	"); //HTTP 1.1

header('Content-disposition: inline ;filename="'.$fname.'"'); 


echo file_get_contents($file);
	
?>