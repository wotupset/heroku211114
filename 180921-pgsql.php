<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
header("content-Type: application/json; charset=utf-8"); //強制
/*
PostgreSQL練習
*/




$time  =time();
$time2 =array_sum( explode( ' ' , microtime() ) );
date_default_timezone_set("Asia/Taipei");//時區設定
//date_default_timezone_set("UTC");//時區設定
$timezone=date_default_timezone_get();
echo '[php]timezone='.$timezone."\n";
echo '[php]now='.date("Y-m-d H:i:s",$time)."\n";
echo '[php]UTC='.gmdate("Y-m-d H:i:s",$time)."\n";



///
try{
/*
PostgreSQL連線
使用herokuapp.com提供的環境變數連線到資料庫
*/
$db_p = parse_url( getenv("DATABASE_URL") );
$db_p["path"]=ltrim($db_p["path"],"/");
//print_r( $db_p );
$db_p2=$db_p;
$db_p2['pass']=substr( $db_p2['pass'] ,0,4)."...省略";
print_r( $db_p2 );

$db_url='';
$db_url="pgsql:";
$db_url2=$db_url;

foreach($db_p as $k => $v){
}
$cc=0;
switch($cc){
case 0:
break;
case 1:
break;
case 2:
break;
default:
}
$FFF=$db_p;
$db_url="pgsql:host=".$FFF['host'].";port=".$FFF['port'].";user=".$FFF['user'].";password=".$FFF['pass'].";dbname=".$FFF["path"].";";
$FFF=$db_p2;
$db_url2="pgsql:host=".$FFF['host'].";port=".$FFF['port'].";user=".$FFF['user'].";password=".$FFF['pass'].";dbname=".$FFF["path"].";";

//print_r($db_url );
print_r($db_url2 );
                           
//pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass
  
  
$db = new PDO( $db_url );
echo "\n";
if(!$db){die('連線失敗');}
$FFF=$db->getAttribute(PDO::ATTR_CONNECTION_STATUS);
$err=$db->errorInfo();
if($err[0]>0){print_r( $err );}//錯誤資訊
echo '[pdo]連線狀態='.$FFF;
echo "\n";
/*
PDO::ATTR_EMULATE_PREPARES 启用或禁用预处理语句的模拟。有些驱动不支持或有限度地支持本地预处理。
使用此设置强制PDO总是模拟预处理语句（如果为 TRUE ），或试着使用本地预处理语句（如果为 FALSE）。
如果驱动不能成功预处理当前查询，它将总是回到模拟预处理语句
*/
$FFF=$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$err=$db->errorInfo();
if($err[0]>0){print_r( $err );}//錯誤資訊
echo '[pdo]PREPARES='.$FFF;
echo "\n";

//$db->setAttribute(PDO::ATTR_ERRORMODE, PDO::ERRORMODE_EXCEPTION); //让 PDO 在发生错误时抛出异常

  


}catch(PDOException $e){
	$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);
}//錯誤訊息


///
//exit;


echo '[php]version='.phpversion()."\n";
/*
PostgreSQL版本資訊
*/
foreach( $db->query("select version();") as $k => $v ){
  echo '[pgsql]version='.$v[0]."\n";
}


/*
PostgreSQL時間日期
*/
foreach( $db->query("SELECT now()::date, now()::time") as $k => $v ){
  print_r($v);
  echo '[pgsql]now()='.$v[0]." ".$v[1]."\n";
}

/*
PostgreSQL時間日期
*/
$stmt=$db->query("SELECT CURRENT_DATE,CURRENT_TIME,CURRENT_TIMESTAMP,LOCALTIMESTAMP");
//print_r($stmt);
//while ($row = $stmt->fetch() ){}
$row = $stmt->fetch();//取回第一筆資料
print_r($row);
echo '[pgsql]current_timestamp='.$row['current_timestamp'];
echo "\n";
echo '[pgsql]localtimestamp='.$row['localtimestamp'];
echo "\n";



