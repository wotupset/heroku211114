<?php
//error_reporting(E_ALL & ~E_NOTICE); //所有錯誤中排除NOTICE提示
//header("content-Type: application/json; charset=utf-8"); //強制
//header('Content-Type: text/html; charset=utf-8');


$html_post=<<<EOT
<form id='form1' action='./211116-chat_post2.php' method='post'  autocomplete="off">
內文<br/>
<textarea name="text" id="id_text" cols="48" rows="4" wrap=soft></textarea>
<input type="submit" value="送出"/>  
</form>

EOT;
echo $html_post;


$html2=<<<EOT
<html>
<head>
<title></title>
</head>
<body>
$html_post
</body>
<html>
EOT;

//echo $html2;


?>