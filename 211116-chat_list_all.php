<?php
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//header("content-Type: application/json; charset=utf-8"); //強制
header('Content-Type: text/html; charset=utf-8');


//echo $page;
//echo "\n";


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
SELECT * FROM {$table_name} ORDER BY id DESC
EOT;
try{
$stmt = $pgConn->query( $sql );
}catch(PDOException $e){
	print_r($e->getCode());//method public
	print_r($e->getMessage());//method public
	exit("錯誤.");
}
$rows_max = $stmt->rowCount();//資料數

//echo 'rows_max='.$rows_max."\n";

$show_rows=100;//歷史頁秀出?筆資料
$all_page = ceil($rows_max/$show_rows);//計算留言版所有頁數
$page=$_GET['page'];
if( $page >0 ){
	//
}else{
	//預設p1
	$page=1;
}

//echo $all_page;
//echo "\n";

if($page>$all_page || $page<0){die('頁數有誤1');}
if( !preg_match("/[0-9]+/",$page) ){die('頁數有誤2');}

//利用迴圈列出所有頁數
$page_bar='';
for($i=1; $i<=$all_page; $i++){
	//1開始
	$FFF='';
	$FFF.='<a href="./211116-chat_list_all.php?page='.$i.'">';
	$FFF.="[p".($i)."]";
	$FFF.='</a>';
	$FFF.="\n";
	$page_bar=$page_bar.$FFF;
}
$FFF="<a href='./211116-chat_post.php'>[主頁]</a> ";
$page_bar=$FFF.$page_bar;
//echo $page_bar;

/*
p1 => id 0-100
$page=0 $page+100
p2 => id 100-200
$page=1  $page*100

*/
$num_start = ($page-1)*$show_rows;
$num_end = ($page-1)*$show_rows + $show_rows;
//利用迴圈列出頁數內的資料
//$FFF=$row = $stmt->fetch();
//print_r($FFF);
$FFF='';
$cc=0;
while($row = $stmt->fetch() ){
	//$row[0] =>id
	//1開始
	$cc++;
	if( ($cc >= $num_start)&&($cc < $num_end) ){
		$FFF.="<div id=box".$cc.">";
		$FFF.='<dt>'.$row[0].', '.$row[1]."</dt>"."\n";
		$FFF.="<dd>".$row[2]."</dd>"."\n";
		$FFF.="❀</div>";
	}

}
$html=$FFF;


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
$page_bar
<dl>
$html
</dl>
$page_bar
</body>
<html>
EOT;
echo $html2;

//exit('結束');
exit('');
/*
Array
(
    [id] => 344
    [0] => 344
    [timestamp] => 2021-11-16 13:24:30.102453
    [1] => 2021-11-16 13:24:30.102453
    [a01] => 標題是權力遊戲 權鬥深度卻很弱<br/>洋屌是不是不懂權鬥的奧妙啊 難道逼我去看古早陸劇？
    [2] => 標題是權力遊戲 權鬥深度卻很弱<br/>洋屌是不是不懂權鬥的奧妙啊 難道逼我去看古早陸劇？
    [z99] => 1
    [3] => 1
)

*/

//echo "列出資料"."\n";
$sql=<<<EOT
select * from {$table_name} ORDER BY id DESC LIMIT 100
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

$html_post=<<<EOT
<form id='form1' action='./211116-chat_post2.php' method='post'  autocomplete="off">

<textarea name="text" id="id_text" cols="48" rows="4" wrap=soft></textarea>
<div style="display:inline-block;vertical-align:top;" >
內文<br/>
<input type="submit" value="送出"/>⚜
</div>
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
<dl>
$html
</dl>
</body>
<html>
EOT;
echo $html2;

?>