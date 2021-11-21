<?php
//
function zh150530(){
	error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
	extract($_POST,EXTR_SKIP);extract($_GET,EXTR_SKIP);extract($_COOKIE,EXTR_SKIP);
	$php_info=pathinfo($_SERVER["PHP_SELF"]);//被執行的文件檔名
	$php_dir=$php_info['dirname'];
	$phpself=$php_info['basename'];
	//die('xxx');
	//print_r($phpself);
	//exit;
	//
	date_default_timezone_set("Asia/Taipei");//時區設定
	//$time = time();//UNIX時間時區設定
	$time=sprintf('%s',time());//%u=零或正整數//%s=字串
	//echo "\n".$time;
	//
	//if(strlen($url_150610)==0){$url_150610='http://codepad.org/lvszFXtk/raw.txt';}
	//$content = file_get_contents($url_150610);//echo $content;
	if($phpself == 'curl_getinfo.php'){
		//
		$query_string=$_SERVER['QUERY_STRING'];
		//echo $query_string;
		$url_150610=$query_string;
		$x=curl_FFF($url_150610);
		$getdata =$x_0=$x[0];
		$getinfo =$x_1=$x[1];
		$geterror=$x_2=$x[2];
		//
		if($getinfo['http_code'] == '200'){
			//echo $getinfo['content_type'];
			//header("content-Type: ".$getinfo['content_type'].""); //語言強制
			//echo $getdata;
			
			header('content-Type: text/plain; charset=utf-8 '); //語言強制
			// header("Access-Control-Allow-Origin: *");
			//echo $getdata;
			//echo "\n".strlen($getdata);
			//
			$file = tempnam(sys_get_temp_dir(), 'poi');
			file_put_contents($file,$getdata);
			echo "\n".filesize($file).' '.$file;
			echo "\n".print_r($getinfo,true);
			echo "\n".print_r($getinfo['size_download'],true);
			echo "\n".$url_150610;
			echo "\n".$phpself;
			
			//
		}else{//錯誤時
			echo "\n".print_r($getinfo,true);
		}
	}else{//由外部檔案呼叫時的反應
		if($getinfo['http_code'] == '200'){
			if(1){
				//不需反應 交由函式處理
			}else{
				//不需反應 交由函式處理
			}
			//
		}else{//錯誤時
			//不需反應 交由函式處理
		}
	}
}
//die('xxx');
zh150530();//直接打開php時的反應
//exit;die('http_code');
/*
更新紀錄
181118 更新$useragent
*/

////////函式區
function curl_FFF($url){
	if(isset($GLOBALS['curl_FFF_cc']) ){
		$GLOBALS['curl_FFF_cc']=$GLOBALS['curl_FFF_cc']+1;
	}else{
		$GLOBALS['curl_FFF_cc']=0;
	}
	//
	if(strlen($url)==0){$url='http://pastebin.com/raw/ZWGrZCM6';}
	// Create a curl handle
	if(!extension_loaded('curl')){die('[x]curl');}//判斷模組是否載入
	if(!function_exists('curl_version')){die('[x]curl_version');}//判斷模組版本
	$tmp='';
	$tmp=curl_version();//curl_version()['protocols']
	//echo print_r($tmp,true);
	if( !in_array('http',$tmp['protocols']) ){die('[x]curl_version2');}
	//exit;
	//$FFF=explode(',', ini_get('disable_functions'));
	//print_r($FFF);exit;
	//if( in_array('curl_exec', $FFF) ){die('[x]curl_exec');}//判斷是否有禁用
	//
	//$useragent='Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36';
	$useragent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36";
	$ch = curl_init();//初始化
	if(!$ch){die('[x]curl初始化失敗');}
	//
	$ret = curl_setopt($ch, CURLOPT_URL,            $url);//網址
	$ret = curl_setopt($ch, CURLOPT_HEADER,         0);//是否顯示header信息
	$ret = curl_setopt($ch, CURLOPT_NOBODY,         0);//是否隱藏body頁面內容
	$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec不直接輸出獲取內容
	$ret = curl_setopt($ch, CURLOPT_TIMEOUT,        10);//设置cURL允许执行的最长秒数。 
	$ret = curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);//在发起连接前等待的时间，如果设置为0，则无限等待。  
	$ret = curl_setopt($ch, CURLOPT_FAILONERROR,    1);//發生錯誤時不回傳內容
	//$ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//跟随重定向页面
	$ret = curl_setopt($ch, CURLOPT_MAXREDIRS,      3);//跟随重定向页面的最大次數
	$ret = curl_setopt($ch, CURLOPT_AUTOREFERER,    1);//重定向页面自动添加 Referer header 
	$ret = curl_setopt($ch, CURLOPT_USERAGENT,      $useragent);
	$ret = curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	//
	if(0){
		try{
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟随重定向页面
			curl_setopt($ch, CURLOPT_MAXREDIRS, 5); //跳转请求的最大次数
			curl_setopt($ch, CURLOPT_AUTOREFERER, 1); //跳转後自动添加 Referer header 
		}catch(Exception $e) {
			//var_dump($e->getMessage());
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); //跟随重定向页面
		}
	}
	//$ret = curl_setopt($ch, CURLOPT_REFERER,        "http://eden.komica.org/");//自訂來路頁面 用來獲取目標
	//$ret = curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	//
	$getdata  = curl_exec($ch);//抓取URL並把它傳遞給變數
	$getinfo  = curl_getinfo($ch);//結果資訊
	$geterror = curl_errno($ch);
	//
	if($getinfo['redirect_url'] && ($GLOBALS['curl_FFF_cc']<5)){
		$url=$getinfo['redirect_url'];
		$x        = curl_FFF($url);
		$getdata  = $x[0];
		$getinfo  = $x[1];
		$geterror = $x[2];
	}
	//
	/*
	$return = curl_getinfo($ch,CURLINFO_HTTP_CODE);//文件狀態
	echo "\n".'CURLINFO_HTTP_CODE'."\n";print_r($return);
	$return = curl_getinfo($ch,CURLINFO_CONTENT_TYPE);//文件類型
	echo "\n".'CURLINFO_CONTENT_TYPE'."\n";print_r($return);
	$return = curl_getinfo($ch,CURLINFO_TOTAL_TIME);//消耗時間
	echo "\n".'CURLINFO_TOTAL_TIME'."\n";print_r($return);
	$return = curl_getinfo($ch,CURLINFO_CONTENT_LENGTH_DOWNLOAD);//消耗時間
	echo "\n".'CURLINFO_CONTENT_LENGTH_DOWNLOAD'."\n";print_r($return);

	echo "\n".'curl_errno'."\n";print_r($geterror);
	*/
	curl_close($ch);
	//
	$x[0]=$getdata;//資料
	$x[1]=$getinfo;//訊息
	$x[2]=$geterror;//錯誤
	return $x;
}
?>