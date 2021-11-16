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
echo $FFF=$pgConn->getAttribute( PDO::ATTR_CONNECTION_STATUS );
echo "\n\n";


//https://www.postgresql.org/docs/9.3/multibyte.html

//exit('結束');






echo "建立table"."\n\n";
$table_name="db211116_chat";
//$stmt = $pgConn->query("DROP TABLE IF EXISTS {$table_name}");

$sql=<<<EOT
CREATE TABLE IF NOT EXISTS {$table_name} (
ID SERIAL UNIQUE PRIMARY KEY,
timestamp timestamp default current_timestamp,
a01 text NOT NULL,
z99 text
);
EOT;
//print_r($sql);
//echo "\n";
//exec 不回傳
//query 會回傳

try{
$stmt = $pgConn->exec( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.建立table");
}

//exit('結束');

$sql=<<<EOT
SELECT
    table_name AS show_tables
FROM
    information_schema.tables
WHERE
    table_schema = 'public';
EOT;

try{
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.建立table");
}

$cc=0;
while($row = $stmt->fetch() ){
	//print_r($row);
	if($row[0] == $table_name){
		$cc++;
	}
}
if($cc>0){
	echo "成功"."\n";
}else{
	exit('失敗.建立table');
}

exit('結束');
exit('終止');


echo "table插入資料 方式1"."\n";

try{
	
$sql=<<<EOT
INSERT INTO {$table_name} (a01,z99) VALUES(:a01,:z99);
EOT;
echo $sql;
echo "\n";
$stmt = $pgConn->prepare($sql);

for($i = 0; $i < 2; $i++) {
$stmt->bindValue(':a01', "aaaa_批次新增".$i);
$stmt->bindValue(':z99', '9999_批次新增'.$i);
//$stmt->execute();
}

}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.插入資料");
}//try-catch


echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	echo $row[0].', '.$row[1]."\n";
	echo $row[2]."\n";

}


exit('終止');


echo "table刪除資料 方式3 ??刪除第10筆之後的資料"."\n";
try{
$sql=<<<EOT
DELETE FROM {$table_name}
WHERE id IN (
select id from {$table_name} ORDER BY id DESC offset 50
);
EOT;
echo $sql;
echo "\n";
$stmt = $pgConn->query( $sql );

}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("刪除資料 方式3");
}










?>