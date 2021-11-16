<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
header("content-Type: application/json; charset=utf-8"); //強制
//header('Content-Type: text/html; charset=utf-8');
//header("refresh:5; url=./211116-chat_post1.php");
header("refresh:4; url=./211116-chat_post.php");

//ob_start();

$chk = 'poi211118';
$chat_cookie = $_COOKIE['chat_cookie'];
if( password_verify( $chk ,  $chat_cookie ) ){
	//echo "yy"."\n";
}else{
	//echo "nn"."\n";
	exit("錯誤.cookie");
}//if


function pg_connection_string(){
	$db = parse_url(getenv("DATABASE_URL"));
	//$db["path"] = ltrim($db["path"], "/");
	// https://devcenter.heroku.com/articles/heroku-postgresql#connecting-in-php
	// 連線到資料庫
	extract( $db );//從obj裡 解析出一堆變數
	$path=ltrim($path,"/");//官方建議
	//連線用的字串
	$db_login = "pgsql:host=$host;port=$port;user=$user;password=$pass;dbname=$path;";
	return $db_login;
}

//print_r( pg_connection_string() );//字串包含帳號密碼 小心

//嘗試連線到heroku的PostgreSQL
try{
	//$db_login='pgsql:host=';
	$db_login=pg_connection_string();//連線用的字串
    $pgConn = new PDO($db_login);//php的資料庫連線函式
	//$pgConn =資料庫連線
}catch(PDOException $e) {
	//失敗會跑到這邊
	//$e->getMessage()
	//$e->getCode()
	//print_r($e);
	if( $pgConn->errorCode() != '00000'){
		$FFF= $pgConn->errorInfo();
		print_r($FFF);
		echo "\n\n";
	}
    exit("錯誤.連線到資料庫");
}//try-catch

//

//print_r( $pgConn );//PDO Object()
//echo gettype( $pgConn );//object
//echo "\n";

$pgConn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//
//echo $FFF=$pgConn->getAttribute( PDO::ATTR_CONNECTION_STATUS );
//echo "\n\n";

$table_name="db211116_chat";

//https://www.postgresql.org/docs/9.3/multibyte.html

//exit('結束');



extract($_POST,EXTR_SKIP);
//print_r($_POST);
$post_text=$_POST['text'];//處理傳進來的字串
if($post_text==''){
	exit('結束.空的資料');
}

function text_fix($in1){
	$tmp_xx=$in1;
	//
	$tmp_xx=str_replace("\r\n", "\r", $tmp_xx);  //改行文字の統一。 
	$tmp_xx=str_replace("\r", "\n",$tmp_xx);//Enter符->換行符
	$tmp_xx=str_replace("　", "",$tmp_xx);//全形空格
	$tmp_xx=str_replace('<', '&lt;', $tmp_xx);//less than 換成 HTML Characters
	//
	$tmp_xx=preg_replace("/[\n]+/","<br/>",$tmp_xx);//換行符 改成<br/>
	$tmp_xx=preg_replace("/[\s]+/"," ",$tmp_xx);//等價於[\f\n\r\t\v]多個空白 換成一個空白
	//
	return $tmp_xx;
}//fc

$post_text=text_fix($post_text);
echo $post_text;




try{

$sql=<<<EOT
INSERT INTO {$table_name} (a01,z99) VALUES (:a01,:z99) 
RETURNING *
EOT;

$stmt = $pgConn->prepare($sql);

$FFF=[];
$FFF[':a01']= $post_text ;
$FFF[':z99']= '1';
//print_r($FFF);
$stmt->execute($FFF);

//回傳插入的資料
while($row = $stmt->fetch() ){
	//print_r($row);
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			//echo $v.', ';
		}
	}
}



}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.插入資料");
}//try-catch

exit('終止');

//echo "table刪除資料 方式3 ??刪除第10筆之後的資料"."\n";
try{
$sql=<<<EOT
DELETE FROM {$table_name}
WHERE id IN (
select id from {$table_name} ORDER BY id DESC offset 50
);
EOT;
//echo $sql;
//echo "\n";
$stmt = $pgConn->query( $sql );

}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("刪除資料 方式3");
}

exit('終止');
?>