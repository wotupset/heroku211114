<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//header("content-Type: application/json; charset=utf-8"); //強制
//header('Content-Type: text/html; charset=utf-8');


//echo $page;
//echo "\n";


if( $_POST['chk'] =='poi211121' && $_POST['poi211121'] =='poi211121' ){
//if(1==1){
	//沒事
}else{

$html10=<<<EOT
EOT;

$html_chk=<<<EOT
<form id='form_c' action='$phpself' method='post' >
<input type="hidden" name="chk" value="poi211121">
<input type="text" name="poi211121" id="poi211121" maxlength="60" size="10"  value="" />
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
</body>
<html>
EOT;

header('Content-Type: text/html; charset=utf-8');
echo $html2;

exit('結束');

	
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
}//pg_connection_string()

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


$table_name="db211116_chat";

//https://www.postgresql.org/docs/9.3/multibyte.html





//exit('結束');
$sql=<<<EOT
SELECT * FROM {$table_name} ORDER BY id DESC ;
EOT;
//LIMIT 5
try{
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.");
}
$rows_max = $stmt->rowCount();//資料數

$FFF='';
while($row = $stmt->fetch() ){
	//print_r($row);
	foreach($row as $k=>$v){
		//
	}
	$FFF=$FFF.$row[0].', '.$row[1].', '.$row[2].', '.$row[3].'';
	$FFF=$FFF."\n";	
}









//exit('結束');

//確認上方都沒有header 跟 echo 
//header("Content-type: text/html; charset=utf-8");
header("Content-type:application/force-download"); //告訴瀏覽器 為下載 
header("Content-Transfer-Encoding: Binary"); //編碼方式
//header("Content-length:".filesize($csvfile)."");  
//header('Content-Type: text/plain');
header("Content-Disposition:attachment; filename=123.csv"); //顯示的檔名

echo $FFF;


exit();

?>