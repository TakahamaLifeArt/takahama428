<?php
/**
 * Instagram API
 * 最新（２０件）の投稿情報を取得
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
$urlAPI = 'https://api.instagram.com/v1/users/self/media/recent/?access_token='._INSTA_ACCESS_TOKEN;
$insta = json_decode(file_get_contents($urlAPI), true);
?>