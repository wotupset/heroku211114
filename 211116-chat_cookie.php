<?php
//error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
header("content-Type: application/json; charset=utf-8"); //強制
//header('Content-Type: text/html; charset=utf-8');
//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
//$query_string=$_SERVER['QUERY_STRING'];

//print_r($_COOKIE);
//setcookie("chat_cookie", '123' );

$chk = 'poi211118';
$chat_cookie = $_COOKIE['chat_cookie'];
//echo "目前=".$chat_cookie;
//echo "\n";
echo strlen($chat_cookie);
echo "\n";

if( password_verify( $chk ,  $chat_cookie ) ){
	echo "yy";
	echo "\n";
	
}else{
	echo "nn";
	echo "\n";
	echo "發送新的cookie";	
	echo "\n";
	$pw_hash=password_hash( $chk , PASSWORD_DEFAULT);
	echo $pw_hash;
	echo "\n";
	setcookie("chat_cookie", $pw_hash ,time()+ (3600*24*365*1) );
	
}//if




exit('結束');





?>