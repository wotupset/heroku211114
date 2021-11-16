<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
header("content-Type: application/json; charset=utf-8"); //強制
/*
PostgreSQL練習
https://www.yiibai.com/postgresql/postgresql_php.html

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
	$db_login="user=$user password=$pass host=$host dbname=".$path.""; # <- you may want to add sslmode=require there too
	return $db_login;
}

$FFF=pg_connection_string();
//echo $FFF; //字串包含帳號密碼 小心
//exit();

$pg_conn = pg_connect( $FFF );//資料庫連線

$FFF=pg_version($pg_conn);
echo "pg_version=";
print_r($FFF['client']);
echo "\n";
/*
Array
(
    [client] => 14.0
    [protocol] => 3
    [server] => 13.4 (Ubuntu 13.4-4.pgdg20.04+1)
    [server_encoding] => UTF8
    [client_encoding] => UTF8
    [is_superuser] => off
    [session_authorization] => jgjcxqhsnyzxup
    [DateStyle] => ISO, MDY
    [IntervalStyle] => postgres
    [TimeZone] => Etc/UTC
    [integer_datetimes] => on
    [standard_conforming_strings] => on
    [application_name] => 
)
*/
 
$FFF = pg_connection_status($pg_conn);
echo "pg_connection_status=";
print_r($FFF);
echo "\n";
if($stat){exit;}

$FFF = pg_connection_busy($pg_conn);
echo "pg_connection_busy=";
print_r($FFF);
echo "\n";
if($stat){exit;}




echo "捕捉錯誤測試"."\n\n";
//try-catch 捕捉錯誤測試
/*
//不能用這種方法
try{
    $res = pg_query($pg_conn, "show me haha");//錯誤的語句
}catch($e){
	print_r($e);
	$FFF= pg_last_error($pg_conn);	
	print_r($FFF);
}

*/
/**/
pg_send_query($pg_conn,"SELECT * FROM aaa;");//不存在的table
$result=pg_get_result($pg_conn);
//
echo $FFF=pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);//狀態//錯誤碼
echo "\n";
if($FFF){
	echo pg_last_error($pg_conn);//連線中的最後一條錯誤
	echo "\n";
	echo pg_result_error($result);//錯誤訊息 跟pg_last_error相同
	echo "\n";
}




/*

if (!pg_connection_busy( $pg_conn )) { //???
	//
	//echo pg_last_notice($pg_conn);//??連線中的最後一條通知??
	//echo "\n";
	//pg_set_error_verbosity可以調整錯誤訊息的詳細程度
	//echo pg_last_error($pg_conn);//連線中的最後一條錯誤
	//echo "\n";
	//echo pg_result_error($res);//錯誤訊息 跟pg_last_error相同
	//echo "\n";
	//echo pg_result_error_field($res, PGSQL_DIAG_SQLSTATE);//錯誤碼42P01
	//echo "\n";
	//echo pg_result_error_field($res, PGSQL_DIAG_SEVERITY);//嚴重性
	//echo "\n";
	//echo pg_result_error_field($res, PGSQL_DIAG_MESSAGE_PRIMARY);//錯誤訊息
	//echo "\n";
	//echo pg_result_error_field($res, PGSQL_DIAG_MESSAGE_DETAIL );//?空
	//echo "\n";
	//echo pg_result_error_field($res, PGSQL_DIAG_CONTEXT );//?空
	//echo "\n";
	//echo pg_result_error_field($res, PGSQL_DIAG_SOURCE_FUNCTION );//???parserOpenTable
	//echo "\n";
	

	echo "\n\n";
}

*/


//exit("結束");


/*
*/


echo "SETSHOW"."\n\n";

$result = pg_query($pg_conn, "SET CLIENT_ENCODING TO 'UTF8';" );
$result = pg_query($pg_conn, "SHOW client_encoding;" );
while ($row = pg_fetch_row($result)){ 
	//print_r( $row ); //UTF8
	echo $row[0]."\n";
}
/*
Array
(
    [0] => UTF8
)
*/
echo "列出所有table"."\n\n";
$result = pg_query($pg_conn, "SELECT * FROM pg_catalog.pg_tables " );
//print_r($result);//Resource id #3

$FFF=pg_num_rows($result);//資料數
echo 'pg_num_rows='.$FFF."\n";//67
$FFF=pg_num_fields($result);//欄位數
echo 'pg_num_fields='.$FFF."\n";//

