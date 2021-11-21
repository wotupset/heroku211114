<?php
//error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
header("content-Type: application/json; charset=utf-8"); //強制
//header('Content-Type: text/html; charset=utf-8');

exit('有需要更新再使用');

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
}//fc

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

$pgConn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//
$table_name="db211116_chat";


$sql=<<<EOT
UPDATE 
	{$table_name}
SET 
	a01 = '更新==320'
WHERE 
	id='320'
RETURNING 
	*
EOT;
echo $sql;
echo "\n";

try{
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.");
}
//回傳的資料
while($row = $stmt->fetch() ){
	print_r($row);
	echo "\n";
}

exit('');




?>