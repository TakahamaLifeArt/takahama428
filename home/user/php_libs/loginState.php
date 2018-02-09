<?php
/**
 * ログイン状態の確認とユーザー情報の設定
 */
require_once dirname(__FILE__).'/funcs.php';
if(isset($_REQUEST['id'], $_REQUEST['reset'])){
	$res = resetLoginMember($_REQUEST['id']);
	echo $res;
}
?>