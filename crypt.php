<?php
//header('Content-Type: application/javascript; charset=utf-8');
//header('Content-type: text/html; charset=utf-8');
//**************
extract($_POST,EXTR_SKIP);//接受post
//header('Content-type: text/html; charset=utf-8');
//header('Content-Type: application/javascript; charset=utf-8');
$phpself=basename($_SERVER["SCRIPT_FILENAME"]);//被執行的文件檔名
error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
date_default_timezone_set("Asia/Taipei");//時區設定
//list($time, $msec) = explode('.', microtime(true)); 
list($msec,$time) = explode(' ', microtime()); 
$GLOBALS['time']=$time;
$GLOBALS['date']=date("y/m/d H:i:s", $time);//年月
define("_def_DATE", $GLOBALS['date']);//UNIX時間時區設定
//**************



ob_start(); //打开缓冲区 

//$uid=uniqid(chr(rand(97,122)).chr(rand(97,122)),true);//建立唯一ID
$host =$_SERVER["SERVER_NAME"];
echo $host ;
echo "\n";
echo date("Y年m月d日", $time);
echo "\n";

//$weekday  = date('w');
$weekday  = date('w', $time);
$weeklist = array('日', '一', '二', '三', '四', '五', '六');
$weekday = '('.$weeklist[$weekday].')';
echo date("Y/m/d", $time).$weekday;
echo "\n";


echo "ymdHis: \t\t\t";
echo date("ymd_His", $time);

echo "\n";
echo "time:\t\t\t\t";
echo $time;
echo "\n";
echo "microseconds:\t\t\t";
echo $msec;

echo "\n";
echo "time+ms:\t\t\t";
$theTime = array_sum( explode( ' ' , microtime() ) );
echo $theTime;



echo "\n";
echo "dechex_time:\t\t\t";
echo dechex($time);


echo "\n";
echo "uniqid: \t\t\t";
echo uniqid();

echo "\n";
echo "uniqid_true: \t\t\t";
echo $uid=uniqid('',true);//建立唯一ID

echo "\n";
echo "uniqid_substr: \t\t\t";
echo substr($uid,0,8);//建立唯一ID



echo "\n";
//$tmp=hexdec($tmp1);
echo "date:\t\t\t\t";
echo $date=date('c', $time);//ISO 8601
echo "\n";
echo "date:\t\t\t\t";
echo $date=date('r', $time);//RFC 2822 formatted date
echo "\n";
echo "str_shuffle:\t\t\t";
echo str_shuffle($date);//打散字串
echo "\n";
echo "md5:\t\t\t\t";
echo md5($date);
echo "\n";
echo "sha1:\t\t\t\t";
echo sha1($date);
echo "\n";
echo "password_hash\t\t\t";
if(function_exists("password_hash")){
	echo password_hash($date,PASSWORD_DEFAULT);
}else{
	echo "不支援";
}
echo "\n";
echo "crypt\t\t\t\t";
echo crypt($date);
echo "\n";

echo "UUID/GUID:\t\t\t";
echo $echo=xxx(8,"xoo")."-".xxx(4,"oxx")."-".xxx(4,"xox")."-".xxx(4,"xxo")."-".xxx(12,"oxo");
echo "\n";

$tmp="自訂函數A:\t\t\t";//
$tmp.=xxx("aa","bb");
echo $tmp;
echo "\n";

require './Discuz_AzDGCrypt.php';//載入加密用函式
echo "Discuz_AzDGCrypt: \t\t";
echo $echo=azdg_encode($time,$time);
echo "\n";

echo "base64_encode(gz):\t\t";
if(function_exists("gzdeflate")){
	echo $echo=base64_encode(gzdeflate($time));//gzinflate( base64_decode(
}else{
	echo "不支援";
}
echo "\n";

echo "自訂函數rdm_str:::\t\t";
echo rdm_str();
echo "\n";

echo "自訂函數html_entity:\t\t";
echo $html_entity=html_entity_extA_rand(6)."".html_entity_rand(6);;
echo "\n";

$htmlbody = ob_get_contents();
ob_end_clean(); 
//**************
$htmlbody=<<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="zh-tw">
</head>
<body>
<pre>
$htmlbody
</pre>
</body>
</html>
EOT;

echo $htmlbody;
//**************
function rdm_str($x=''){
	for($i=0;$i<3;$i++){
		$x=$x.chr(rand(48,57)).chr(rand(65,90)).chr(rand(97,122)); //
	}
	return $x;
}
//**************
function xxx($n,$f){
	if(preg_match("/[0-9]+/",$n)){}else{$n=16;}
	if(preg_match("/[a-z]{3}?/",$f)){}else{$f="ooo";}
	$f=(string)$f;
	$tarr=array();
	$a=substr($f,0,1); //echo $a; 第1個數字
	$b=substr($f,1,1); //echo $b; 第2個數字
	$c=substr($f,2,1); //echo $c; 第3個數字
	if($a=="o"){array_push($tarr,"1");} //加入1
	if($b=="o"){array_push($tarr,"2");} //加入2
	if($c=="o"){array_push($tarr,"3");} //加入3
	//echo "<pre>".print_r($tarr,true)."</pre>";
	shuffle($tarr);//打亂陣列
	$ct=count($tarr);//計算元素
	$tmp="";
	for($i=0; $i<$n; $i++){//n=字串長度
		$fork=rand(0,$ct-1);
		$fork=$tarr[$fork];
		//$fork=array_rand($tarr,1); //隨機取1個元素 
		//$fork=$tarr[$fork]; //
		switch($fork){
			case "1":
				$tmp=$tmp.chr(rand(48,57)); //數字
			break;
			case "2":
				$tmp=$tmp.chr(rand(65,90)); //大寫
			break;
			case "3":
				$tmp=$tmp.chr(rand(97,122)); //小寫英文
			break;
			default:
				//沒事
			break;
		}
	}
	return $tmp;
}
//**************
function html_entity_extA_rand($n){
	$s='';
	for($x=0;$x<$n;$x++){
		$s.='&#'.rand(13312,19894).';';//建立唯一ID 
		//CJK統合漢字拡張A
		//中日韓越漢字擴展A區
		//19968-40959(實際40869)
		//13312-19903(實際19894)
	}
	//
	$x=$s;
	return $s;
}
function html_entity_rand($n){
	$s='';
	for($x=0;$x<$n;$x++){
		$s.='&#'.rand(19968,40869).';';//建立唯一ID 
		//CJK統合漢字拡張A
		//中日韓越漢字擴展A區
		//19968-40959(實際40869)
		//13312-19903(實際19894)
	}
	//
	$x=$s;
	return $s;
}
//
//**************
function htmlhead(){
$title=_def_DATE;
$x=<<<EOT
<html><head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Script-Type" content="text/javascript">
<META http-equiv="Content-Style-Type" content="text/css">
<meta name="Robots" content="noindex,follow">
<STYLE TYPE="text/css"><!--
body2 { font-family:"細明體",'MingLiU'; }
--></STYLE>
</head>
<body bgcolor="#FFFFEE" text="#800000" link="#0000EE" vlink="#0000EE">
EOT;
$x="\n".$x."\n";
return $x;
}
//echo htmlhead();

function htmlend(){
$x=<<<EOT
</body></html>
EOT;
$x="\n".$x."\n";
return $x;
}
//echo htmlend();


?>