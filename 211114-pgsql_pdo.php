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
    $pgConn->exec("QUERY WITH SYNTAX ERROR");//錯誤的語句
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
https://www.postgresql.org/docs/9.3/multibyte.html
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

echo "建立table"."\n\n";
//$table_name="db211115_byPDO";//pgsql有大小寫的問題 建議使用全小寫
$table_name="db211115_bypdo";//pgsql有大小寫的問題 建議使用全小寫
//$stmt = $pgConn->query("DROP TABLE IF EXISTS {$table_name}");

//exit('結束');



$sql=<<<EOT
CREATE TABLE IF NOT EXISTS {$table_name} (
ID SERIAL UNIQUE PRIMARY KEY,
timestamp timestamp default current_timestamp,
a01 text NOT NULL,
z99 text
);
EOT;
print_r($sql);
echo "\n";
//exec 不回傳
//query 會回傳

try{
$stmt = $pgConn->exec( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.建立table");
}

/*
if( $pgConn->errorCode() != '00000'){
	$FFF= $pgConn->errorInfo();
	print_r($FFF[2]);
	echo "\n\n";
	exit("錯誤.建立table");
}

*/



echo "列出table欄位"."\n\n";
$sql=<<<EOT
SELECT
	table_name,
	column_name,
	data_type
FROM
	information_schema.columns
WHERE
	table_schema = 'public'
ORDER BY table_name ASC 
EOT;
print_r($sql);
echo "\n";
try{
$stmt = $pgConn->query( $sql );


}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.列出table欄位");
}

while($row = $stmt->fetch() ){
	//print_r($row);
	if( $row[0] == strtolower($table_name) ){
		foreach($row as $k=>$v){
			if( preg_match("/^[0-9]$/",$k) ){
				echo $v.', ';
			}
		}
		echo "\n";
	}else{
		continue;
	}

}


/*
Array
(
    [table_catalog] => d3dabjnt5ntia9
    [0] => d3dabjnt5ntia9
    [table_schema] => public
    [1] => public
    [table_name] => db211115_bypcn
    [2] => db211115_bypcn
    [column_name] => id
    [3] => id
    [ordinal_position] => 1
    [4] => 1
    [column_default] => nextval('db211115_bypcn_id_seq'::regclass)
    [5] => nextval('db211115_bypcn_id_seq'::regclass)
    [is_nullable] => NO
    [6] => NO
    [data_type] => integer
    [7] => integer
    [character_maximum_length] => 
    [8] => 
    [character_octet_length] => 
    [9] => 
    [numeric_precision] => 32
    [10] => 32
    [numeric_precision_radix] => 2
    [11] => 2
    [numeric_scale] => 0
    [12] => 0
    [datetime_precision] => 
    [13] => 
    [interval_type] => 
    [14] => 
    [interval_precision] => 
    [15] => 
    [character_set_catalog] => 
    [16] => 
    [character_set_schema] => 
    [17] => 
    [character_set_name] => 
    [18] => 
    [collation_catalog] => 
    [19] => 
    [collation_schema] => 
    [20] => 
    [collation_name] => 
    [21] => 
    [domain_catalog] => 
    [22] => 
    [domain_schema] => 
    [23] => 
    [domain_name] => 
    [24] => 
    [udt_catalog] => d3dabjnt5ntia9
    [25] => d3dabjnt5ntia9
    [udt_schema] => pg_catalog
    [26] => pg_catalog
    [udt_name] => int4
    [27] => int4
    [scope_catalog] => 
    [28] => 
    [scope_schema] => 
    [29] => 
    [scope_name] => 
    [30] => 
    [maximum_cardinality] => 
    [31] => 
    [dtd_identifier] => 1
    [32] => 1
    [is_self_referencing] => NO
    [33] => NO
    [is_identity] => NO
    [34] => NO
    [identity_generation] => 
    [35] => 
    [identity_start] => 
    [36] => 
    [identity_increment] => 
    [37] => 
    [identity_maximum] => 
    [38] => 
    [identity_minimum] => 
    [39] => 
    [identity_cycle] => NO
    [40] => NO
    [is_generated] => NEVER
    [41] => NEVER
    [generation_expression] => 
    [42] => 
    [is_updatable] => YES
    [43] => YES
)
*/

//https://www.postgresqltutorial.com/postgresql-describe-table/

//exit('終止');



echo "列出非系統table"."\n";
$sql=<<<EOT
SELECT * FROM pg_catalog.pg_tables 
WHERE schemaname = 'public';
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	//print_r($row);
	echo $row[1];//tablename
	echo "\n";
}

echo "table插入資料 方式1"."\n";

