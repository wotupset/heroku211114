<?php
//error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//header("content-Type: application/json; charset=utf-8"); //強制
header('Content-Type: text/html; charset=utf-8');
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
//$query_string=$_SERVER['QUERY_STRING'];
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
//print_r($_COOKIE);
//setcookie("chat_cookie", '123' );

ob_start();


$chk = 'poi211118';
$chat_cookie = $_COOKIE['chat_cookie'];
//echo "目前=".$chat_cookie;
//echo "\n";
echo strlen($chat_cookie);
echo "\t";
echo substr($chat_cookie, -8);
echo "\n";

echo "驗證=";
if( password_verify( $chk ,  $chat_cookie ) ){
	echo "yy";
}else{
	echo "nn";
}//if
echo "\n";

if( $_GET['chk'] =='poi211118' ){
	echo "發送新的cookie";	
	echo "\n";
	$pw_hash=password_hash( $chk , PASSWORD_DEFAULT);
	//echo $pw_hash;
	echo substr($pw_hash, -8);
	echo "\n";
	setcookie("chat_cookie", $pw_hash ,time()+ (3600*24*365*1) );
}else{
	
}




$html1=ob_get_contents();
ob_end_clean();



$html10=<<<EOT
EOT;

$html_chk=<<<EOT
<form id='form_c' action='$phpself' method='get' autocomplete="off">
<input type="hidden" name="chk" value="poi211118">
<input type="submit" value="送出"/>  
</form>
<a href="$phpself">$phpself</a>
EOT;


$html2=<<<EOT
<html>
<head>
<title></title>
<style>
</style>
</head>
<body>
$html_chk
<pre>
$html1
</pre>
</body>
<html>
EOT;
echo $html2;

exit('結束');





?>