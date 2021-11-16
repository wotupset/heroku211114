<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//header("content-Type: application/json; charset=utf-8"); //強制
header('Content-Type: text/html; charset=utf-8');

//extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
//$query_string=$_SERVER['QUERY_STRING'];

$words=$_POST['words'];
if($words == ''){
$html_find=<<<EOT
<form id='form2' action='./211116-chat_find.php' method='post' autocomplete="off">
<input type="hidden" name="mode" value="find">
<input type="text" name="words" maxlength="32" size="16" placeholder="find" value=""/>
<input type="submit" value="送出"/>  
</form>
空
EOT;

$html2=<<<EOT
<html>
<head>
<title></title>
<style>
</style>
</head>
<body>
$html_find
</body>
<html>
EOT;
echo $html2;

exit('');
}

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
}//pg_connection_string()


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
//echo $FFF=$pgConn->getAttribute( PDO::ATTR_CONNECTION_STATUS );

$table_name="db211116_chat";

//$words='批次';
$words = preg_split("/(　| )+/", $words);//用空白來分割字串
//print_r($words);


$sql_find_word="";
foreach($words as $k => $v){
	$sql_find_word.="AND a01 LIKE '%$v%' ";
}



$sql=<<<EOT
SELECT * FROM {$table_name}
WHERE id > 0 $sql_find_word  ORDER BY id DESC LIMIT 10;
EOT;
//print_r($sql);
//echo "\n";
//exit();


try{
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.");
}

$FFF='';
$cc=0;
$words_count=count($words);//分割的數量
while($row = $stmt->fetch() ){
	if( $cc % 2 ){
		//1
		$FFF.='<div id=box'.$cc.' style="">';
	}else{
		//0
		$FFF.="<div id=box".$cc.">";
	}
	$FFF.='<dt>'.$row[0].', '.$row[1]."</dt>"."\n";
	for($i = 0; $i < $words_count; $i++){
		$row[2]=str_replace($words[$i],"<span style='background-color:yellow;'>".$words[$i]."</span>", $row[2] );//高亮標示 //str_replace
	}
	$FFF.="<dd>".$row[2]."</dd>"."\n";
	$FFF.="❀</div>";
}

$html=$FFF;
//echo $html;



$html_find=<<<EOT
<form id='form2' action='./211116-chat_find.php' method='post' autocomplete="off">
<input type="hidden" name="mode" value="find">
<input type="text" name="word" maxlength="32" size="16" placeholder="find" value=""/>
<input type="submit" value="送出"/>  
</form>
EOT;

$html2=<<<EOT
<html>
<head>
<title></title>
<style>
dl div:nth-child(odd){
	background-color:#CCFFFF;
}

</style>
</head>
<body>
$html_find
<dl>
$html
</dl>
</body>
<html>
EOT;
echo $html2;






?>