try{
$table_name=<<<EOT
nya20190226
EOT;
echo '[pgsql]table_name='.$table_name;
echo "\n";

/*
PostgreSQL移除table
*/
define("移除table", TRUE );//TRUE//false
//define("移除table", false );//TRUE//false
//echo 移除table;
//echo "\n";

if( 移除table ){
echo "移除table";
echo "\n";
	
$sql=<<<EOT
DROP TABLE IF EXISTS {$table_name}
EOT;

  
print_r($sql);
echo "\n";
//IF NOT EXISTS
$stmt = $db->prepare($sql);
//$stmt->execute( $table_name ); //通过数组设置参数，执行 SQL 模版
/*
You can't use binding values for table names, database names etc.
https://stackoverflow.com/questions/41430780/php-mysql-drop-database-using-prepared-statement
*/
//$stmt->bindParam(':table_name', $table_name); //通过bindParam设置参数
$stmt->execute();
//$stmt=$db->query($sql);
  
$err=$db->errorInfo();
if($err[0]>0){print_r( $err );}//錯誤資訊

}else{
echo "不移除table";
echo "\n";
	
}
  
/*
PostgreSQL列出全部table
*/

$sql=<<<EOT
SELECT * FROM pg_catalog.pg_tables 
WHERE schemaname != 'pg_catalog' 
AND schemaname != 'information_schema';
EOT;
$sql=<<<EOT
SELECT * FROM pg_catalog.pg_tables 
WHERE schemaname = 'public';
EOT;
  
print_r($sql);
echo "\n";
//$stmt=$db->query($sql);
$stmt = $db->prepare($sql);
$stmt->execute();
$err=$db->errorInfo();
if($err[0]>0){print_r( $err );}//錯誤資訊

$cc=0;
foreach($stmt as  $key => $value){ 
  $cc++;
  echo "#".$cc."\t";
  //print_r($value);
  echo $value['tablename']."";
  echo "\n";
}



/*
PostgreSQL建立table
*/

$sql=<<<EOT
CREATE TABLE IF NOT EXISTS {$table_name} 
(
    c01 text NOT NULL,
    c02 text NOT NULL,
    c03 text NOT NULL,
    ID SERIAL UNIQUE PRIMARY KEY,
    timestamp timestamp default current_timestamp
)
EOT;

print_r($sql);
echo "\n";
//IF NOT EXISTS
//$stmt=$db->query($sql);
$stmt = $db->prepare($sql);
//$stmt->bindParam(':table_name', $table_name); //通过bindParam设置参数
$stmt->execute();
  
$err=$db->errorInfo();
if($err[0]>0){print_r( $err );}//錯誤資訊

////
echo "列出非系統table";
echo "\n";
$sql=<<<EOT
SELECT * FROM pg_catalog.pg_tables 
WHERE schemaname = 'public';
EOT;
print_r($sql);
echo "\n";
//$stmt=$db->query($sql);
$stmt = $db->prepare($sql);
$stmt->execute();
$err=$db->errorInfo();
if($err[0]>0){print_r( $err );}//錯誤資訊
$cc=0;
while ($row = $stmt->fetch() ) {
  //print_r($row);
  $cc++;
  echo "b".$cc."\t";
  echo $row['tablename']."";
  echo "\n";
}
  
}catch(PDOException $e){
	$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);
}//錯誤訊息


//exit;


try{
echo "插入資料";
echo "\n";

//插入資料 方法1 问号占位符的预处理语句
$sql=<<<EOT
INSERT INTO {$table_name} (c01,c02,c03) VALUES ( ? , ? , ? );
EOT;
print_r($sql);
echo "\n";
$stmt=$db->prepare($sql);
$array=array();
$array[0]=array( uniqid('u',1),'不用不用',  $time );
$array[1]=array( uniqid('u',1),'问号占位符的预处理语句111',  $time );
foreach ($array as $k=>$v){
  $stmt->execute($v);
  $err=$db->errorInfo();
  if($err[0]>0){print_r( $err );}//錯誤資訊
}



//插入資料 方法2 命名占位符的预处理语句
$sql=<<<EOT
INSERT INTO {$table_name} (c01,c02,c03) VALUES ( :c01 , :c02 , :c03 );
EOT;
print_r($sql);
echo "\n";
$stmt=$db->prepare($sql);
$array=array();
$array[0]=array(
  ':c01' => uniqid('u',1), 
  ':c02' => '肏肏肏肏肏肏肏肏',
  ':c03' => base64_encode($time2) ,
);
$array[1]=array(
  ':c01' => uniqid('u',1), 
  ':c02' => '命名占位符的预处理语句222',
  ':c03' => base64_encode($time2) ,
);
foreach ($array as $k=>$v){
  $stmt->execute($v);
  $err=$db->errorInfo();
  if($err[0]>0){print_r( $err );}//錯誤資訊
}

  
}catch(Exception $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息


//exit;

try{
echo "列出資料";
echo "\n";
	
$sql=<<<EOT
select * from {$table_name} 
EOT;
//ORDER BY timestamp DESC
// LIMIT 10
$stmt = $db->prepare($sql);
$stmt->execute();
$rows_max = $stmt->rowCount();//計數
echo 'rows_max='.$rows_max."\n";
$columns_max = $stmt->columnCount();//計數
echo 'columns_max='.$columns_max."\n";
//
$cc=0;
while ($row = $stmt->fetch() ) {
  $cc++;
  if($cc>50){echo('break'."\n");break;}
  echo $cc.", ";
  for($x = 0; $x < $columns_max; $x++) {
    echo $row[$x].", ";
  }
  echo "\n";

  //echo $row['c01']."\t".$row['c02']."\t".$row['c03']."\t".$row['c04']."\t".$row['id']."\t".$row['timestamp']."\n";
  //echo pg_unescape_bytea($row['c03'])."\n";
}
  
  
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息

//exit;


try{
echo "資料庫size";
echo "\n";
/*
PostgreSQL資料庫size
*/

$sql=<<<EOT
SELECT pg_size_pretty(pg_database_size('Database Name'));
EOT;
$sql=<<<EOT
SELECT pg_size_pretty(pg_relation_size('{$table_name}'));
EOT;
$sql=<<<EOT
SELECT pg_size_pretty( pg_total_relation_size( '{$table_name}' ) );
EOT;
$sql=<<<EOT
SELECT pg_size_pretty(pg_database_size(current_database()));
EOT;


/*

*/
print_r($sql);
echo "\n";
$stmt=$db->query($sql);
  
$err=$db->errorInfo();
if($err[0]>0){print_r( $err );}//錯誤資訊

//print_r($stmt);

$cc=0;
while($row = $stmt->fetch() ) {
  //print_r($cc++);
  print_r($row);
}


//
}catch(PDOException $e){$chk=$e->getMessage();print_r("try-catch錯誤:".$chk);}//錯誤訊息




?>