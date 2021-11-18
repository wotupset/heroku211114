<?php
/*
* Passport 加密函數
*
* @param		string		等待加密的原字串
* @param		string		私有密匙(用於解密和加密)
*
* @return	string		原字串經過私有密匙加密後的結果
*/
function passport_encrypt($txt, $key) {
	// 使用隨機數發生器產生 0~32000 的值並 MD5()
	srand((double)microtime() * 1000000);
	$encrypt_key = md5(rand(0, 32000));
	// 變量初始化
	$ctr = 0;
	$tmp = '';
	// for 循環，$i 為從 0 開始，到小於 $txt 字串長度的整數
	for($i = 0; $i < strlen($txt); $i++) {
		// 如果 $ctr = $encrypt_key 的長度，則 $ctr 清零
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		// $tmp 字串在末尾增加兩位，其第一位內容為 $encrypt_key 的第 $ctr 位，
		// 第二位內容為 $txt 的第 $i 位與 $encrypt_key 的 $ctr 位取異或。然後 $ctr = $ctr + 1
		$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
	}
	// 返回結果，結果為 passport_key() 函數返回值的 base65 編碼結果
	return base64_encode(passport_key($tmp, $key));
}

/**
* Passport 解密函數
*
* @param		string		加密後的字串
* @param		string		私有密匙(用於解密和加密)
*
* @return	string		字串經過私有密匙解密後的結果
*/
function passport_decrypt($txt, $key) {
	// $txt 的結果為加密後的字串經過 base64 解碼，然後與私有密匙一起，
	// 經過 passport_key() 函數處理後的返回值
	$txt = passport_key(base64_decode($txt), $key);
	// 變量初始化
	$tmp = '';
	// for 循環，$i 為從 0 開始，到小於 $txt 字串長度的整數
	for ($i = 0; $i < strlen($txt); $i++) {
		// $tmp 字串在末尾增加一位，其內容為 $txt 的第 $i 位，
		// 與 $txt 的第 $i + 1 位取異或。然後 $i = $i + 1
		$tmp .= $txt[$i] ^ $txt[++$i];
	}
	// 返回 $tmp 的值作為結果
	return $tmp;
}

/**
* Passport 密匙處理函數
*
* @param		string		待加密或待解密的字串
* @param		string		私有密匙(用於解密和加密)
*
* @return	string		處理後的密匙
*/
function passport_key($txt, $encrypt_key) {
	// 將 $encrypt_key 賦為 $encrypt_key 經 md5() 後的值
	$encrypt_key = md5($encrypt_key);
	// 變量初始化
	$ctr = 0;
	$tmp = '';
	// for 循環，$i 為從 0 開始，到小於 $txt 字串長度的整數
	for($i = 0; $i < strlen($txt); $i++) {
		// 如果 $ctr = $encrypt_key 的長度，則 $ctr 清零
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		// $tmp 字串在末尾增加一位，其內容為 $txt 的第 $i 位，
		// 與 $encrypt_key 的第 $ctr + 1 位取異或。然後 $ctr = $ctr + 1
		$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
	}
	// 返回 $tmp 的值作為結果
	return $tmp;
}

/*
* Passport 信息(數組)編碼函數
*
* @param		array		待編碼的數組
*
* @return	string		數組經編碼後的字串
*/
function passport_encode($array) {
	// 數組變量初始化
	$arrayenc = array();
	// 遍歷數組 $array，其中 $key 為當前元素的下標，$val 為其對應的值
	foreach($array as $key => $val) {
		// $arrayenc 數組增加一個元素，其內容為 "$key=經過 urlencode() 後的 $val 值"
		$arrayenc[] = $key.'='.urlencode($val);
	}
	// 返回以 "&" 連接的 $arrayenc 的值(implode)，例如 $arrayenc = array('aa', 'bb', 'cc', 'dd')，
	// 則 implode('&', $arrayenc) 後的結果為 」aa&bb&cc&dd"
	return implode('&', $arrayenc);
	
}

function azdg_encode($a1,$a2){
	//$a1 = input
	//$a2 = key
	$encrypt = passport_encrypt($a1,$a2);
	$x= $encrypt;
	return $x;
}
function azdg_decode($a1,$a2){
	//$a1 = input
	//$a2 = key
	$decrypt = passport_decrypt($a1,$a2);
	$x= $decrypt;
	return $x;
}
switch($azdg){
	case 'a':
	break;
	default:
	break;
}
?>