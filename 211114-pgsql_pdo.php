<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
header("content-Type: application/json; charset=utf-8"); //強制
/*
PostgreSQL練習
*/


$db_p = parse_url( getenv("DATABASE_URL") );
//$_ENV["DATABASE_URL"])
$db_p["path"]=ltrim($db_p["path"],"/");

//print_r($db_p);//字串包含帳號密碼 小心



function pg_connection_string(){
	$db = parse_url(getenv("DATABASE_URL"));
	//$db["path"] = ltrim($db["path"], "/");
	// https://devcenter.heroku.com/articles/heroku-postgresql#connecting-in-php
	// 連線到資料庫
	extract( $db );//從obj裡 解析出一堆變數
	$path=ltrim($path,"/");
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
	//$pgConn 資料庫連線
}catch(PDOException $e) {
	//失敗會跑到這邊
	//$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);
	print_r($e);
    exit("連線到資料庫");
}//try-catch

//

//print_r( $pgConn );//PDO Object()
//echo gettype( $pgConn );//object
//echo "\n";

$pgConn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//Throw exceptions.
$FFF= $pgConn->errorInfo();
if($FFF[0] >0){
	print_r($FFF);
	echo "\n";
}

echo "pdo測試";
echo "\n\n";

function pdo_attr($in1,$in2){
	$FFF=$in1;//'PDO::ATTR_CONNECTION_STATUS';
	$pgConn=$in2;
	//
	print_r($FFF);
	$FFF=constant( $FFF );
	//print_r($FFF);
	echo "\n";
	$FFF= $pgConn->getAttribute( $FFF );
	print_r($FFF);
	echo "\n";
}//fc

pdo_attr( 'PDO::ATTR_CONNECTION_STATUS' ,$pgConn );
pdo_attr( 'PDO::ATTR_CLIENT_VERSION' ,$pgConn );
pdo_attr( 'PDO::ATTR_DRIVER_NAME' ,$pgConn );



//pdo_attr( 'PDO::ATTR_ERRMODE' ,$pgConn );
//pdo_attr( 'PDO::ATTR_CASE' ,$pgConn );
//pdo_attr( 'PDO::ATTR_ORACLE_NULLS' ,$pgConn );

//pdo_attr( 'PDO::ATTR_SERVER_INFO' ,$pgConn );
//pdo_attr( 'PDO::ATTR_SERVER_VERSION' ,$pgConn );
//pdo_attr( 'PDO::ATTR_TIMEOUT' ,$pgConn );//??不支援

//pdo_attr( 'PDO::ATTR_AUTOCOMMIT' ,$pgConn );//??不支援
//pdo_attr( 'PDO::ATTR_PERSISTENT' ,$pgConn );//??不支援 沒東西

/*
PDO::ATTR_AUTOCOMMIT  //（在OCI，Firebird 以及 MySQL中可用）： 是否自动提交每个单独的语句。
PDO::ATTR_PREFETCH

	PDO::ATTR_CLIENT_VERSION
	PDO::ATTR_CONNECTION_STATUS
	PDO::ATTR_DRIVER_NAME
	
	PDO::ATTR_ERRMODE
	PDO::ATTR_CASE
	PDO::ATTR_ORACLE_NULLS

PDO::ATTR_SERVER_INFO
PDO::ATTR_SERVER_VERSION
PDO::ATTR_TIMEOUT  //指定超时的秒数。并非所有驱动都支持此选项
*/
echo "\n";
echo "捕捉錯誤測試"."\n\n";
//try-catch 捕捉錯誤測試
try{
    $pgConn->exec ("QUERY WITH SYNTAX ERROR");//錯誤的語句
}catch(PDOException $e){
	//print_r($e);echo "\n";echo "\n";
	//從拋出的錯誤 抓取錯誤碼
	print_r($e->getCode());//method public
	echo "\n";
	//從拋出的錯誤 抓取錯誤的訊息
	print_r($e->getMessage());//method public
	echo "\n";
	//從連線中 抓取錯誤的訊息
	echo $pgConn->errorCode();
	echo "\n";
	if( $pgConn->errorCode() != '00000'){
		$FFF= $pgConn->errorInfo();
		print_r($FFF[2]);
		echo "\n";
	}
/* 
SQLSTATE error code SQLSTATE 錯誤碼。
Driver-specific error code. 驅動程式特有的錯誤碼。
Driver-specific error message. 驅動程式特有的錯誤訊息。
*/
}//try-catch

//https://www.postgresql.org/docs/9.3/multibyte.html


echo "SETSHOW"."\n\n";
$stmt=$pgConn->query("SET CLIENT_ENCODING TO 'UTF8';");
if( $pgConn->errorCode() != '00000'){
	$FFF= $pgConn->errorInfo();
	print_r($FFF[2]);
	echo "\n\n";
}

$stmt=$pgConn->query("SHOW client_encoding;");
while ($row = $stmt->fetch() ){
	//print_r($row);
	echo $row[0]."\n";
}
echo "\n";
/*
Array
(
    [client_encoding] => UTF8
    [0] => UTF8
)
*/

echo "列出所有table"."\n\n";
$stmt = $pgConn->query("SELECT * FROM pg_catalog.pg_tables");
if( $pgConn->errorCode() != '00000'){
	$FFF= $pgConn->errorInfo();
	print_r($FFF[2]);
	echo "\n\n";
}

$FFF = $stmt->rowCount();//資料數
echo 'rows_max='.$FFF."\n";
$FFF = $stmt->columnCount();//欄位數量
echo 'columns_max='.$FFF."\n";


while ($row = $stmt->fetch() ){
	//print_r($row);
	echo $row[1]."\n";
}

/*
Array
(
    [schemaname] => public
    [0] => public
    [tablename] => nya20190226
    [1] => nya20190226
    [tableowner] => jgjcxqhsnyzxup
    [2] => jgjcxqhsnyzxup
    [tablespace] => 
    [3] => 
    [hasindexes] => 1
    [4] => 1
    [hasrules] => 
    [5] => 
    [hastriggers] => 
    [6] => 
    [rowsecurity] => 
    [7] => 
)
*/






exit("結束");


foreach ( $stmt as $k => $v){
    echo $v;
	echo "\n\n";
};



?>