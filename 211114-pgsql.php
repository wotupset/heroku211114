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

if (!pg_connection_busy( $pg_conn )) {
	pg_send_query($pg_conn,"SELECT * FROM aaa;");//不存在的table
	$res=pg_get_result($pg_conn);
	//
	//echo pg_last_notice($pg_conn);//??連線中的最後一條通知??
	//echo "\n";
	//pg_set_error_verbosity可以調整錯誤訊息的詳細程度
	echo pg_last_error($pg_conn);//連線中的最後一條錯誤
	echo "\n";
	echo pg_result_error($res);//錯誤訊息 跟pg_last_error相同
	echo "\n";
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


exit("結束");






?>