while($row = pg_fetch_row($result)){ 
	//print_r( $row ); //
	echo $row[1]."\n";
}
/*
Array
(
    [0] => public   //schemaname
    [1] => nya20190226   //tablename
    [2] => jgjcxqhsnyzxup    //tableowner
    [3] => 
    [4] => t
    [5] => f
    [6] => f
    [7] => f
)
*/


//exit("結束");
echo "建立table"."\n\n";
//$table_name="db211115_byPCN";//pgsql有大小寫的問題 建議使用全小寫
$table_name="db211115_bypcn";//pgsql有大小寫的問題 建議使用全小寫
//$result = pg_query($pg_conn, "DROP TABLE IF EXISTS {$table_name}" );//移除table
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

pg_send_query($pg_conn, $sql );//
$result=pg_get_result($pg_conn);
//print_r( $res );//Resource id #7
echo "\n";
$FFF=pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);//狀態//錯誤碼
if($FFF){
	echo pg_last_error($pg_conn);//連線中的最後一條錯誤
	echo "\n";
	echo pg_result_error($result);//錯誤訊息 跟pg_last_error相同
	echo "\n";
}




echo "列出非系統table"."\n";
$sql=<<<EOT
SELECT * FROM pg_catalog.pg_tables 
WHERE schemaname = 'public';
EOT;
pg_send_query($pg_conn, $sql );//
$result=pg_get_result($pg_conn);
//print_r( $res );//Resource id #7
echo "\n";
$FFF=pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);//狀態//錯誤碼
if($FFF){
	echo pg_last_error($pg_conn);//連線中的最後一條錯誤
	echo "\n";
	echo pg_result_error($result);//錯誤訊息 跟pg_last_error相同
	echo "\n";
}

while ($row = pg_fetch_row($result)){ 
	//print_r( $row ); //UTF8
	echo $row[1]."\n";
}

echo "table插入資料 方式1"."\n";
$sql=<<<EOT
INSERT INTO {$table_name} (a01,z99) VALUES ('aaaa🤣9.0','zzzz🧲11.0');
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );

echo "table插入資料 方式2"."\n";
$result = pg_prepare($pg_conn, "myquery", "INSERT INTO {$table_name} (a01,z99) VALUES ($1,$2);");
$result = pg_execute($pg_conn, "myquery", ['aaa01','zzz01']);
$result = pg_execute($pg_conn, "myquery", ['aaa02','zzz02']);

for($i = 0; $i < 5; $i++) {
$result = pg_execute($pg_conn, "myquery", ['aaa_批次新增'.$i,'zzz_批次新增'.$i]);
}






echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );
while($row = pg_fetch_row($result)){ 
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	echo "\n";
}
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
echo "列出最舊的id(1筆)"."\n";
$sql=<<<EOT
SELECT MIN(id) FROM {$table_name} 
EOT;
//LIMIT 1
$result = pg_query($pg_conn, $sql );
while ($row = pg_fetch_row($result)){ 
	//print_r( $row ); //UTF8
	echo $row[0];
	echo "\n";
}

//exit("結束");


echo "table更新資料(最舊的id)"."\n";


$sql=<<<EOT
UPDATE {$table_name} SET a01 = '好想交這樣的女生當女友喔' 
WHERE id IN (
SELECT MIN(id) FROM {$table_name} 
);
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );


echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );
while($row = pg_fetch_row($result)){ 
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	echo "\n";
}





echo "table刪除資料 方式2 ??依照時間 只保留十分鐘的資料"."\n";
$sql=<<<EOT
DELETE FROM {$table_name}
WHERE timestamp < now() - interval '10 minutes'
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );

echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );
while($row = pg_fetch_row($result)){ 
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	echo "\n";
}




//exit("結束");

echo "列出第10筆之後的資料"."\n";
$sql=<<<EOT
select id from {$table_name} ORDER BY id DESC offset 10
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );
while($row = pg_fetch_row($result)){ 
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	echo "\n";
}



echo "table刪除資料 方式3 ??刪除第10筆之後的資料"."\n";
$sql=<<<EOT
DELETE FROM {$table_name}
WHERE id IN (
select id from {$table_name} ORDER BY id DESC offset 10
);
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );



echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC 
EOT;
echo $sql;
echo "\n";
$result = pg_query($pg_conn, $sql );
while($row = pg_fetch_row($result)){ 
	foreach($row as $k=>$v){
		if( preg_match("/^[0-9]$/",$k) ){
			echo $v.', ';
		}
	}
	echo "\n";
}


exit("結束");
?>