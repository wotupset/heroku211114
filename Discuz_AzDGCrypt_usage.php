<?php
header('Content-Type: application/javascript; charset=utf-8');
//header('Content-type: text/html; charset=utf-8');

$time=time();
echo $time;
echo "\n";


require './Discuz_AzDGCrypt.php';//載入加密用函式
echo "Discuz_AzDGCrypt: \t\t";
echo $echo=azdg_encode('中文'.$time,$time);
echo "\n";

echo "Discuz_AzDGCrypt: \t\t";
echo $echo=azdg_decode($echo,$time);
echo "\n";

?>