try{
$sql=<<<EOT
INSERT INTO {$table_name} (a01,z99) VALUES(:a01,:z99);
EOT;
echo $sql;
echo "\n";
$stmt = $pgConn->prepare($sql);
$stmt->bindValue(':a01', "aaaa");
$stmt->bindValue(':z99', '9999');
$stmt->execute();

$stmt->bindValue(':a01', "aaaa第二次");
$stmt->bindValue(':z99', '9999第二次');
$stmt->execute();

$FFF=[];
$FFF=[':a01'=>'aaaa第三次',':z99'=>'9999第三次'];
$stmt->execute($FFF);

$FFF=[];
$FFF[':a01']='aaaa第四次🤣9.0';
$FFF[':z99']='9999第四次🧲11.0';
$stmt->execute($FFF);

for($i = 0; $i < 5; $i++) {
$stmt->bindValue(':a01', "aaaa_批次新增".$i);
$stmt->bindValue(':z99', '9999_批次新增'.$i);
$stmt->execute();

}

}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.插入資料");
}

/*
echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} 
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	print_r($row);
	//echo $row[1];//tablename
	echo "\n";
}

*/

echo "table插入資料 方式2"."\n";

try{
$sql=<<<EOT
INSERT INTO {$table_name} (a01,z99) VALUES('aa2','zz2');
EOT;
echo $sql;
echo "\n";
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.插入資料");
}

/*
echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} 
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	print_r($row);
	//echo $row[1];//tablename
	echo "\n";
}

*/

echo "table插入資料 方式3 (z99=空白)"."\n";

try{
$sql=<<<EOT
INSERT INTO {$table_name} (a01) VALUES('aa3');
EOT;
echo $sql;
echo "\n";
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.插入資料");
}




echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	//print_r($row);
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	//echo $row[1];//tablename
	echo "\n";
}
/*
ORDER BY last_name DESC
LIMIT 20 OFFSET 0
ASC 由小至大排列
DESC 由大至小排列
*/
/*
Array
(
    [id] => 4
    [0] => 4
    [timestamp] => 2021-11-15 05:44:08.055701
    [1] => 2021-11-15 05:44:08.055701
    [a01] => aaaa第四次🤣9.0
    [2] => aaaa第四次🤣9.0
    [z99] => 9999第四次🧲11.0
    [3] => 9999第四次🧲11.0
)

*/



/*
AVG() – return the average value.
COUNT() – return the number of values.
MAX() – return the maximum value.
MIN() – return the minimum value.
SUM() – return the sum of all or distinct values.
https://www.postgresqltutorial.com/postgresql-aggregate-functions/
*/
//GREATST
//LEAST
//MAX
//MIN
// LIMIT 1
echo "列出最舊的資料(1筆)"."\n";
$sql=<<<EOT
SELECT MIN(id) FROM {$table_name}
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	//print_r($row);
	echo $row[0];
	echo "\n";
}

//exit("終止");


echo "table更新資料(最舊的id)"."\n";




try{
$sql=<<<EOT
UPDATE {$table_name} SET a01 = '梨斗常常跟妹妹一起洗澡' 
WHERE id IN (
SELECT MIN(id) FROM {$table_name} 
);
EOT;
echo $sql;
echo "\n";
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.刪除資料");
}

echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	//print_r($row);
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	//echo $row[1];//tablename
	echo "\n";
}


//exit("終止");



//SELECT id,a01,row_number() OVER () as rn FROM {$table_name} ORDER BY timestamp DESC ;
echo "table刪除資料 方式2 ??依照時間 只保留十分鐘的資料"."\n";

try{

$sql=<<<EOT
DELETE FROM {$table_name}
WHERE timestamp < now() - interval '10 minutes'
EOT;
echo $sql;
echo "\n";
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	print_r($row);
	echo "\n";
}


}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.刪除資料");
}

echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	//print_r($row);
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	//echo $row[1];//tablename
	echo "\n";
}

echo "列出第10筆之後的資料"."\n";
$sql=<<<EOT
select id from {$table_name} ORDER BY id DESC offset 10
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	//print_r($row);
	echo $row[0];
	echo "\n";
}


//exit("終止");

echo "table刪除資料 方式3 ??刪除第10筆之後的資料"."\n";
try{
$sql=<<<EOT
DELETE FROM {$table_name}
WHERE id IN (
select id from {$table_name} ORDER BY id DESC offset 10
);
EOT;
echo $sql;
echo "\n";
$stmt = $pgConn->query( $sql );

}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.插入資料");
}

echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
$stmt = $pgConn->query( $sql );
while($row = $stmt->fetch() ){
	//print_r($row);
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	//echo $row[1];//tablename
	echo "\n";
}

exit("結束");


foreach ( $stmt as $k => $v){
    echo $v;
	echo "\n\n";
};



?>