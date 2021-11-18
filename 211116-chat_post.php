<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//header("content-Type: application/json; charset=utf-8"); //強制
header('Content-Type: text/html; charset=utf-8');
/*
PostgreSQL練習
*/



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


$table_name="db211116_chat";

//https://www.postgresql.org/docs/9.3/multibyte.html

//exit('結束');

//$show_new = 50;//最新頁秀出?筆資料
//echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC LIMIT 50
EOT;
//print_r($sql);
//echo "\n";
try{
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.建立table");
}

$FFF='';
$cc=0;
while($row = $stmt->fetch() ){
	$cc++;
	if( $cc % 2 ){
		//1
		$FFF.='<div id=box'.$cc.' style="">';
	}else{
		//0
		$FFF.="<div id=box".$cc.">";
	}
	$FFF.='<dt>'.$row[0].', '.$row[1]."</dt>"."\n";
	$FFF.="<dd>".$row[2]."</dd>"."\n";
	$FFF.="❀</div>";
}

$html=$FFF;

$chat_cookie = $_COOKIE['chat_cookie'];
$html_post=<<<EOT
<form id='form1' action='./211116-chat_post2.php' method='post'  autocomplete="on">
<div style="display:inline-block;vertical-align:top;" >
<input type="hidden" name="mode" value="reg">
<textarea name="text" id="id_text" cols="48" rows="4" wrap=soft placeholder="內文"></textarea>
<br/>
<input type="submit" id="input_submit" value="送出"/>⚜

</div>
<div style="display:inline-block;vertical-align:top;" >
<input type="password" name="pw_hash" id="pw_hash" maxlength="60" size="16"  value="$chat_cookie"  />
</div>




</form>
<a href="./211116-chat_list_all.php">[最新50篇]</a>
EOT;
$html2=<<<EOT
<html>
<head>
<title></title>
<style>
dl div:nth-child(odd){
	background-color:#CCFFFF;
}
#input_submit {
    width: 100px;
    height: 50px;
}
#pw_hash {
    width: 100px;
    height: 50px;
}
</style>
</head>
<body>
$html_post
<dl>
$html
</dl>
</body>
<html>
EOT;
echo $html2;

?>